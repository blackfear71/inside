<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/movies.php');
  include_once('../../includes/classes/restaurants.php');
  include_once('../../includes/classes/parcours.php');
  include_once('../../includes/classes/missions.php');

  // METIER : Initialise la sauvegarde en session de la recherche
  // RETOUR : Aucun
  function initializeSaveSearch()
  {
    $_SESSION['save']['search'] = $_POST['text_search'];
  }

  // METIER : Recherche dans les bases de données
  // RETOUR : Tableau des résultats par catégorie
  function getSearch()
  {
    // Initialisations
    $results    = array();
    $results_MH = array();
    $results_FA = array();
    $results_PP = array();
    $results_MI = array();
    $nb_MH      = 0;
    $nb_FA      = 0;
    $nb_PP      = 0;
    $nb_MI      = 0;

    // Récupération des données
    $recherche = htmlspecialchars($_SESSION['save']['search']);

    if (!empty($recherche))
    {
      global $bdd;

      // Movie House (films non à supprimer)
      $reponse1 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND film LIKE "%' . $recherche . '%" ORDER BY date_theater DESC, film ASC');
      while ($donnees1 = $reponse1->fetch())
      {
        $myMovie = Movie::withData($donnees1);
        $nb_MH = $reponse1->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_MH, $myMovie);
      }
      $reponse1->closeCursor();

      // Restaurants
      $reponse2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE name LIKE "%' . $recherche . '%" ORDER BY location ASC, name ASC');
      while ($donnees2 = $reponse2->fetch())
      {
        $myRestaurant = Restaurant::withData($donnees2);
        $nb_FA = $reponse2->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_FA, $myRestaurant);
      }
      $reponse2->closeCursor();

      // Parcours
      $reponse3 = $bdd->query('SELECT * FROM petits_pedestres_parcours WHERE nom LIKE "%' . $recherche . '%" ORDER BY nom ASC');
      while ($donnees3 = $reponse3->fetch())
      {
        $myParcours = Parcours::withData($donnees3);
        $nb_PP = $reponse3->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_PP, $myParcours);
      }
      $reponse3->closeCursor();

      // Missions (déjà commencées ou terminées)
      $reponse4 = $bdd->query('SELECT * FROM missions WHERE date_deb <= ' . date("Ymd") . ' AND mission LIKE "%' . $recherche . '%" ORDER BY date_deb DESC, mission ASC');
      while ($donnees4 = $reponse4->fetch())
      {
        $myMission = Mission::withData($donnees4);
        $nb_MI = $reponse4->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_MI, $myMission);
      }
      $reponse4->closeCursor();

      // On ajoute les résultats au tableau final
      $results = array('movie_house'         => $results_MH,
                       'food_advisor'        => $results_FA,
                       'petits_pedestres'    => $results_PP,
                       'missions'            => $results_MI,
                       'nb_movie_house'      => $nb_MH,
                       'nb_food_advisor'     => $nb_FA,
                       'nb_petits_pedestres' => $nb_PP,
                       'nb_missions'         => $nb_MI
                      );
    }

    return $results;
  }
