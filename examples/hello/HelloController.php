<?php

use R3born\R3volver\Controller;
use R3born\R3volver\Services;

/**
 * A simple example of a controller.
 * 
 * r3volver controllers are meant to stay simple. They don't have any
 * function other than providing the callbacks that respond to requests.
 * 
 * Application controllers MUST extend R3born\R3volver\Controller. 
 */
class HelloController extends Controller {

    // this is the method that answers to the /hello/:name route
    public function sayHello($name) {
        
        // ask Slim for the 'lang' query (get) parameter
        // we neet that to know which greeter service we'll be using
        $lang = $this->app()->request()->get('lang');

        // default to the english greeter
        if (empty($lang)) {
            $lang = 'en';
        }

        // get our wanted greeter service
        $greetingService = Services::get(strtoupper($lang) . 'Greeter');

        // render the template
        $this->app()->render('template.php', array(
            'body' => $greetingService->greet($name)
        ));
        
        // final note: everything that is made available by the slim instance
        // can be used through $this->app() within any Controller
        // should you need the slim app anywhere else in your application, it
        // is also available through Services::getApp()
    }

    public function sayHi($name) {
        echo 'Hi, ' . $name;
    }

}