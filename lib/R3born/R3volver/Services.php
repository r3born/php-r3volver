<?php

namespace R3born\R3volver;

use Slim\Slim;
use R3born\R3volver\Configuration;

/**
 * r3volver's Service Container.
 * 
 * This is the true core of the framework. Services is a non-instantiable
 * multi-singleton factory. The basic contract for anything defined as a service
 * and also for Controllers and App (Slim instance) is that:
 * - if retrieved through Services, an object is only instatiated once
 * - no object is instantiated until it is needed
 * 
 * Being static, The service container is easily available anywhere in
 * the application (just like Configuration).
 */
abstract class Services {

    /**
     * Service instances initialized so far.
     * @var array 
     */
    protected static $services = array();

    /**
     * Get a service.
     * @param string $name
     * @return mixed
     */
    public static function get($name) {
        if (empty(self::$services[$name])) {
            self::$services[$name] = self::initializeService($name);
        }

        return self::$services[$name];
    }

    /**
     * Get the running Slim instance.
     * @return \Slim\Slim
     */
    public static function getApp() {
        if (empty(self::$services['r3volver.slim.app'])) {
            self::$services['r3volver.slim.app'] = self::initializeApp();
        }

        return self::$services['r3volver.slim.app'];
    }

    /**
     * Get an application Controller.
     * @param string $name
     * @return \R3born\R3volver\Controller
     */
    public static function getController($name) {
        if (empty(self::$services['r3volver.controllers.' . $name])) {
            self::$services['r3volver.controllers.' . $name] = self::initializeController($name);
        }

        return self::$services['r3volver.controllers.' . $name];
    }

    /**
     * Initialize a service.
     * @param type $name
     * @return mixed
     * @throws \Exception
     */
    protected static function initializeService($name) {
        // check common configuration requirements
        self::checkR3volverConfiguration();

        // get configuration under 'r3volver'
        $r3volver = Configuration::get()->r3volver;

        // check if the requested service is configured
        if (!property_exists($r3volver, 'services') ||
                !property_exists($r3volver->services, $name)) {
            throw new \Exception('Unknown service: ' . $name);
        }

        // get configuration for the service
        $service = $r3volver->services->{$name};

        // check if the constructor declaration exists and is correct
        if (!property_exists($service, 'class') || !is_array($service->class)) {
            throw new \Exception('Bad service configuration: ' . $name);
        }

        // use reflection to create a new instance with the provided arguments
        $reflectionClass = new \ReflectionClass($service->class[0]);
        $serviceInstance = $reflectionClass->newInstanceArgs(\array_slice($service->class, 1));

        // if any method calls exist for the service object, perform them
        if (property_exists($service, 'calls')) {

            // check if method calls are properly configured
            if (!is_array($service->calls)) {
                throw new \Exception('Bad service configuration: ' . $name);
            }

            foreach ($service->calls as $c) {

                // check if method calls are properly configured
                if (!is_array($c) || !is_string($c[0])) {
                    throw new \Exception('Bad service configuration: ' . $name);
                }

                // call the supplied method
                call_user_func_array(array($serviceInstance, $c[0]), array_slice($c, 1));
            }
        }

        // return the fully configured service instance
        return $serviceInstance;
    }

    /**
     * Initialize the App (Slim instance).
     * @return \Slim\Slim
     * @throws \Exception
     */
    protected static function initializeApp() {

        // check common configuration requirements
        self::checkR3volverConfiguration();

        // get configuration under 'r3volver' and initialize Slim
        $r3volver = Configuration::get()->r3volver;
        $app      = new Slim();

        // check if there is any configuration for Slim
        if (property_exists($r3volver, 'app')) {

            // check that configuration is well formed
            if (!is_object($r3volver->app)) {
                throw new \Exception('Bad app configuration (check "app" key in configuration file)');
            }

            // pass each configuration key to the Slim instance
            $slimConfiguration = (array) $r3volver->app;
            foreach ($slimConfiguration as $k => $v) {
                $app->config($k, $v);
            }
        }

        // check if routes are configured
        if (!property_exists($r3volver, 'routes')) {
            throw new \Exception('No routes configured');
        }

        // configure routes
        foreach ($r3volver->routes as $name => $route) {

            // separate controller name from its handler method
            list($controller, $function) = explode('.', $route[2]);

            // configure handler
            $m = $app
                    ->map($route[1], array(Services::getController($controller), $function))
                    ->via($route[0])
                    ->name($name);

//            if (!empty($route[3])) {
//                $m->conditions($route[3]);
//            }
        }

        return $app;
    }

    /**
     * Initialize a controller.
     * @param string $name
     * @return \R3born\R3volver\Controller
     * @throws \Exception
     */
    protected static function initializeController($name) {

        // check common configuration requirements
        self::checkR3volverConfiguration();

        // get configuration under 'r3volver'
        $r3volver = Configuration::get()->r3volver;

        // check if the requested controller is configured
        if (!property_exists($r3volver, 'controllers') ||
                !property_exists($r3volver->controllers, $name)) {
            throw new \Exception('Unknown controller: ' . $name);
        }

        // get the controller's fully qualified class name
        $fqcn = $r3volver->controllers->{$name};

        // check that the class exists
        if (!class_exists($fqcn)) {
            throw new \Exception('Controller class does not exist: ' . $fqcn);
        }

        // check that the class has R3born\R3volver\Controller as one of its
        // parents
        if (!is_subclass_of($fqcn, 'R3born\R3volver\Controller')) {
            throw new \Exception('Controller class does not extend "\R3born\R3volver\Controller": ' . $fqcn);
        }

        // return the new instance of the controller
        // the contract for controllers is that their constructors are
        // parameterless
        return new $fqcn();
    }

    /**
     * Check that the configuration was loaded and contains a 'r3volver' key.
     * @throws \Exception
     */
    protected static function checkR3volverConfiguration() {
        $configuration = Configuration::get();

        if (empty($configuration) ||
                !property_exists($configuration, 'r3volver') ||
                empty($configuration->r3volver) ||
                !is_object($configuration->r3volver)) {
            throw new \Exception('Bad configuration: R3volver must be properly configured');
        }
    }

}
