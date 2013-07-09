<?php

namespace R3born\R3volver;

use Symfony\Component\Yaml\Yaml;

class Configuration {

    private static $configuration = null;

    public static function load($path) {
        self::$configuration = json_decode(json_encode(Yaml::parse(file_get_contents($path))));
    }

    public static function get() {
        return self::$configuration;
    }

}