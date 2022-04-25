<?php
  include_once('../../includes/classes/parcours.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Erreur
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if (!isset($_SESSION['alerts']['distance_error']) OR $_SESSION['alerts']['distance_error'] != true)
    {
      unset($_SESSION['save']);

      $_SESSION['save']['nom_parcours']      = '';
      $_SESSION['save']['distance_parcours'] = '';
      $_SESSION['save']['lieu_parcours']     = '';
      $_SESSION['save']['url_parcours']      = '';
    }
  }

  // METIER : Lecture liste des parcours
  // RETOUR : Liste des parcours
  function getListeParcours($equipe)
  {
    // Récupération de la liste des parcours
    $listeParcours = physiqueListeParcours($equipe);

    // Retour
    return $listeParcours;
  }

  // METIER : Contrôle parcours existant
  // RETOUR : Booléen
  function isParcoursDisponible($idParcours, $equipe)
  {
    // Contrôle parcours disponible
    $parcoursDisponible = controleParcoursDisponible($idParcours, $equipe);

    // Retour
    return $parcoursDisponible;
  }

  // METIER : Lecture d'un parcours
  // RETOUR : Objet Parcours
  function getParcours($idParcours)
  {
    // Récupération des détails d'un parcours
    $parcours = physiqueParcours($idParcours);

    // Retour
    return $parcours;
  }

  // METIER : Insertion d'un nouveau parcours
  // RETOUR : Booléen
  function insertParcours($post, $equipe)
  {
    // Initialisations
    $erreur     = false;
    $control_ok = true;

    // Récupération des données
    $nom      = $post['name'];
    $distance = $post['distance'];
    $lieu     = $post['location'];
    $url      = $post['url'];

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['nom_parcours']      = $post['name'];
    $_SESSION['save']['distance_parcours'] = $post['distance'];
    $_SESSION['save']['lieu_parcours']     = $post['location'];
    $_SESSION['save']['url_parcours']    = $post['url'];

    // Contrôle distance numérique
    $control_ok = controleDistanceNumerique($distance);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $parcours = array('team'     => $equipe,
                        'nom'      => $nom,
                        'distance' => $distance,
                        'lieu'     => $lieu,
                        'url'      => $url
                       );

      physiqueInsertionParcours($parcours);

      // Message d'alerte
      $_SESSION['alerts']['course_added'] = true;
    }

    // Positionnement erreur
    if ($control_ok != true)
      $erreur = true;

    // Retour
    return $erreur;
  }

  // METIER : Modification d'un parcours
  // RETOUR : Erreur
  function updateParcours($idParcours, $post)
  {
    // Initialisations
    $erreur     = false;
    $control_ok = true;

    // Récupération des données
    $nom      = $post['name'];
    $distance = $post['distance'];
    $lieu     = $post['location'];
    $url      = $post['url'];

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['nom_parcours']      = $post['name'];
    $_SESSION['save']['distance_parcours'] = $post['distance'];
    $_SESSION['save']['lieu_parcours']     = $post['location'];
    $_SESSION['save']['url_parcours']      = $post['url'];

    // Contrôle distance numérique
    $control_ok = controleDistanceNumerique($distance);

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $parcours = array('nom'      => $nom,
                        'distance' => $distance,
                        'lieu'     => $lieu,
                        'url'      => $url
                       );

      physiqueUpdateParcours($idParcours, $parcours);

      // Message d'alerte
      $_SESSION['alerts']['course_updated'] = true;
    }

    // Positionnement erreur
    if ($control_ok != true)
      $erreur = true;

    // Retour
    return $erreur;
  }

  // METIER : Conversion de la liste d'objets des parcours en tableau simple pour JSON
  // RETOUR : Tableau des parcours
  function convertForJsonListeParcours($listeParcours)
  {
    // Initialisations
    $listeParcoursAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($listeParcours as $parcours)
    {
      $parcours = array('id'      => $parcours->getId(),
                        'nom'     => $parcours->getNom(),
                        'distance'=> $parcours->getDistance(),
                        'lieu'    => $parcours->getLieu()
                       );

      array_push($listeParcoursAConvertir, $parcours);
    }

    // Retour
    return $listeParcoursAConvertir;
  }
?>
