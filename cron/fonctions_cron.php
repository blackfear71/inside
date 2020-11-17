<?php
  /********************************/
  /*** Liste des fonctions CRON ***/
  /********************************/

  // FONCTION : Insertion notification sortie cinéma du jour
  // RETOUR : Tableau log traitement
  // FREQUENCE : tous les jours à 7h
  function isCinemaToday()
  {
    $log = array('trt' => '/* Sortie cinéma du jour */', 'status' => 'KO');

    global $bdd;

    $req = $bdd->query('SELECT id, date_doodle FROM movie_house WHERE date_doodle = ' . date('Ymd') . ' ORDER BY id ASC');

    while ($data = $req->fetch())
    {
      // Contrôle notification non existante
      $notificationCinemaExist = controlNotification('cinema', $data['id']);

      // Génération notification sortie cinéma
      if ($notificationCinemaExist != true)
        insertNotification('admin', 'cinema', $data['id']);

      // Traitement effectué
      $log['status'] = 'OK';
    }

    // Cas pas d'enregristrement en base
    if ($req->rowCount() == 0)
      $log['status'] = 'OK';

    $req->closeCursor();

    // Retour
    return $log;
  }

  // FONCTION : Vérification durée mission
  // RETOUR : Booléen
  // FREQUENCE : tous les jours à 7h
  function durationMissions()
  {
    $oneDayMissions = array();

    global $bdd;

    $req = $bdd->query('SELECT id, date_deb, date_fin FROM missions WHERE date_deb = ' . date('Ymd') . ' OR date_fin = ' . date('Ymd'));
    while ($data = $req->fetch())
    {
      if ($data['date_deb'] == $data['date_fin'])
        $mission = array('id_mission' => $data['id'], 'one_day' => 'O');
      elseif (date('Ymd') != $data['date_fin'] AND date('Ymd') == $data['date_deb'])
        $mission = array('id_mission' => $data['id'], 'one_day' => 'F');
      elseif (date('Ymd') != $data['date_deb'] AND date('Ymd') == $data['date_fin'])
        $mission = array('id_mission' => $data['id'], 'one_day' => 'L');
      else
        $mission = array('id_mission' => $data['id'], 'one_day' => 'N');

      array_push($oneDayMissions, $mission);
    }
    $req->closeCursor();

    // Retour
    return $oneDayMissions;
  }

  // FONCTION : Insertion notification début de mission
  // RETOUR : Tableau log traitement
  // FREQUENCE : tous les jours à 7h
  function isFirstDayMission($idMission)
  {
    $log = array('trt' => '/* Début de mission */', 'status' => 'KO');

    global $bdd;

    $req = $bdd->query('SELECT id, date_deb FROM missions WHERE id = ' . $idMission);
    $data = $req->fetch();

    // Contrôle notification non existante
    $notificationMissionExist = controlNotification('start_mission', $idMission);

    // Génération notification début mission
    if ($notificationMissionExist != true)
      insertNotification('admin', 'start_mission', $data['id']);

    // Traitement effectué
    $log['status'] = 'OK';

    $req->closeCursor();

    // Retour
    return $log;
  }

  // FONCTION : Insertion notification fin de mission
  // RETOUR : Tableau log traitement
  // FREQUENCE : tous les jours à 7h
  function isLastDayMission($idMission)
  {
    $log = array('trt' => '/* Fin de mission */', 'status' => 'KO');

    global $bdd;

    $req = $bdd->query('SELECT id, date_fin FROM missions WHERE id = ' . $idMission);
    $data = $req->fetch();

    // Contrôle notification non existante
    $notificationMissionExist = controlNotification('end_mission', $idMission);

    // Génération notification fin mission
    if ($notificationMissionExist != true)
      insertNotification('admin', 'end_mission', $data['id']);

    // Traitement effectué
    $log['status'] = 'OK';

    // Cas pas d'enregristrement en base
    if ($req->rowCount() == 0)
      $log['status'] = 'OK';

    $req->closeCursor();

    // Retour
    return $log;
  }

  // FONCTION : Insertion notification mission unique
  // RETOUR : Tableau log traitement
  // FREQUENCE : tous les jours à 7h
  function isOneDayMission($idMission)
  {
    $log = array('trt' => '/* Mission unique */', 'status' => 'KO');

    global $bdd;

    $req = $bdd->query('SELECT id, date_deb, date_fin FROM missions WHERE id = ' . $idMission);
    $data = $req->fetch();

    // Contrôle notification non existante
    $notificationMissionExist = controlNotification('one_mission', $data['id']);

    // Génération notification mission unique
    if ($notificationMissionExist != true)
      insertNotification('admin', 'one_mission', $data['id']);

    // Traitement effectué
    $log['status'] = 'OK';

    $req->closeCursor();

    // Retour
    return $log;
  }

  // FONCTION : Attribution expérience fin de mission
  // RETOUR : Tableau log traitement
  // Fréquence : tous les jours à 7h
  function insertExperienceWinners()
  {
    $log     = NULL;
    $doneYet = false;

    // Détermination si chaîne déjà passée
    $dirJ = '../cron/logs/daily';

    $filesJ = scandir($dirJ, 1);

    // Suppression racines de dossier
    unset($filesJ[array_search('..', $filesJ)]);
    unset($filesJ[array_search('.', $filesJ)]);

    if (!empty($filesJ))
    {
      // Tri sur date
      foreach ($filesJ as $fileJ)
      {
        $triAnneeJ[]   = substr($fileJ, 12, 4);
        $triMoisJ[]    = substr($fileJ, 9, 2);
        $triJourJ[]    = substr($fileJ, 6, 2);
        $triHeureJ[]   = substr($fileJ, 17, 2);
        $triMinuteJ[]  = substr($fileJ, 20, 2);
        $triSecondeJ[] = substr($fileJ, 23, 2);
      }

      array_multisort($triAnneeJ, SORT_DESC, $triMoisJ, SORT_DESC, $triJourJ, SORT_DESC, $triHeureJ, SORT_DESC, $triMinuteJ, SORT_DESC, $triSecondeJ, SORT_DESC, $filesJ);

      // Test si CRON déjà passé
      foreach ($filesJ as $fileJ)
      {
        $dateFichier = substr($fileJ, 12, 4) . substr($fileJ, 9, 2) . substr($fileJ, 6, 2);

        // Si fichier ancien, on arrête la boucle
        if ($dateFichier < date('Ymd'))
          break;

        // Si fichier du jour alors on sait qu'on a déjà attribué l'expérience
        if ($dateFichier == date('Ymd'))
        {
          $doneYet = true;
          break;
        }
      }
    }

    if ($doneYet == false)
    {
      $log = array('trt' => '/* Expérience missions */', 'status' => 'KO');

      global $bdd;

      $dateMoins1 = date('Ymd', strtotime('now - 1 Days'));

      // Lecture des missions se terminant la veille
      $req = $bdd->query('SELECT id, date_fin FROM missions WHERE date_fin = ' . $dateMoins1);

      while ($data = $req->fetch())
      {
        $idMission = $data['id'];
        $tableauUsers = array();

        // Construction tableau des participants
        $req2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $idMission . ' ORDER BY identifiant ASC');
        while ($data2 = $req2->fetch())
        {
          // Récupération des valeurs d'avancement de chaque mission par utilisateur
          if (!isset($tableauUsers[$data2['identifiant']]) OR empty($tableauUsers[$data2['identifiant']]))
            $tableauUsers[$data2['identifiant']] = array('avancement' => intval($data2['avancement']), 'rank' => 0);
          else
            $tableauUsers[$data2['identifiant']] = array('avancement' => $tableauUsers[$data2['identifiant']]['avancement'] + intval($data2['avancement']), 'rank' => 0);
        }
        $req2->closeCursor();

        if (!empty($tableauUsers))
        {
          // Tri sur avancement
          foreach ($tableauUsers as $user)
          {
            $triRank[] = $user['avancement'];
          }

          array_multisort($triRank, SORT_DESC, $tableauUsers);

          // Affectation du rang
          $prevTotal   = 0;
          $currentRank = 0;

          foreach ($tableauUsers as $key => &$user)
          {
            $currentTotal = $user['avancement'];

            if ($currentTotal != $prevTotal)
            {
              $currentRank += 1;
              $prevTotal    = $currentTotal;
            }

            // Suppression des rangs > 3 sinon on enregistre le rang
            if ($currentRank > 3)
              unset($tableauUsers[$key]);
            else
             $user['rank'] = $currentRank;
          }

          unset($user);

          // Ajout expérience pour chaque gagnant
          foreach ($tableauUsers as $key => $user)
          {
            switch ($user['rank'])
            {
              case 1:
                insertExperience($key, 'winner_mission_1');
                break;

              case 2:
                insertExperience($key, 'winner_mission_2');
                break;

              case 3:
                insertExperience($key, 'winner_mission_3');
                break;

              default:
                break;
            }
          }
        }
      }

      // Traitement effectué
      $log['status'] = 'OK';

      $req->closeCursor();
    }

    // Retour
    return $log;
  }

  // FONCTION : Recalcul des dépenses pour tous les utilisateurs
  // RETOUR : Tableau log traitement
  // Fréquence : tous les lundis à 7h
  function reinitializeExpenses()
  {
    $log = array('trt' => '/* Remise à plat des bilans des dépenses */', 'status' => 'KO');

    // Initialisation tableau des utilisateurs
    $listeUsers = array();

    global $bdd;

    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
    while ($data1 = $req1->fetch())
    {
      // On calcule le bilan des dépenses de l'utilisateur courant
      $bilanUser = 0;

      // Calcul des bilans
      $req2 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
      while ($data2 = $req2->fetch())
      {
        // Identifiant de l'acheteur
        $acheteur = $data2['buyer'];

        // Nombre de parts et prix par parts
        $nombrePartsTotal   = 0;
        $nombrePartsUser    = 0;
        $nombreUtilisateurs = 0;

        $req3 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data2['id']);
        while ($data3 = $req3->fetch())
        {
          // Nombre de parts total
          $nombrePartsTotal += $data3['parts'];

          // Nombre de parts de l'utilisateur
          if ($data1['identifiant'] == $data3['identifiant'])
            $nombrePartsUser = $data3['parts'];

          // Nombre de participants
          $nombreUtilisateurs += 1;
        }

        if ($data2['type'] == 'M')
        {
          // Frais d'achat
          $fraisAchat = formatAmountForInsert($data2['price']);

          // Montant de la part
          $montantUser = formatAmountForInsert($nombrePartsUser);

          // Calcul de la répartition des frais
          if (empty($fraisAchat))
            $fraisAchat = 0;

          if ($montantUser != 0)
            $fraisUser = $fraisAchat / $nombreUtilisateurs;
          else
            $fraisUser = 0;

          // Calcul du bilan de l'utilisateur (s'il participe ou qu'il est l'acheteur)
          if ($acheteur == $data1['identifiant'] OR $montantUser != 0)
          {
            if ($acheteur == $data1['identifiant'])
              $bilanUser += $fraisAchat + $nombrePartsTotal - ($montantUser + $fraisUser);
            else
              $bilanUser -= $montantUser + $fraisUser;
          }
        }
        else
        {
          // Prix d'achat
          $prixAchat = formatAmountForInsert($data2['price']);

          // Prix par parts
          if ($nombrePartsTotal != 0)
            $prixParPart = $prixAchat / $nombrePartsTotal;
          else
            $prixParPart = 0;

          // Somme des dépenses moins les parts consommées pour calculer le bilan
          if ($acheteur == $data1['identifiant'])
            $bilanUser += $prixAchat - ($prixParPart * $nombrePartsUser);
          else
            $bilanUser -= $prixParPart * $nombrePartsUser;
        }

        $req3->closeCursor();
      }
      $req2->closeCursor();

      // On construit un tableau des utilisateurs
      $user = array('id'          => $data1['id'],
                    'identifiant' => $data1['identifiant'],
                    'bilan'       => $bilanUser,
                   );

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $req1->closeCursor();

    // Mise à jour des utilisateurs
    foreach ($listeUsers as $userBilan)
    {
      $req4 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $userBilan['identifiant'] . '"');
      $req4->execute(array(
        'expenses' => $userBilan['bilan']
      ));
      $req4->closeCursor();

      // Traitement effectué
      $log['status'] = 'OK';
    }

    // Retour
    return $log;
  }

  // FONCTION : Création fichier log
  // RETOUR : Aucun
  // Fréquence : tous les jours à 7h pour les types 'j' et tous les lundis à 7h pour les types 'h'
  function generateLog($typeLog, $etatTrt, $heureDeb, $heureFin)
  {
    // On vérifie la présence des dossiers, sinon on les créé
    $dossier      = 'logs';
    $sousDossierJ = 'daily';
    $sousDossierH = 'weekly';

    if (!is_dir($dossier))
      mkdir($dossier);

    if (!is_dir($dossier . '/' . $sousDossierJ))
      mkdir($dossier . '/' . $sousDossierJ);

    if (!is_dir($dossier . '/' . $sousDossierH))
      mkdir($dossier . '/' . $sousDossierH);

    // Titre
    if ($typeLog == 'j')
      $titreLog = "/******************************/\r\n/* Traitement CRON journalier */\r\n/******************************/\r\n";
    elseif ($typeLog == 'h')
      $titreLog = "/********************************/\r\n/* Traitement CRON hebdomadaire */\r\n/********************************/\r\n";

    // Type de traitement
    if (isset($_POST['daily_cron']) OR isset($_POST['weekly_cron']))
      $exeLog = '## Traitement asynchrone';
    else
      $exeLog = '## Traitement automatique';

    // Date du traitement
    $dateLog = '## Date.......................' . date('d/m/Y');

    // Traitement global OK ou KO
    $control_ok = true;

    foreach ($etatTrt as $trt)
    {
      if ($trt['status'] == 'KO')
      {
        $control_ok = false;
        break;
      }
    }

    if ($control_ok == true)
      $etatLog = '## Etat traitements...........OK';
    else
      $etatLog = '## Etat traitements...........KO';

    // Durée totale des traitements
    $dureeTot = calculDureeTraitement($heureDeb, $heureFin);
    $dureeLog = '## Durée traitements..........' . $dureeTot['heures'] . ' heures, ' . $dureeTot['minutes'] . ' minutes et ' . $dureeTot['secondes'] . ' secondes';

    // Ouverture / création fichier
    if ($typeLog == 'j')
      $log = fopen('logs/daily/' . $typeLog . 'log_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.txt', 'a+');
    elseif ($typeLog == 'h')
      $log = fopen('logs/weekly/' . $typeLog . 'log_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.txt', 'a+');

    // On repositionne le curseur du fichier au début
    fseek($log, 0);

    // On écrit dans le fichier
    fputs($log, $titreLog);
    fputs($log, "\r\n");
    fputs($log, $exeLog);
    fputs($log, "\r\n");
    fputs($log, $dateLog);
    fputs($log, "\r\n");
    fputs($log, $etatLog);
    fputs($log, "\r\n");
    fputs($log, $dureeLog);
    fputs($log, "\r\n");

    if (!empty($etatTrt))
    {
      foreach ($etatTrt as $trt)
      {
        fputs($log, "\r\n");
        $nomTrt    = $trt['trt'];
        $statutTrt = '## Status.....................' . $trt['status'];
        fputs($log, $nomTrt);
        fputs($log, "\r\n");
        fputs($log, $statutTrt);
        fputs($log, "\r\n");
      }
    }

    // Fermeture fichier
    fclose($log);
  }
?>
