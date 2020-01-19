<?php

use Slim\Http\Response;
use Slim\Http\Request;

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions/appel_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/Toolbox.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/users/UsersBase.php';

class UserController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function read(Request $request, Response $response, array $args = []): Response
    {
        /** @var UsersBase */
        if (Toolbox::isConnected($request, $response)) {
            // Initialisation tableau d'utilisateurs
            $listeUsers = array();

            global $bdd;

            $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
            while ($donnees = $reponse->fetch()) {
                /**
                 */
                $user = new UsersBase($donnees);

                // On ajoute la ligne au tableau
                array_push($listeUsers, $user);
            }
            $reponse->closeCursor();

            $data = array(
                'users' => $listeUsers,
                'pathAvatars' => Toolbox::getServerURL() . Constantes::PATH_AVATARS,
            );
            $response->write(json_encode($data));
            $response->withStatus(200);
        }
        return $response;
    }
}