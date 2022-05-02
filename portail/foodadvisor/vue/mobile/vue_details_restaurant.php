<?php
  echo '<div id="zone_details_restaurant" class="fond_details">';
    echo '<div class="div_details">';
      echo '<div class="zone_contenu_details">';
        // Titre
        echo '<div class="titre_details">';
          echo '<img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />';
          echo '<div class="texte_titre_section"></div>';
        echo '</div>';

        // Image
        echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="" class="image_details" />';

        // Choix rapide
        if ($choixRapide == true)
        {
          echo '<form id="choix_rapide_details" method="post" action="">';
            echo '<input type="hidden" name="id_restaurant" value="" />';
            echo '<input type="submit" name="fast_restaurant" value="Voter pour ce restaurant" class="bouton_action_details" />';
          echo '</form>';
        }

        // Informations
        echo '<div class="zone_details_informations">';
          // Titre
          echo '<div id="titre_details_informations" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Informations</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Informations du restautant
          echo '<div id="afficher_details_informations">';
            // Lieu
            echo '<div class="zone_lieu_details">';
              echo '<img src="../../includes/icons/foodadvisor/location.png" alt="location" class="icone_details" />';
              echo '<div class="lieu_details"></div>';
            echo '</div>';

            // Jours d'ouverture
            echo '<div class="zone_details_ouverture">';
              $semaineShort  = array('Lu', 'Ma', 'Me', 'Je', 'Ve');

              foreach ($semaineShort as $keyDay => $dayShort)
              {
                echo '<div id="jour_details_' . $keyDay . '" class="jour_details">' . $dayShort . '</div>';
              }
            echo '</div>';

            // Prix
            echo '<div class="prix_details"></div>';

            // Types du restaurant
            echo '<div class="zone_types_details"></div>';

            // Numéro de téléphone
            echo '<div class="zone_appelant_details">';
              // Logo
              echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Numéro de téléphone" class="icone_telephone_details" />';

              // Numéro de téléphone
              echo '<div class="telephone_details"></div>';
            echo '</div>';

            // Liens
            echo '<div class="zone_liens_details">';
              echo '<a id="website_details" href="" target="_blank">';
                echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="icone_lien_details" />';
              echo '</a>';

              echo '<a id="plan_details" href="" target="_blank">';
                echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Plan" class="icone_lien_details" />';
              echo '</a>';

              echo '<a id="lafourchette_details" href="" target="_blank">';
                echo '<img src="../../includes/icons/foodadvisor/lafourchette.png" alt="lafourchette" title="LaFourchette" class="icone_lien_details" />';
              echo '</a>';
            echo '</div>';
          echo '</div>';
        echo '</div>';

        // Description du restaurant
        echo '<div class="zone_details_description">';
          // Titre
          echo '<div id="titre_details_description" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/description_grey.png" alt="description_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">À propos du restaurant</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Description
          echo '<div id="afficher_details_description" class="zone_details_texte"></div>';
        echo '</div>';

        // Actions
        echo '<div class="zone_details_actions">';
          // Modifier
          echo '<a title="Modifier" class="lien_modifier_restaurant modifierRestaurant">';
            echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="icone_modifier_restaurant" />';
          echo '</a>';

          // Supprimer
          echo '<form method="post" action="restaurants.php?action=doSupprimer" class="form_supprimer_restaurant">';
            echo '<input type="hidden" name="id_restaurant" value="" />';
            echo '<input type="submit" name="delete_restaurant" value="" title="Supprimer" class="icone_supprimer_restaurant eventConfirm" />';
            echo '<input type="hidden" value="Supprimer ce restaurant de la liste ?" class="eventMessage" />';
          echo '</form>';
        echo '</div>';
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerDetailsRestaurant" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
