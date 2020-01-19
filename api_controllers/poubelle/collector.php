<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require $_SERVER['DOCUMENT_ROOT'] .'/api_controllers/collector/CollectorController.php';

// Create Slim app
$app = new App(Constantes::CONFIG);

$app->group('', function () use ($app) {
// Login
    $app->get('/read', function (Request $req, Response $response, $args) {
        return CollectorController::read($req, $response, $args);
    });
});

// Run app
try {
    $app->run();
} catch (Throwable $e) {
    print_r($e);
}

