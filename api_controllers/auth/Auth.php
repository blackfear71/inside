<?php


use Slim\App;
use Slim\Http\Response;
use Slim\Http\Request;

require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/auth/AuthController.php';

class Auth
{
    public static function controller(App $app)
    {
        return $app->group('/auth', function () use ($app) {
            // Login
            $app->post('/login', function (Request $req, Response $response, $args) {
                return Toolbox::addHeaders(AuthController::login($req, $response, $args));
            });
        });
    }
}