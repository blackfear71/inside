<?php
  // Initialisation des messages d'alerte
  $messages = array();

  if (isset($_SESSION['alerts'])AND !empty($_SESSION['alerts']))
  {
    // Initialisation variables d'alerte
    foreach ($_SESSION['alerts'] as $key_alert => $alert)
    {
      if ($alert != true)
        unset($_SESSION['alerts'][$key_alert]);
    }

    // Boucle de lecture des messages d'alerte
    foreach ($_SESSION['alerts'] as $key_alert => $alert)
    {
      if (isset($alert) AND $alert == true)
      {
        $reponse = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $key_alert . '"');
        $donnees = $reponse->fetch();

        // On ajoute la ligne au tableau (logo + message)
        if ($reponse->rowCount() > 0)
          $ligneMessage = array('logo' => $donnees['type'], 'texte' => $donnees['message']);
        else
          $ligneMessage = array('logo' => 'question', 'texte' => 'Message d\'alerte non défini pour : ' . $key_alert);

        array_push($messages, $ligneMessage);

        $reponse->closeCursor();

        // Réinitialisation de l'erreur
        unset($_SESSION['alerts'][$key_alert]);
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

      echo '<div class="zone_alertes">';
        foreach ($messages as $message)
        {
          echo '<div class="zone_alerte">';
            // Icône
            switch ($message['logo'])
            {
              case "erreur":
                echo '<img src="/inside/includes/icons/common/bug.png" alt="bug" title="Erreur" class="logo_alerte" />';
                break;

              case "info":
                echo '<img src="/inside/includes/icons/common/info.png" alt="info" title="Information" class="logo_alerte" />';
                break;

              case "question":
                echo '<img src="/inside/includes/icons/common/question.png" alt="question" title="Inconnu" class="logo_alerte" />';
                break;

              default:
                break;
            }

            // Texte
            echo '<div class="texte_alerte">';
              echo $message['texte'];
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';

      echo '<div class="boutons_alerte">';
        echo '<a id="boutonFermerAlerte" class="bouton_alerte">Fermer</a>';
      echo '</div>';
    echo '</div>';

    $messages = array();
  }
?>
