<?php

namespace R3born\R3volver;

use Slim\Slim;

class Controller {

    /**
     *
     * @var Slim
     */
    protected $app = null;

    public function setApp(Slim $app) {
        $this->app = $app;
        return $this;
    }

}