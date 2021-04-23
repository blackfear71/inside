<?php
  // Récupération des messages d'alerte
  $messages = getAlertesInside();

  // Affichage des messages
  if (!empty($messages))
  {
    echo '<div class="fond_alerte">';
      echo '<div class="zone_affichage_alerte" id="alerte">';
        // Titre
        echo '<div class="zone_titre_alerte">';
          echo '<img src="/inside/includes/icons/common/inside_grey.png" alt="inside_grey" class="image_alerte" />';
          echo '<div class="titre_alerte">Inside - Alerte</div>';
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
                  echo '<img src="/inside/includes/icons/common/alerts_grey.png" alt="alerts_grey" title="Alerte" class="logo_alerte" />';
                  break;

                case 'info':
                  echo '<img src="/inside/includes/icons/common/information_grey.png" alt="information_grey" title="Information" class="logo_alerte" />';
                  break;

                case 'question':
                  echo '<img src="/inside/includes/icons/common/question_grey.png" alt="question_grey" title="Inconnu" class="logo_alerte" />';
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
        echo '<a id="fermerAlerte" class="bouton_alerte">Fermer</a>';
      echo '</div>';
    echo '</div>';

    // Suppression des messages une fois affichés
    $messages = array();
  }
?>
