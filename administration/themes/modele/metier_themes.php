<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/themes.php');

  // METIER : Lecture des thèmes existants par type
  // RETOUR : Tableau des thèmes
  function getThemes($type)
  {
    $themes = array();

    global $bdd;

    // Lecture de la base des thèmes
    $reponse = $bdd->query('SELECT * FROM themes WHERE type = "' . $type . '" ORDER BY date_deb DESC, level ASC');

    while ($donnees = $reponse->fetch())
    {
      $myTheme = Theme::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($themes, $myTheme);
    }

    $reponse->closeCursor();

    return $themes;
  }

  // METIER : Insertion nouveau thème
  // RETOUR : Id enregistrement créé
  function insertTheme($post, $files)
  {
    global $bdd;

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['theme_title']      = $post['theme_title'];
    $_SESSION['save']['theme_ref']        = $post['theme_ref'];

    if ($post['theme_type'] == "M")
    {
      $_SESSION['save']['theme_date_deb'] = $post['theme_date_deb'];
      $_SESSION['save']['theme_date_fin'] = $post['theme_date_fin'];
      $_SESSION['save']['theme_level']    = '';
    }
    else
    {
      $_SESSION['save']['theme_date_deb'] = '';
      $_SESSION['save']['theme_date_fin'] = '';
      $_SESSION['save']['theme_level']    = $post['theme_level'];
    }

    $new_id     = NULL;
    $control_ok = true;

    // Récupération des données
    $theme     = $post['theme_title'];
    $reference = $post['theme_ref'];
    $logo      = "N";
    $type      = $post['theme_type'];

    if ($type == "M")
    {
      $date_deb = $post['theme_date_deb'];
      $date_fin = $post['theme_date_fin'];
      $level    = '';
    }
    else
    {
      $date_deb = '';
      $date_fin = '';
      $level    = $post['theme_level'];
    }

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(" ", "é", "è", "ê", "ë", "à", "â", "ç", "ô", "û");
    $replace   = array("_", "e", "e", "e", "e", "a", "a", "c", "o", "u");
    $reference = str_replace($search, $replace, $reference);

    // Contrôle référence unique
    $req1 = $bdd->query('SELECT * FROM themes WHERE reference = "' . $reference . '"');
    if ($req1->rowCount() > 0)
    {
      $_SESSION['alerts']['already_ref_theme'] = true;
      $control_ok                              = false;
    }
    $req1->closeCursor();

    if ($type == "M")
    {
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

      // Contrôle chevauchement dates
      if ($control_ok == true)
      {
        $conflict = false;
        $conflict = controlGeneratedTheme($date_deb, $date_fin, NULL);

        if ($conflict == true)
        {
          $_SESSION['alerts']['date_conflict'] = true;
          $control_ok                          = false;
        }
      }
    }
    else
    {
      // Contrôle niveau numérique
      if ($control_ok == true)
      {
        if (!is_numeric($level) OR $level < 0)
        {
          $_SESSION['alerts']['level_theme_numeric'] = true;
          $control_ok                                = false;
        }
      }
    }

    // Contrôle images présentes et indicateur présence logo
    if ($control_ok == true)
    {
      foreach ($files as $key_file => $file)
      {
        // Contrôle présence logo
        if ($key_file == "logo" AND !empty($file['name']) AND !empty($file['type']))
          $logo = "Y";

        // Contrôle présence autres fichiers
        if ($key_file != 'logo' AND (empty($file['name']) OR $file['name'] == NULL))
        {
          $_SESSION['alerts']['missing_theme_file'] = true;
          $control_ok                               = false;
        }
      }
    }

    // Contrôle des fichiers
    if ($control_ok == true)
    {
      // On contrôle la présence du dossier des images, sinon on le créé
      $dossier = "../../includes/images/themes";

      if (!is_dir($dossier))
        mkdir($dossier);

      // On contrôle la présence du dossier des entête, sinon on le créé
      $dossier_headers = $dossier . "/headers";

      if (!is_dir($dossier_headers))
        mkdir($dossier_headers);

      // On contrôle la présence du dossier des fonds, sinon on le créé
      $dossier_backgrounds = $dossier . "/backgrounds";

      if (!is_dir($dossier_backgrounds))
        mkdir($dossier_backgrounds);

      // On contrôle la présence du dossier des bas de page, sinon on le créé
      $dossier_footers = $dossier . "/footers";

      if (!is_dir($dossier_footers))
        mkdir($dossier_footers);

      // On contrôle la présence du dossier des logos, sinon on le créé
      $dossier_logos = $dossier . "/logos";

      if (!is_dir($dossier_logos))
        mkdir($dossier_logos);

      // Contrôle des fichiers
      foreach ($files as $key_file => $file)
      {
        // Si logo présent ou autre fichier que logo
        if (($key_file == "logo" AND $logo == "Y") OR $key_file != "logo")
        {
          // Nom du fichier
          switch ($key_file)
          {
            case "header":
              $name = $reference . '_h';
              break;

            case "footer":
              $name = $reference . '_f';
              break;

            case "logo":
              $name = $reference . '_l';
              break;

            case "background":
            default:
              $name = $reference;
              break;
          }

          $controlsFile = controlsUploadFile($file, $name, 'png');

          if ($controlsFile['control_ok'] == false)
          {
            $control_ok = false;
            break;
          }
        }
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // Insertion des fichiers
      foreach ($files as $key_file => $file)
      {
        // Insertion logo si présent ou autre que logo
        if (($key_file == "logo" AND $logo == "Y") OR $key_file != "logo")
        {
          // Dossier de destination
          switch ($key_file)
          {
            case "header":
              $dest_dir = $dossier_headers . '/';
              break;

            case "footer":
              $dest_dir = $dossier_footers . '/';
              break;

            case "logo":
              $dest_dir = $dossier_logos . '/';
              break;

            case "background":
            default:
              $dest_dir = $dossier_backgrounds . '/';
              break;
          }

          // Nouveau nom
          switch ($key_file)
          {
            case "header":
              $new_name = $reference . '_h.png';
              break;

            case "footer":
              $new_name = $reference . '_f.png';
              break;

            case "logo":
              $new_name = $reference . '_l.png';
              break;

            case "background":
            default:
              $new_name = $reference . '.png';
              break;
          }

          // Données à envoyer pour l'upload
          $controlsFile = array('control_ok' => true,
                                'new_name'   => $new_name,
                                'tmp_file'   => $file['tmp_name'],
                                'type_file'  => $file['type']
                               );

          // Upload fichier
          $control_ok = uploadFile($file, $controlsFile, $dest_dir);

          if ($controlsFile['control_ok'] == false)
          {
            $control_ok = false;
            break;
          }
        }
      }
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('INSERT INTO themes(reference,
                                                name,
                                                type,
                                                level,
                                                logo,
                                                date_deb,
                                                date_fin)
                                        VALUES(:reference,
                                               :name,
                                               :type,
                                               :level,
                                               :logo,
                                               :date_deb,
                                               :date_fin)');
      $req2->execute(array(
        'reference' => $reference,
        'name'      => $theme,
        'type'      => $type,
        'level'     => $level,
        'logo'      => $logo,
        'date_deb'  => $date_deb,
        'date_fin'  => $date_fin
      ));
      $req2->closeCursor();

      $new_id = $bdd->lastInsertId();

      $_SESSION['alerts']['theme_added'] = true;
    }

    return $new_id;
  }

  // METIER : Modification thème existant
  // RETOUR : Id thème
  function updateTheme($post)
  {
    global $bdd;

    $control_ok = true;

    $id_theme = $post['id_theme'];
    $theme    = $post['theme_title'];
    $type     = $post['theme_type'];

    if ($type == "M")
    {
      $date_deb = $post['theme_date_deb'];
      $date_fin = $post['theme_date_fin'];
      $level    = '';
    }
    else
    {
      $date_deb = '';
      $date_fin = '';
      $level    = $post['theme_level'];
    }

    if ($type == "M")
    {
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

      // Contrôle chevauchement dates
      if ($control_ok == true)
      {
        $conflict = false;
        $conflict = controlGeneratedTheme($date_deb, $date_fin, $id_theme);

        if ($conflict == true)
        {
          $_SESSION['alerts']['date_conflict'] = true;
          $control_ok                          = false;
        }
      }
    }
    else
    {
      // Contrôle niveau numérique
      if ($control_ok == true)
      {
        if (!is_numeric($level) OR $level < 0)
        {
          $_SESSION['alerts']['level_theme_numeric'] = true;
          $control_ok                                = false;
        }
      }
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $req = $bdd->prepare('UPDATE themes SET name     = :name,
                                              type     = :type,
                                              level    = :level,
                                              date_deb = :date_deb,
                                              date_fin = :date_fin
                                        WHERE id       = ' . $id_theme);
      $req->execute(array(
        'name'     => $theme,
        'type'     => $type,
        'level'    => $level,
        'date_deb' => $date_deb,
        'date_fin' => $date_fin
      ));
      $req->closeCursor();

      $_SESSION['alerts']['theme_updated'] = true;
    }

    return $id_theme;
  }

  // METIER : Contrôle dates thème non superposées
  // RETOUR : Booléen
  function controlGeneratedTheme($date_deb, $date_fin, $id_theme)
  {
    global $bdd;

    $conflict = false;

    if (!empty($id_theme))
      $reponse = $bdd->query('SELECT * FROM themes WHERE id != ' . $id_theme . ' AND type = "M" ORDER BY date_deb DESC ');
    else
      $reponse = $bdd->query('SELECT * FROM themes WHERE type = "M" ORDER BY date_deb DESC');

    while ($donnees = $reponse->fetch())
    {
      if (($date_deb >= $donnees['date_deb'] AND $date_deb <= $donnees['date_fin'])
      OR  ($date_fin >= $donnees['date_deb'] AND $date_fin <= $donnees['date_fin'])
      OR  ($date_deb <= $donnees['date_deb'] AND $date_fin >= $donnees['date_fin']))
      {
        $conflict = true;
        break;
      }
    }

    $reponse->closeCursor();

    return $conflict;
  }

  // METIER : Suppression thème
  // RETOUR : Aucun
  function deleteTheme($post)
  {
    $id_theme = $post['id_theme'];

    global $bdd;

    // Suppression images
    $req1 = $bdd->query('SELECT id, reference, logo FROM themes WHERE id = ' . $id_theme);
    $data1 = $req1->fetch();

    if (isset($data1['reference']) AND !empty($data1['reference']))
    {
      $reference = $data1['reference'];

      unlink("../../includes/images/themes/headers/" . $data1['reference'] . "_h.png");
      unlink("../../includes/images/themes/backgrounds/" . $data1['reference'] . ".png");
      unlink("../../includes/images/themes/footers/" . $data1['reference'] . "_f.png");

      if ($data1['logo'] == "Y")
        unlink("../../includes/images/themes/logos/" . $data1['reference'] . "_l.png");
    }

    $req1->closeCursor();

    // Suppression enregistrement base
    $req2 = $bdd->exec('DELETE FROM themes WHERE id = ' . $id_theme);

    // Suppression préférence utilisateurs
    if (isset($reference) AND !empty($reference))
    {
      $new_reference = "";

      $req3 = $bdd->prepare('UPDATE preferences SET ref_theme = :ref_theme WHERE ref_theme = "' . $reference . '"');
      $req3->execute(array(
        'ref_theme' => $new_reference
      ));
      $req3->closeCursor();
    }

    // Message d'alerte
    $_SESSION['alerts']['theme_deleted'] = true;
  }
?>
