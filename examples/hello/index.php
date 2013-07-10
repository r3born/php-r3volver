<?php

// require the composer generated autoloader
require_once __DIR__ . '/vendor/autoload.php';

// we could use composer for our classes too
// for simplicity's sake, we'll just require them directly
require_once __DIR__ . '/GreeterService.php';
require_once __DIR__ . '/HelloController.php';

// load..
R3born\R3volver\Configuration::load(__DIR__ . '/config.yml');

// ..and fire!
R3born\R3volver\Services::getApp()->run();