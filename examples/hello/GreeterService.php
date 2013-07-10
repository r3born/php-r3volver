<?php

/**
 * A simple example of a service. Services can be anything, really.
 * 
 * Services must be configured in order to be available through the service
 * container. See the config.yml for configuration details.
 */
class GreeterService {

    // each greeter service instance can be configured to use a different
    // language
    private $lang = null;

    // language is passed as a parameter to the constructor
    public function __construct($lang) {
        $this->lang = $lang;
    }

    // greeting templates for each language are available in the configuration
    // file; here we show how to retrieve configuration keys
    public function greet($name) {
        
        // get the configuration object
        $cfg = R3born\R3volver\Configuration::get();
        
        // use our template to print really polite messages
        return preg_replace('/:name/', $name,
                $cfg->hello->greeting_templates->{$this->lang});
    }

}