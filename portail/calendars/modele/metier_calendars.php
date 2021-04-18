<?php
  include_once('../../includes/classes/calendars.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    // Initialisations
    $anneeExistante = false;

    // Vérification année présente en base
    if (isset($year) AND is_numeric($year))
      $anneeExistante = physiqueAnneeExistante($year);

    // Retour
    return $anneeExistante;
  }

  // METIER : Lecture années distinctes pour les onglets
  // RETOUR : Liste des années existantes
  function getOnglets()
  {
    // Récupération de la liste des années existantes
    $onglets = physiqueOnglets();

    // Retour
    return $onglets;
  }

  // METIER : Lecture calendriers pour l'année renseignée
  // RETOUR : Liste des calendriers
  function getCalendars($year)
  {
    // Récupération de la liste des calendriers
    $listeCalendriers = physiqueCalendriers($year);

    // Retour
    return $listeCalendriers;
  }

  // METIER : Lecture annexes des calendriers
  // RETOUR : Liste des annexes
  function getAnnexes()
  {
    // Récupération de la liste des annexes
    $listeAnnexes = physiqueAnnexes();

    // Retour
    return $listeAnnexes;
  }

  // METIER : Demande de suppression d'un calendrier
  // RETOUR : Aucun
  function deleteCalendrier($post)
  {
    // Récupération des données
    $idCalendrier = $post['id_cal'];
    $toDelete     = 'Y';

    // Modification de l'enregistrement en base (en attendant validation de l'admin)
    physiqueUpdateStatusCalendars('calendars', $idCalendrier, $toDelete);

    // Mise à jour du statut de la notification
    updateNotification('calendrier', $idCalendrier, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['calendar_removed'] = true;
  }

  // METIER : Demande de suppression d'une annexe
  // RETOUR : Aucun
  function deleteAnnexe($post)
  {
    // Récupération des données
    $idAnnexe = $post['id_annexe'];
    $toDelete = 'Y';

    // Modification de l'enregistrement en base (en attendant validation de l'admin)
    physiqueUpdateStatusCalendars('calendars_annexes', $idAnnexe, $toDelete);

    // Mise à jour du statut de la notification
    updateNotification('annexe', $idAnnexe, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['annexe_removed'] = true;
  }

  // METIER : Ajout calendrier avec création miniature
  // RETOUR : Aucun
  function insertCalendrier($post, $files, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $month    = $post['months'];
    $year     = $post['years'];
    $toDelete = 'N';
    $name     = $post['months'] . '-' . $post['years'] . '-' . rand();
    $folder   = '../../includes/images/calendars/' . $year;

    // Insertion image
    $nameCalendar = uploadImage($files, $name, 'calendar', $folder);

    // Contrôle saisie non vide
    $control_ok = controleCalendar($nameCalendar, 'calendar');

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $calendar = array('to_delete' => $toDelete,
                        'month'     => $month,
                        'year'      => $year,
                        'calendar'  => $nameCalendar
                        );

      $idCalendar = physiqueInsertionCalendrier($calendar);

      // Insertion notification
      insertNotification($identifiant, 'calendrier', $idCalendar);

      // Message d'alerte
      $_SESSION['alerts']['calendar_added'] = true;
    }
  }

  // METIER : Ajout annexe avec création miniature
  // RETOUR : Aucun
  function insertAnnexe($post, $files, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $toDelete = 'N';
    $title    = $post['title'];
    $folder   = '../../includes/images/calendars/annexes';

    // Insertion image
    $nameAnnexe = uploadImage($files, rand(), 'annexe', $folder);

    // Contrôle saisie non vide
    $control_ok = controleCalendar($nameAnnexe, 'annexe');

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $annexe = array('to_delete' => $toDelete,
                      'annexe'    => $nameAnnexe,
                      'title'     => $title
                      );

      $idAnnexe = physiqueInsertionAnnexe($annexe);

      // Insertion notification
      insertNotification($identifiant, 'annexe', $idAnnexe);

      // Message d'alerte
      $_SESSION['alerts']['annexe_added'] = true;
    }
  }

  // METIER : Formatage et insertion image Calendars
  // RETOUR : Nom fichier avec extension
  function uploadImage($files, $name, $type, $dossier)
  {
    // Initialisations
    $newName    = '';
    $control_ok = true;

    // Dossier de destination des miniatures
    $dossierMiniatures = $dossier . '/mini';

    // On vérifie la présence du dossier des miniatures, sinon on le créé
    if (!is_dir($dossierMiniatures))
      mkdir($dossierMiniatures, 0777, true);

    // Contrôles fichier
    if ($type == 'annexe')
      $fileDatas = controlsUploadFile($files['annexe'], $name, 'all');
    else
      $fileDatas = controlsUploadFile($files['calendar'], $name, 'all');

    // Récupération contrôles
    $control_ok = controleFichier($fileDatas);

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($fileDatas, $dossier);

    // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
    if ($control_ok == true)
    {
      $newName = $fileDatas['new_name'];

      imagethumb($dossier . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, FALSE, FALSE);
    }

    // Retour
    return $newName;
  }

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($identifiant)
  {
    // Lecture des préférences utilisateur
    $preferences = physiquePreferences($identifiant);

    // Retour
    return $preferences;
  }
?>
