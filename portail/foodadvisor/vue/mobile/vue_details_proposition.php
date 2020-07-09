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
        echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="" class="image_details" />';

        // Indicateur réservation
        echo '<div id="reserved_details" class="reserved_details">Réservé !</div>';

        // Choix rapide
        if ($actions['choix_rapide'] == true)
        {
          echo '<form id="choix_rapide_details" method="post" action="">';
            echo '<input type="hidden" name="id_restaurant" value="" />';
            echo '<input type="submit" name="fast_restaurant" value="Voter pour ce restaurant" class="bouton_action_details" />';
          echo '</form>';
        }

        // Actions
        echo '<div id="indicateurs_details">';
          if ($actions['reserver'] == true)
          {
            // Bouton réservation
            echo '<form id="reserver_details" method="post" action="">';
              echo '<input type="hidden" name="id_restaurant" value="" />';
              echo '<input type="submit" name="reserve" value="J\'ai réservé !" class="bouton_action_details" />';
            echo '</form>';

            // Bouton complet
            echo '<form id="choice_complete_details" method="post" action="">';
              echo '<input type="hidden" name="id_restaurant" value="" />';
              echo '<input type="submit" name="complete" value="Complet..." class="bouton_action_details eventConfirm" />';
              echo '<input type="hidden" value="Signaler ce choix comme complet ? Les votes des autres utilisateurs seront supprimés et la détermination relancée." class="eventMessage" />';
            echo '</form>';
          }

          // Bouton annulation réservation
          if ($actions['annuler_reserver'] == true)
          {
            echo '<form id="annuler_details" method="post" action="">';
              echo '<input type="hidden" name="id_restaurant" value="" />';
              echo '<input type="submit" name="unreserve" value="Annuler la réservation" class="bouton_action_details" />';
            echo '</form>';
          }
        echo '</div>';

        // Informations
        echo '<div class="zone_details_informations">';
          // Titre
          echo '<div id="titre_proposition_infos" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Informations</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Informations du restautant
          echo '<div id="afficher_proposition_infos" class="zone_details_restaurant">';
            // Lieu
            echo '<div class="zone_lieu_details">';
              echo '<img src="../../includes/icons/foodadvisor/location.png" alt="location" class="icone_details" />';
              echo '<div class="lieu_details"></div>';
            echo '</div>';

            // Nombre de participants
            echo '<div class="zone_nombre_participants_details">';
              echo '<img src="../../includes/icons/foodadvisor/users.png" alt="users" class="icone_details" />';
              echo '<div class="nombre_participants_details"></div>';
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

            // Appelant & numéro de téléphone
            echo '<div class="zone_appelant_details">';
              // Logo
              echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Numéro de téléphone" class="icone_telephone_details" />';

              // Numéro de téléphone
              echo '<div class="telephone_details"></div>';

              // Avatar
              echo '<img src="../../includes/icons/common/default.png" alt="avatar" class="avatar_appelant_details" />';
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

        // Participants
        echo '<div class="zone_details_users">';
          // Titre
          echo '<div id="titre_proposition_users" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les participants</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Liste des participants
          echo '<div id="afficher_proposition_users" class="zone_details_participants"></div>';
        echo '</div>';

        // Description du restaurant
        echo '<div class="zone_details_description">';
          // Titre
          echo '<div id="titre_proposition_description" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/description_grey.png" alt="description_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">À propos du restaurant</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Description
          echo '<div id="afficher_proposition_description" class="zone_details_texte"></div>';
        echo '</div>';
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerDetailsProposition" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
