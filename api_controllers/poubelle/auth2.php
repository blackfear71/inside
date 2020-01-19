<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


// Create Slim app
$app = new App(Constantes::CONFIG);

// Define app routes
$app->get('/hello/{name}', function ($request, Response $response, $args) {
    return $response->write("Hello " . $args['name']);
});

$app->group('', function () use ($app) {
    // Login
    $this->post('/login', function (Request $req, Response $response, $args) {
        return AuthController::login($req, $response, $args);
    });
});

// Run app
try {
    $app->run();
} catch (Throwable $e) {
    print_r($e);
}

