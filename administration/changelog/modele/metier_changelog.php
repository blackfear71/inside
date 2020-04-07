<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/changelog.php');

  // METIER : Initialise les paramètres des changelogs
  // RETOUR : Paramètres
  function initializeChangeLog()
  {
    // Initialisations
    $changeLogParameters = new ChangeLogParameters();

    return $changeLogParameters;
  }

  // METIER : Récupère les paramètres des changelogs
  // RETOUR : Paramètres
  function getChangeLogParameters($parametres)
  {
    // Suppression de la session
    unset($_SESSION['changelog']);

    // Initialisations
    $changeLogParameters = new ChangeLogParameters();

    // Récupération des paramètres
    $changeLogParameters->setAction($parametres['action']);
    $changeLogParameters->setYear($parametres['year']);
    $changeLogParameters->setWeek($parametres['week']);

    return $changeLogParameters;
  }

  // METIER : Sauvegarde des paramètres en session
  // Retour : Aucun
  function saveChangeLogParameters($post)
  {
    $_SESSION['changelog']['action'] = $_POST['action_changelog'];
    $_SESSION['changelog']['year']   = $_POST['annee_changelog'];
    $_SESSION['changelog']['week']   = $_POST['semaine_changelog'];
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

  // METIER : Contrôle l'existence d'un journal
  // RETOUR : Booléen
  function controlChangeLog($parametres)
  {
    $exist  = false;
    $error  = false;
    $action = $parametres->getAction();
    $year   = $parametres->getYear();
    $week   = $parametres->getWeek();

    global $bdd;

    // Lecture des données
    $reponse = $bdd->query('SELECT * FROM change_log WHERE year = "' . $year . '" AND week = "' . $week . '"');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
      $exist = true;

    $reponse->closeCursor();

    if ($action == 'M' OR $action == 'S')
    {
      if ($exist == false)
      {
        $_SESSION['alerts']['log_doesnt_exist'] = true;
        $error                                  = true;
      }
    }
    else
    {
      if ($exist == true)
      {
        $_SESSION['alerts']['log_already_exist'] = true;
        $error                                   = true;
      }
    }

    return $error;
  }

  // METIER : Récupération des données existantes
  // RETOUR : Log à modifier ou supprimer
  function getChangeLog($parametres, $categories)
  {
    $year = $parametres->getYear();
    $week = $parametres->getWeek();

    global $bdd;

    // Récupération du log
    $reponse = $bdd->query('SELECT * FROM change_log WHERE year = "' . $year . '" AND week = "' . $week . '"');
    $donnees = $reponse->fetch();

    $changelog = ChangeLog::withData($donnees);

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

    $changelog->setLogs($sortedLogs);

    $reponse->closeCursor();

    return $changelog;
  }
?>
