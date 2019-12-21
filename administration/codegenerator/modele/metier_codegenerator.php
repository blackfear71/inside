<?php
  include_once('../../includes/classes/generator.php');

  // METIER : Définition des options
  // RETOUR : Options
  function initializeOptions()
  {
    $options = array(array('option' => 'admin',      'checked' => 'N', 'titre' => 'Page admin',         'categorie' => 'Contrôleur'),
                     array('option' => 'common',     'checked' => 'Y', 'titre' => 'Fonctions communes', 'categorie' => 'Contrôleur'),
                     array('option' => 'dates',      'checked' => 'N', 'titre' => 'Fonctions dates',    'categorie' => 'Contrôleur'),
                     array('option' => 'regex',      'checked' => 'N', 'titre' => 'Fonctions regex',    'categorie' => 'Contrôleur'),
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
        $generatorOption->setChecked($option['checked']);

      array_push($generatorOptions, $generatorOption);
    }

    // Ajout à l'objet GeneratorParameters
    $generatorParameters->setOptions($generatorOptions);

    return $generatorParameters;
  }
?>
