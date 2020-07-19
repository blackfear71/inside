<?php
  echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">' . $profil->getPseudo() . '</div></div>';

  echo '<div class="margin_top_moins_10">';
    // Avatar
    echo '<div class="zone_profil_avatar">';
      $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_profil" />';
    echo '</div>';

    // Expérience
    echo '<div class="zone_profil_experience">';
      echo '<div class="circular_bar" id="progress_circle" data-perc="' . $progression->getPourcentage() . '" data-text="' . $profil->getExperience() . ' XP"></div>';
    echo '</div>';

    // Infos
    echo '<div class="zone_profil_infos">';
      echo '<div class="zone_info">';
        echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_profil" />';
        echo '<div class="texte_profil">Insider de <strong>niveau ' . $progression->getNiveau() . '</strong></div>';
      echo '</div>';

      if (!empty($profil->getEmail()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_profil" />';
          echo '<div class="texte_profil">' . $profil->getEmail() . '</div>';
        echo '</div>';
      }

      if (!empty($profil->getAnniversary()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../../includes/icons/profil/anniversary_grey.png" alt="anniversary_grey" class="logo_profil" />';
          echo '<div class="texte_profil">Anniversaire le ' . formatDateForDisplay($profil->getAnniversary()) . '</div>';
        echo '</div>';
      }
    echo '</div>';
  echo '</div>';
?>
