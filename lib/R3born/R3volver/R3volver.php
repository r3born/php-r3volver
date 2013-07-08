<?php

use R3born\R3volver\Configuration;
use R3born\R3volver\Services;

class R3volver {

    public static function fire($configurationPath) {
        Configuration::load($configurationPath);
        Services::getApp()->run();
    }

}
