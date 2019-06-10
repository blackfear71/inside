<?php
  /***********************************/
  /*** Détails de la détermination ***/
  /***********************************/
  echo '<div id="zone_details" class="fond_saisie_restaurant">';
    echo '<div class="zone_details_proposition">';
      /**************************/
      /*** Détails restaurant ***/
      /**************************/
      echo '<div class="zone_details_proposition_left">';
        // Image + lien
        echo '<a id="lien_details_proposition" href="" class="lien_proposition_top">';
          echo '<img id="image_details_proposition" src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_proposition_top" />';
        echo '</a>';

        // Nom du restaurant
        echo '<div id="nom_details_proposition" class="nom_restaurant_details"></div>';

        echo '<div>';
          // Jours d'ouverture
          echo '<div class="zone_ouverture_mes_choix">';
            $semaine_short  = array("Lu", "Ma", "Me", "Je", "Ve");

            foreach ($semaine_short as $keyDay => $day_short)
            {
              echo '<div id="jour_details_proposition_' . $keyDay . '">' . $day_short . '</div>';
            }
          echo '</div>';

          // Prix
          echo '<div class="zone_price_details">';
            echo '<div id="prix_details_proposition" class="price_details"></div>';
          echo '</div>';

          // Lieu
          echo '<span class="lieu_proposition">';
            echo '<img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />';
            echo '<span id="lieu_details_proposition"></span>';
          echo '</span>';

          // Nombre de participants
          echo '<span id="participants_details_proposition" class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" /></span>';
        echo '</div>';

        // Type de restaurant
        echo '<div id="types_details_proposition" class="zone_types_details"></div>';

        // Appelant
        echo '<div class="zone_caller_details">';
          echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone_details" />';

          // Numéro de téléphone
          echo '<div id="telephone_details_proposition" class="telephone_details"></div>';

          // Avatar
          echo '<div class="zone_avatar_details">';
              echo '<img src="../../includes/icons/common/default.png" alt="avatar" id="caller_details_propositions" class="avatar_caller_details" />';
          echo '</div>';
        echo '</div>';

        // Liens
        echo '<div class="zone_liens_details">';
          echo '<a id="website_details_proposition" href="" target="_blank">';
            echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="icone_lien_details" />';
          echo '</a>';

          echo '<a id="plan_details_proposition" href="" target="_blank">';
            echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Plan" class="icone_lien_details" />';
          echo '</a>';
        echo '</div>';

        // Bouton réservation
        echo '<div id="indicateurs_details_proposition" class="zone_reservation">';
          if ($actions["reserver"] == true)
          {
            echo '<form id="reserver_details_proposition" method="post" action="">';
              echo '<input type="hidden" name="id_restaurant" value="" />';
              echo '<input type="submit" name="reserve" value="J\'ai réservé !" class="bouton_reserver_details"/>';
            echo '</form>';
          }

          // Indicateur réservation
          echo '<div id="reserved_details_proposition" class="reserved_details">Réservé !</div>';

          // Bouton annulation réservation
          if ($actions["annuler_reserver"] == true)
          {
            echo '<form id="annuler_details_proposition" method="post" action="">';
              echo '<input type="hidden" name="id_restaurant" value="" />';
              echo '<input type="submit" name="unreserve" value="Annuler la réservation" class="bouton_reserver_details" style="margin-top: 10px;"/>';
            echo '</form>';
          }
        echo '</div>';
      echo '</div>';

      /****************************/
      /*** Détails utilisateurs ***/
      /****************************/
      echo '<div class="zone_details_proposition_right">';
        // Participants
        echo '<div class="titre_details" style="margin-top: -10px;"><img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />Les participants</div>';

        // Bouton fermeture
        echo '<a id="fermerDetails" class="close_details"><img src="../../includes/icons/common/close_grey.png" alt="close_grey" title="Fermer" class="close_img" /></a>';

        // Participants, transports et horaires
        echo '<div id="top_details_proposition"></div>';

        // Menus proposés
        echo '<div class="titre_details" style="margin-top: 40px;"><img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />Les menus proposés</div>';

        // Menus
        echo '<div class="zone_details_user_bottom"></div>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
