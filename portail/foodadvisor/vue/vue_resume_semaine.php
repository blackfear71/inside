<?php
  /**************************/
  /*** Historique semaine ***/
  /**************************/
  echo '<div class="titre_section"><img src="../../includes/icons/foodadvisor/week_grey.png" alt="week_grey" class="logo_titre_section" />Le résumé de la semaine</div>';

  echo '<div class="zone_propositions">';
    $numero_jour = 0;

    foreach ($choixSemaine as $jour => $choixJour)
    {
      $numero_jour++;

      if (!empty($choixJour))
      {
        echo '<div class="zone_proposition_resume" id="zone_resume_' . $jour . '">';
          // Jour
          echo '<div class="jour_semaine">' . $jour . '</div>';

          // Image + lien
          echo '<a href="restaurants.php?action=goConsulter&anchor=' . $choixJour->getId_restaurant() . '" class="lien_mon_choix">';
            if (!empty($choixJour->getPicture()))
              echo '<img src="../../includes/images/foodadvisor/' . $choixJour->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
            else
              echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
          echo '</a>';

          // Nom du restaurant
          echo '<div class="nom_mon_choix">' . $choixJour->getName() . '</div>';

          echo '<div>';
            // Lieu
            echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $choixJour->getLocation() . '</span>';

            // Nombre de participants
            if ($choixJour->getNb_participants() == 1)
              echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $choixJour->getNb_participants() . ' participant</span>';
            else
              echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $choixJour->getNb_participants() . ' participants</span>';
          echo '</div>';

          if (!empty($choixJour->getCaller()))
          {
            echo '<div class="caller_resume">';
              echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

              // Avatar
              if (!empty($choixJour->getAvatar()))
                echo '<img src="../../includes/images/profil/avatars/' . $choixJour->getAvatar() . '" alt="avatar" title="' . $choixJour->getPseudo() . '" class="avatar_caller" />';
              else
                echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $choixJour->getPseudo() . '" class="avatar_caller" />';
            echo '</div>';
          }
        echo '</div>';
      }
      else
      {
        echo '<div class="zone_proposition_top" id="zone_resume_' . $jour . '">';
          // Jour
          echo '<div class="jour_semaine">' . $jour . '</div>';

          // Pas de proposition
          echo '<div id="no_proposal_' . $numero_jour . '" class="no_proposal">Pas de proposition pour ce jour</div>';

          // Bouton ajout choix (si pas de choix fait dans la matinée)
          if ($numero_jour <= date("N") AND $actions["choix_resume"] == true)
          {
            echo '<a id="choix_resume_' . $numero_jour . '" class="bouton_resume afficherResume">';
              echo '<span class="fond_plus">+</span>';
              echo 'Ajouter un choix';
            echo '</a>';
          }

        echo '</div>';
      }
    }
  echo '</div>';
?>
