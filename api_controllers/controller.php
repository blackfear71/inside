<?php

use Slim\App;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/headers.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/Constantes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/auth/Auth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/collector/Collectors.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/users/Users.php';

// Create Slim app
$app = new App(Constantes::CONFIG);

$app->group('/api', function () use ($app) {
    /**Authentification*/
    Auth::controller($this);
    /**Collectors*/
    Collectors::controller($this);
    /**Users*/
    Users::controller($this);
});


// Run app
try {
    $app->run();
} catch (Throwable $e) {
    print_r($e);
}

