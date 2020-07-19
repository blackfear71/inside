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
    $_SESSION['search']['text_search'] = $_POST['text_search'];
  }

  // METIER : Recherche dans les bases de données
  // RETOUR : Tableau des résultats par catégorie
  function getSearch()
  {
    // Initialisations
    $results   = array();
    $resultsMH = array();
    $resultsFA = array();
    $resultsPP = array();
    $resultsMI = array();
    $nombreMH  = 0;
    $nombreFA  = 0;
    $nombrePP  = 0;
    $nombreMI  = 0;

    // Récupération des données
    $recherche = htmlspecialchars($_SESSION['search']['text_search']);

    if (!empty($recherche))
    {
      global $bdd;

      // Movie House (films non à supprimer)
      $reponse1 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND film LIKE "%' . $recherche . '%" ORDER BY date_theater DESC, film ASC');
      while ($donnees1 = $reponse1->fetch())
      {
        $movie = Movie::withData($donnees1);
        $nombreMH = $reponse1->rowCount();

        // On ajoute la ligne au tableau
        array_push($resultsMH, $movie);
      }
      $reponse1->closeCursor();

      // Restaurants
      $reponse2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE name LIKE "%' . $recherche . '%" ORDER BY location ASC, name ASC');
      while ($donnees2 = $reponse2->fetch())
      {
        $restaurant = Restaurant::withData($donnees2);
        $nombreFA = $reponse2->rowCount();

        // On ajoute la ligne au tableau
        array_push($resultsFA, $restaurant);
      }
      $reponse2->closeCursor();

      // Parcours
      $reponse3 = $bdd->query('SELECT * FROM petits_pedestres_parcours WHERE nom LIKE "%' . $recherche . '%" ORDER BY nom ASC');
      while ($donnees3 = $reponse3->fetch())
      {
        $parcours = Parcours::withData($donnees3);
        $nombrePP = $reponse3->rowCount();

        // On ajoute la ligne au tableau
        array_push($resultsPP, $parcours);
      }
      $reponse3->closeCursor();

      // Missions (déjà commencées ou terminées)
      $reponse4 = $bdd->query('SELECT * FROM missions WHERE date_deb <= ' . date('Ymd') . ' AND mission LIKE "%' . $recherche . '%" ORDER BY date_deb DESC, mission ASC');
      while ($donnees4 = $reponse4->fetch())
      {
        $mission = Mission::withData($donnees4);
        $nombreMI = $reponse4->rowCount();

        // On ajoute la ligne au tableau
        array_push($resultsMI, $mission);
      }
      $reponse4->closeCursor();

      // On ajoute les résultats au tableau final
      $results = array('movie_house'         => $resultsMH,
                       'food_advisor'        => $resultsFA,
                       'petits_pedestres'    => $resultsPP,
                       'missions'            => $resultsMI,
                       'nb_movie_house'      => $nombreMH,
                       'nb_food_advisor'     => $nombreFA,
                       'nb_petits_pedestres' => $nombrePP,
                       'nb_missions'         => $nombreMI
                      );
    }

    return $results;
  }
