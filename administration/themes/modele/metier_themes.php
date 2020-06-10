<?php
  include_once('../../includes/classes/themes.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['date_less'])           OR $_SESSION['alerts']['date_less']           != true)
    AND (!isset($_SESSION['alerts']['date_conflict'])       OR $_SESSION['alerts']['date_conflict']       != true)
    AND (!isset($_SESSION['alerts']['wrong_date'])          OR $_SESSION['alerts']['wrong_date']          != true)
    AND (!isset($_SESSION['alerts']['already_ref_theme'])   OR $_SESSION['alerts']['already_ref_theme']   != true)
    AND (!isset($_SESSION['alerts']['missing_theme_file'])  OR $_SESSION['alerts']['missing_theme_file']  != true)
    AND (!isset($_SESSION['alerts']['file_too_big'])        OR $_SESSION['alerts']['file_too_big']        != true)
    AND (!isset($_SESSION['alerts']['temp_not_found'])      OR $_SESSION['alerts']['temp_not_found']      != true)
    AND (!isset($_SESSION['alerts']['wrong_file_type'])     OR $_SESSION['alerts']['wrong_file_type']     != true)
    AND (!isset($_SESSION['alerts']['wrong_file'])          OR $_SESSION['alerts']['wrong_file']          != true)
    AND (!isset($_SESSION['alerts']['level_theme_numeric']) OR $_SESSION['alerts']['level_theme_numeric'] != true))
    {
      unset($_SESSION['save']);

      $_SESSION['save']['theme_title']    = '';
      $_SESSION['save']['theme_ref']      = '';
      $_SESSION['save']['theme_level']    = '';
      $_SESSION['save']['theme_date_deb'] = '';
      $_SESSION['save']['theme_date_fin'] = '';
    }
  }

  // METIER : Lecture des thèmes existants par type
  // RETOUR : Tableau des thèmes
  function getThemes($typeTheme)
  {
    // Récupération des thèmes
    $themes = physiqueThemes($typeTheme);

    // Retour
    return $themes;
  }

  // METIER : Insertion nouveau thème
  // RETOUR : Id enregistrement créé
  function insertTheme($post, $files)
  {
    // Initialisations
    $newId      = NULL;
    $control_ok = true;

    // Récupération des données
    $titre        = $post['theme_title'];
    $reference    = $post['theme_ref'];
    $presenceLogo = 'N';
    $type         = $post['theme_type'];

    if ($type == 'M')
    {
      $dateDeb = $post['theme_date_deb'];
      $dateFin = $post['theme_date_fin'];
      $level   = '';
    }
    else
    {
      $dateDeb = '';
      $dateFin = '';
      $level   = $post['theme_level'];
    }

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['theme_title']    = $titre;
    $_SESSION['save']['theme_ref']      = $reference;
    $_SESSION['save']['theme_date_deb'] = $dateDeb;
    $_SESSION['save']['theme_date_fin'] = $dateFin;
    $_SESSION['save']['theme_level']    = $level;

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'ô', 'û');
    $replace   = array('_', 'e', 'e', 'e', 'e', 'a', 'a', 'c', 'o', 'u');
    $reference = str_replace($search, $replace, $reference);

    // Contrôle référence unique
    $control_ok = controleReferenceUnique($reference);

    // Contrôles spécifiques au type de thème
    if ($control_ok == true)
    {
      if ($type == 'M')
      {
        // Contrôle format date début
        $control_ok = controleFormatDate($dateDeb, 'd/m/Y');

        // Formatage de la date de début pour insertion
        if ($control_ok == true)
          $dateDeb = formatDateForInsert($dateDeb);

        // Contrôle format date fin
        if ($control_ok == true)
          $control_ok = controleFormatDate($dateFin, 'd/m/Y');

        // Formatage de la date de fin pour insertion
        if ($control_ok == true)
          $dateFin = formatDateForInsert($dateFin);

        // Contrôle date début <= date fin
        if ($control_ok == true)
          $control_ok = controleOrdreDates($dateDeb, $dateFin);

        // Contrôle chevauchement dates
        if ($control_ok == true)
          $control_ok = controleSuperpositionDates($dateDeb, $dateFin, NULL);
      }
      else
      {
        // Contrôle niveau numérique
        $control_ok = controleNiveauNumerique($level);
      }
    }

    // Contrôle images présentes et indicateur présence logo
    if ($control_ok == true)
    {
      foreach ($files as $keyFile => $file)
      {
        // Indicateur présence logo
        if ($keyFile == 'logo' AND !empty($file['name']) AND !empty($file['type']))
          $presenceLogo = 'Y';

        // Contrôle présence autres fichiers
        $control_ok = controleAutresFichiers($keyFile, $file['name']);
      }
    }

    // Vérification des dossiers et contrôle des fichiers
    if ($control_ok == true)
    {
      // On vérifie la présence du dossier des images, sinon on le créé
      $dossier = '../../includes/images/themes';

      if (!is_dir($dossier))
        mkdir($dossier);

      // On vérifie la présence du dossier des entête, sinon on le créé
      $dossierHeaders = $dossier . '/headers';

      if (!is_dir($dossierHeaders))
        mkdir($dossierHeaders);

      // On vérifie la présence du dossier des fonds, sinon on le créé
      $dossierBackgrounds = $dossier . '/backgrounds';

      if (!is_dir($dossierBackgrounds))
        mkdir($dossierBackgrounds);

      // On vérifie la présence du dossier des bas de page, sinon on le créé
      $dossierFooters = $dossier . '/footers';

      if (!is_dir($dossierFooters))
        mkdir($dossierFooters);

      // On vérifie la présence du dossier des logos, sinon on le créé
      $dossierLogos = $dossier . '/logos';

      if (!is_dir($dossierLogos))
        mkdir($dossierLogos);

      // Contrôle des fichiers
      foreach ($files as $keyFile => $file)
      {
        // Si logo présent ou autre fichier que logo
        if (($keyFile == 'logo' AND $presenceLogo == 'Y') OR $keyFile != 'logo')
        {
          // Nom du fichier
          switch ($keyFile)
          {
            case 'header':
              $name = $reference . '_h';
              break;

            case 'footer':
              $name = $reference . '_f';
              break;

            case 'logo':
              $name = $reference . '_l';
              break;

            case 'background':
            default:
              $name = $reference;
              break;
          }

          // Contrôles communs d'un fichier
          $fileDatas  = controlsUploadFile($file, $name, 'png');

          // Récupération contrôles
          $control_ok = controleFichier($fileDatas);

          // Arrêt de la boucle en cas d'erreur
          if ($control_ok == false)
            break;
        }
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // Insertion de chaque fichier
      foreach ($files as $keyFile => $file)
      {
        // Insertion logo si présent ou autre que logo
        if (($keyFile == 'logo' AND $presenceLogo == 'Y') OR $keyFile != 'logo')
        {
          // Dossier de destination
          switch ($keyFile)
          {
            case 'header':
              $destDir = $dossierHeaders . '/';
              break;

            case 'footer':
              $destDir = $dossierFooters . '/';
              break;

            case 'logo':
              $destDir = $dossierLogos . '/';
              break;

            case 'background':
            default:
              $destDir = $dossierBackgrounds . '/';
              break;
          }

          // Nouveau nom
          switch ($keyFile)
          {
            case 'header':
              $newName = $reference . '_h.png';
              break;

            case 'footer':
              $newName = $reference . '_f.png';
              break;

            case 'logo':
              $newName = $reference . '_l.png';
              break;

            case 'background':
            default:
              $newName = $reference . '.png';
              break;
          }

          // Données à envoyer pour l'upload
          $fileDatas = array('control_ok' => true,
                             'new_name'   => $newName,
                             'tmp_file'   => $file['tmp_name'],
                             'type_file'  => $file['type']
                            );

          // Upload fichier
          $control_ok = uploadFile($file, $fileDatas, $destDir);

          // Arrêt de la boucle en cas d'erreur
          if ($control_ok == false)
            break;
        }
      }
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $theme = array('reference' => $reference,
                     'name'      => $titre,
                     'type'      => $type,
                     'level'     => $level,
                     'logo'      => $presenceLogo,
                     'date_deb'  => $dateDeb,
                     'date_fin'  => $dateFin
                    );

      $newId = physiqueInsertionTheme($theme);

      // Message d'alerte
      $_SESSION['alerts']['theme_added'] = true;
    }

    // Retour
    return $newId;
  }

  // METIER : Modification thème existant
  // RETOUR : Id thème
  function updateTheme($post)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idTheme = $post['id_theme'];
    $titre   = $post['theme_title'];
    $type    = $post['theme_type'];

    if ($type == 'M')
    {
      $dateDeb = $post['theme_date_deb'];
      $dateFin = $post['theme_date_fin'];
      $level   = '';
    }
    else
    {
      $dateDeb = '';
      $dateFin = '';
      $level   = $post['theme_level'];
    }

    if ($type == 'M')
    {
      // Contrôle format date début
      $control_ok = controleFormatDate($dateDeb, 'd/m/Y');

      // Formatage de la date de début pour insertion
      if ($control_ok == true)
        $dateDeb = formatDateForInsert($dateDeb);

      // Contrôle format date fin
      if ($control_ok == true)
        $control_ok = controleFormatDate($dateFin, 'd/m/Y');

      // Formatage de la date de fin pour insertion
      if ($control_ok == true)
        $dateFin = formatDateForInsert($dateFin);

      // Contrôle date début <= date fin
      if ($control_ok == true)
        $control_ok = controleOrdreDates($dateDeb, $dateFin);

      // Contrôle chevauchement dates
      if ($control_ok == true)
        $control_ok = controleSuperpositionDates($dateDeb, $dateFin, $idTheme);
    }
    else
    {
      // Contrôle niveau numérique
      $control_ok = controleNiveauNumerique($level);
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $theme = array('name'      => $titre,
                     'type'      => $type,
                     'level'     => $level,
                     'date_deb'  => $dateDeb,
                     'date_fin'  => $dateFin
                    );

      physiqueUpdateTheme($idTheme, $theme);

      // Message d'alerte
      $_SESSION['alerts']['theme_updated'] = true;
    }

    // Retour
    return $idTheme;
  }

  // METIER : Suppression thème
  // RETOUR : Aucun
  function deleteTheme($post)
  {
    // Récupération des données
    $idTheme = $post['id_theme'];

    // Récupération des données du thème
    $theme = physiqueTheme($idTheme);

    // Suppression des images
    if (!empty($theme->getReference()))
    {
      unlink('../../includes/images/themes/headers/' . $theme->getReference() . '_h.png');
      unlink('../../includes/images/themes/backgrounds/' . $theme->getReference() . '.png');
      unlink('../../includes/images/themes/footers/' . $theme->getReference() . '_f.png');

      if ($theme->getLogo() == 'Y')
        unlink('../../includes/images/themes/logos/' . $theme->getReference() . '_l.png');
    }

    // Suppression de l'enregistrement en base
    physiqueDeleteTheme($idTheme);

    // Suppression préférence utilisateurs
    if (!empty($theme->getReference()))
      physiqueUpdateThemeUsers($theme->getReference());

    // Message d'alerte
    $_SESSION['alerts']['theme_deleted'] = true;
  }
?>
