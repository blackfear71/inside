<?php
  // Initialisation des messages d'alerte
  $messages = array();

  if (isset($_SESSION['alerts'])AND !empty($_SESSION['alerts']))
  {
    // Initialisation variables d'alerte
    foreach ($_SESSION['alerts'] as $key_alert => $alert)
    {
      if ($alert != true)
        $_SESSION['alerts'][$key_alert] = NULL;
    }

    // Boucle de lecture des messages d'alerte
    foreach ($_SESSION['alerts'] as $key_alert => $alert)
    {
      if (isset($alert) AND $alert == true)
      {
        $reponse2 = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $key_alert . '"');
        $donnees2 = $reponse2->fetch();

        // On ajoute la ligne au tableau (logo + message)
        if ($reponse2->rowCount() > 0)
          $ligneMessage = array('logo' => $donnees2['type'], 'texte' => $donnees2['message'] . '<br />');
        else
          $ligneMessage = array('logo' => '', 'texte' => 'Message d\'alerte non défini pour : ' . $key_alert . '<br />');

        array_push($messages, $ligneMessage);

        $reponse2->closeCursor();

        // Réinitialisation de l'erreur
        $_SESSION['alerts'][$key_alert] = NULL;
      }
    }
  }

  // Affichage des messages
  if (!empty($messages))
  {
    echo '<div class="message_alerte" id="alerte">';
      echo '<div class="inside_alerte">';
        echo 'Inside';
      echo '</div>';
      echo '<div class="texte_alerte">';
        foreach ($messages as $message)
        {
          switch ($message['logo'])
          {
            case "info":
              echo '<img src="/inside/includes/icons/common/info.png" alt="info" title="Information" class="logo_alerte" />';
              break;

            case "erreur":
              echo '<img src="/inside/includes/icons/common/bug.png" alt="bug" title="Erreur" class="logo_alerte" />';
              break;

            default:
              break;
          }

          echo $message['texte'];
        }
      echo '</div>';
      echo '<div class="boutons_alerte">';
        echo '<a onclick="masquerAlerte(\'alerte\')" class="close_alerte">Fermer</a>';
      echo '</div>';
    echo '</div>';

    $messages = array();
  }
?>
