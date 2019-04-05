<?php
  /**************************/
  /*** Historique semaine ***/
  /**************************/
  echo '<div class="titre_section">Le résumé de la semaine</div>';

  echo '<div class="zone_propositions">';
    foreach ($choixSemaine as $jour => $choixJour)
    {
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

          echo '<div class="zone_icones_mon_choix">';
            // Lieu
            echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $choixJour->getLocation() . '</span>';

            // Nombre de participants
            if ($choixJour->getNb_participants() == 1)
              echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $choixJour->getNb_participants() . ' participant</span>';
            else
              echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/user.png" alt="user" class="image_lieu_proposition" />' . $choixJour->getNb_participants() . ' participants</span>';
          echo '</div>';

          echo '<div class="caller">';
            echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

            // Avatar
            if (!empty($choixJour->getAvatar()))
              echo '<img src="../../includes/images/profil/avatars/' . $choixJour->getAvatar() . '" alt="avatar" title="' . $choixJour->getPseudo() . '" class="avatar_caller" />';
            else
              echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $choixJour->getPseudo() . '" class="avatar_caller" />';
          echo '</div>';
        echo '</div>';
      }
      else
      {
        echo '<div class="zone_proposition_top" id="zone_resume_' . $jour . '">';
          // Jour
          echo '<div class="jour_semaine">' . $jour . '</div>';

          // Pas de proposition
          echo '<div class="no_proposal">Pas de proposition pour ce jour</div>';
        echo '</div>';
      }
    }
  echo '</div>';
?>
