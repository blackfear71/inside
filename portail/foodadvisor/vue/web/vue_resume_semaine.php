<?php
  /**************************/
  /*** Historique semaine ***/
  /**************************/
  echo '<div class="titre_section"><img src="../../includes/icons/foodadvisor/week_grey.png" alt="week_grey" class="logo_titre_section" /><div class="texte_titre_section">Le résumé de la semaine</div></div>';

  echo '<div class="zone_propositions">';
    $numeroJour = 0;

    foreach ($choixSemaine as $jour => $choixJour)
    {
      $numeroJour++;

      if (!empty($choixJour))
      {
        echo '<div class="zone_proposition_resume" id="zone_resume_' . $jour . '">';
          // Jour
          echo '<div class="jour_semaine">' . $jour . '</div>';

          // Suppression si disponible
          if (empty($choixJour->getCaller()) AND (($numeroJour < date('N')) OR ($numeroJour == date('N') AND date('H') >= 13)))
          {
            echo '<form id="delete_resume_' . $numeroJour . '" method="post" action="foodadvisor.php?action=doSupprimerResume">';
              echo '<input type="hidden" name="id_resume" value="' . $choixJour->getId_restaurant() . '" />';
              echo '<input type="hidden" name="date_resume" value="' . $choixJour->getDate() . '" />';
              echo '<input type="submit" name="delete_resume" value="" title="Supprimer le choix" class="icon_delete_resume eventConfirm" />';
              echo '<input type="hidden" value="Supprimer ce choix ?" class="eventMessage" />';
            echo '</form>';
          }

          // Image + lien
          echo '<a href="restaurants.php?action=goConsulter&anchor=' . $choixJour->getId_restaurant() . '" class="lien_mon_choix">';
            if (!empty($choixJour->getPicture()))
              echo '<img src="../../includes/images/foodadvisor/' . $choixJour->getPicture() . '" alt="' . $choixJour->getPicture() . '" title="' . $choixJour->getName() . '" class="image_mon_choix" />';
            else
              echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $choixJour->getName() . '" class="image_mon_choix" />';
          echo '</a>';

          // Nom du restaurant
          echo '<div class="nom_mon_choix">' . $choixJour->getName() . '</div>';

          // Lieu et participants
          echo '<div class="zone_icones_mon_choix">';
            // Lieu
            echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $choixJour->getLocation() . '</span>';

            // Nombre de participants
            if ($choixJour->getNb_participants() >= 1)
            {
              if ($choixJour->getNb_participants() == 1)
                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $choixJour->getNb_participants() . ' participant</span>';
              else
                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $choixJour->getNb_participants() . ' participants</span>';
            }
          echo '</div>';

          // Appelant si renseigné
          if (!empty($choixJour->getCaller()))
          {
            echo '<div class="caller_resume">';
              echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

              // Avatar
              $avatarFormatted = formatAvatar($choixJour->getAvatar(), $choixJour->getPseudo(), 2, 'avatar');

              echo '<div class="zone_avatar_caller">';
                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_caller" />';
              echo '</div>';
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
          echo '<div id="no_proposal_' . $numeroJour . '" class="no_proposal">Pas de proposition pour ce jour</div>';

          // Bouton ajout choix (si pas de choix fait dans la matinée)
          if (($numeroJour < date('N')) OR ($numeroJour == date('N') AND date('H') >= 13))
          {
            echo '<a id="choix_resume_' . $numeroJour . '" class="bouton_resume afficherResume">';
              echo '<span class="fond_plus">+</span>';
              echo 'Ajouter un choix';
            echo '</a>';
          }
        echo '</div>';
      }
    }
  echo '</div>';
?>
