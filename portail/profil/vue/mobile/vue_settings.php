<?php
  /*********************/
  /* Paramètres profil */
  /*********************/
  // Titre
  echo '<div class="titre_section">';
    echo '<img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />';
    echo '<div class="texte_titre_section">Mes informations</div>';
  echo '</div>';

  // Avatar actuel & suppression
  echo '<div class="zone_avatar_parametres">';
    echo '<div class="zone_parametres_avatar">';
      $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

      echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_parametres" />';
    echo '</div>';

    echo '<form method="post" action="profil.php?action=doSupprimerAvatar" enctype="multipart/form-data">';
      echo '<input type="submit" name="delete_avatar" value="Supprimer" class="bouton_validation_image" />';
    echo '</form>';
  echo '</div>';

  // Modification avatar
  echo '<form method="post" action="profil.php?action=doModifierAvatar" enctype="multipart/form-data" class="form_update_avatar">';
    echo '<div class="zone_saisie_image">';
      echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

      echo '<div class="zone_parcourir_image">';
        echo '<div class="symbole_saisie_image">+</div>';
        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_image loadSaisieAvatar" required />';
      echo '</div>';

      echo '<div class="mask_image">';
        echo '<img id="image_avatar_saisie" alt="" class="image" />';
      echo '</div>';
    echo '</div>';

    // Bouton d'action
    echo '<input type="submit" name="post_avatar" value="Modifier l\'avatar" id="bouton_saisie_avatar" class="bouton_validation_image bouton_validation_image_margin" />';
  echo '</form>';

  // Mise à jour informations
  echo '<form method="post" action="profil.php?action=doUpdateInfosMobile" class="form_update_infos">';
    // Pseudo
    echo '<div class="zone_saisie_information">';
      echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_information" />';
      echo '<input type="text" name="pseudo" placeholder="Pseudo" value="' . $profil->getPseudo() . '" maxlength="255" class="saisie_information" />';
    echo '</div>';

    // Email
    echo '<div class="zone_saisie_information">';
      echo '<img src="../../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_information" />';
      echo '<input type="email" name="email" placeholder="Adresse mail" value="' . $profil->getEmail() . '" maxlength="255" class="saisie_information" />';
    echo '</div>';

    // Anniversaire
    echo '<div class="zone_saisie_information">';
      echo '<img src="../../includes/icons/profil/anniversary_grey.png" alt="anniversary_grey" class="logo_information" />';
      echo '<input type="date" name="anniversaire" value="' . formatDateForDisplayMobile($profil->getAnniversary()) . '" placeholder="Date" maxlength="10" autocomplete="off" class="saisie_information" />';
    echo '</div>';

    // Bouton d'action
    echo '<input type="submit" name="saisie_pseudo" value="Mettre à jour les informations" class="bouton_validation_form" />';
  echo '</form>';
?>
