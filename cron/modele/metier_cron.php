<?php
  include_once('../includes/classes/missions.php');
  include_once('../includes/classes/movies.php');

  // METIER : Insertion notification sortie cinéma du jour
  // RETOUR : Compte-rendu traitement
  function generateNotificationsSortieCinema()
  {
    // Initialisations
    $nombreNotifications = 0;
    $log                 = array('trt'    => '/* Sortie cinéma du jour */',
                                 'status' => 'KO',
                                 'infos'  => ''
                                );

    // Récupération de la liste des films ayant une sortie ce jour
    $listeFilmsSortie = physiqueSortiesOrganisees();

    // Contrôle et insertion des notifications
    if (count($listeFilmsSortie) > 0)
    {
      foreach ($listeFilmsSortie as $film)
      {
        // Contrôle notification non existante
        $notificationCinemaExist = controlNotification('cinema', $film->getId());

        // Insertion notification
        if ($notificationCinemaExist != true)
        {
          insertNotification('admin', 'cinema', $film->getId());

          // Compteur de notifications générées
          $nombreNotifications++;
        }

        // Ajout des données au log
        $log['status'] = 'OK';
        $log['infos']  = $nombreNotifications . ' notifications insérées';
      }
    }
    else
    {
      // Ajout des données au log
      $log['status'] = 'OK';
      $log['infos']  = 'Pas de sorties organisées';
    }

    // Retour
    return $log;
  }

  // METIER : Lecture des données missions
  // RETOUR : Tableau de compte-rendus des traitements
  function generateNotificationsMissions()
  {
    // Initialisations
    $listeLogsMissions = array();

    // Récupération de la durée des missions
    $dureesMissions = physiqueDureesMissions();

    // Contrôle et insertion des notifications
    if (count($dureesMissions) > 0)
    {
      foreach ($dureesMissions as $mission)
      {
        if ($mission['duration'] == 'O' OR $mission['duration'] == 'F' OR $mission['duration'] == 'L')
        {
          // Détermination des données
          switch ($mission['duration'])
          {
            case 'O':
              $notification = 'one_mission';
              $log          = array('trt'    => '/* Mission unique (' . htmlspecialchars($mission['mission']) . ') */',
                                    'status' => 'KO',
                                    'infos'  => ''
                                   );
              break;

            case 'F':
              $notification = 'start_mission';
              $log          = array('trt'    => '/* Début de mission (' . htmlspecialchars($mission['mission']) . ') */',
                                    'status' => 'KO',
                                    'infos'  => ''
                                   );
              break;

            case 'L':
              $notification = 'end_mission';
              $log          = array('trt'    => '/* Fin de mission (' . htmlspecialchars($mission['mission']) . ') */',
                                    'status' => 'KO',
                                    'infos'  => ''
                                   );
              break;

            default:
              break;
          }

          // Contrôle notification non existante
          $notificationMissionExist = controlNotification($notification, $mission['id_mission']);

          // Insertion notification
          if ($notificationMissionExist != true)
          {
            insertNotification('admin', $notification, $mission['id_mission']);

            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Notification insérée';
          }
          else
          {
            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Notification déjà insérée';
          }

          // Ajout du compte-rendu à la liste des logs
          array_push($listeLogsMissions, $log);
        }
      }
    }
    else
    {
      // Ajout des données au log
      $log['status'] = 'OK';
      $log['infos']  = 'Pas de sorties organisées';
    }

    // Retour
    return $listeLogsMissions;
  }

  // METIER : Attribution expérience fin de mission
  // RETOUR : Compte-rendu traitement
  function insertExperienceGagnants()
  {
    // Initialisations
    $chaineExecutee    = false;
    $dossierQuotidien  = '../cron/logs/daily';
    $listeLogsMissions = array();

    // Scan des fichiers présents par ordre décroissant
    $fichiersQuotidiens = scandir($dossierQuotidien, 1);

    // Suppression des racines du dossier de la liste
    unset($fichiersQuotidiens[array_search('..', $fichiersQuotidiens)]);
    unset($fichiersQuotidiens[array_search('.', $fichiersQuotidiens)]);

    // Détermination si la chaîne CRON est déjà passée
    if (!empty($fichiersQuotidiens))
    {
      // Tri sur date
      foreach ($fichiersQuotidiens as $fichier)
      {
        $triAnnee[]   = substr($fichier, 12, 4);
        $triMois[]    = substr($fichier, 9, 2);
        $triJour[]    = substr($fichier, 6, 2);
        $triHeure[]   = substr($fichier, 17, 2);
        $triMinute[]  = substr($fichier, 20, 2);
        $triSeconde[] = substr($fichier, 23, 2);
      }

      array_multisort($triAnnee, SORT_DESC, $triMois, SORT_DESC, $triJour, SORT_DESC, $triHeure, SORT_DESC, $triMinute, SORT_DESC, $triSeconde, SORT_DESC, $fichiersQuotidiens);

      // Test si CRON déjà passé
      foreach ($fichiersQuotidiens as $fichier)
      {
        // Récupération de la date du fichier
        $dateFichier = substr($fichier, 12, 4) . substr($fichier, 9, 2) . substr($fichier, 6, 2);

        // Si le premier fichier est antérieur à la date du jour, on arrête la boucle
        if ($dateFichier < date('Ymd'))
          break;

        // Si le fichier date du jour alors on sait qu'on a déjà attribué l'expérience
        if ($dateFichier == date('Ymd'))
        {
          $chaineExecutee = true;
          break;
        }
      }
    }

    // Traitement des participants des missions si la chaîne n'a pas été exécutée
    if ($chaineExecutee == false)
    {
      // Récupération de la date de la veille
      $dateMoins1 = date('Ymd', strtotime('now - 1 Days'));

      // Récupération des missions se terminant la veille
      $listeMissions = physiqueFinsMissionsVeille($dateMoins1);

      // Traitement de chaque mission
      if (!empty($listeMissions))
      {
        foreach ($listeMissions as $mission)
        {
          // Initialisation du log de mission
          $log = array('trt'    => '/* Expérience mission (' . htmlspecialchars($mission->getMission()) . ') */',
                       'status' => 'KO',
                       'infos'  => ''
                      );

          // Récupération des participants de la mission et de leur avancement
          $listeParticipants = physiqueParticipantsMission($mission->getId());

          if (!empty($listeParticipants))
          {
            // Tri sur avancement
            foreach ($listeParticipants as $participant)
            {
              $triRank[] = $participant['avancement'];
            }

            array_multisort($triRank, SORT_DESC, $listeParticipants);

            // Réinitialisation du tri pour la prochaine occurence
            unset($triRank);

            // Affectation du rang
            $prevTotal   = 0;
            $currentRank = 0;

            foreach ($listeParticipants as $identifiant => &$participant)
            {
              $currentTotal = $participant['avancement'];

              if ($currentTotal != $prevTotal)
              {
                $currentRank += 1;
                $prevTotal    = $currentTotal;
              }

              // Suppression des rangs > 3 sinon on enregistre le rang
              if ($currentRank > 3)
                unset($listeParticipants[$identifiant]);
              else
                $participant['rank'] = $currentRank;
            }

            unset($participant);

            // Ajout de l'expérience pour chaque gagnant
            foreach ($listeParticipants as $identifiant => $participant)
            {
              switch ($participant['rank'])
              {
                case 1:
                  insertExperience($identifiant, 'winner_mission_1');
                  break;

                case 2:
                  insertExperience($identifiant, 'winner_mission_2');
                  break;

                case 3:
                  insertExperience($identifiant, 'winner_mission_3');
                  break;

                default:
                  break;
              }
            }

            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Expérience attribuée';
          }
          else
          {
            // Ajout des données au log
            $log['status'] = 'OK';
            $log['infos']  = 'Pas de participants';
          }

          // Ajout du compte-rendu à la liste des logs
          array_push($listeLogsMissions, $log);
        }
      }
      else
      {
        // Ajout des données au log
        $log = array('trt'    => '/* Expérience missions */',
                     'status' => 'OK',
                     'infos'  => 'Pas de missions'
                    );

        // Ajout du compte-rendu à la liste des logs
        array_push($listeLogsMissions, $log);
      }
    }
    else
    {
      // Ajout des données au log
      $log = array('trt'    => '/* Expérience missions */',
                   'status' => 'OK',
                   'infos'  => 'Chaîne déjà exécutée'
                  );

      // Ajout du compte-rendu à la liste des logs
      array_push($listeLogsMissions, $log);
    }

    // Retour
    return $listeLogsMissions;
  }























  // METIER : Recalcul des dépenses pour tous les utilisateurs
  // RETOUR : Compte-rendu traitement
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

  // METIER : Création fichier log
  // RETOUR : Aucun
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
        $nomTrt    = $trt['trt'];
        $statutTrt = '## Status.....................' . $trt['status'];
        $infoTrt   = '## Informations...............' . $trt['infos'];
        fputs($log, "\r\n");
        fputs($log, $nomTrt);
        fputs($log, "\r\n");
        fputs($log, $statutTrt);
        fputs($log, "\r\n");
        fputs($log, $infoTrt);
        fputs($log, "\r\n");
      }
    }

    // Fermeture fichier
    fclose($log);
  }
?>
