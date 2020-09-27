<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/calendars.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $anneeExistante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT * FROM calendars WHERE year = "' . $year . '" AND to_delete != "Y" ORDER BY year ASC');

      if ($reponse->rowCount() > 0)
        $anneeExistante = true;

      $reponse->closeCursor();
    }

    return $anneeExistante;
  }

  // METIER : Lecture années distinctes
  // RETOUR : Liste des années existantes
  function getOnglets()
  {
    $onglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT year FROM calendars WHERE to_delete != "Y" ORDER BY year DESC');
    while ($donnees = $reponse->fetch())
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
    $listeCalendriers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM calendars WHERE year = ' . $year . ' AND to_delete != "Y" ORDER BY month DESC, id DESC');
    while ($donnees = $reponse->fetch())
    {
      $calendrier = Calendrier::withData($donnees);
      $calendrier->setTitle(formatMonthForDisplayStrong($calendrier->getMonth())) ;

      array_push($listeCalendriers, $calendrier);
    }
    $reponse->closeCursor();

    // Retour
    return $listeCalendriers;
  }

  // METIER : Lecture annexes Calendars
  // RETOUR : Liste des annexes
  function getAnnexes()
  {
    global $bdd;

    $listeAnnexes = array();

    $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE to_delete != "Y" ORDER BY id DESC');
    while ($donnees = $reponse->fetch())
    {
      $annexe = Annexe::withData($donnees);
      array_push($listeAnnexes, $annexe);
    }
    $reponse->closeCursor();

    return $listeAnnexes;
  }

  // METIER : Demande suppression calendrier
  // RETOUR : Aucun
  function deleteCalendrier($post)
  {
    $idCalendrier = $post['id_cal'];
    $toDelete     = 'Y';

    global $bdd;

    // On fait simplement une mise à jour du top en attendant validation de l'admin
    $reponse = $bdd->prepare('UPDATE calendars SET to_delete = :to_delete WHERE id = ' . $idCalendrier);
    $reponse->execute(array(
      'to_delete' => $toDelete
    ));
    $reponse->closeCursor();

    $_SESSION['alerts']['calendar_removed'] = true;
  }

  // METIER : Demande suppression annexe
  // RETOUR : Aucun
  function deleteAnnexe($post)
  {
    $idAnnexe = $post['id_annexe'];
    $toDelete = 'Y';

    global $bdd;

    // On fait simplement une mise à jour du top en attendant validation de l'admin
    $reponse = $bdd->prepare('UPDATE calendars_annexes SET to_delete = :to_delete WHERE id = ' . $idAnnexe);
    $reponse->execute(array(
      'to_delete' => $toDelete
    ));
    $reponse->closeCursor();

    $_SESSION['alerts']['annexe_removed'] = true;
  }

  // METIER : Ajout calendrier avec création miniature
  // RETOUR : Aucun
  function insertCalendrier($post, $files, $user)
  {
    // On récupère les données
    $month    = $post['months'];
    $year     = $post['years'];
    $toDelete = 'N';

    global $bdd;

    $control_ok = true;

    // Dossiers de destination
    $dossierCalendriers = '../../includes/images/calendars/' . $post['years'];
    $dossierMiniatures  = $dossierCalendriers . '/mini';

    // On vérifie la présence du dossier des miniatures, sinon on le créé
    if (!is_dir($dossierMiniatures))
      mkdir($dossierMiniatures, 0777, true);

    // Nom du fichier
    $nameFile = $post['months'] . '-' . $post['years'] . '-' . rand();

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['calendar'], $nameFile, 'all');

    // Traitements fichier
    if ($fileDatas['control_ok'] == true)
    {
      // Upload fichier
      $control_ok = uploadFile($fileDatas, $dossierCalendriers);

      if ($control_ok == true)
      {
        $newName = $fileDatas['new_name'];

        // Créé une miniature de la source vers la destination largeur max de 500px (cf fonction imagethumb.php)
        imagethumb($dossierCalendriers . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, FALSE, FALSE);

        // On stocke la référence du nouveau calendrier dans la base
        $reponse = $bdd->prepare('INSERT INTO calendars(to_delete, month, year, calendar) VALUES(:to_delete, :month, :year, :calendar)');
        $reponse->execute(array(
          'to_delete' => $toDelete,
          'month'     => $month,
          'year'      => $year,
          'calendar'  => $newName
        ));
        $reponse->closeCursor();

        // Génération notification calendrier ajouté
        $newId = $bdd->lastInsertId();

        insertNotification($user, 'calendrier', $newId);

        $_SESSION['alerts']['calendar_added'] = true;
      }
    }
  }

  // METIER : Ajout annexe avec création miniature
  // RETOUR : Aucun
  function insertAnnexe($post, $files, $user)
  {
    // On récupère les données
    $title    = $post['title'];
    $toDelete = 'N';

    global $bdd;

    $control_ok = true;

    // Dossiers de destination
    $dossierAnnexes    = '../../includes/images/calendars/annexes';
    $dossierMiniatures = $dossierAnnexes . '/mini';

    // On vérifie la présence du dossier des miniatures, sinon on le créé
    if (!is_dir($dossierMiniatures))
      mkdir($dossierMiniatures, 0777, true);

    // Nom du fichier
    $nameFile = rand();

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['annexe'], $nameFile, 'all');

    // Traitements fichier
    if ($fileDatas['control_ok'] == true)
    {
      // Upload fichier
      $control_ok = uploadFile($fileDatas, $dossierAnnexes);

      if ($control_ok == true)
      {
        $newName = $fileDatas['new_name'];

        // Créé une miniature de la source vers la destination largeur max de 500px (cf fonction imagethumb.php)
        imagethumb($dossierAnnexes . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, FALSE, FALSE);

        // On stocke la référence du nouveau fichier dans la base
        $reponse = $bdd->prepare('INSERT INTO calendars_annexes(to_delete, annexe, title) VALUES(:to_delete, :annexe, :title)');
        $reponse->execute(array(
          'to_delete' => $toDelete,
          'annexe'    => $newName,
          'title'     => $title
        ));
        $reponse->closeCursor();

        // Génération notification calendrier ajouté
        $newId = $bdd->lastInsertId();

        insertNotification($user, 'annexe', $newId);

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

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }
?>
