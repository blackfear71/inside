<?php
  echo '<div id="zone_details_proposition" class="fond_details">';
    echo '<div class="div_details">';
      echo '<div class="zone_contenu_details">';
        // Titre
        echo '<div class="titre_details">';
          echo '<img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />';
          echo '<div class="texte_titre_section"></div>';
        echo '</div>';

        // Image
        echo '<img src="" alt="" title="" class="image_details" />';

        // Informations
        echo '<div class="zone_details_informations">';
          // Titre
          echo '<div id="titre_proposition_infos" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Informations</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Informations du restautant
          echo '<div id="afficher_proposition_infos" class="zone_details_restaurant">';
            // Lieu
            echo '<div class="lieu_details_proposition">';
              echo '<img src="../../includes/icons/foodadvisor/location.png" alt="location" class="icone_details" />';
              echo '<div class="lieu_details"></div>';
            echo '</div>';

            // Nombre de participants
            echo '<div class="nombre_participants_details_proposition">';
              echo '<img src="../../includes/icons/foodadvisor/users.png" alt="users" class="icone_details" />';
              echo '<div class="nombre_participants_details"></div>';
            echo '</div>';

            // Jours d'ouverture
            echo '<div class="zone_details_ouverture">';
              $semaineShort  = array("Lu", "Ma", "Me", "Je", "Ve");

              foreach ($semaineShort as $keyDay => $dayShort)
              {
                echo '<div id="jour_details_proposition_' . $keyDay . '" class="jour_details">' . $dayShort . '</div>';
              }
            echo '</div>';

            // Prix
            echo '<div class="prix_details"></div>';

            // Types du restaurant

            // Appelant & numéro de téléphone

            // Liens

          echo '</div>';
        echo '</div>';

        // Participants

        // Description du restaurant

        // Actions
        
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerDetailsProposition" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
