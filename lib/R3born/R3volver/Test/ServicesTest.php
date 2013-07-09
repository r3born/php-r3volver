<?php

namespace R3born\R3volver\Test;

use \PHPUnit_Framework_TestCase;
use R3born\R3volver\Configuration;
use R3born\R3volver\Services;

class TestService {

    private $property = null;

    public function __construct($property = 0) {
        $this->setProperty($property);
    }

    public function getProperty() {
        return $this->property;
    }

    public function setProperty($property) {
        $this->property = $property;
    }

}

class ServicesTest extends PHPUnit_Framework_TestCase {

    public function testEmptyConfig() {
        $this->setExpectedException('Exception', 'Bad configuration: R3volver must be properly configured');

        Configuration::load(__DIR__ . '/test_files/empty.yml');
        Services::get('some_service');
    }

    public function testNoServiceKey() {
        $this->setExpectedException('Exception', 'Unknown service: some_service');

        Configuration::load(__DIR__ . '/test_files/empty_r3volver.yml');
        Services::get('some_service');
    }

    public function testBadServiceConfiguration() {
        $this->setExpectedException('Exception', 'Bad service configuration: a_bad_service');

        Configuration::load(__DIR__ . '/test_files/services_bad_configuration.yml');
        Services::get('a_bad_service');

        $this->setExpectedException('Exception', 'Bad service configuration: another_bad_service');

        Configuration::load(__DIR__ . '/test_files/services_bad_configuration.yml');
        Services::get('another_bad_service');
    }

    public function testGetService() {
        Configuration::load(__DIR__ . '/test_files/services_good_configuration.yml');

        $this->assertInstanceOf('\R3born\R3volver\Test\TestService', Services::get('nice_service'));
        $this->assertObjectHasAttribute('property', Services::get('nice_service'));
        $this->assertEquals(42, Services::get('nice_service')->getProperty());

        $this->assertInstanceOf('\R3born\R3volver\Test\TestService', Services::get('another_nice_service'));
        $this->assertObjectHasAttribute('property', Services::get('another_nice_service'));
        $this->assertEquals(17, Services::get('another_nice_service')->getProperty());
    }

}