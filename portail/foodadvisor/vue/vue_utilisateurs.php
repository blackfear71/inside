<?php
  /********************/
  /*** Utilisateurs ***/
  /********************/
  echo '<div class="zone_propositions_left">';
    echo '<div class="titre_section"><img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />Utilisateurs</div>';

    // Bande à part et votants restants
    if (!empty($solos) OR !empty($sansPropositions))
    {
      // Bande à part
      if (!empty($solos))
      {
        echo '<div class="zone_proposition_solo_no_votes">';
          echo '<div class="titre_solo">Bande à part</div>';

          foreach ($solos as $solo)
          {
            echo '<div class="zone_solo">';
              // Avatar
              if (!empty($solo->getAvatar()))
                echo '<img src="../../includes/images/profil/avatars/' . $solo->getAvatar() . '" alt="avatar" title="' . $solo->getPseudo() . '" class="avatar_solo" />';
              else
                echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $solo->getPseudo() . '" class="avatar_solo" />';

              // Pseudo
              echo '<div class="pseudo_solo">' . $solo->getPseudo() . '</div>';
            echo '</div>';
          }
        echo '</div>';
      }

      // Utilisateurs sans votes
      if (!empty($sansPropositions))
      {
        echo '<div class="zone_proposition_solo_no_votes">';
          if (date("N") <= 5 AND date("H") < 13)
            echo '<div class="titre_solo">Ils n\'ont pas encore fait de choix</div>';
          else
            echo '<div class="titre_solo">Ils n\'ont pas fait de choix aujourd\'hui</div>';

          foreach ($sansPropositions as $userNoChoice)
          {
            echo '<div class="zone_no_vote">';
              // Avatar
              if (!empty($userNoChoice->getAvatar()))
                echo '<img src="../../includes/images/profil/avatars/' . $userNoChoice->getAvatar() . '" alt="avatar" title="' . $userNoChoice->getPseudo() . '" class="avatar_no_vote" />';
              else
                echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $userNoChoice->getPseudo() . '" class="avatar_no_vote" />';

              // Pseudo
              echo '<div class="pseudo_no_vote">' . $userNoChoice->getPseudo() . '</div>';
            echo '</div>';
          }
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Rien à signaler...</div>';
  echo '</div>';
?>
