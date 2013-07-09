<?php
require_once __DIR__ . '/vendor/autoload.php';

class GreeterService {

    private $lang = null;

    public function __construct($lang) {
        $this->lang = $lang;
    }

    public function greet($name) {
        $cfg = R3born\R3volver\Configuration::get();
        return preg_replace('/:name/', $name, $cfg->hello->greeting_templates->{$this->lang});
    }

}

class HelloController extends \R3born\R3volver\Controller {

    public function sayHello($name) {
        $lang = $this->app()->request()->get('lang');

        if (empty($lang)) {
            $lang = 'en';
        }

        $greetingService = R3born\R3volver\Services::get(strtoupper($lang) . 'Greeter');

        $this->app()->render('template.php', array(
           'body' => $greetingService->greet($name)
        ));
    }

    public function sayHi($name) {
        echo 'Hi, ' . $name;
    }

}

// load..
R3born\R3volver\Configuration::load(__DIR__ . '/config.yml');

// ..and fire!
R3born\R3volver\Services::getApp()->run();