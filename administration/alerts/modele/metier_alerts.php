<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/alerts.php');

  // METIER : Liste des messages d'alerte
  // RETOUR : Messages d'alerte
  function getAlerts()
  {
    $alerts = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM alerts ORDER BY category ASC, type DESC, alert ASC');
    while($donnees = $reponse->fetch())
    {
      $myAlert = Alerte::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($alerts, $myAlert);
    }
    $reponse->closeCursor();

    return $alerts;
  }

  // METIER : Insertion d'une alerte
  // RETOUR : Id alerte créée
  function insertAlert($post)
  {
    $type      = $post['type_alert'];
    $category  = $post['category_alert'];
    $reference = $post['reference_alert'];
    $message   = $post['message_alert'];

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['type_alert']      = $type;
    $_SESSION['save']['category_alert']  = $category;
    $_SESSION['save']['reference_alert'] = $reference;
    $_SESSION['save']['message_alert']   = $message;

    $new_id     = NULL;
    $control_ok = true;

    global $bdd;

    // Contrôle référence
    $reponse = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $reference . '"');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
    {
      $control_ok = false;
      $_SESSION['alerts']['already_referenced'] = true;
    }

    $reponse->closeCursor();

    // Si contrôles ok, insertion table
    if ($control_ok == true)
    {
      $reponse2 = $bdd->prepare('INSERT INTO alerts(category,
                                                    type,
                                                    alert,
                                                    message)
                                             VALUES(:category,
                                                    :type,
                                                    :alert,
                                                    :message)');
      $reponse2->execute(array(
        'category' => $category,
        'type'     => $type,
        'alert'    => $reference,
        'message'  => $message
        ));
      $reponse2->closeCursor();

      $new_id = $bdd->lastInsertId();
      $_SESSION['alerts']['alert_added'] = true;
    }

    return $new_id;
  }

  // METIER : Modification d'une alerte
  // RETOUR : Id alerte
  function updateAlert($post, $id_alert)
  {
    $id_alert  = $post['id_alert'];
    $type      = $post['type_alert'];
    $category  = $post['category_alert'];
    $reference = $post['reference_alert'];
    $message   = $post['message_alert'];

    $control_ok = true;

    global $bdd;

    // Contrôle référence
    $reponse = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $reference . '" AND id != ' . $id_alert);
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
    {
      $control_ok = false;
      $_SESSION['alerts']['already_referenced'] = true;
    }

    $reponse->closeCursor();

    // Si contrôles ok, modification table
    if ($control_ok == true)
    {
      $reponse2 = $bdd->prepare('UPDATE alerts SET category = :category,
                                                   type     = :type,
                                                   alert    = :alert,
                                                   message  = :message
                                             WHERE id = ' . $id_alert);
      $reponse2->execute(array(
        'category' => $category,
        'type'     => $type,
        'alert'    => $reference,
        'message'  => $message
      ));
      $reponse2->closeCursor();

      $_SESSION['alerts']['alert_updated'] = true;
    }

    return $id_alert;
  }

  // METIER : Suppression d'une alerte
  // RETOUR : Aucun
  function deleteAlert($post)
  {
    $id_alert = $post['id_alert'];

    global $bdd;

    // Suppression de l'alerte de la base
    $reponse = $bdd->exec('DELETE FROM alerts WHERE id = ' . $id_alert);

    $_SESSION['alerts']['alert_deleted'] = true;
  }
?>
