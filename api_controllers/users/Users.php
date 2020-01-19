<?php

use Slim\App;
use Slim\Http\Response;
use Slim\Http\Request;

require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/users/UserController.php';

class Users
{
    public static function controller(App $app)
    {
        $app->group('/user', function () use ($app) {
            $app->get('/read', function (Request $req, Response $response, $args) {
                return Toolbox::addHeaders(UserController::read($req, $response, $args));
            });
        });
    }
}