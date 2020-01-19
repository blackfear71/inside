<?php

use Slim\App;
use Slim\Http\Response;
use Slim\Http\Request;

require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/collector/CollectorController.php';

class Collectors
{
    public static function controller(App $app)
    {
        $app->group('/collector', function () use ($app) {
            $app->get('/read', function (Request $req, Response $response, $args) {
                return Toolbox::addHeaders(CollectorController::read($req, $response, $args));
            });
        });
    }
}