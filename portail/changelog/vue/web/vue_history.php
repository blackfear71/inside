<?php
    // Histoire du site
    echo '<div class="zone_changelog_right">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Une brève histoire d\'Internet...</div></div>';

        // Affichage des textes
        foreach ($histoires as $keyHistoire => $histoire)
        {
            if ($keyHistoire == 0)
            {
                // Introduction
                echo '<div class="introduction_history">' . $histoire[0] . '</div>';
                echo '<div class="signature_history">' . $histoire[1] . '</div>';
            }
            else
            {
                // Contenu
                echo '<div class="event_history">';
                    foreach ($histoire as $keyContenu => $contenu)
                    {
                        if ($keyContenu == 0)
                            echo '<div class="date_history">' . $contenu . '</div><div class="trait_history"></div>';
                        else
                            echo '<div class="details_history">' . $contenu . '</div>';
                    }
                echo '</div>';
            }
        }
    echo '</div>';
?>