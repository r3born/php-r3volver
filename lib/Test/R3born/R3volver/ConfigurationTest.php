<?php

namespace R3born\R3volver;

use \PHPUnit_Framework_TestCase;
use R3born\R3volver\Configuration;

class ConfigurationTest extends PHPUnit_Framework_TestCase {

    public function testLoad() {
        Configuration::load(__DIR__ . '/test_files/config.yml');
    }
    
    public function testGet() {
        $this->assertInstanceOf('stdClass', Configuration::get());
    }

}