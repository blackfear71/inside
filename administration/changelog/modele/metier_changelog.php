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
    $_SESSION['changelog']['week']   = formatWeekForInsert($_POST['semaine_changelog']);
  }

  // METIER : Récupération des catégories pour les logs
  // RETOUR : Liste des catégories
  function getCategories()
  {
    // Liste des catégories
    $listCategories = array('general'          => 'GÉNÉRAL',
                            'portail'          => 'PORTAIL',
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

    // Tri par catégories
    $sortedLogs = array();

    foreach ($categories as $categorie => $labelCategorie)
    {
      foreach ($extractLogs as $keyExtract => $extractedLog)
      {
        if(!empty($extractedLog))
        {
          list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);

          if ($categoryExtracted == $categorie)
          {
            if (!isset($sortedLogs[$categorie]))
              $sortedLogs[$categorie] = array();

            array_push($sortedLogs[$categorie], $entryExtracted);
            unset($extractLogs[$keyExtract]);
          }
        }
        else
          unset($extractLogs[$keyExtract]);
      }
    }

    // Sécurité si besoin (logs restants sans catégorie)
    if (!empty($extractLogs))
    {
      if (!isset($sortedLogs['other']))
        $sortedLogs['other'] = array();

      foreach ($extractLogs as $keyExtract => $extractedLog)
      {
        if(!empty($extractedLog))
        {
          list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);
          array_push($sortedLogs['other'], $entryExtracted);
        }
      }
    }

    $changelog->setLogs($sortedLogs);

    $reponse->closeCursor();

    return $changelog;
  }

  // METIER : Insertion d'un journal
  // RETOUR : Aucun
  function insertChangeLog($post, $categories)
  {
    $control_ok = true;

    // Récupération des données
    $year               = $post['saisie_annee_changelog'];
    $week               = $post['saisie_semaine_changelog'];
    $notes              = $post['saisie_notes_changelog'];
    $saisies_entrees    = $post['saisies_entrees'];
    $categories_entrees = $post['categories_entrees'];

    // Initialisations
    $entries              = '';
    $content_notification = $week . ';' . $year . ';';

    // Filtrage des entrées vides
    foreach ($saisies_entrees as $keyEntry => $entry)
    {
      if (empty($entry))
        unset($saisies_entrees[$keyEntry]);
    }

    // Contrôle si notes et entrées vides
    if (empty($notes) AND empty($saisies_entrees))
    {
      $_SESSION['alerts']['log_empty'] = true;
      $control_ok                      = false;
    }

    // Filtrage des symboles interdits
    if ($control_ok == true)
    {
      $search  = array("@", ";");
      $replace = array(" ", " ");

      foreach ($saisies_entrees as $keyEntry => $entry)
      {
        $saisies_entrees[$keyEntry] = str_replace($search, $replace, $entry);
      }
    }

    // Tri et récupération des entrées
    if ($control_ok == true)
    {
      foreach ($categories as $categorie => $labelCategorie)
      {
        if(!empty($saisies_entrees))
        {
          foreach ($saisies_entrees as $keyEntry => $entry)
          {
            if ($categories_entrees[$keyEntry] == $categorie)
            {
              $entries .= $entry . '@' . $categories_entrees[$keyEntry] . ';';
              unset($saisies_entrees[$keyEntry]);
            }
          }
        }
      }
    }

    // Insertion enregistrement
    if ($control_ok == true)
    {
      global $bdd;

      // Enregistrement en base
      $reponse = $bdd->prepare('INSERT INTO change_log(week,
                                                       year,
                                                       notes,
                                                       logs)
                                               VALUES(:week,
                                                      :year,
                                                      :notes,
                                                      :logs)');
      $reponse->execute(array(
        'week'  => $week,
        'year'  => $year,
        'notes' => $notes,
        'logs'  => $entries
      ));
      $reponse->closeCursor();

      // Insertion notification
      insertNotification('admin', 'changelog', $content_notification);

      // Message d'alerte
      $_SESSION['alerts']['log_added'] = true;
    }
  }

  // METIER : Mise à jour d'un journal
  // RETOUR : Aucun
  function updateChangeLog($post, $categories)
  {
    $control_ok = true;

    // Récupération des données
    $year               = $post['saisie_annee_changelog'];
    $week               = $post['saisie_semaine_changelog'];
    $notes              = $post['saisie_notes_changelog'];
    $saisies_entrees    = $post['saisies_entrees'];
    $categories_entrees = $post['categories_entrees'];

    // Initialisations
    $entries              = '';
    $content_notification = $week . ';' . $year . ';';

    // Filtrage des entrées vides
    foreach ($saisies_entrees as $keyEntry => $entry)
    {
      if (empty($entry))
        unset($saisies_entrees[$keyEntry]);
    }

    // Contrôle si notes et entrées vides
    if (empty($notes) AND empty($saisies_entrees))
    {
      $_SESSION['alerts']['log_empty'] = true;
      $control_ok                      = false;
    }

    // Filtrage des symboles interdits
    if ($control_ok == true)
    {
      $search  = array("@", ";");
      $replace = array(" ", " ");

      foreach ($saisies_entrees as $keyEntry => $entry)
      {
        $saisies_entrees[$keyEntry] = str_replace($search, $replace, $entry);
      }
    }

    // Tri et récupération des entrées
    if ($control_ok == true)
    {
      foreach ($categories as $categorie => $labelCategorie)
      {
        if(!empty($saisies_entrees))
        {
          foreach ($saisies_entrees as $keyEntry => $entry)
          {
            if ($categories_entrees[$keyEntry] == $categorie)
            {
              $entries .= $entry . '@' . $categories_entrees[$keyEntry] . ';';
              unset($saisies_entrees[$keyEntry]);
            }
          }
        }
      }
    }

    // Mise à jour enregistrement
    if ($control_ok == true)
    {
      global $bdd;

      // Enregistrement en base
      $reponse = $bdd->prepare('UPDATE change_log SET notes = :notes, logs = :logs WHERE year = ' . $year . ' AND week = ' . $week);
      $reponse->execute(array(
        'notes' => $notes,
        'logs'  => $entries
      ));
      $reponse->closeCursor();

      // Message d'alerte
      $_SESSION['alerts']['log_updated'] = true;
    }
  }

  // METIER : Suppression d'un journal
  // RETOUR : Aucun
  function deleteChangeLog($post)
  {
    $year                 = $post['saisie_annee_changelog'];
    $week                 = $post['saisie_semaine_changelog'];
    $content_notification = $week . ';' . $year . ';';

    global $bdd;

    // Suppression du journal
    $reponse = $bdd->exec('DELETE FROM change_log WHERE year = ' . $year . ' AND week = ' . $week);

    // Suppression des notifications
    deleteNotification('changelog', $content_notification);

    // Message d'alerte
    $_SESSION['alerts']['log_deleted'] = true;
  }
?>
