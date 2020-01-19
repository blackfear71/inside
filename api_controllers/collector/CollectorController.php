<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions/appel_bdd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/portail/collector/modele/metier_collector.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/collector/CollectorBase.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api_controllers/Toolbox.php';

class CollectorController
{
    public static function read(Request $request, Response $response, array $args = []): Response
    {
        $user = new UsersBase();
        if (Toolbox::isConnected($request, $response, $user)) {
            // Access is granted. Add code of the operation here
            $page = $_GET['offset'] ?? 0;
            $identifiant = $user->getIdentifiant();
            $sort = $_GET['sort'] ?? 'dateDesc';
            $filter = $_GET['filter'] ?? 'none';
            $per_page = $_GET['per_page'] ?? 10;


            // Lecture du nombre de pages
            $collectorsTotal = CollectorController::getTotalCollectors($filter, $identifiant);

            $listeCollectors = array();
            if ($collectorsTotal > 0) {
                $listeCollectors = CollectorController::getCollectorsAPI(null, $collectorsTotal, $page, $identifiant, $sort, $filter, $per_page);
                // Charger la list des votes dans la $listeCollectors
                CollectorController::getVotesApi($listeCollectors);
            }
            // set response code - 200 OK
            http_response_code(200);
            $data = array(
                'collectorsData' => array(
                    'collectorsTotal' => $collectorsTotal,
                    'collectors' => $listeCollectors,
                ),
                'pathEmoticones' => Toolbox::getServerURL() . Constantes::PATH_EMOTICONES,
                'pathCollectorsImages' => Toolbox::getServerURL() . Constantes::PATH_COLLECTORS_IMAGES,
            );
            // show products data in json format
            $dataJson = json_encode($data);
            echo $dataJson;
        }

        return $response;
    }

    /**
     * @param $filtre
     * @param $identifiant
     * @return int
     */
    public static function getTotalCollectors($filtre, $identifiant)
    {
        $totalCollectors = 0;
        $min_golden = 6;

        global $bdd;

        // Calcul du nombre total d'enregistrements pour chaque filtre
        switch ($filtre) {
            case "noVote":
                $req = $bdd->query('SELECT COUNT(collector.id)
                            AS nb_col
                            FROM collector
                            WHERE NOT EXISTS (SELECT id, id_collector, identifiant
                                              FROM collector_users
                                              WHERE (collector.id = collector_users.id_collector
                                              AND    collector_users.identifiant = "' . $identifiant . '"))');

                break;

            case "meOnly":
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE speaker = "' . $identifiant . '"');
                break;

            case "byMe":
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE author = "' . $identifiant . '"');
                break;

            case "usersOnly":
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE (type_speaker = "user" AND speaker != "' . $identifiant . '")');
                break;

            case "othersOnly":
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_speaker = "other"');
                break;

            case "textOnly":
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_collector = "T"');
                break;

            case "picturesOnly":
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_collector = "I"');
                break;

            case "topCulte":
                $req = $bdd->query('SELECT COUNT(collector.id)
                            AS nb_col
                            FROM collector
                            WHERE (SELECT COUNT(collector_users.id)
                                   FROM collector_users
                                   WHERE collector_users.id_collector = collector.id) >= ' . $min_golden);
                break;

            case "none":
            default:
                $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector');
                break;
        }

        $data = $req->fetch();

        if (isset($data['nb_col']))
            $totalCollectors = $data['nb_col'];

        $req->closeCursor();

        return $totalCollectors;
    }

    /**
     * Lecture des phrases cultes
     * @param $listUsers
     * @param $totalPages
     * @param $offset
     * @param $identifiant
     * @param $tri
     * @param $filtre
     * @param $nb_par_page
     * @return CollectorBase[] Liste phrases cultes
     */
    public static function getCollectorsAPI($listUsers, $totalPages, $offset, $identifiant, $tri, $filtre, $nb_par_page = 18)
    {
        // Contrôle dernière page
        if ($offset > $totalPages)
            $offset = $totalPages;

        $listCollectors = array();
        $min_golden = 6;

        $premiere_entree = $offset;

        // Détermination sens tri
        switch ($tri) {
            case "dateAsc":
                $order = "collector.date_collector ASC, collector.id ASC";
                break;

            case "dateDesc":
            default:
                $order = "collector.date_collector DESC, collector.id DESC";
                break;
        }

        // Lecture des enregistrements en fonction du filtre
        global $bdd;

        switch ($filtre) {
            case "noVote":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE NOT EXISTS (SELECT id, id_collector, identifiant
                                                  FROM collector_users
                                                  WHERE (collector.id = collector_users.id_collector
                                                  AND    collector_users.identifiant = "' . $identifiant . '"))
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);

                break;

            case "meOnly":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.speaker = "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "byMe":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.author = "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "usersOnly":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_speaker = "user" AND collector.speaker != "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "othersOnly":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_speaker = "other")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "textOnly":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_collector = "T")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "picturesOnly":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_collector = "I")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "topCulte":
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (SELECT COUNT(collector_users.id)
                                       FROM collector_users
                                       WHERE collector_users.id_collector = collector.id) >= ' . $min_golden . '
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;

            case "none":
            default:
                $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
                break;
        }

        while ($donnees = $reponse->fetch()) {
            $myCollector = new CollectorBase($donnees);

            array_push($listCollectors, $myCollector);
        }
        $reponse->closeCursor();

        return $listCollectors;
    }

    /**
     * Liste des votes par phrase culte
     * @param $list_collectors CollectorBase[]
     */
    public static function getVotesApi($list_collectors)
    {
        global $bdd;
        foreach ($list_collectors as $collector) {
            $req2 = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $collector->getId() . ' ORDER BY vote ASC');
            $myArray = array();
            while ($donnees = $req2->fetch()) {
                $votesCollector = VotesCollector::withData($donnees);
                array_push($myArray, $votesCollector);
            }
            $collector->setVotesCollector($myArray);
        }
    }
}