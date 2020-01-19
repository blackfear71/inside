<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions/appel_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

class AuthController
{
    /**
     * Fonction d'identification et de connexion
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public static function login(Request $request, Response $response, array $args = []): Response
    {
        global $bdd;
        $data = $request->getParsedBody();
        $responseBody = '';
        if (empty($data["identifiant"]) || empty($data["password"])) {
            $responseBody = array(
                "log" => "error",
                "message" => "Empty value in login/password"
            );
            $responseCode = 302;
        } else {
            $identifiant = $data["identifiant"];
            $password = $data["password"];

            $table_name = 'users';

            $query = "SELECT id, pseudo, password, salt, avatar FROM " . $table_name . " WHERE identifiant = ? LIMIT 0,1";
            $stmt = $bdd->prepare($query);
            $stmt->bindParam(1, $identifiant);
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row['id'];
                $pseudo = $row['pseudo'];
                $password2 = $row['password'];
                $salt = $row['salt'];
                $avatar = $row['avatar'];

                $mdp = htmlspecialchars(hash('sha1', $password . $salt));
                if ($mdp === $password2) {
                    $issuer_claim = "THE_ISSUER"; // this can be the servername
                    $audience_claim = "THE_AUDIENCE";
                    $issuedat_claim = time(); // issued at
                    $notbefore_claim = $issuedat_claim + 2; //not before in seconds
                    $expire_claim = $issuedat_claim + 3600; // expire time in seconds
                    $token = array(
                        "iss" => $issuer_claim,
                        "aud" => $audience_claim,
                        "iat" => $issuedat_claim,
                        "nbf" => $notbefore_claim,
                        "exp" => $expire_claim,
                        "user" => array(
                            "id" => $id,
                            "pseudo" => $pseudo,
                            "identifiant" => $identifiant,
                            "avatar" => $avatar,
                        ));
                    $responseCode = 200;
                    $jwt = JWT::encode($token, Constantes::SECRET_KEY);
                    $responseBody =
                        array(
                            "message" => "Successful login.",
                            "user" => array(
                                "id" => $id,
                                "pseudo" => $pseudo,
                                "identifiant" => $identifiant,
                                "avatar" => $avatar,
                                "pathAvatars" => Constantes::PATH_AVATARS,
                                "access_token" => $jwt,
                                "expires_in" => $expire_claim,
                            )
                        );
                } else {
                    $responseCode = 401;
                }
            } else {
                $responseCode = 401;
            }

            if ($responseCode == 401) {
                $responseBody = array(
                    "message" => "Login failed.");

            }
        }
        $response->write(json_encode($responseBody));
        $response->withStatus($responseCode);
        return $response;
    }
}