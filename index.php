<?php


// get core application
require_once './core/Application.php';

// initiate the application core
$app = new Application(array('default_controller' => 'home'));

// start execute requests
$app->run();
