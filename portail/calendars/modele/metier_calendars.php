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

  // METIER : Récupération des mois de l'année
  // RETOUR : Liste des mois
  function getMonthsCalendars()
  {
    // Construction de la liste des mois
    $listeMois = array('01' => 'Janvier',
                       '02' => 'Février',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Août',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Décembre'
                      );

    // Retour
    return $listeMois;
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
  // RETOUR : Année
  function insertCalendrier($post, $files, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $month    = $post['month_calendar'];
    $year     = $post['year_calendar'];
    $toDelete = 'N';
    $name     = $post['month_calendar'] . '-' . $post['year_calendar'] . '-' . rand();
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

    // Retour
    return $year;
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

      imagethumb($dossier . '/' . $newName, $dossierMiniatures . '/' . $newName, 500, false, false);
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

  // METIER : Vérification utilisateur autorisé
  // RETOUR : Booléen
  function getAutorisationUser($preferences)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle utilisateur autorisé
    $control_ok = controleUtilisateurAutorise($preferences->getManage_calendars());

    // Retour
    return $control_ok;
  }

  // METIER : Initialise les paramètres de calendrier
  // RETOUR : Paramètres
  function initializeCalendar()
  {
    // Initialisations
    $calendarParameters = new CalendarParameters();

    // Retour
    return $calendarParameters;
  }

  // METIER : Récupère les paramètres de calendrier
  // RETOUR : Paramètres
  function getCalendarParameters($parametres)
  {
    // Suppression de la session
    unset($_SESSION['calendar']);

    // Initialisations
    $calendarParameters = new CalendarParameters();

    // Récupération des paramètres
    $calendarParameters->setMonth($parametres['month']);
    $calendarParameters->setYear($parametres['year']);
    $calendarParameters->setPicture($parametres['picture']);

    // Retour
    return $calendarParameters;
  }

  // METIER : Détermine les données nécessaires à la génération d'un calendrier
  // RETOUR : Données du calendrier
  function getCalendarDatas($calendarParameters)
  {
    // Initialisations
    $premierJour       = '01';
    $donneesCalendrier = array();

    // Récupération des données
    $mois  = $calendarParameters->getMonth();
    $annee = $calendarParameters->getYear();

    // Nombre de jours à afficher par ligne
    $donneesCalendrier['nombre_jours_par_ligne'] = 5;

    // Calcul du nombre de jours du mois
    $nombreJoursMois = date('t', strtotime($annee . $mois . $premierJour));

    // Détermination du numéro dans la semaine du premier jour du mois
    $numeroPremierJourDuMois = date('N', strtotime($annee . $mois . $premierJour));

    // Si le premier jour du mois est un samedi ou un dimanche, on supprimera la ligne
    $premiereSemaineVide = false;

    if ($numeroPremierJourDuMois == 6 OR $numeroPremierJourDuMois == 7)
      $premiereSemaineVide = true;

    // Calcul du nombre de lignes à afficher (pour afficher seulement 5 jours, on retire la première ligne si les premiers jours du mois ne font pas partie de la semaine)
    if ($premiereSemaineVide == true)
      $donneesCalendrier['nombre_lignes_calendrier'] = ceil(($nombreJoursMois + $numeroPremierJourDuMois - 1) / 7 - 1);
    else
      $donneesCalendrier['nombre_lignes_calendrier'] = ceil(($nombreJoursMois + $numeroPremierJourDuMois - 1) / 7);

    // Détermination du premier numéro de jour du mois dans la semaine et du numéro dans la semaine du premier jour à afficher
    switch ($numeroPremierJourDuMois)
    {
      // Samedi
      case 6:
        $donneesCalendrier['numero_premier_jour_semaine']    = 3;
        $donneesCalendrier['numero_premier_jour_a_afficher'] = 1;
        break;

      // Dimanche
      case 7:
        $donneesCalendrier['numero_premier_jour_semaine']    = 2;
        $donneesCalendrier['numero_premier_jour_a_afficher'] = 1;
        break;

      // Du lundi au vendredi
      default:
        $donneesCalendrier['numero_premier_jour_semaine']    = 1;
        $donneesCalendrier['numero_premier_jour_a_afficher'] = $numeroPremierJourDuMois;
        break;
    }

    // Détermination du numéro dans la semaine du dernier jour du mois à afficher et du dernier jour à afficher
    $numeroDernierJourDuMois = date('N', strtotime($annee . $mois . $nombreJoursMois));

    switch ($numeroDernierJourDuMois)
    {
      // Samedi
      case 6:
        $donneesCalendrier['numero_dernier_jour_a_afficher'] = $numeroDernierJourDuMois - 1;
        $dernierJourAAfficher                                = $nombreJoursMois - 1;
        break;

      // Dimanche
      case 7:
        $donneesCalendrier['numero_dernier_jour_a_afficher'] = $numeroDernierJourDuMois - 2;
        $dernierJourAAfficher                                = $nombreJoursMois - 2;
        break;

      // Du lundi au vendredi
      default:
        $donneesCalendrier['numero_dernier_jour_a_afficher'] = $numeroDernierJourDuMois;
        $dernierJourAAfficher                                = $nombreJoursMois;
        break;
    }

    // Calcul des numéros de semaines du mois
    if ($donneesCalendrier['numero_premier_jour_semaine'] < 10)
      $donneesCalendrier['semaine_debut_mois'] = intval(date('W', strtotime($annee . $mois . '0' . $donneesCalendrier['numero_premier_jour_semaine'])));
    else
      $donneesCalendrier['semaine_debut_mois'] = intval(date('W', strtotime($annee . $mois . $donneesCalendrier['numero_premier_jour_semaine'])));

    if ($dernierJourAAfficher < 10)
      $donneesCalendrier['semaine_fin_mois'] = intval(date('W', strtotime($annee . $mois . '0' . $dernierJourAAfficher)));
    else
      $donneesCalendrier['semaine_fin_mois'] = intval(date('W', strtotime($annee . $mois . $dernierJourAAfficher)));

    // Retour
    return $donneesCalendrier;
  }

  // METIER : Détermine les dates de vacances d'une année et d'un mois
  // RETOUR : Tableau des vacances
  function getVacances($calendarParameters)
  {
    // Initialisations
    $vacances = array();

    // Récupération des données
    $year  = $calendarParameters->getYear();
    $month = $calendarParameters->getMonth();

    // Lecture du fichier des périodes de vacances
    if ($month >= 10)
    {
      $anneeInitiale = $year;
      $anneeFinale   = $year + 1;
    }
    else
    {
      $anneeInitiale = $year - 1;
      $anneeFinale   = $year;
    }

    $nomFichier = $anneeInitiale . '-' . $anneeFinale . '.csv';

    // Vérification fichier existant
    $dossierVacances = '../../includes/datas/calendars';

    // Si le fichier existe, on récupère les données
    if (file_exists($dossierVacances . '/' . $nomFichier))
    {
      // Lecture des dates de vacances
      $file = fopen($dossierVacances . '/' . $nomFichier, 'r');
      $i    = 0;

      while (!feof($file))
      {
        $line[] = fgetcsv($file, 1024);

        // Récupération des dates
        if (substr($line[$i][0], 0, 4) == $year AND substr($line[$i][0], 5, 2) == $month)
        {
          $vacances[str_replace('-', '', $line[$i][0])] = array('date'            => $line[$i][0],
                                                                'vacances_zone_a' => $line[$i][1],
                                                                'vacances_zone_b' => $line[$i][2],
                                                                'vacances_zone_c' => $line[$i][3],
                                                                'nom_vacances'    => $line[$i][4]
                                                               );
        }

        // Arrêt de la boucle si dates dépassées
        if (substr($line[$i][0], 0, 4) > $year OR (substr($line[$i][0], 0, 4) == $year AND substr($line[$i][0], 5, 2) > $month))
          break;

        $i++;
      }

      fclose($file);
    }

    // Retour
    return $vacances;
  }

  // METIER : Sauvegarde des paramètres en session
  // RETOUR : Nom de l'image
  function saveCalendarParameters($post, $files)
  {
    // Détermination du nom de l'image
    if (isset($post['picture_calendar_generated']) AND !empty($post['picture_calendar_generated']))
    {
      // Si le calendrier a déjà été généré mais que la date a changé
      if (($post['month_calendar'] != substr($post['picture_calendar_generated'], 0, 2))
      OR  ($post['year_calendar']  != substr($post['picture_calendar_generated'], 3, 4)))
      {
        // Si l'image change, on supprime l'ancienne et on détermine un nouveau nom, sinon on renomme l'ancienne avec un nouveau nom
        if (isset($files['picture_calendar']) AND !empty($files['picture_calendar']['name']))
        {
          // Suppression des images temporaires
          $dossierTemporaire = '../../includes/images/calendars/temp';

          unlink($dossierTemporaire . '/' . $post['picture_calendar_generated']);
          unlink($dossierTemporaire . '/trim_' . $post['picture_calendar_generated']);

          // Détermination du nouveau nom
          $type     = pathinfo($files['picture_calendar']['name'], PATHINFO_EXTENSION);
          $nomImage = $post['month_calendar'] . '-' . $post['year_calendar'] . '-' . rand() . '.' . $type;
        }
        else
        {
          // Détermination du nouveau nom
          $type     = pathinfo($post['picture_calendar_generated'], PATHINFO_EXTENSION);
          $nomImage = $post['month_calendar'] . '-' . $post['year_calendar'] . '-' . rand() . '.' . $type;

          // Renommage des images temporaires
          $dossierTemporaire = '../../includes/images/calendars/temp';

          rename($dossierTemporaire . '/' . $post['picture_calendar_generated'], $dossierTemporaire . '/' . $nomImage);
          rename($dossierTemporaire . '/trim_' . $post['picture_calendar_generated'], $dossierTemporaire . '/trim_' . $nomImage);
        }
      }
      else
        $nomImage = $post['picture_calendar_generated'];
    }
    else
    {
      // Première saisie
      if (isset($files['picture_calendar']) AND !empty($files['picture_calendar']['name']))
      {
        $type     = pathinfo($files['picture_calendar']['name'], PATHINFO_EXTENSION);
        $nomImage = $post['month_calendar'] . '-' . $post['year_calendar'] . '-' . rand() . '.' . $type;
      }
      else
        $nomImage = '';
    }

    // Sauvegarde des paramètres saisis en session
    $_SESSION['calendar']['month']   = $post['month_calendar'];
    $_SESSION['calendar']['year']    = $post['year_calendar'];
    $_SESSION['calendar']['picture'] = $nomImage;

    // Retour
    return $nomImage;
  }

  // METIER : Insertion de l'image dans un dossier temporaire
  // RETOUR : Aucun
  function insertImageCalendrier($post, $files, $nom)
  {
    // Initialisations
    $control_ok = true;

    // Dossier de destination des images temporaires
    $dossierTemporaire = '../../includes/images/calendars/temp';

    // On vérifie la présence du dossier des miniatures, sinon on le créé
    if (!is_dir($dossierTemporaire))
      mkdir($dossierTemporaire, 0777, true);

    // Récupération du nom de l'image
    $search   = array('.jpg', '.jpeg', '.gif', '.bmp', '.png');
    $replace  = array('', '', '', '', '');
    $nomImage = str_replace($search, $replace, $nom);

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['picture_calendar'], $nomImage, 'all');

    // Récupération contrôles
    $control_ok = controleFichier($fileDatas);

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($fileDatas, $dossierTemporaire);

    // Duplique et rogne l'image originale de la source vers la destination en forçant une hauteur/largeur (cf fonction imagethumb.php)
    if ($control_ok == true)
    {
      $newName = $fileDatas['new_name'];

      imagetrim($dossierTemporaire . '/' . $fileDatas['new_name'], $dossierTemporaire . '/trim_' . $fileDatas['new_name'], 3508, 4461, true);
    }
  }

  // METIER : Sauvegarde du calendrier généré
  // RETOUR : Année
  function insertCalendrierGenere($post, $identifiant)
  {
    // Récupération des données
    $picture  = $post['calendar_generator'];
    $month    = $post['month_generator'];
    $year     = $post['year_generator'];
    $tempName = $post['temp_name_generator'];
    $toDelete = 'N';
    $name     = $post['month_generator'] . '-' . $post['year_generator'] . '-' . rand() . '.jpg';

    // Décodage du flux de l'image
    $encodedPicture = str_replace(' ', '+', substr($picture, strpos($picture, ',') + 1));
    $decodedPicture = base64_decode($encodedPicture);

    // On vérifie la présence du dossier, sinon on le créé de manière récursive
    $dossier = '../../includes/images/calendars/' . $year;

    if (!is_dir($dossier))
      mkdir($dossier, 0777, true);

    // On vérifie la présence du dossier des miniatures, sinon on le créé
    $dossierMiniatures = $dossier . '/mini';

    if (!is_dir($dossierMiniatures))
      mkdir($dossierMiniatures, 0777, true);

    // Sauvegarde du fichier
    file_put_contents($dossier . '/' . $name, $decodedPicture);

    // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
    imagethumb($dossier . '/' . $name, $dossierMiniatures . '/' . $name, 500, false, false);

    // Insertion de l'enregistrement en base
    $calendar = array('to_delete' => $toDelete,
                      'month'     => $month,
                      'year'      => $year,
                      'calendar'  => $name
                      );

    $idCalendar = physiqueInsertionCalendrier($calendar);

    // Suppression des images temporaires
    $dossierTemporaire = '../../includes/images/calendars/temp';

    unlink($dossierTemporaire . '/' . $tempName);
    unlink($dossierTemporaire . '/trim_' . $tempName);

    // Insertion notification
    insertNotification($identifiant, 'calendrier', $idCalendar);

    // Message d'alerte
    $_SESSION['alerts']['calendar_added'] = true;

    // Retour
    return $year;
  }
?>
