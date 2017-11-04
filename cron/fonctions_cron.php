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

    $req = $bdd->query('SELECT id, date_doodle FROM movie_house WHERE date_doodle = ' . date("Ymd") . ' ORDER BY id ASC');

    while($data = $req->fetch())
    {
      // Contrôle notification non existante
      $notification_cinema_exist = controlNotification('cinema', $data['id']);

      // Génération notification sortie cinéma
      if ($notification_cinema_exist != true)
        insertNotification('admin', 'cinema', $data['id']);

      // Traitement effectué
      $log['status'] = 'OK';
    }

    if ($req->rowCount() == 0)
      $log['status'] = 'OK';

    $req->closeCursor();

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

    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant != "admin" AND reset != "I" ORDER BY identifiant ASC');
    while($data1 = $req1->fetch())
    {
      // On calcule le bilan des dépenses de l'utilisateur courant
      $bilan = 0;

      // Calcul des bilans
      $req2 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
      while($data2 = $req2->fetch())
      {
        // Prix d'achat
        $prix_achat = $data2['price'];

        // Identifiant de l'acheteur
        $acheteur   = $data2['buyer'];

        // Nombre de parts et prix par parts
        $nb_parts_total = 0;
        $nb_parts_user  = 0;

        $req3 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data2['id']);
        while($data3 = $req3->fetch())
        {
          // Nombre de parts total
          $nb_parts_total += $data3['parts'];

          // Nombre de parts de l'utilisateur
          if ($data1['identifiant'] == $data3['identifiant'])
            $nb_parts_user = $data3['parts'];
        }

        if ($nb_parts_total != 0)
          $prix_par_part = $prix_achat / $nb_parts_total;
        else
          $prix_par_part = 0;

        // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
        if ($data2['buyer'] == $data1['identifiant'])
          $bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_user);
        else
          $bilan = $bilan - ($prix_par_part * $nb_parts_user);

        $req3->closeCursor();
      }
      $req2->closeCursor();

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $data1['id'],
                      'identifiant' => $data1['identifiant'],
                      'bilan'       => $bilan,
                     );

      // On ajoute la ligne au tableau
      array_push($listeUsers, $myUser);
    }
    $req1->closeCursor();

    //var_dump($listeUsers);

    // Mise à jour des utilisateurs
    foreach ($listeUsers as $user)
    {
      $req4 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $user['identifiant'] . '"');
      $req4->execute(array(
        'expenses' => $user['bilan']
      ));
      $req4->closeCursor();

      // Traitement effectué
      $log['status'] = 'OK';
    }

    return $log;
  }

  // FONCTION : Création fichier log journalier
  // RETOUR : Aucun
  // Fréquence : tous les jours à 7h
  function generateJLog($daily_trt)
  {
    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "logs";

    if (!is_dir($dossier))
      mkdir($dossier);

    // Titre
    $titre_log = "/******************************/\r\n/* Traitement CRON journalier */\r\n/******************************/\r\n";

    // Type de traitement
    if (isset($_POST['daily_cron']))
      $type_log = "## Traitement asynchrone";
    else
      $type_log = "## Traitement automatique";

    // Date du traitement
    $date_log = "## Date................................." . date("d/m/Y");

    // Traitement global OK ou KO
    $control_ok = true;

    foreach ($daily_trt as $trt)
    {
      if ($trt['status'] == "KO")
      {
        $control_ok = false;
        break;
      }
    }

    if ($control_ok == true)
      $etat_log = "## Etat traitements.....................OK";
    else
      $etat_log = "## Etat traitements.....................KO";

    // Durée totale des traitements si non asynchrone (heure courante - 7 heures)
    if (!isset($_POST['daily_cron']))
    {
      $calc_duree = (date("H")*60*60 + date("i")*60 + date("s")) - 25200;
      $total      = $calc_duree;
      $heures     = intval(abs($total / 3600));
      $total      = $total - ($heures * 3600);
      $minutes    = intval(abs($total / 60));
      $total      = $total - ($minutes * 60);
      $secondes   = $total;
      $duree_log  = "## Durée traitements...................." . $heures . " heures, " . $minutes . " minutes et " . $secondes . " secondes";
    }

    // Ouverture / création fichier
    $myJLog = fopen('logs/jlog_(' . date("d-m-Y") . '_' . date("H-i-s") . ')_' . rand(1,11111111) . '.txt', 'a+');

    // On repositionne le curseur du fichier au début
    fseek($myJLog, 0);

    // On écrit dans le fichier
    fputs($myJLog, $titre_log);
    fputs($myJLog, "\r\n");
    fputs($myJLog, $type_log);
    fputs($myJLog, "\r\n");
    fputs($myJLog, $date_log);
    fputs($myJLog, "\r\n");
    fputs($myJLog, $etat_log);
    fputs($myJLog, "\r\n");
    if (!isset($_POST['daily_cron']))
    {
      fputs($myJLog, $duree_log);
      fputs($myJLog, "\r\n");
    }
    if (!empty($daily_trt))
    {
      foreach ($daily_trt as $trt)
      {
        fputs($myJLog, "\r\n");
        $nom_trt    = $trt['trt'];
        $statut_trt = "## Status..............................." . $trt['status'];
        fputs($myJLog, $nom_trt);
        fputs($myJLog, "\r\n");
        fputs($myJLog, $statut_trt);
        fputs($myJLog, "\r\n");
      }
    }

    // Fermeture fichier
    fclose($myJLog);
  }

  // FONCTION : Création fichier log hebdomadaire
  // RETOUR : Aucun
  // Fréquence : tous les lundis à 7h
  function generateHLog($weekly_trt)
  {
    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "logs";

    if (!is_dir($dossier))
      mkdir($dossier);

    // Titre
    $titre_log = "/********************************/\r\n/* Traitement CRON hebdomadaire */\r\n/********************************/\r\n";

    // Type de traitement
    if (isset($_POST['weekly_cron']))
      $type_log = "## Traitement asynchrone";
    else
      $type_log = "## Traitement automatique";

    // Date du traitement
    $date_log = "## Date................................." . date("d/m/Y");

    // Traitement global OK ou KO
    $control_ok = true;

    foreach ($weekly_trt as $trt)
    {
      if ($trt['status'] == "KO")
      {
        $control_ok = false;
        break;
      }
    }

    if ($control_ok == true)
      $etat_log = "## Etat traitements.....................OK";
    else
      $etat_log = "## Etat traitements.....................KO";

    // Durée totale des traitements si non asynchrone (heure courante - 7 heures)
    if (!isset($_POST['weekly_cron']))
    {
      $calc_duree = (date("H")*60*60 + date("i")*60 + date("s")) - 25200;
      $total      = $calc_duree;
      $heures     = intval(abs($total / 3600));
      $total      = $total - ($heures * 3600);
      $minutes    = intval(abs($total / 60));
      $total      = $total - ($minutes * 60);
      $secondes   = $total;
      $duree_log  = "## Durée traitements...................." . $heures . " heures, " . $minutes . " minutes et " . $secondes . " secondes";
    }

    // Ouverture / création fichier
    $myHLog = fopen('logs/hlog_(' . date("d-m-Y") . '_' . date("H-i-s") . ')_' . rand(1,11111111) . '.txt', 'a+');

    // On repositionne le curseur du fichier au début
    fseek($myHLog, 0);

    // On écrit dans le fichier
    fputs($myHLog, $titre_log);
    fputs($myHLog, "\r\n");
    fputs($myHLog, $type_log);
    fputs($myHLog, "\r\n");
    fputs($myHLog, $date_log);
    fputs($myHLog, "\r\n");
    fputs($myHLog, $etat_log);
    fputs($myHLog, "\r\n");
    if (!isset($_POST['weekly_cron']))
    {
      fputs($myHLog, $duree_log);
      fputs($myHLog, "\r\n");
    }
    if (!empty($weekly_trt))
    {
      foreach ($weekly_trt as $trt)
      {
        fputs($myHLog, "\r\n");
        $nom_trt    = $trt['trt'];
        $statut_trt = "## Status..............................." . $trt['status'];
        fputs($myHLog, $nom_trt);
        fputs($myHLog, "\r\n");
        fputs($myHLog, $statut_trt);
        fputs($myHLog, "\r\n");
      }
    }

    // Fermeture fichier
    fclose($myHLog);
  }
?>
