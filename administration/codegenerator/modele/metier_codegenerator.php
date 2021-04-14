<?php
  include_once('../../includes/classes/generator.php');

  // METIER : Définition des options
  // RETOUR : Options
  function initializeOptions()
  {
    // Liste des options
    $options = array(array('option' => 'common',     'checked' => 'Y', 'titre' => 'Fonctions communes', 'categorie' => 'Contrôleur'),
                     array('option' => 'dates',      'checked' => 'N', 'titre' => 'Fonctions dates',    'categorie' => 'Contrôleur'),
                     array('option' => 'regex',      'checked' => 'N', 'titre' => 'Fonctions regex',    'categorie' => 'Contrôleur'),
                     array('option' => 'admin',      'checked' => 'N', 'titre' => 'Page admin',         'categorie' => 'Contrôleur'),
                     array('option' => 'angular',    'checked' => 'N', 'titre' => 'Angular',            'categorie' => 'Vue'),
                     array('option' => 'chat',       'checked' => 'Y', 'titre' => 'Chat',               'categorie' => 'Vue'),
                     array('option' => 'datepicker', 'checked' => 'N', 'titre' => 'Datepicker',         'categorie' => 'Vue'),
                     array('option' => 'masonry',    'checked' => 'N', 'titre' => 'Masonry',            'categorie' => 'Vue'),
                     array('option' => 'exif',       'checked' => 'N', 'titre' => 'Données EXIF',       'categorie' => 'Vue'),
                     array('option' => 'onglets',    'checked' => 'Y', 'titre' => 'Onglets',            'categorie' => 'Vue'),
                     array('option' => 'alerts',     'checked' => 'Y', 'titre' => 'Alertes',            'categorie' => 'Vue'),
                     array('option' => 'success',    'checked' => 'Y', 'titre' => 'Déblocage succès',   'categorie' => 'Vue'),
                     array('option' => 'mobile',     'checked' => 'N', 'titre' => 'Avec version mobile',     'categorie' => 'Vue')
                    );

    // Retour
    return $options;
  }

  // METIER : Initialise les options du générateur
  // RETOUR : Options
  function initializeGenerator()
  {
    // Initialisations
    $generatorParameters = new GeneratorParameters();
    $generatorOptions    = array();

    // Récupération des options
    $options = initializeOptions();

    // Génération des options sous forme d'objet
    foreach ($options as $option)
    {
      $generatorOption = new GeneratorOptions();

      $generatorOption->setOption($option['option']);
      $generatorOption->setChecked($option['checked']);
      $generatorOption->setTitre($option['titre']);
      $generatorOption->setCategorie($option['categorie']);

      // Ajout à la liste des options
      array_push($generatorOptions, $generatorOption);
    }

    // Ajout à l'objet GeneratorParameters
    $generatorParameters->setOptions($generatorOptions);

    // Retour
    return $generatorParameters;
  }

  // METIER : Sauvegarde des paramètres en session
  // RETOUR : Aucun
  function saveParameters($post)
  {
    // Récupération des options
    $options = initializeOptions();

    // Sauvegarde en session
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

    // Récupération des options
    $options = initializeOptions();

    // Génération des options sous forme d'objet
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

      // Ajout à la liste des options
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
    $nomFonctionnel = trim($generatorParameters->getNom_section());
    $nomTechnique   = str_replace(' ', '_', trim($generatorParameters->getNom_technique()));

    // On met les options dans un tableau associatif
    $options = array();

    foreach ($generatorParameters->getOptions() as $generatorOption)
    {
      $options[$generatorOption->getOption()] = $generatorOption;
    }

    // Données du fichier
    $file           = 'templates/controler.php';
    $controler      = array('filename' => $nomTechnique . '.php',
                            'content'  => file_get_contents($file)
                           );

    // Nom section
    $lengthName = strlen($nomFonctionnel);

    $controler = str_replace('/*******************', '/' . str_repeat('*', $lengthName + 7), $controler);
    $controler = str_replace('section_name', $nomFonctionnel, $controler);
    $controler = str_replace('********************', str_repeat('*', $lengthName + 8), $controler);
    $controler = str_replace('*******************/', str_repeat('*', $lengthName + 7) . '/', $controler);

    // Titre fonctions communes
    if ($options['common']->getChecked() == 'Y' OR $options['dates']->getChecked() == 'Y' OR $options['regex']->getChecked() == 'Y')
      $controler = str_replace('/*title_common*/', '
  // Fonctions communes', $controler);
    else
      $controler = str_replace('/*title_common*/
  /*common_functions*/', '', $controler);

    // Fonctions communes
    if ($options['common']->getChecked() == 'Y')
      $controler = str_replace('/*common_functions*/', 'include_once(\'../../includes/functions/metier_commun.php\');
  include_once(\'../../includes/functions/physique_commun.php\');
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

    // Appels métier
    $controler = str_replace('/*functions_calls*/', 'include_once(\'modele/metier_' . $nomTechnique . '.php\');
  include_once(\'modele/controles_' . $nomTechnique . '.php\');
  include_once(\'modele/physique_' . $nomTechnique . '.php\');', $controler);

    // Contrôle action URL renseignée
    $controler = str_replace('/*control_action*/', 'header(\'location: ' . $nomTechnique . '.php?action=goConsulter\');', $controler);

    // Redirection affichage
    if ($options['mobile']->getChecked() == 'Y')
      $controler = str_replace('/*include_view*/', 'include_once(\'vue/\' . $_SESSION[\'index\'][\'plateforme\'] . \'/vue_' . $nomTechnique . '.php\');', $controler);
    else
      $controler = str_replace('/*include_view*/', 'include_once(\'vue/vue_' . $nomTechnique . '.php\');', $controler);

    // Retour
    return $controler;
  }

  // METIER : Formate le fichier Métier
  // RETOUR : Fichier Métier
  function getMetier($generatorParameters)
  {
    // Initialisations
    $nomTechnique = str_replace(' ', '_', trim($generatorParameters->getNom_technique()));
    $file         = 'templates/metier.php';
    $metier       = array('filename' => 'metier_' . $nomTechnique . '.php',
                          'content'  => file_get_contents($file)
                         );

    // Retour
    return $metier;
  }

  // METIER : Formate le fichier Contrôles
  // RETOUR : Fichier Contrôles
  function getControles($generatorParameters)
  {
    // Initialisations
    $nomTechnique = str_replace(' ', '_', trim($generatorParameters->getNom_technique()));
    $file         = 'templates/controles.php';
    $controles    = array('filename' => 'controles_' . $nomTechnique . '.php',
                          'content'  => file_get_contents($file)
                         );

    // Retour
    return $controles;
  }

  // METIER : Formate le fichier Physique
  // RETOUR : Fichier Physique
  function getPhysique($generatorParameters)
  {
    // Initialisations
    $nomTechnique = str_replace(' ', '_', trim($generatorParameters->getNom_technique()));
    $file         = 'templates/physique.php';
    $physique     = array('filename' => 'physique_' . $nomTechnique . '.php',
                          'content'  => file_get_contents($file)
                         );

    // Retour
    return $physique;
  }

  // METIER : Récupère les vues en fonction des options saisies
  // RETOUR : Liste des vues
  function getVues($generatorParameters)
  {
    // Initialisations
    $listeVues = array('vue_web'    => '',
                       'vue_mobile' => ''
                      );

    // On met les options dans un tableau associatif
    $options = array();

    foreach ($generatorParameters->getOptions() as $generatorOption)
    {
      $options[$generatorOption->getOption()] = $generatorOption;
    }

    // Récupération vue web
    $vueWeb = getVue($generatorParameters, false);

    // On ajoute la vue au tableau
    $listeVues['vue_web'] = $vueWeb;

    // Récupération vue mobile
    if ($options['mobile']->getChecked() == 'Y')
    {
      $vueMobile = getVue($generatorParameters, true);

      // On ajoute la vue au tableau
      $listeVues['vue_mobile'] = $vueMobile;
    }

    // Retour
    return $listeVues;
  }

  // METIER : Formate le fichier Vue
  // RETOUR : Fichier Vue
  function getVue($generatorParameters, $isMobile)
  {
    // Initialisations
    $nomFonctionnel = trim($generatorParameters->getNom_section());
    $nomHead        = trim($generatorParameters->getNom_head());

    $search  = array(' ', '.css', '.js');
    $replace = array('_', '', '');

    $nomTechnique = str_replace($search, $replace, trim($generatorParameters->getNom_technique()));

    if (!empty($generatorParameters->getStyle_specifique()))
      $styleSpecifique = str_replace($search, $replace, trim($generatorParameters->getStyle_specifique())) . '.css';
    else
      $styleSpecifique = '';

    if (!empty($generatorParameters->getStyle_specifique()))
      $scriptSpecifique = str_replace($search, $replace, trim($generatorParameters->getScript_specifique())) . '.js';
    else
      $scriptSpecifique = '';

    // On met les options dans un tableau associatif
    $options = array();

    foreach ($generatorParameters->getOptions() as $generatorOption)
    {
      $options[$generatorOption->getOption()] = $generatorOption;
    }

    // Données du fichier
    if ($isMobile == true)
      $file = 'templates/vue_mobile.php';
    else
      $file = 'templates/vue_web.php';

    $vue = array('filename' => 'vue_' . $nomTechnique . '.php',
                 'content'  => file_get_contents($file)
                );

    // Titre onglet navigateur
    $vue = str_replace('/*title_head*/', "'" . $nomHead . "'", $vue);

    // Style spécifique
    $vue = str_replace('/*style_specifique*/', "'" . $styleSpecifique . "'", $vue);

    // Script spécifique
    $vue = str_replace('/*script_specifique*/', "'" . $scriptSpecifique . "'", $vue);

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

    // Titre de la page (header)
    $vue = str_replace('/*title*/', "'" . $nomFonctionnel . "'", $vue);

    // Onglets
    if ($options['admin']->getChecked() == 'Y' OR $options['onglets']->getChecked() == 'N')
      $vue = str_replace('/*onglets*/', '', $vue);
    else
      $vue = str_replace('/*onglets*/', '
        include(\'../../includes/common/onglets.php\');', $vue);

    // Style balise section sans onglets (hors admin)
    if ($options['admin']->getChecked() != 'Y' AND $options['onglets']->getChecked() == 'N')
      $vue = str_replace('<section>', '<section class="section_no_nav">', $vue);

    // Alertes
    if ($options['alerts']->getChecked() == 'Y')
      $vue = str_replace('/*alerts*/', '
      <!-- Messages d\'alerte -->
      <?php include(\'../../includes/common/alerts.php\'); ?>
', $vue);
    else
      $vue = str_replace('/*alerts*/', '', $vue);

    // Déblocage succès (hors admin)
    if ($options['admin']->getChecked() != 'Y' AND $options['success']->getChecked() == 'Y')
      $vue = str_replace('/*success*/', '
      <!-- Déblocage succès -->
      <?php include(\'../../includes/common/success.php\'); ?>
', $vue);
    else
      $vue = str_replace('/*success*/', '', $vue);

    // Celsius
    if ($isMobile == true)
      $vue = str_replace('/*celsius*/', '\'' . $nomTechnique . '\'', $vue);

    // Missions
    if ($options['admin']->getChecked() == 'Y')
      $vue = str_replace('/*missions*/', '', $vue);
    else
      $vue = str_replace('/*missions*/', '
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = \'article\';
          include(\'../../includes/common/missions.php\');
', $vue);

    // Chat
    if ($options['chat']->getChecked() == 'Y' AND $options['admin']->getChecked() != 'Y')
      $vue = str_replace('/*chat*/', '

      <!-- Chat -->
      <?php include(\'../../includes/chat/chat.php\'); ?>', $vue);
    else
      $vue = str_replace('/*chat*/', '', $vue);

    // Retour
    return $vue;
  }
?>
