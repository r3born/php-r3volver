<?php

namespace R3born\R3volver;

use R3born\R3volver\Services;

class Controller {

    /**
     * 
     * @return \Slim\Slim
     */
    public function app() {
        return Services::getApp();
    }
}