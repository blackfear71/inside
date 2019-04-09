<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/calendars.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $annee_existante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT DISTINCT year FROM calendars WHERE to_delete != "Y" ORDER BY year ASC');
      while($donnees = $reponse->fetch())
      {
        if ($year == $donnees['year'])
          $annee_existante = true;
      }
      $reponse->closeCursor();
    }

    return $annee_existante;
  }

  // METIER : Lecture années distinctes
  // RETOUR : Liste des années existantes
  function getOnglets()
  {
    $onglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT year FROM calendars WHERE to_delete != "Y" ORDER BY year DESC');
    while($donnees = $reponse->fetch())
    {
      array_push($onglets, $donnees['year']);
    }
    $reponse->closeCursor();

    return $onglets;
  }

  // METIER : Lecture calendriers pour l'année renseignée
  // RETOUR : Liste des calendriers
  function getCalendars($year)
  {
    $calendars = array();

    $listeMois = array('01' => 'Janvier',
                       '02' => 'Fevrier',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Aout',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Decembre'
                      );

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM calendars WHERE year = ' . $year . ' AND to_delete != "Y" ORDER BY month DESC, id DESC');
    while($donnees = $reponse->fetch())
    {
      $myCalendar = Calendrier::withData($donnees);

      $fileinfo  = getimagesize("../../includes/images/calendars/" . $myCalendar->getYear() . "/" . $myCalendar->getCalendar());

      $myCalendar->setTitle(strtoupper($listeMois[$myCalendar->getMonth()]));
      $myCalendar->setWidth($fileinfo[0]);
      $myCalendar->setHeight($fileinfo[1]);

      array_push($calendars, $myCalendar);
    }
    $reponse->closeCursor();

    return $calendars;
  }

  // METIER : Lecture annexes Calendars
  // RETOUR : Liste des annexes
  function getAnnexes()
  {
    global $bdd;

    $annexes = array();

    $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE to_delete != "Y" ORDER BY id DESC');
    while($donnees = $reponse->fetch())
    {
      $myAnnexe = Annexe::withData($donnees);
      array_push($annexes, $myAnnexe);
    }
    $reponse->closeCursor();

    return $annexes;
  }

  // METIER : Demande suppression calendrier
  // RETOUR : Aucun
  function deleteCalendrier($id_cal)
  {
    $to_delete = "Y";

    global $bdd;

    // On fait simplement une mise à jour du top en attendant validation de l'admin
    $reponse = $bdd->prepare('UPDATE calendars SET to_delete = :to_delete WHERE id = ' . $id_cal);
    $reponse->execute(array(
      'to_delete' => $to_delete
    ));
    $reponse->closeCursor();

    $_SESSION['alerts']['calendar_removed'] = true;
  }

  // METIER : Demande suppression annexe
  // RETOUR : Aucun
  function deleteAnnexe($id_annexe)
  {
    $to_delete = "Y";

    global $bdd;

    // On fait simplement une mise à jour du top en attendant validation de l'admin
    $reponse = $bdd->prepare('UPDATE calendars_annexes SET to_delete = :to_delete WHERE id = ' . $id_annexe);
    $reponse->execute(array(
      'to_delete' => $to_delete
    ));
    $reponse->closeCursor();

    $_SESSION['alerts']['annexe_removed'] = true;
  }

  // METIER : Ajout calendrier avec création miniature
  // RETOUR : Aucun
  function insertCalendrier($post, $files, $user)
  {
    // On récupère les données
    $month     = $post['months'];
    $year      = $post['years'];
    $to_delete = "N";

    global $bdd;

    // On contrôle la présence du dossier des calendriers, sinon on le créé
    $dossier = "../../includes/images/calendars";

    if (!is_dir($dossier))
       mkdir($dossier);

    // On contrôle la présence du dossier des années, sinon on le créé
    $dossier_calendriers = $dossier . "/" . $post['years'];

    if (!is_dir($dossier_calendriers))
       mkdir($dossier_calendriers);

    // On contrôle la présence du dossier des miniatures, sinon on le créé
    $dossier_miniatures = $dossier_calendriers . "/mini";

    if (!is_dir($dossier_miniatures))
      mkdir($dossier_miniatures);

    // On définit le nom du fichier
    $namefile = $post['months'] . "-" . $post['years'] . "-" . rand();

    // Si on a bien une image
    if ($files['calendar']['name'] != NULL)
    {
      // Dossiers de destination
      $calendars_dir = $dossier_calendriers . '/';
      $minis_dir     = $dossier_miniatures . '/';

      // Données du fichier
      $file      = $files['calendar']['name'];
      $tmp_file  = $files['calendar']['tmp_name'];
      $size_file = $files['calendar']['size'];
      $maxsize   = 8388608; // 8Mo

      // Si le fichier n'est pas trop grand
      if ($size_file < $maxsize)
      {
        // Contrôle fichier temporaire existant
        if (!is_uploaded_file($tmp_file))
        {
          exit("Le fichier est introuvable");
        }

        // Contrôle type de fichier
        $type_file = $files['calendar']['type'];

        if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
        {
          exit("Le fichier n'est pas une image valide");
        }
        else
        {
          $type_image = pathinfo($file, PATHINFO_EXTENSION);
          $new_name   = $namefile . '.' . $type_image;
        }

        // Contrôle upload (si tout est bon, l'image est envoyée)
        if (!move_uploaded_file($tmp_file, $calendars_dir . $new_name))
        {
          exit("Impossible de copier le fichier dans $calendars_dir");
        }

        // Créé une miniature de la source vers la destination largeur max de 500px (cf fonction imagethumb.php)
        imagethumb($calendars_dir . $new_name, $minis_dir . $new_name, 500, FALSE, FALSE);

        //echo "Le fichier a bien été uploadé";

        // On stocke la référence du nouveau calendrier dans la base
        $reponse = $bdd->prepare('INSERT INTO calendars(to_delete, month, year, calendar) VALUES(:to_delete, :month, :year, :calendar)');
				$reponse->execute(array(
          'to_delete' => $to_delete,
					'month'     => $month,
          'year'      => $year,
          'calendar'  => $new_name
					));
				$reponse->closeCursor();

        // Génération notification calendrier ajouté
        $new_id = $bdd->lastInsertId();

        insertNotification($user, 'calendrier', $new_id);

        $_SESSION['alerts']['calendar_added'] = true;
      }
    }
  }

  // METIER : Ajout annexe avec création miniature
  // RETOUR : Aucun
  function insertAnnexe($post, $files)
  {
    // On récupère les données
    $title     = $post['title'];
    $to_delete = "N";

    global $bdd;

    // On contrôle la présence du dossier des calendriers, sinon on le créé
    $dossier = "../../includes/images/calendars";

    if (!is_dir($dossier))
       mkdir($dossier);

    // On contrôle la présence du dossier des annexes, sinon on le créé
    $dossier_annexes = $dossier . "/annexes";

    if (!is_dir($dossier_annexes))
       mkdir($dossier_annexes);

    // On contrôle la présence du dossier des miniatures, sinon on le créé
    $dossier_miniatures = $dossier_annexes . "/mini";

    if (!is_dir($dossier_miniatures))
      mkdir($dossier_miniatures);

    // On définit le nom du fichier
    $namefile = rand();

    // Si on a bien une image
    if ($files['annexe']['name'] != NULL)
    {
      // Dossiers de destination
      $annexes_dir = $dossier_annexes . '/';
      $minis_dir   = $dossier_miniatures . '/';

      // Données du fichier
      $file      = $files['annexe']['name'];
      $tmp_file  = $files['annexe']['tmp_name'];
      $size_file = $files['annexe']['size'];
      $maxsize   = 8388608; // 8Mo

      // Si le fichier n'est pas trop grand
      if ($size_file < $maxsize)
      {
        // Contrôle fichier temporaire existant
        if (!is_uploaded_file($tmp_file))
        {
          exit("Le fichier est introuvable");
        }

        // Contrôle type de fichier
        $type_file = $files['annexe']['type'];

        if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
        {
          exit("Le fichier n'est pas une image valide");
        }
        else
        {
          $type_image = pathinfo($file, PATHINFO_EXTENSION);
          $new_name   = $namefile . '.' . $type_image;
        }

        // Contrôle upload (si tout est bon, l'image est envoyée)
        if (!move_uploaded_file($tmp_file, $annexes_dir . $new_name))
        {
          exit("Impossible de copier le fichier dans $annexes_dir");
        }

        // Créé une miniature de la source vers la destination largeur max de 500px (cf fonction imagethumb.php)
        imagethumb($annexes_dir . $new_name, $minis_dir . $new_name, 500, FALSE, FALSE);

        //echo "Le fichier a bien été uploadé";

        // On stocke la référence du nouveau fichier dans la base
        $reponse = $bdd->prepare('INSERT INTO calendars_annexes(to_delete, annexe, title) VALUES(:to_delete, :annexe, :title)');
				$reponse->execute(array(
          'to_delete' => $to_delete,
					'annexe'    => $new_name,
          'title'     => $title
					));
				$reponse->closeCursor();

        $_SESSION['alerts']['annexe_added'] = true;
      }
    }
  }

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }
?>
