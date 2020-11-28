<?php
  // Récupération des messages d'alerte
  $messages = getAlertesInside();

  // Affichage des messages
  if (!empty($messages))
  {
    echo '<div class="fond_alerte" id="alerte">';
      echo '<div class="zone_affichage_alerte">';
        // Titre
        echo '<div class="titre_alerte">';
          echo 'Inside';
        echo '</div>';

        // Affichage des alertes
        echo '<div class="zone_alertes">';
          foreach ($messages as $message)
          {
            echo '<div class="zone_texte_alerte">';
              // Icône
              switch ($message['logo'])
              {
                case 'erreur':
                  echo '<img src="/inside/includes/icons/common/alert.png" alt="alert" title="Alerte" class="logo_alerte" />';
                  break;

                case 'info':
                  echo '<img src="/inside/includes/icons/common/information.png" alt="information" title="Information" class="logo_alerte" />';
                  break;

                case 'question':
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

        // Bouton
        echo '<div class="zone_boutons_alerte">';
          echo '<a id="boutonFermerAlerte" class="bouton_alerte">Fermer</a>';
        echo '</div>';
      echo '</div>';
    echo '</div>';

    $messages = array();
  }
?>
