<?php

namespace R3born\R3volver;

use Symfony\Component\Yaml\Yaml;

/**
 * r3volver's configuration container.
 * 
 * It is very simple, as it's only function is to parse the configuration
 * file and make that configuration available to the application through the
 * static method get(). 
 * 
 * Being static, The configuration is easily available anywhere in
 * the application (just like Services).
 */
abstract class Configuration {

    /**
     * Holds the configuration object once the configuration is loaded.
     * @var \stdClass
     */
    private static $configuration = null;

    /**
     * Loads configuration from the specified file.
     * @param string $path
     */
    public static function load($path) {
        // use encode/decode to convert array into object
        self::$configuration = json_decode(json_encode(
                        Yaml::parse(file_get_contents($path))
        ));
    }

    /**
     * Get the configuration object.
     * @return \stdClass
     */
    public static function get() {
        return self::$configuration;
    }

}