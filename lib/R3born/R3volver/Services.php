<?php

namespace R3born\R3volver;

use Slim\Slim;
use R3born\R3volver\Configuration;

class Services {

    protected static $services = array();

    public static function get($name) {
        if (empty(self::$services[$name])) {
            self::$services[$name] = self::initializeService($name);
        }

        return self::$services[$name];
    }

    public static function getApp() {
        if (empty(self::$services['r3volver.slim.app'])) {
            self::$services['r3volver.slim.app'] = self::initializeApp();
        }

        return self::$services['r3volver.slim.app'];
    }

    public static function getController($name) {
        if (empty(self::$services['r3volver.controllers.' . $name])) {
            self::$services['r3volver.controllers.' . $name] = self::initializeController($name);
        }

        return self::$services['r3volver.controllers.' . $name];
    }

    protected static function initializeService($name) {
        self::checkR3volverConfiguration();

        $r3volver = Configuration::get()->r3volver;

        if (!property_exists($r3volver, 'services') ||
                !property_exists($r3volver->services, $name)) {
            throw new \Exception('Unknown service: ' . $name);
        }

        $service = $r3volver->services->{$name};

        if (!property_exists($service, 'class') || !is_array($service->class)) {
            throw new \Exception('Bad service configuration: ' . $name);
        }

        $reflectionClass = new \ReflectionClass($service->class[0]);
        $serviceInstance = $reflectionClass->newInstanceArgs(\array_slice($service->class, 1));

        if (property_exists($service, 'calls')) {

            if (!is_array($service->calls)) {
                throw new \Exception('Bad service configuration: ' . $name);
            }

            foreach ($service->calls as $c) {
                call_user_func_array(array($serviceInstance, $c[0]), array_slice($c, 1));
            }
        }

        return $serviceInstance;
    }

    protected static function initializeApp() {
        self::checkR3volverConfiguration();

        $r3volver = Configuration::get()->r3volver;
        $app      = new Slim();

        if (property_exists($r3volver, 'app')) {
            if (!is_object($r3volver->app)) {
                throw new \Exception('Bad app configuration (check "app" key in configuration file)');
            }

            $slimConfiguration = (array) $r3volver->app;
            foreach ($slimConfiguration as $k => $v) {
                $app->config($k, $v);
            }
        }

        if (!property_exists($r3volver, 'routes')) {
            throw new \Exception('No routes configured');
        }

        foreach ($r3volver->routes as $name => $route) {
            list($controller, $function) = explode('.', $route[2]);
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

    protected static function initializeController($name) {
        self::checkR3volverConfiguration();

        $r3volver = Configuration::get()->r3volver;

        if (!property_exists($r3volver, 'controllers') ||
                !property_exists($r3volver->controllers, $name)) {
            throw new \Exception('Unknown controller: ' . $name);
        }

        $fqcn = $r3volver->controllers->{$name};

        if (!is_subclass_of($fqcn, 'R3born\R3volver\Controller')) {
            throw new \Exception('Controller class does not extend "\R3born\R3volver\Controller": ' . $fqcn);
        }

        return new $fqcn();
    }

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
