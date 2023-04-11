<?php
    echo '<div class="zone_profil_admin">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Mes informations</div></div>';

        // Avatar actuel & suppression
        echo '<div class="zone_profil_avatar_parametres">';
            $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_profil" />';

            echo '<div class="texte_parametres">Avatar actuel</div>';

            echo '<form method="post" action="profil.php?action=doSupprimerAvatar" enctype="multipart/form-data">';
                echo '<input type="submit" name="delete_avatar" value="Supprimer" class="bouton_validation" />';
            echo '</form>';
        echo '</div>';

        // Modification avatar
        echo '<form method="post" action="profil.php?action=doModifierAvatar" enctype="multipart/form-data" class="form_update_avatar">';
            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

            echo '<span class="zone_parcourir_avatar">';
                echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="avatar" class="bouton_parcourir_avatar loadAvatar" required />';
            echo '</span>';

            echo '<div class="mask_avatar">';
                echo '<img id="avatar" alt="" class="avatar_update_profil" />';
            echo '</div>';

            // Bouton
            echo '<div class="zone_bouton_saisie">';
                echo '<input type="submit" name="post_avatar" value="Modifier l\'avatar" id="bouton_saisie_avatar" class="bouton_validation" />';
            echo '</div>';
        echo '</form>';

        // Mise à jour informations
        echo '<form method="post" action="profil.php?action=doModifierInfos" class="form_update_infos">';
            // Pseudo
            echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_parametres" />';
            echo '<input type="text" name="pseudo" placeholder="Pseudo" value="' . $profil->getPseudo() . '" maxlength="255" class="monoligne_saisie" />';

            // Email
            echo '<img src="../../includes/icons/profil/mailing_red.png" alt="mailing_red" class="logo_parametres" />';
            echo '<input type="email" name="email" placeholder="Adresse mail" value="' . $profil->getEmail() . '" maxlength="255" class="monoligne_saisie" />';

            echo '<input type="submit" name="saisie_pseudo" value="Mettre à jour" class="bouton_validation" />';
        echo '</form>';
    echo '</div>';
?>