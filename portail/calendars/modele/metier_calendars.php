<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/calendars.php');
  include_once('../../includes/imagethumb.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $annee_existante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT DISTINCT year FROM calendars ORDER BY year ASC');
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

    $reponse = $bdd->query('SELECT DISTINCT year FROM calendars ORDER BY year ASC');
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
      $myCalendar->setTitle(strtoupper($listeMois[$myCalendar->getMonth()]));
      array_push($calendars, $myCalendar);
    }
    $reponse->closeCursor();

    return $calendars;
  }

  // METIER : Suppression calendrier de la base et des fichiers
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

    $_SESSION['calendar_removed'] = true;
  }

  // METIER : Ajout calendrier avec création miniature
  // RETOUR : Aucun
  function insertCalendrier($post, $files)
  {
    // On récupère les données
    $month     = $post['months'];
    $year      = $post['years'];
    $to_delete = "N";

    global $bdd;

    // On contrôle la présence du dossier des calendriers, sinon on le créé
    $dossier_calendriers = "images/" . $post['years'];

    if (!is_dir($dossier_calendriers))
       mkdir($dossier_calendriers);

    // On contrôle la présence du dossier des miniatures, sinon on le créé
    $dossier_miniatures = "images/" . $post['years'] . "/mini";

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
        $test = imagethumb($calendars_dir . $new_name, $minis_dir . $new_name, 500, FALSE, FALSE);

        // echo "Le fichier a bien été uploadé";

        // On stocke la référence du nouveau calendrier dans la base
        $reponse = $bdd->prepare('INSERT INTO calendars(to_delete, month, year, calendar) VALUES(:to_delete, :month, :year, :calendar)');
				$reponse->execute(array(
          'to_delete' => $to_delete,
					'month'     => $month,
          'year'      => $year,
          'calendar'  => $new_name
					));
				$reponse->closeCursor();

        $_SESSION['calendar_added'] = true;
      }
    }
  }
?>
