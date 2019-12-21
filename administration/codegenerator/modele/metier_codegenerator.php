<?php
  include_once('../../includes/classes/generator.php');

  // METIER : Initialise les options du générateur
  // RETOUR : Options
  function initializeGenerator()
  {
    // Initialisations
    $generatorParameters = new GeneratorParameters();
    $generatorOptions    = array();

    // Options
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
?>
