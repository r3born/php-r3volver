<?php

namespace R3born\R3volver\Test;

use \PHPUnit_Framework_TestCase;
use R3born\R3volver\Configuration;

class ConfigurationTest extends PHPUnit_Framework_TestCase {

    public function testGetNullConfiguration() {
        Configuration::load(__DIR__ . '/test_files/empty.yml');
        $this->assertNull(Configuration::get());
    }
    
    public function testGetNotNullConfiguration() {
        Configuration::load(__DIR__ . '/test_files/empty_r3volver.yml');
        $this->assertInstanceOf('stdClass', Configuration::get());
    }

}