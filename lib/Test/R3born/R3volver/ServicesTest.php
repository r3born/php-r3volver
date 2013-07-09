<?php

namespace R3born\R3volver;

use \PHPUnit_Framework_TestCase;
use R3born\R3volver\Configuration;

class ServicesTest extends PHPUnit_Framework_TestCase {

    public function testEmptyConfig() {
        $this->setExpectedException('Exception', 'Bad configuration: R3volver must be configured');
        
        Configuration::load(__DIR__.'/test_files/empty.yml');
        Services::get('some_service');
    }

    public function testNoServicesKey() {
        //$this->setExpectedException('Exception', 'Bad configuration: R3volver must be configured');
        
        Configuration::load(__DIR__.'/test_files/empty_r3volver.yml');
        var_dump(Configuration::get()); die();
        Services::get('some_service');
    }
}