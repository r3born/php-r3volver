<?php

namespace R3born\R3volver\Test;

use \PHPUnit_Framework_TestCase;
use R3born\R3volver\Configuration;
use R3born\R3volver\Services;
use R3born\R3volver\Controller;

class TestController extends \R3born\R3volver\Controller {

    public function someAction() {
        echo 'This is a test.';
    }

}

class ControllerTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        //Remove environment mode if set
        unset($_ENV['SLIM_MODE']);

        //Reset session
        $_SESSION = array();

        //Prepare default environment variables
        \Slim\Environment::mock(array(
            'SCRIPT_NAME' => '/foo', //<-- Physical
            'PATH_INFO' => '/bar', //<-- Virtual
            'QUERY_STRING' => 'one=foo&two=bar',
            'SERVER_NAME' => 'slimframework.com',
        ));
    }
    
    public function testEmptyConfig() {
        $this->setExpectedException('Exception', 'Bad configuration: R3volver must be properly configured');

        Configuration::load(__DIR__ . '/test_files/empty.yml');
        Services::getController('some_controller');
    }

    public function testNoControllerKey() {
        $this->setExpectedException('Exception', 'Unknown controller: some_controller');

        Configuration::load(__DIR__ . '/test_files/empty_r3volver.yml');
        Services::getController('some_controller');
    }

    public function testBadControllerConfiguration() {
        $this->setExpectedException('Exception', 'Controller class does not extend "\R3born\R3volver\Controller": 23');

        Configuration::load(__DIR__ . '/test_files/controllers_bad_configuration.yml');
        Services::getController('some_bad_controller');

        $this->setExpectedException('Exception', 'Controller class does not extend "\R3born\R3volver\Controller": 23');

        Configuration::load(__DIR__ . '/test_files/controllers_bad_configuration.yml');
        Services::getController('another_bad_controller');
    }

    public function testGetController() {
        Configuration::load(__DIR__ . '/test_files/controllers_good_configuration.yml');
        Services::getController('nice_controller');
        
        $this->assertInstanceOf('\R3born\R3volver\Test\TestController', Services::getController('nice_controller'));
    }

}
