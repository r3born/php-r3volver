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
        if (empty(self::$services['r3volver.slim.app.' . $name])) {
            self::$services['r3volver.slim.app.' . $name] = self::initializeApp();
        }

        return self::$services['r3volver.slim.app.' . $name];
    }

    public static function getController($name) {
        if (empty(self::$services['r3volver.controllers.' . $name])) {
            self::$services['r3volver.controllers.' . $name] = self::initializeController($name);
        }

        return self::$services['r3volver.controllers.' . $name];
    }

    protected static function initializeService($name) {
        $configuration = Configuration::get();

        if (!property_exists($configuration, 'services') ||
                !property_exists($configuration->services, $name)) {
            throw new \Exception('Unknown service: ' . $name);
        }

        $service = $configuration->services->{name};

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
        $configuration = Configuration::get();
        $app           = new Slim();

        if (property_exists($configuration, 'app')) {
            if (!is_object($configuration->app)) {
                throw new \Exception('Bad app configuration (check "app" key in configuration file)');
            }

            $slimConfiguration = (array) $configuration->app;
            foreach ($slimConfiguration as $k => $v) {
                $app->config($k, $v);
            }
        }

        if (!property_exists($configuration, 'routes')) {
            throw new \Exception('The configuration file must contain the "routes" field');
        }

        return $app;
    }

    protected static function initializeController($name) {
        $configuration = Configuration::get();

        if (!property_exists($configuration, 'controllers') ||
                !property_exists($configuration->controllers, $name)) {
            throw new \Exception('Unknown controller: ' . $name);
        }

        $fqcn = $configuration->controllers->{$name};

        if (!is_subclass_of('\R3born\R3volver\Controller', $fqcn)) {
            throw new \Exception('Class does not extend "\R3born\R3volver\Controller": ' . $fqcn);
        }

        $controller = new $fqcn();
        $controller->setApp(self::getApp());

        return $controller;
    }

}
