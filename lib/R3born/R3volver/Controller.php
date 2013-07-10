<?php

namespace R3born\R3volver;

use R3born\R3volver\Services;

/**
 * r3volver controller base class.
 * 
 * All controllers must have this class as their parent. Controllers CANNOT have
 * parameters in their constructor. Future features may require aditional
 * contracts to be enforced on controllers, which will be dealt with
 * through this base class.
 */
abstract class Controller {

    /**
     * Get the Slim instance.
     * 
     * This is a helper method for controllers, for easy access to
     * request data and the like.
     * 
     * @return \Slim\Slim
     */
    public function app() {
        return Services::getApp();
    }

}