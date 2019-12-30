<?php
  include_once('../../includes/classes/generator.php');

  // METIER : Définition des options
  // RETOUR : Options
  function initializeOptions()
  {
    $options = array(array('option' => 'common',     'checked' => 'Y', 'titre' => 'Fonctions communes', 'categorie' => 'Contrôleur'),
                     array('option' => 'dates',      'checked' => 'N', 'titre' => 'Fonctions dates',    'categorie' => 'Contrôleur'),
                     array('option' => 'regex',      'checked' => 'N', 'titre' => 'Fonctions regex',    'categorie' => 'Contrôleur'),
                     array('option' => 'admin',      'checked' => 'N', 'titre' => 'Page admin',         'categorie' => 'Contrôleur'),
                     array('option' => 'angular',    'checked' => 'N', 'titre' => 'Angular',            'categorie' => 'Commun'),
                     array('option' => 'chat',       'checked' => 'Y', 'titre' => 'Chat',               'categorie' => 'Commun'),
                     array('option' => 'datepicker', 'checked' => 'N', 'titre' => 'Datepicker',         'categorie' => 'Commun'),
                     array('option' => 'masonry',    'checked' => 'N', 'titre' => 'Masonry',            'categorie' => 'Commun'),
                     array('option' => 'exif',       'checked' => 'N', 'titre' => 'Données EXIF',       'categorie' => 'Commun'),
                     array('option' => 'alerts',     'checked' => 'Y', 'titre' => 'Alertes',            'categorie' => 'Commun')
                    );

    return $options;
  }

  // METIER : Initialise les options du générateur
  // RETOUR : Options
  function initializeGenerator()
  {
    // Initialisations
    $generatorParameters = new GeneratorParameters();
    $generatorOptions    = array();

    // Options
    $options = initializeOptions();

    foreach ($options as $option)
    {
      $generatorOption = new GeneratorOptions();

      $generatorOption->setOption($option['option']);
      $generatorOption->setChecked($option['checked']);
      $generatorOption->setTitre($option['titre']);
      $generatorOption->setCategorie($option['categorie']);

      array_push($generatorOptions, $generatorOption);
    }

    // Ajout à l'objet GeneratorParameters
    $generatorParameters->setOptions($generatorOptions);

    return $generatorParameters;
  }

  // METIER : Sauvegarde des paramètres en session
  // Retour : Aucun
  function saveParameters($post)
  {
    $options = initializeOptions();

    $_SESSION['generator']['nom_fonctionnel']   = $_POST['nom_fonctionnel'];
    $_SESSION['generator']['nom_technique']     = $_POST['nom_technique'];
    $_SESSION['generator']['nom_head']          = $_POST['nom_head'];
    $_SESSION['generator']['style_specifique']  = $_POST['style_specifique'];
    $_SESSION['generator']['script_specifique'] = $_POST['script_specifique'];

    foreach ($options as $option)
    {
      if (isset($post[$option['option']]) AND !empty($post[$option['option']]))
      {
        $_SESSION['generator'][$option['option']] = $option['option'];
      }
    }
  }

  // METIER : Génère le code de la nouvelle page
  // RETOUR : Paramètres générateur
  function getGenerator($parametres)
  {
    // Suppression de la session
    unset($_SESSION['generator']);

    // Initialisations
    $generatorParameters = new GeneratorParameters();
    $generatorOptions    = array();

    // Récupération des paramètres
    $generatorParameters->setNom_section($parametres['nom_fonctionnel']);
    $generatorParameters->setNom_technique($parametres['nom_technique']);
    $generatorParameters->setNom_head($parametres['nom_head']);
    $generatorParameters->setStyle_specifique($parametres['style_specifique']);
    $generatorParameters->setScript_specifique($parametres['script_specifique']);

    // Options
    $options = initializeOptions();

    foreach ($options as $option)
    {
      $generatorOption = new GeneratorOptions();

      $generatorOption->setOption($option['option']);
      $generatorOption->setTitre($option['titre']);
      $generatorOption->setCategorie($option['categorie']);

      if (isset($parametres[$option['option']]))
        $generatorOption->setChecked('Y');
      else
        $generatorOption->setChecked('N');

      array_push($generatorOptions, $generatorOption);
    }

    // Ajout à l'objet GeneratorParameters
    $generatorParameters->setOptions($generatorOptions);

    return $generatorParameters;
  }

  // METIER : Formate le fichier Contrôleur
  // RETOUR : Fichier Contrôleur
  function getControler($generatorParameters)
  {
    // Initialisations
    $nom_technique = str_replace(' ', '_', $generatorParameters->getNom_technique());
    $file          = 'templates/controler.php';
    $options       = array();
    $controler     = array('filename' => $nom_technique . '.php',
                           'content'  => file_get_contents($file)
                          );

    // On met les options dans un tableau associatif
    foreach ($generatorParameters->getOptions() as $generatorOption)
    {
      $options[$generatorOption->getOption()] = $generatorOption;
    }

    // Nom section
    $length_name = strlen($generatorParameters->getNom_section());

    $controler = str_replace('/********************', '/' . str_repeat('*', $length_name + 8), $controler);
    $controler = str_replace('section_name', $generatorParameters->getNom_section(), $controler);
    $controler = str_replace('*********************', str_repeat('*', $length_name + 9), $controler);
    $controler = str_replace('********************/', str_repeat('*', $length_name + 8) . '/', $controler);

    // Titre fonctions communes
    if ($options['common']->getChecked() == 'Y' OR $options['dates']->getChecked() == 'Y' OR $options['regex']->getChecked() == 'Y')
      $controler = str_replace('/*title_common*/', '
  // Fonctions communes', $controler);
    else
      $controler = str_replace('/*title_common*/
  /*common_functions*/', '', $controler);

    // Fonctions communes
    if ($options['common']->getChecked() == 'Y')
      $controler = str_replace('/*common_functions*/', 'include_once(\'../../includes/functions/fonctions_communes.php\');
  /*common_functions*/', $controler);

    // Fonctions dates
    if ($options['dates']->getChecked() == 'Y')
      $controler = str_replace('/*common_functions*/', 'include_once(\'../../includes/functions/fonctions_dates.php\');
  /*common_functions*/', $controler);

    // Fonctions regex
    if ($options['regex']->getChecked() == 'Y')
      $controler = str_replace('/*common_functions*/', 'include_once(\'../../includes/functions/fonctions_regex.php\');
', $controler);

    // Suppression balise
    $controler = str_replace('/*common_functions*/', '', $controler);

    // Contrôles
    if ($options['admin']->getChecked() == 'Y')
    {
      $controler = str_replace('/*title_controls*/', '// Contrôles communs Administrateur', $controler);
      $controler = str_replace('/*control_function*/', 'controlsAdmin();', $controler);
    }
    else
    {
      $controler = str_replace('/*title_controls*/', '// Contrôles communs Utilisateur', $controler);
      $controler = str_replace('/*control_function*/', 'controlsUser();', $controler);
    }

    // Appel métier
    $controler = str_replace('/*functions_calls*/', 'include_once(\'modele/metier_' . $nom_technique . '.php\');', $controler);

    // Contrôle action URL renseignée
    $controler = str_replace('/*control_action*/', 'header(\'location: ' . $nom_technique . '.php?action=goConsulter\');', $controler);

    // Redirection affichage
    $controler = str_replace('/*include_view*/', 'include_once(\'vue/vue_' . $nom_technique . '.php\');', $controler);

    return $controler;
  }

  // METIER : Formate le fichier Métier
  // RETOUR : Fichier Métier
  function getMetier($generatorParameters)
  {
    // Initialisations
    $nom_technique = str_replace(' ', '_', $generatorParameters->getNom_technique());
    $file          = 'templates/metier.php';
    $options       = array();
    $metier        = array('filename' => 'metier_' . $nom_technique . '.php',
                           'content'  => file_get_contents($file)
                          );

    return $metier;
  }

  // METIER : Formate le fichier Vue
  // RETOUR : Fichier Vue
  function getVue($generatorParameters)
  {
    // Initialisations
    $search  = array(" ", ".css", ".js");
    $replace = array("_", "", "");

    $nom_fonctionnel = str_replace($search, $replace, $generatorParameters->getNom_section());
    $nom_technique   = str_replace($search, $replace, $generatorParameters->getNom_technique());
    $nom_head        = str_replace($search, $replace, $generatorParameters->getNom_head());

    if (!empty($generatorParameters->getStyle_specifique()))
      $style_specifique = str_replace($search, $replace, $generatorParameters->getStyle_specifique()) . ".css";
    else
      $style_specifique = "";

    if (!empty($generatorParameters->getStyle_specifique()))
      $script_specifique = str_replace($search, $replace, $generatorParameters->getScript_specifique()) . ".js";
    else
      $script_specifique = "";

    $file    = 'templates/vue.php';
    $options = array();
    $vue     = array('filename' => 'vue_' . $nom_technique . '.php',
                     'content'  => file_get_contents($file)
                    );

    // On met les options dans un tableau associatif
    foreach ($generatorParameters->getOptions() as $generatorOption)
    {
      $options[$generatorOption->getOption()] = $generatorOption;
    }

    // Titre Head
    $vue = str_replace('/*title_head*/', '"' . $nom_head . '"', $vue);

    // Style spécifique
    $vue = str_replace('/*style_specifique*/', '"' . $style_specifique . '"', $vue);

    // Script spécifique
    $vue = str_replace('/*script_specifique*/', '"' . $script_specifique . '"', $vue);

    // Appels communs
    if ($options['angular']->getChecked() == 'Y')
      $vue = str_replace('/*angular_head*/', 'true', $vue);
    else
      $vue = str_replace('/*angular_head*/', 'false', $vue);

    if ($options['chat']->getChecked() == 'Y' AND $options['admin']->getChecked() != 'Y')
      $vue = str_replace('/*chat_head*/', 'true', $vue);
    else
      $vue = str_replace('/*chat_head*/', 'false', $vue);

    if ($options['datepicker']->getChecked() == 'Y')
      $vue = str_replace('/*datepicker_head*/', 'true', $vue);
    else
      $vue = str_replace('/*datepicker_head*/', 'false', $vue);

    if ($options['masonry']->getChecked() == 'Y')
      $vue = str_replace('/*masonry_head*/', 'true', $vue);
    else
      $vue = str_replace('/*masonry_head*/', 'false', $vue);

    if ($options['exif']->getChecked() == 'Y')
      $vue = str_replace('/*exif_head*/', 'true', $vue);
    else
      $vue = str_replace('/*exif_head*/', 'false', $vue);

    // Titre de la page
    $vue = str_replace('/*title*/', '"' . $nom_fonctionnel . '"', $vue);

    // Onglets
    if ($options['admin']->getChecked() == 'Y')
      $vue = str_replace('/*onglets*/', '', $vue);
    else
      $vue = str_replace('/*onglets*/', '
        include(\'../../includes/common/onglets.php\');', $vue);

    // Alertes
    if ($options['alerts']->getChecked() == 'Y')
      $vue = str_replace('/*alerts*/', '
			<!-- Messages d\'alerte -->
			<?php
				include(\'../../includes/common/alerts.php\');
			?>
', $vue);
    else
      $vue = str_replace('/*alerts*/', '', $vue);

    // Missions
    if ($options['admin']->getChecked() == 'Y')
      $vue = str_replace('/*missions*/', '', $vue);
    else
      $vue = str_replace('/*missions*/', '
          /********************/
          /* Boutons missions */
          /********************/
          $zone_inside = "article";
          include(\'../../includes/common/missions.php\');
', $vue);

    // Chat
    if ($options['admin']->getChecked() == 'Y')
      $vue = str_replace('/*chat*/', '', $vue);
    else
      $vue = str_replace('/*chat*/', '

      <?php include(\'../../includes/chat/chat.php\'); ?>', $vue);

    return $vue;
  }
?>
