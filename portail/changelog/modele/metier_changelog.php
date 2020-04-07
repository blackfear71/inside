<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/changelog.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $annee_existante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT * FROM change_log WHERE year = "' . $year . '" ORDER BY year DESC');

      if ($reponse->rowCount() > 0)
        $annee_existante = true;

      $reponse->closeCursor();
    }

    return $annee_existante;
  }

  // METIER : Lecture des années distinctes
  // RETOUR : Liste des années
  function getOnglets()
  {
    $listOnglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT year FROM change_log ORDER BY year DESC');
    while ($donnees = $reponse->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($listOnglets, $donnees['year']);
    }
    $reponse->closeCursor();

    return $listOnglets;
  }

  // METIER : Récupération des mois en français
  // RETOUR : Liste des mois
  function getMonths()
  {
    $months = array(1  => "Janvier",
                    2  => "Février",
                    3  => "Mars",
                    4  => "Avril",
                    5  => "Mai",
                    6  => "Juin",
                    7  => "Juillet",
                    8  => "Août",
                    9  => "Septembre",
                    10 => "Octobre",
                    11 => "Novembre",
                    12 => "Décembre"
                   );

    return $months;
  }

  // METIER : Récupération des catégories pour les logs
  // RETOUR : Liste des catégories
  function getCategories()
  {
    // Liste des catégories
    $listCategories = array('portail'          => 'PORTAIL',
                            'movie_house'      => 'MOVIE HOUSE',
                            'food_advisor'     => 'LES ENFANTS ! À TABLE !',
                            'cooking_box'      => 'COOKING BOX',
                            'expense_center'   => 'EXPENSE CENTER',
                            'collector'        => 'COLLECTOR ROOM',
                            'calendars'        => 'CALENDARS',
                            'petits_pedestres' => 'LES PETITS PÉDESTRES',
                            'missions'         => 'MISSIONS : INSIDER',
                            'notifications'    => 'NOTIFICATIONS',
                            'search'           => 'RECHERCHE',
                            'profile'          => 'PROFIL',
                            'chat'             => 'INSIDE ROOM',
                            'change_log'       => 'JOURNAL DES MODIFICATIONS',
                            'bugs'             => 'DEMANDES D\'ÉVOLUTION',
                            'ideas'            => '#THEBOX',
                            'admin'            => 'ADMINISTRATION',
                            'cron'             => 'TÂCHES CRON',
                            'technical'        => 'TECHNIQUE',
                            'other'            => 'AUTRE'
                           );

    return $listCategories;
  }

  // METIER : Lecture de la liste des logs
  // RETOUR : Liste des logs
  function getLogs($year, $categories)
  {
    $listlogs = array();

    global $bdd;

    // Récupération de la liste de logs de l'année
    $reponse = $bdd->query('SELECT * FROM change_log WHERE year = "' . $year . '" ORDER BY week DESC');
    while ($donnees = $reponse->fetch())
    {
      $myLog = ChangeLog::withData($donnees);

      // Extraction des logs
      $extractLogs = explode(';', $donnees['logs']);

      // Extraction des catégories
      $logsByCategories = array();

      foreach ($extractLogs as $extractedLog)
      {
        if (!empty($extractedLog))
        {
          list($entry, $cat) = explode('@', $extractedLog);
          $extractCategorie  = array('categorie' => $cat,
                                     'entry'     => $entry
                                    );

          array_push($logsByCategories, $extractCategorie);
        }
      }

      // Tri sur catégories
      $sortedLogs = array();

      foreach ($categories as $categorie => $labelCategorie)
      {
        if(!empty($logsByCategories))
        {
          foreach ($logsByCategories as $keyLog => $logByCategory)
          {
            if ($logByCategory['categorie'] == $categorie)
            {
              if (!isset($sortedLogs[$categorie]))
                $sortedLogs[$categorie] = array();

              array_push($sortedLogs[$categorie], $logByCategory['entry']);
              unset($logsByCategories[$keyLog]);
            }
          }
        }
        else
          break;
      }

      // Sécurité si besoin (logs restants sans catégorie)
      if (!empty($logsByCategories))
      {
        $sortedLogs['other'] = array();

        foreach ($logsByCategories as $keyLog => $logByCategory)
        {
          array_push($sortedLogs['other'], $logByCategory['entry']);
        }
      }

      $myLog->setLogs($sortedLogs);

      // Ajout à la liste des logs
      array_push($listlogs, $myLog);
    }
    $reponse->closeCursor();

    return $listlogs;
  }
?>
