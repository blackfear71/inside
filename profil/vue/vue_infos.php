<?php
  echo '<div class="titre_section"><img src="../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />' . $profil->getPseudo() . '</div>';

  echo '<div class="margin_top">';
    // Avatar
    echo '<div class="zone_profil_avatar">';
      if (!empty($profil->getAvatar()))
        echo '<img src="../includes/images/profil/avatars/' . $profil->getAvatar() . '" alt="avatar" title="' . $profil->getPseudo() . '" class="avatar_profil" />';
      else
        echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $profil->getPseudo() . '" class="avatar_profil" />';
    echo '</div>';

    // Expérience
    echo '<div class="zone_profil_experience">';
      echo '<div class="circular_bar" id="progress_circle" data-perc="' . $progression['percent'] . '" data-text="' . $profil->getExperience() . ' XP"></div>';
    echo '</div>';

    // Infos
    echo '<div class="zone_profil_infos">';
      echo '<div class="zone_info">';
        echo '<img src="../includes/icons/common/inside_red.png" alt="inside_red" class="logo_profil" />';
        echo '<div class="texte_profil">Insider de <strong>niveau ' . $progression['niveau'] . '</strong></div>';
      echo '</div>';

      if (!empty($profil->getEmail()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_profil" />';
          echo '<div class="texte_profil">' . $profil->getEmail() . '</div>';
        echo '</div>';
      }

      if (!empty($profil->getAnniversary()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../includes/icons/profil/date_grey.png" alt="date_grey" class="logo_profil" />';
          echo '<div class="texte_profil">Anniversaire le ' . formatDateForDisplay($profil->getAnniversary()) . '</div>';
        echo '</div>';
      }
    echo '</div>';
  echo '</div>';
?>
