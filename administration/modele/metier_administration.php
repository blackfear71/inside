<?php
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/classes/alerts.php');
  include_once('../includes/classes/bugs.php');
  include_once('../includes/classes/calendars.php');
  include_once('../includes/classes/missions.php');
  include_once('../includes/classes/movies.php');
  include_once('../includes/classes/profile.php');
  include_once('../includes/classes/success.php');
  include_once('../includes/libraries/php/imagethumb.php');


















  // METIER : Lecture liste des succès
  // RETOUR : Liste des succès
  function getSuccess()
  {
    $listSuccess = array();

    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM success');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Success à partir des données remontées de la bdd
      $mySuccess = Success::withData($donnees);
      array_push($listSuccess, $mySuccess);
    }
    $reponse->closeCursor();

    // Tri sur niveau puis ordonnancement
    foreach ($listSuccess as $success)
    {
      $tri_level[] = $success->getLevel();
      $tri_order[] = $success->getOrder_success();
    }
    array_multisort($tri_level, SORT_ASC, $tri_order, SORT_ASC, $listSuccess);

    return $listSuccess;
  }

  // METIER : Insertion nouveau succès
  // RETOUR : Aucun
  function insertSuccess($post, $files)
  {
    $reference     = $post['reference'];
    $level         = $post['level'];
    $order_success = $post['order_success'];
    $defined       = "N";
    $title         = $post['title'];
    $description   = $post['description'];
    $limit_success = $post['limit_success'];
    $explanation   = $post['explanation'];

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['reference_success']   = $reference;
    $_SESSION['save']['level']               = $level;
    $_SESSION['save']['order_success']       = $order_success;
    $_SESSION['save']['title_success']       = $title;
    $_SESSION['save']['description_success'] = $description;
    $_SESSION['save']['limit_success']       = $limit_success;
    $_SESSION['save']['explanation_success'] = $explanation;

    $control_ok = true;
    global $bdd;

    // Contrôle référence
    $reponse = $bdd->query('SELECT * FROM success');
    while($donnees = $reponse->fetch())
    {
      if ($reference == $donnees['reference'])
      {
        $control_ok = false;
        $_SESSION['alerts']['already_referenced'] = true;
        break;
      }
    }
    $reponse->closeCursor();

    // Contrôles niveau
    if ($control_ok == true)
    {
      if (!is_numeric($level) OR $level <= 0)
      {
        $control_ok                    = false;
        $_SESSION['alerts']['level_not_numeric'] = true;
      }
    }

    // Contrôles ordonnancement
    if ($control_ok == true)
    {
      if (is_numeric($order_success))
      {
        $reponse = $bdd->query('SELECT * FROM success WHERE level = ' . $level);
        while($donnees = $reponse->fetch())
        {
          if ($order_success == $donnees['order_success'])
          {
            $control_ok                  = false;
            $_SESSION['alerts']['already_ordered'] = true;
            break;
          }
        }
        $reponse->closeCursor();
      }
      else
      {
        $control_ok                    = false;
        $_SESSION['alerts']['order_not_numeric'] = true;
      }
    }

    // Contrôle condition
    if ($control_ok == true)
    {
      if (!is_numeric($limit_success))
      {
        $control_ok                    = false;
        $_SESSION['alerts']['limit_not_numeric'] = true;
      }
    }

    // Si contrôles ok, insertion image puis table
    if ($control_ok == true)
    {
      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../includes/images/profil";

      if (!is_dir($dossier))
         mkdir($dossier);

      // On contrôle la présence du dossier des succès, sinon on le créé
      $dossier_succes = $dossier . '/success';

      if (!is_dir($dossier_succes))
         mkdir($dossier_succes);

      // Insertion image
      // Si on a bien une image
   		if ($files['success']['name'] != NULL)
   		{
   			// Dossier de destination
   			$success_dir = $dossier_succes . '/';

   			// Données du fichier
   			$file      = $files['success']['name'];
   			$tmp_file  = $files['success']['tmp_name'];
   			$size_file = $files['success']['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
   			if ($size_file < $maxsize)
   			{
   				// Contrôle fichier temporaire existant
   				if (!is_uploaded_file($tmp_file))
   					exit("Le fichier est introuvable");

   				// Contrôle type de fichier
   				$type_file = $files['success']['type'];

   				if (!strstr($type_file, 'png'))
   					exit("Le fichier n'est pas une image valide");
   				else
   				{
   					$type_image = pathinfo($file, PATHINFO_EXTENSION);
   					$new_name   = $reference . '.' . $type_image;
   				}

   				// Contrôle upload (si tout est bon, l'image est envoyée)
   				if (!move_uploaded_file($tmp_file, $success_dir . $new_name))
   				{
   					exit("Impossible de copier le fichier dans $success_dir");
   				}

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
   				imagethumb($success_dir . $new_name, $success_dir . $new_name, 500, FALSE, TRUE);

   				// echo "Le fichier a bien été uploadé";

   				// On stocke le nouveau succès dans la base
          $reponse = $bdd->prepare('INSERT INTO success(reference,
                                                        level,
                                                        order_success,
                                                        defined,
                                                        title,
                                                        description,
                                                        limit_success,
                                                        explanation)
                                                 VALUES(:reference,
                                                        :level,
                                                        :order_success,
                                                        :defined,
                                                        :title,
                                                        :description,
                                                        :limit_success,
                                                        :explanation)');
  				$reponse->execute(array(
  					'reference'     => $reference,
            'level'         => $level,
            'order_success' => $order_success,
            'defined'       => $defined,
            'title'         => $title,
            'description'   => $description,
  					'limit_success' => $limit_success,
            'explanation'   => $explanation
  					));
  				$reponse->closeCursor();

   				$_SESSION['alerts']['success_added'] = true;
   			}
   		}
    }
  }

  // METIER : Suppression succès
  // RETOUR : Aucun
  function deleteSuccess($post)
  {
    $id_success = $post['id_success'];

    global $bdd;

    // Suppression de l'image
    $req1 = $bdd->query('SELECT id, reference FROM success WHERE id = ' . $id_success);
    $data1 = $req1->fetch();

    if (isset($data1['reference']) AND !empty($data1['reference']))
    {
      $reference = $data1['reference'];
      unlink ("../includes/images/profil/success/" . $data1['reference'] . ".png");
    }

    $req1->closeCursor();

    // Suppression des données utilisateurs
    $req2 = $bdd->exec('DELETE FROM success_users WHERE reference = "' . $reference . '"');

    // Suppression du succès de la base
    $req3 = $bdd->exec('DELETE FROM success WHERE id = ' . $id_success);

    $_SESSION['alerts']['success_deleted'] = true;
  }


  // METIER : Modification succès
  // RETOUR : Aucun
  function updateSuccess($post)
  {
    $update     = array();
    $control_ok = true;
    $erreur     = NULL;

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['save_success'] = $post;

    // Construction tableau pour mise à jour
    foreach ($post['id'] as $id)
    {
      $myUpdate = array('id'            => $post['id'][$id],
                        'level'         => $post['level'][$id],
                        'order_success' => $post['order_success'][$id],
                        'defined'       => $post['defined'][$id],
                        'title'         => $post['title'][$id],
                        'description'   => $post['description'][$id],
                        'limit_success' => $post['limit_success'][$id],
                        'explanation'   => $post['explanation'][$id],
                       );
      array_push($update, $myUpdate);
    }

    global $bdd;

    foreach ($update as $success)
    {
      // Contrôles niveau
      if ($control_ok == true)
      {
        if (!is_numeric($success['level']) OR $success['level'] <= 0)
        {
          $control_ok                    = false;
          $_SESSION['alerts']['level_not_numeric'] = true;
        }
      }

      // Contrôles ordonnancement
      if ($control_ok == true)
      {
        if (is_numeric($success['order_success']))
        {
          // Contrôle doublons
          foreach ($update as $test_order)
          {
            if ($success['id'] != $test_order['id'] AND $success['order_success'] == $test_order['order_success'] AND $success['level'] == $test_order['level'])
            {
              $control_ok = false;
              $_SESSION['alerts']['already_ordered'] = true;
              break;
            }
          }
        }
        else
        {
          $control_ok = false;
          $_SESSION['alerts']['order_not_numeric'] = true;
        }
      }

      // Contrôle condition
      if ($control_ok == true)
      {
        if (!is_numeric($success['limit_success']))
        {
          $control_ok = false;
          $_SESSION['alerts']['limit_not_numeric'] = true;
        }
      }

      // Mise à jour si pas d'erreur
      if ($control_ok == true)
      {
        $req = $bdd->prepare('UPDATE success SET level         = :level,
                                                 order_success = :order_success,
                                                 defined       = :defined,
                                                 title         = :title,
                                                 description   = :description,
                                                 limit_success = :limit_success,
                                                 explanation   = :explanation
                                           WHERE id = ' . $success['id']);
        $req->execute(array(
          'level'         => $success['level'],
          'order_success' => $success['order_success'],
          'defined'       => $success['defined'],
          'title'         => $success['title'],
          'description'   => $success['description'],
          'limit_success' => $success['limit_success'],
          'explanation'   => $success['explanation']
        ));
        $req->closeCursor();
      }

      // On quitte la boucle s'il y a une erreur
      if ($control_ok != true)
        break;
    }

    if ($control_ok != true)
      $erreur = true;
    else
      $_SESSION['alerts']['success_updated'] = true;

    return $erreur;
  }

  // METIER : Initialisation champs erreur modification succès
  // RETOUR : Tableau sauvegardé et trié
  function initModErrSucces($listSuccess, $session_succes)
  {
    foreach ($listSuccess as $success)
    {
      $success->setLevel($session_succes['level'][$success->getId()]);
      $success->setOrder_success($session_succes['order_success'][$success->getId()]);
      $success->setDefined($session_succes['defined'][$success->getId()]);
      $success->setTitle($session_succes['title'][$success->getId()]);
      $success->setDescription($session_succes['description'][$success->getId()]);
      $success->setLimit_success($session_succes['limit_success'][$success->getId()]);
      $success->setExplanation($session_succes['explanation'][$success->getId()]);

      // Tri sur niveau puis ordonnancement
      $tri_level[] = $success->getLevel();
      $tri_order[] = $success->getOrder_success();
    }

    array_multisort($tri_level, SORT_ASC, $tri_order, SORT_ASC, $listSuccess);

    return $listSuccess;
  }



  // METIER : Initialisation des succès
  // RETOUR : Aucun
  function initializeSuccess($listSuccess, $listUsers)
  {
    global $bdd;

    if (!empty($listSuccess) AND !empty($listUsers))
    {
      // Boucle de traitement sur les utilisateurs
      foreach ($listUsers as $user)
      {
        // Boucle de traitement sur les succès
        foreach ($listSuccess as $success)
        {
          $value  = NULL;
          $action = NULL;

          /**************************************/
          /*** Détermination valeur à insérer ***/
          /**************************************/
          switch ($success->getReference())
          {
            // J'étais là
            case "beginning":
            // Je l'ai fait !
            case "developper":
              $req = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();

              if ($req->rowCount() > 0)
                $value = $data['value'];

              $req->closeCursor();
              break;

            // Cinéphile amateur
            case "publisher":
              $nb_films_publies = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_films_publies FROM movie_house WHERE identifiant_add = "' . $user->getIdentifiant() . '" AND to_delete != "Y"');
              $data = $req->fetch();
              $nb_films_publies = $data['nb_films_publies'];
              $req->closeCursor();

              $value = $nb_films_publies;
              break;

            // Cinéphile professionnel
            case "viewer":
              $nb_films_vus = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_films_vus FROM movie_house_users WHERE identifiant = "' . $user->getIdentifiant() . '" AND participation = "S"');
              $data = $req->fetch();
              $nb_films_vus = $data['nb_films_vus'];
              $req->closeCursor();

              $value = $nb_films_vus;
              break;

            // Commentateur sportif
            case "commentator":
              $nb_commentaires_films = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_commentaires_films FROM movie_house_comments WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_commentaires_films = $data['nb_commentaires_films'];
              $req->closeCursor();

              $value = $nb_commentaires_films;
              break;

            // Expert acoustique
            case "listener":
              $nb_collector_publiees = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_collector_publiees FROM collector WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_collector_publiees = $data['nb_collector_publiees'];
              $req->closeCursor();

              $value = $nb_collector_publiees;
              break;

            // Dommage collatéral
            case "speaker":
              $nb_collector_speaker = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_collector_speaker FROM collector WHERE speaker = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_collector_speaker = $data['nb_collector_speaker'];
              $req->closeCursor();

              $value = $nb_collector_speaker;
              break;

            // Rigolo compulsif
            case "funny":
              $nb_collector_user = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_collector_user FROM collector_users WHERE identifiant = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_collector_user = $data['nb_collector_user'];
              $req->closeCursor();

              $value = $nb_collector_user;
              break;

            // Auto-satisfait
            case "self-satisfied":
              $nb_auto_voted = 0;

              $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_auto_voted
                                  FROM collector
                                  LEFT JOIN collector_users
                                  ON (collector.id = collector_users.id_collector AND collector_users.identifiant = "' . $user->getIdentifiant() . '")
                                  WHERE collector.speaker = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_auto_voted = $data['nb_auto_voted'];
              $req->closeCursor();

              $value = $nb_auto_voted;
              break;

            // Désigné volontaire
            case "buyer":
              $nb_buyer = 0;

              $req = $bdd->query('SELECT COUNT(expense_center.id) AS nb_buyer
                                  FROM expense_center
                                  WHERE (expense_center.buyer = "' . $user->getIdentifiant() . '" AND expense_center.price > 0)
                                  AND EXISTS (SELECT * FROM expense_center_users
                                              WHERE (expense_center.id = expense_center_users.id_expense))');
              $data = $req->fetch();
              $nb_buyer = $data['nb_buyer'];
              $req->closeCursor();

              $value = $nb_buyer;
              break;

            // Profiteur occasionnel
            case "eater":
              $nb_parts = 0;

              $req = $bdd->query('SELECT * FROM expense_center_users WHERE identifiant = "' . $user->getIdentifiant() . '"');
              while($data = $req->fetch())
              {
                $nb_parts += $data['parts'];
              }
              $req->closeCursor();

              $value = $nb_parts;
              break;

            // Mer il et fou !
            case "generous":
              $nb_expense_no_parts = 0;

              $req = $bdd->query('SELECT COUNT(expense_center.id) AS nb_expense_no_parts
                                  FROM expense_center
                                  WHERE (expense_center.buyer = "' . $user->getIdentifiant() . '" AND expense_center.price > 0)
                                  AND NOT EXISTS (SELECT * FROM expense_center_users
                                                  WHERE (expense_center.id = expense_center_users.id_expense
                                                  AND    expense_center_users.identifiant = "' . $user->getIdentifiant() . '"))');
              $data = $req->fetch();
              $nb_expense_no_parts = $data['nb_expense_no_parts'];
              $req->closeCursor();

              $value = $nb_expense_no_parts;
              break;

            // Economie de marché (doit être initialisé manuellement si réinitialisation des succès)
            case "greedy":
              $bilan = 0;

              $req0 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user->getIdentifiant() . '"');
              $data0 = $req0->fetch();
              $bilan = $data0['expenses'];
              $req0->closeCursor();

              // Contrôle si total inférieur au total précédent
              $req1 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $data1 = $req1->fetch();

              if ($req1->rowCount() > 0)
              {
                if ($bilan > $data1['value'])
                  $value = $bilan;
              }
              else
                $value = $bilan;

              $req1->closeCursor();
              break;

            // Génie créatif
            case "creator":
              $nb_idees_publiees = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_idees_publiees FROM ideas WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_idees_publiees = $data['nb_idees_publiees'];
              $req->closeCursor();

              $value = $nb_idees_publiees;
              break;

            // Top développeur
            case "applier":
              $nb_idees_resolues = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_idees_resolues FROM ideas WHERE developper = "' . $user->getIdentifiant() . '" AND status = "D"');
              $data = $req->fetch();
              $nb_idees_resolues = $data['nb_idees_resolues'];
              $req->closeCursor();

              $value = $nb_idees_resolues;
              break;

            // Débugger aguerri
            case "debugger":
              $nb_bugs_publies = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_bugs_publies FROM bugs WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_bugs_publies = $data['nb_bugs_publies'];
              $req->closeCursor();

              $value = $nb_bugs_publies;
              break;

            // Compilateur intégré
            case "compiler":
              $nb_bugs_resolus = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_bugs_resolus FROM bugs WHERE author = "' . $user->getIdentifiant() . '" AND resolved = "Y"');
              $data = $req->fetch();
              $nb_bugs_resolus = $data['nb_bugs_resolus'];
              $req->closeCursor();

              $value = $nb_bugs_resolus;
              break;

            // Véritable Jedi
            case "padawan":
              $star_wars_8 = 0;

              // Date de sortie du film
              $req0 = $bdd->query('SELECT id, date_theater FROM movie_house WHERE id = 16');
              $data0 = $req0->fetch();
              $date_sw8 = $data0['date_theater'];
              $req0->closeCursor();

              if (date("Ymd") >= $date_sw8)
              {
                // Participation utilisateur
                $req1 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = 16 AND identifiant = "' . $user->getIdentifiant() . '" AND participation = "S"');
                $data1 = $req1->fetch();

                if ($req1->rowCount() > 0)
                  $star_wars_8 = 1;

                $req1->closeCursor();

                $value = $star_wars_8;
              }
              break;

            // Radar à bouffe (doit être initialisé manuellement si réinitialisation des succès)
            case "restaurant-finder":
              $value = 0;
              break;

            // Chef étoilé
            case "star-chief":
              $nb_repas_organises = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_repas_organises FROM food_advisor_choices WHERE caller = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_repas_organises = $data['nb_repas_organises'];
              $req->closeCursor();

              $value = $nb_repas_organises;
              break;

            // Cuisto expérimental
            case "cooker":
              $nb_gateaux_realises = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_gateaux_realises FROM cooking_box WHERE identifiant = "' . $user->getIdentifiant() . '" AND cooked = "Y"');
              $data = $req->fetch();
              $nb_gateaux_realises = $data['nb_gateaux_realises'];
              $req->closeCursor();

              $value = $nb_gateaux_realises;
              break;

            // Maître pâtissier
            case "recipe-master":
              $nb_recettes_saisies = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_recettes_saisies FROM cooking_box WHERE identifiant = "' . $user->getIdentifiant() . '" AND name != "" AND picture != ""');
              $data = $req->fetch();
              $nb_recettes_saisies = $data['nb_recettes_saisies'];
              $req->closeCursor();

              $value = $nb_recettes_saisies;
              break;

            // Lutin de Noël
            case "christmas2017":
            // Je suis ton Père Noël !
            case "christmas2017_2":
            // Un coeur en or
            case "golden-egg":
            // Mettre tous ses oeufs dans le même panier
            case "rainbow-egg":
            // Apprenti sorcier
            case "wizard":
            // Le plein de cadeaux !
            case "christmas2018":
            // C'est tout ce que j'ai ?!
            case "christmas2018_2":
            // Première étoile
            case "christmas2019":
              $mission = 0;

              if ($success->getReference() == "christmas2017" OR $success->getReference() == "christmas2017_2")
                $reference = "noel_2017";
              elseif ($success->getReference() == "golden-egg" OR $success->getReference() == "rainbow-egg")
                $reference = "paques_2018";
              elseif ($success->getReference() == "wizard")
                $reference = "halloween_2018";
              elseif ($success->getReference() == "christmas2018" OR $success->getReference() == "christmas2018_2")
                $reference = "noel_2018";
              elseif ($success->getReference() == "christmas2019")
                $reference = "noel_2019";

              // Récupération Id mission et date de fin
              $req0 = $bdd->query('SELECT * FROM missions WHERE reference = "' . $reference . '"');
              $data0 = $req0->fetch();

              $id_mission = $data0['id'];
              $date_fin   = $data0['date_fin'];

              $req0->closeCursor();

              if (date('Ymd') > $date_fin)
              {
                // Nombre total d'objectifs sur la mission
                $req1 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id_mission . ' AND identifiant = "' . $user->getIdentifiant() . '"');
                while($data1 = $req1->fetch())
                {
                  $mission += $data1['avancement'];
                }
                $req1->closeCursor();
              }

              $value = $mission;
              break;

            default:
              break;
          }

          /****************************************/
          /*** Détermination action à effectuer ***/
          /****************************************/
          if (!is_null($value))
          {
            if ($value != 0)
            {
              $req2 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $data2 = $req2->fetch();

              if ($req2->rowCount() > 0)
              {
                // On ne met à jour que si la nouvelle valeur est supérieure à l'ancienne
                if ($value > $data2['value'])
                  $action = 'update';
              }
              else
                $action = 'insert';

              $req2->closeCursor();
            }
          }

          /*************************************************/
          /*** Insertion / modification de chaque succès ***/
          /*************************************************/
          switch ($action)
          {
            case 'insert':
              $req3 = $bdd->prepare('INSERT INTO success_users(reference,
                                                               identifiant,
                                                               value)
                                                        VALUES(:reference,
                                                               :identifiant,
                                                               :value)');
              $req3->execute(array(
                'reference'   => $success->getReference(),
                'identifiant' => $user->getIdentifiant(),
                'value'       => $value
                ));
              $req3->closeCursor();
              break;

            case 'update':
              $req3 = $bdd->prepare('UPDATE success_users
                                     SET value = :value
                                     WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $req3->execute(array(
                'value' => $value
              ));
              $req3->closeCursor();
              break;

            default:
              break;
          }

          /***************************************/
          /*** Purge éventuelle des succès à 0 ***/
          /***************************************/
          $req4 = $bdd->exec('DELETE FROM success_users WHERE value = 0');
        }
      }

      $_SESSION['alerts']['success_initialized'] = true;
    }
  }













  // METIER : Récupération des missions
  // RETOUR : Objets mission
  function getMissions()
  {
    $missions = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);

      if (date('Ymd') < $myMission->getDate_deb() OR (date('Ymd') == $myMission->getDate_deb() AND date('His') < $myMission->getHeure()))
        $myMission->setStatut('V');
      elseif (((date('Ymd') == $myMission->getDate_deb() AND date('His') >= $myMission->getHeure()) OR date('Ymd') > $myMission->getDate_deb()) AND date('Ymd') <= $myMission->getDate_fin())
        $myMission->setStatut('C');
      elseif (date('Ymd') > $myMission->getDate_fin())
        $myMission->setStatut('A');

      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    // Tri sur statut (V : à venir, C : en cours, A : ancienne)
    foreach ($missions as $mission)
    {
      $tri_statut[]   = $mission->getStatut();
      $tri_date_deb[] = $mission->getDate_deb();
    }

    array_multisort($tri_statut, SORT_DESC, $tri_date_deb, SORT_DESC, $missions);

    return $missions;
  }

  // METIER : Initialisation ajout mission
  // RETOUR : Objets mission
  function initAddMission()
  {
    $mission = new Mission();
    return $mission;
  }

  // METIER : Récupération mission spécifique pour modification
  // RETOUR : Objet mission
  function initModMission($id)
  {
    $mission = new Mission();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE id = ' . $id);
    $donnees = $reponse->fetch();

    $mission = Mission::withData($donnees);

    $reponse->closeCursor();

    return $mission;
  }

  // METIER : Initialisation mission en cas d'erreur de saisie (ajout et modification)
  // RETOUR : Objet mission
  function initErrMission($save, $id_mission)
  {
    $save_mission = new Mission();

    if (!empty($id_mission))
      $save_mission->setId($id_mission);

    $save_mission->setMission($save['mission']);
    $save_mission->setDate_deb($save['date_deb']);
    $save_mission->setDate_fin($save['date_fin']);
    $save_mission->setHeure($save['heures'] . $save['minutes'] . '00');
    $save_mission->setDescription($save['description']);
    $save_mission->setReference($save['reference']);
    $save_mission->setObjectif($save['objectif']);
    $save_mission->setExplications($save['explications']);
    $save_mission->setConclusion($save['conclusion']);

    return $save_mission;
  }

  // METIER : Récupération des participants d'une mission
  // RETOUR : Objets Profil
  function getParticipants($id)
  {
    $participants = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT identifiant FROM missions_users WHERE id_mission = ' . $id . ' ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
      $donnees2 = $reponse2->fetch();

      $myParticipant = Profile::withData($donnees2);

      $reponse2->closeCursor();

      array_push($participants, $myParticipant);
    }
    $reponse->closeCursor();

    return $participants;
  }

  // METIER : Classement des utilisateurs sur la mission
  // RETOUR : Tableau classement
  function getRankingMission($id, $users)
  {
    $ranking = array();

    global $bdd;

    foreach ($users as $user)
    {
      $totalMission = 0;
      $initRankUser = 0;

      // Nombre total d'objectifs sur la mission
      $reponse = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id . ' AND identifiant = "' . $user->getIdentifiant() . '"');
      while($donnees = $reponse->fetch())
      {
        $totalMission += $donnees['avancement'];
      }
      $reponse->closeCursor();

      $myRanking = array('identifiant' => $user->getIdentifiant(),
                         'pseudo'      => $user->getPseudo(),
                         'avatar'      => $user->getAvatar(),
                         'total'       => $totalMission,
                         'rank'        => $initRankUser
                       );

      array_push($ranking, $myRanking);
    }

    if (!empty($ranking))
    {
      // Tri sur avancement puis identifiant
      foreach ($ranking as $rankUser)
      {
        $tri_rank[]  = $rankUser['total'];
        $tri_alpha[] = $rankUser['identifiant'];
      }

      array_multisort($tri_rank, SORT_DESC, $tri_alpha, SORT_ASC, $ranking);

      // Affectation du rang
      $prevTotal   = $ranking[0]['total'];
      $currentRank = 1;

      foreach ($ranking as &$rankUser)
      {
        $currentTotal = $rankUser['total'];

        if ($currentTotal != $prevTotal)
        {
          $currentRank += 1;
          $prevTotal = $rankUser['total'];
        }

        $rankUser['rank'] = $currentRank;
      }

      unset($rankUser);
    }

    return $ranking;
  }

  // METIER : Insertion d'une nouvelle mission
  // RETOUR : Erreur éventuelle
  function insertMission($post, $files)
  {
    global $bdd;

    // Récupération des données
    $mission      = $post['mission'];
    $date_deb     = $post['date_deb'];
    $date_fin     = $post['date_fin'];
    $heures       = $post['heures'];
    $minutes      = $post['minutes'];
    $description  = $post['description'];
    $reference    = $post['reference'];
    $objectif     = $post['objectif'];
    $explications = $post['explications'];
    $conclusion   = $post['conclusion'];

    // Sauvegarde des données
    $_SESSION['save']['new_mission'] = array('post' => $post, 'files' => $files);
    $control_ok                      = true;

    //var_dump($_SESSION['save']);
    //var_dump($_SESSION['save']['new_mission']['post']);
    //var_dump($_SESSION['save']['new_mission']['files']);

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(" ", "é", "è", "ê", "ë", "à", "â", "ç", "ô", "û");
    $replace   = array("_", "e", "e", "e", "e", "a", "a", "c", "o", "u");
    $reference = str_replace($search, $replace, $reference);

    // Formatage heure
    $heure = $heures . $minutes . '00';

    // Contrôle référence unique
    $req1 = $bdd->query('SELECT * FROM missions WHERE reference = "' . $reference . '"');
    if ($req1->rowCount() > 0)
    {
      $_SESSION['alerts']['already_ref_mission'] = true;
      $control_ok                                = false;
    }
    $req1->closeCursor();

    // Contrôle format date début
    if ($control_ok == true)
    {
      if (validateDate($date_deb, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_deb = formatDateForInsert($date_deb);
    }

    // Contrôle format date fin
    if ($control_ok == true)
    {
      if (validateDate($date_fin, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_fin = formatDateForInsert($date_fin);
    }

    // Contrôle date début <= date fin
    if ($control_ok == true)
    {
      if ($date_fin < $date_deb)
      {
        $_SESSION['alerts']['date_less'] = true;
        $control_ok                      = false;
      }
    }

    // Contrôle objectif > 0
    if ($control_ok == true)
    {
      if (!is_numeric($objectif) OR $objectif <= 0)
      {
        $_SESSION['alerts']['objective_not_numeric'] = true;
        $control_ok                                  = false;
      }
    }

    // Contrôle images présentes
    if ($control_ok == true)
    {
      foreach ($files as $file)
      {
        if (empty($file['name']) OR $file['name'] == NULL)
        {
          $_SESSION['alerts']['missing_mission_file'] = true;
          $control_ok                                 = false;
        }
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // On contrôle la présence du dossier des images, sinon on le créé
      $dossier = "../includes/images/missions";

      if (!is_dir($dossier))
        mkdir($dossier);

      // On contrôle la présence du dossier des bannières, sinon on le créé
      $dossier_images = $dossier . "/banners";

      if (!is_dir($dossier_images))
        mkdir($dossier_images);

      // On contrôle la présence du dossier des boutons, sinon on le créé
      $dossier_icones = $dossier . "/buttons";

      if (!is_dir($dossier_icones))
        mkdir($dossier_icones);

      foreach ($files as $key_file => $file)
      {
        // Dossier de destination
        if ($key_file == "mission_image")
          $dest_dir = $dossier_images . '/';
        else
          $dest_dir = $dossier_icones . '/';

        // Fichier
        $name_file = $file['name'];
        $tmp_file  = $file['tmp_name'];
        $size_file = $file['size'];
        $type_file = $file['type'];

        // Taille max
        $maxsize = 8388608; // 8Mo

        // Nouveau nom
        switch ($key_file)
        {
          case "mission_icone_g":
            $new_name = $reference . '_g';
            break;

          case "mission_icone_m":
            $new_name = $reference . '_m';
            break;

          case "mission_icone_d":
            $new_name = $reference . '_d';
            break;

          case "mission_image":
          default:
            $new_name = $reference;
            break;
        }

        // Si le fichier n'est pas trop grand
        if ($size_file < $maxsize)
        {
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
          {
            $_SESSION['alerts']['wrong_file'] = true;
            $control_ok                       = false;
            // exit("Le fichier est introuvable");
          }

          // Contrôle type de fichier
          if (!strstr($type_file, 'png'))
          {
            $_SESSION['alerts']['wrong_file'] = true;
            $control_ok                       = false;
            // exit("Le fichier n'est pas une image valide");
          }

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $dest_dir . $new_name . '.png'))
          {
            $_SESSION['alerts']['wrong_file'] = true;
            $control_ok                       = false;
            // exit("Impossible de copier le fichier dans $dest_dir");
          }

          /*if ($control_ok == true)
            echo "Le fichier a bien été uploadé";*/
        }
      }
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('INSERT INTO missions(mission,
                                                  reference,
                                                  date_deb,
                                                  date_fin,
                                                  heure,
                                                  objectif,
                                                  description,
                                                  explications,
                                                  conclusion)
                                          VALUES(:mission,
                                                 :reference,
                                                 :date_deb,
                                                 :date_fin,
                                                 :heure,
                                                 :objectif,
                                                 :description,
                                                 :explications,
                                                 :conclusion)');
      $req2->execute(array(
        'mission'      => $mission,
        'reference'    => $reference,
        'date_deb'     => $date_deb,
        'date_fin'     => $date_fin,
        'heure'        => $heure,
        'objectif'     => $objectif,
        'description'  => $description,
        'explications' => $explications,
        'conclusion'   => $conclusion
        ));
      $req2->closeCursor();

      $_SESSION['alerts']['mission_added'] = true;
    }

    if ($control_ok != true)
      $erreur_mission = true;
    else
      $erreur_mission = NULL;

    return $erreur_mission;
  }

  // METIER : Modification d'une mission existante
  // RETOUR : Id mission
  function updateMission($post, $files)
  {
    global $bdd;

    // Récupération des données
    $id_mission   = $post['id_mission'];
    $mission      = $post['mission'];
    $date_deb     = $post['date_deb'];
    $date_fin     = $post['date_fin'];
    $heures       = $post['heures'];
    $minutes      = $post['minutes'];
    $description  = $post['description'];
    $reference    = $post['reference'];
    $objectif     = $post['objectif'];
    $explications = $post['explications'];
    $conclusion   = $post['conclusion'];

    // Sauvegarde des données
    $_SESSION['save']['old_mission'] = array('post' => $post, 'files' => $files);
    $control_ok                      = true;

    //var_dump($_SESSION['save']);
    //var_dump($_SESSION['save']['old_mission']['post']);
    //var_dump($_SESSION['save']['old_mission']['files']);

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(" ", "é", "è", "ê", "ë", "à", "â", "ç", "ô", "û");
    $replace   = array("_", "e", "e", "e", "e", "a", "a", "c", "o", "u");
    $reference = str_replace($search, $replace, $reference);

    // Formatage heure
    $heure = $heures . $minutes . '00';

    // Contrôle format date début
    if ($control_ok == true)
    {
      if (validateDate($date_deb, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_deb = formatDateForInsert($date_deb);
    }

    // Contrôle format date fin
    if ($control_ok == true)
    {
      if (validateDate($date_fin, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_fin = formatDateForInsert($date_fin);
    }

    // Contrôle date début <= date fin
    if ($control_ok == true)
    {
      if ($date_fin < $date_deb)
      {
        $_SESSION['alerts']['date_less'] = true;
        $control_ok                      = false;
      }
    }

    // Contrôle objectif > 0
    if ($control_ok == true)
    {
      if (!is_numeric($objectif) OR $objectif <= 0)
      {
        $_SESSION['alerts']['objective_not_numeric'] = true;
        $control_ok                                  = false;
      }
    }

    // Contrôle images présentes, si présentes alors on modifie l'image
    if ($control_ok == true)
    {
      foreach ($files as $key_file => $file)
      {
        if (!empty($file['name']) AND !$file['name'] == NULL)
        {
          // Chemins
          $dossier_images = "../includes/images/missions/banners";
          $dossier_icones = "../includes/images/missions/buttons";

          // Dossier de destination
          if ($key_file == "mission_image")
            $dest_dir = $dossier_images . '/';
          else
            $dest_dir = $dossier_icones . '/';

          // Fichier
          $name_file = $file['name'];
          $tmp_file  = $file['tmp_name'];
          $size_file = $file['size'];
          $type_file = $file['type'];

          // Taille max
          $maxsize = 8388608; // 8Mo

          // Nouveau nom
          switch ($key_file)
          {
            case "mission_icone_g":
              $new_name = $reference . '_g';
              break;

            case "mission_icone_m":
              $new_name = $reference . '_m';
              break;

            case "mission_icone_d":
              $new_name = $reference . '_d';
              break;

            case "mission_image":
            default:
              $new_name = $reference;
              break;
          }

          // Suppression ancienne image
          unlink ($dest_dir . $new_name . '.png');

          // Insertion nouvelle image
          if ($size_file < $maxsize)
          {
            // Contrôle fichier temporaire existant
            if (!is_uploaded_file($tmp_file))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
              // exit("Le fichier est introuvable");
            }

            // Contrôle type de fichier
            if (!strstr($type_file, 'png'))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
              // exit("Le fichier n'est pas une image valide");
            }

            // Contrôle upload (si tout est bon, l'image est envoyée)
            if (!move_uploaded_file($tmp_file, $dest_dir . $new_name . '.png'))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
              // exit("Impossible de copier le fichier dans $dest_dir");
            }

            /*if ($control_ok == true)
              echo "Le fichier a bien été uploadé";*/
          }
        }
      }
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('UPDATE missions SET mission      = :mission,
                                                 date_deb     = :date_deb,
                                                 date_fin     = :date_fin,
                                                 heure        = :heure,
                                                 objectif     = :objectif,
                                                 description  = :description,
                                                 explications = :explications,
                                                 conclusion   = :conclusion
                                           WHERE id = ' . $id_mission);
      $req2->execute(array(
        'mission'      => $mission,
        'date_deb'     => $date_deb,
        'date_fin'     => $date_fin,
        'heure'        => $heure,
        'objectif'     => $objectif,
        'description'  => $description,
        'explications' => $explications,
        'conclusion'   => $conclusion
      ));
      $req2->closeCursor();

      $_SESSION['alerts']['mission_updated'] = true;
    }

    return $id_mission;
  }

  // METIER : Suppression d'une mission existante
  // RETOUR : Aucun
  function deleteMission($post)
  {
    $id_mission = $post['id_mission'];

    global $bdd;

    // Lecture référence mission
    $reponse = $bdd->query('SELECT id, reference FROM missions WHERE id = ' . $id_mission);
    $donnees = $reponse->fetch();
    $reference = $donnees['reference'];
    $reponse->closeCursor();

    // Suppression des images
    unlink ("../includes/images/missions/banners/" . $reference . ".png");
    unlink ("../includes/images/missions/buttons/" . $reference . "_g.png");
    unlink ("../includes/images/missions/buttons/" . $reference . "_m.png");
    unlink ("../includes/images/missions/buttons/" . $reference . "_d.png");

    // Suppression de la mission en table
    $reponse2 = $bdd->exec('DELETE FROM missions WHERE id = ' . $id_mission);

    // Suppression des participations en table
    $reponse3 = $bdd->exec('DELETE FROM missions_users WHERE id_mission = ' . $id_mission);

    // Suppression des notifications
    deleteNotification('start_mission', $id_mission);
    deleteNotification('end_mission', $id_mission);
    deleteNotification('one_mission', $id_mission);

    $_SESSION['alerts']['mission_deleted'] = true;
  }
?>
