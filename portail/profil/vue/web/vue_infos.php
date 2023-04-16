<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">' . $profil->getPseudo() . '</div></div>';

    // Avatar
    echo '<div class="zone_profil_avatar">';
        $avatarFormatted = formatAvatar($profil->getAvatar(), $profil->getPseudo(), 2, 'avatar');

        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_profil_infos" />';
    echo '</div>';

    // Expérience
    echo '<div class="zone_profil_experience">';
        echo '<canvas class="experience_profil" id="canvas_profil_100" width="130" height="130">Ce navigateur ne prend pas en charge &lt;canvas&gt;</canvas>';
        echo '<canvas class="experience_profil experience_profil_2" id="canvas_profil_' . $progression->getPourcentage() . '" width="130" height="130">Ce navigateur ne prend pas en charge &lt;canvas&gt;</canvas>';
        echo '<input type="hidden" id="valeur_experience_profil" value="' . $profil->getExperience() . '" />';
    echo '</div>';

    // Infos
    echo '<div class="zone_profil_infos">';
        // Niveau
        echo '<div class="zone_info">';
            echo '<img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_profil" />';
            echo '<div class="texte_profil">Insider de <strong>niveau ' . $progression->getNiveau() . '</strong></div>';
        echo '</div>';

        // Equipe
        echo '<div class="zone_info">';
            echo '<img src="../../includes/icons/profil/team_grey.png" alt="team_grey" class="logo_profil" />';
            echo '<div class="texte_profil">' . $equipe->getTeam() . '</div>';
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
?>