<?php

session_start();

// environment
require_once('vendor/autoload.php');
require_once('.environment.php');
require_once('helpers.php');

// autoloader
spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", "/", $class_name);
    require_once $class_name . '.php';
});

// db connection
$DB = new App\System\DB();

// run application
App\Begin::run();