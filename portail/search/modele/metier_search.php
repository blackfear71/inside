<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/movies.php');
  include_once('../../includes/classes/parcours.php');
  include_once('../../includes/classes/missions.php');

  // METIER : Recherche dans les bases de données
  // RETOUR : Tableau des résultats par catégorie
  function getSearch($search)
  {
    $results    = array();
    $results_MH = array();
    $results_PP = array();
    $results_MI = array();
    $nb_MH      = 0;
    $nb_PP      = 0;
    $nb_MI      = 0;

    $recherche  = htmlspecialchars($search);

    if (!empty($recherche))
    {
      global $bdd;

      // Movie House (films non à supprimer)
      $reponse1 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND film LIKE "%' . $recherche . '%" ORDER BY date_theater DESC, film ASC');
      while($donnees1 = $reponse1->fetch())
      {
        $myMovie = Movie::withData($donnees1);
        $nb_MH = $reponse1->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_MH, $myMovie);
      }
      $reponse1->closeCursor();

      // Parcours
      $reponse2 = $bdd->query('SELECT * FROM petits_pedestres_parcours WHERE nom LIKE "%' . $recherche . '%" ORDER BY nom ASC');
      while($donnees2 = $reponse2->fetch())
      {
        $myParcours = Parcours::withData($donnees2);
        $nb_PP = $reponse2->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_PP, $myParcours);
      }
      $reponse2->closeCursor();

      // Missions (déjà commencées ou terminées)
      $reponse3 = $bdd->query('SELECT * FROM missions WHERE date_deb <= ' . date("Ymd") . ' AND mission LIKE "%' . $recherche . '%" ORDER BY date_deb DESC, mission ASC');
      while($donnees3 = $reponse3->fetch())
      {
        $myMission = Mission::withData($donnees3);
        $nb_MI = $reponse3->rowCount();

        // On ajoute la ligne au tableau
        array_push($results_MI, $myMission);
      }
      $reponse3->closeCursor();

      // On ajoute les résultats au tableau final
      $results = array('movie_house'         => $results_MH,
                       'petits_pedestres'    => $results_PP,
                       'missions'            => $results_MI,
                       'nb_movie_house'      => $nb_MH,
                       'nb_petits_pedestres' => $nb_PP,
                       'nb_missions'         => $nb_MI
                      );
    }

    return $results;
  }
