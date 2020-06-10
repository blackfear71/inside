<?php
  include_once('../../includes/classes/alerts.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['already_referenced']) OR $_SESSION['alerts']['already_referenced'] != true))
    {
      unset($_SESSION['save']);

      $_SESSION['save']['type_alert']      = '';
      $_SESSION['save']['category_alert']  = '';
      $_SESSION['save']['reference_alert'] = '';
      $_SESSION['save']['message_alert']   = '';
    }
  }

  // METIER : Liste des messages d'alerte
  // RETOUR : Messages d'alerte
  function getAlerts()
  {
    // Récupération des alertes
    $alertes = physiqueListeAlertes();

    // Retour
    return $alertes;
  }

  // METIER : Insertion d'une alerte
  // RETOUR : Id alerte créée
  function insertAlert($post)
  {
    // Initialisations
    $newId      = NULL;
    $control_ok = true;

    // Récupération des données
    $type      = $post['type_alert'];
    $category  = $post['category_alert'];
    $reference = $post['reference_alert'];
    $message   = $post['message_alert'];

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['type_alert']      = $type;
    $_SESSION['save']['category_alert']  = $category;
    $_SESSION['save']['reference_alert'] = $reference;
    $_SESSION['save']['message_alert']   = $message;

    // Contrôle référence unique
    $control_ok = controleReferenceUnique($reference);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $alerte = array('category' => $category,
                      'type'     => $type,
                      'alert'    => $reference,
                      'message'  => $message
                     );

      $newId = physiqueInsertionAlerte($alerte);

      // Message d'alerte
      $_SESSION['alerts']['alert_added'] = true;
    }

    return $newId;
  }

  // METIER : Modification d'une alerte
  // RETOUR : Id alerte
  function updateAlert($post)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idAlert   = $post['id_alert'];
    $type      = $post['type_alert'];
    $category  = $post['category_alert'];
    $reference = $post['reference_alert'];
    $message   = $post['message_alert'];

    // Contrôle référence unique
    $control_ok = controleReferenceUniqueUpdate($reference, $idAlert);

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $alerte = array('category' => $category,
                      'type'     => $type,
                      'alert'    => $reference,
                      'message'  => $message
                     );

      physiqueUpdateAlerte($alerte, $idAlert);

      // Message d'alerte
      $_SESSION['alerts']['alert_updated'] = true;
    }

    // Retour
    return $idAlert;
  }

  // METIER : Suppression d'une alerte
  // RETOUR : Aucun
  function deleteAlert($post)
  {
    // Récupération des données
    $idAlert = $post['id_alert'];

    // Suppression de l'enregistrement en base
    physiqueDeleteAlerte($idAlert);

    // Message d'alerte
    $_SESSION['alerts']['alert_deleted'] = true;
  }
?>
