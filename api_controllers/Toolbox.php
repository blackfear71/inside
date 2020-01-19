<?php


use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;

class Toolbox
{
    /**
     * Retourner l'URL du serveur
     * @return string
     */
    public static function getServerURL(): string
    {
        $prefixeURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $serverName = $_SERVER['HTTP_HOST'];
        if (strpos($serverName, $prefixeURL) == false)
            $serverName = $prefixeURL . '://' . $serverName;
        return $serverName;
    }

    /**
     * Vérifier le token envoyer par le client
     * @param Request $request
     * @param Response $response
     * @param UsersBase|null $user
     * @return bool
     */
    public static function isConnected(Request $request, Response $response, UsersBase $user = null): bool
    {
        $output = false;
        $authHeader = $request->getHeader("Authorization")[0];
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1];
        $responseBody = null;
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, Constantes::SECRET_KEY, array('HS256'));
                // Access is granted. Add code of the operation here
                /**Convertir une classe en un tableau*/
                if ($user)
                    $user->__construct1(json_decode(json_encode($decoded->user), true));
                $output = true;
            } catch (Exception $e) {
                $responseBody = array(
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                );
            }
        } else {
            $responseBody = array(
                "message" => "Access denied.",
                "error" => "Header abscent"
            );
        }
        if ($output == false) {
            $response->withStatus(401);
            $response->write(json_encode($responseBody));
        }
        return $output;
    }

    public static function addHeaders(Response $response)
    {
        $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
        return $response;
    }
}