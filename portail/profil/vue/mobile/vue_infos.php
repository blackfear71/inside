<?php
  /****************/
  /* Informations */
  /****************/
  echo '<div class="zone_infos_profil">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">' . $profil->getPseudo() . '</div>';
    echo '</div>';

    // Avatar
    $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_profil" />';

    // Informations
    echo '<div class="zone_texte_infos">';
      // Niveau
      echo '<div class="zone_info">';
        echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_profil" />';
        echo '<div class="texte_profil">Insider de <strong>niveau ' . $progression->getNiveau() . '</strong></div>';
      echo '</div>';

      // Expérience
      echo '<div class="zone_info">';
        echo '<img src="../../includes/icons/profil/experience_grey.png" alt="experience_grey" class="logo_profil" />';
        echo '<div class="fond_experience_profil"><div class="experience_profil" style="width: ' . $progression->getPourcentage() . '%;"></div></div>';
      echo '</div>';

      // Adresse mail
      if (!empty($profil->getEmail()))
      {
        echo '<div class="zone_info">';
          echo '<img src="../../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_profil" />';
          echo '<div class="texte_profil">' . $profil->getEmail() . '</div>';
        echo '</div>';
      }

      // Anniversaire
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
