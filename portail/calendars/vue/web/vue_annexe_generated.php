<?php
    // Génération de l'annexe au format HTML
    $nombreEtiquettes = 19;

    echo '<div class="zone_annexe_generator_hidden">';
        echo '<div class="zone_annexe_generator">';
            // Zone étiquettes spéciales
            echo '<div class="zone_etiquettes_speciales">';
                // Scrum Master
                echo '<div class="zone_etiquette_scrum">';
                    echo '<div class="etiquette_scrum">';
                        // Couronne
                        echo '<img src="../../includes/icons/calendars/crown.png" alt="crown" class="couronne_scrum" />';

                        // Avatar
                        echo '<img src="../../includes/images/calendars/temp/trim_' . $annexeParameters->getPicture() . '" alt="trim_' . $annexeParameters->getPicture() . '" class="avatar_scrum" />';

                        // Gâteau
                        echo '<img src="../../includes/icons/calendars/cake_scrum.png" alt="cake_scrum" class="cake_scrum" />';
                    echo '</div>';
                echo '</div>';

                // Anniversaire
                echo '<div class="zone_etiquette_normale zone_etiquette_normale_green">';
                    // Texte
                    echo '<div class="texte_etiquette texte_etiquette_half texte_etiquette_green">BON ANNIVERSAIRE !!!</div>';
                    echo '<div class="triangle_3 triangle_3_green"></div>';

                    // Avatar
                    echo '<img src="../../includes/images/calendars/temp/trim_' . $annexeParameters->getPicture() . '" alt="trim_' . $annexeParameters->getPicture() . '" class="avatar_etiquette" />';
                echo '</div>';
            echo '</div>';

            // Etiquettes ABSENCE
            for ($i = 1; $i <= $nombreEtiquettes; $i++)
            {
                echo '<div class="zone_etiquette_normale zone_etiquette_normale_red">';
                    // Texte
                    echo '<div class="texte_etiquette texte_etiquette_full texte_etiquette_red">ABSENCE</div>';
                    echo '<div class="triangle_3 triangle_3_red"></div>';

                    // Avatar
                    echo '<img src="../../includes/images/calendars/temp/trim_' . $annexeParameters->getPicture() . '" alt="trim_' . $annexeParameters->getPicture() . '" class="avatar_etiquette" />';
                echo '</div>';
            }

            // Etiquettes CONGÉS
            for ($i = 1; $i <= $nombreEtiquettes; $i++)
            {
                echo '<div class="zone_etiquette_normale zone_etiquette_normale_blue">';
                    // Texte
                    echo '<div class="texte_etiquette texte_etiquette_full texte_etiquette_blue">CONGÉS</div>';
                    echo '<div class="triangle_3 triangle_3_blue"></div>';

                    // Avatar
                    echo '<img src="../../includes/images/calendars/temp/trim_' . $annexeParameters->getPicture() . '" alt="trim_' . $annexeParameters->getPicture() . '" class="avatar_etiquette" />';
                echo '</div>';
            }

            // Etiquettes ABSENCE PRÉVISIONNELLE
            for ($i = 1; $i <= $nombreEtiquettes; $i++)
            {
                echo '<div class="zone_etiquette_normale zone_etiquette_normale_orange">';
                    // Texte
                    echo '<div class="texte_etiquette texte_etiquette_half texte_etiquette_orange">ABSENCE PRÉVISIONNELLE</div>';
                    echo '<div class="triangle_3 triangle_3_orange"></div>';

                    // Avatar
                    echo '<img src="../../includes/images/calendars/temp/trim_' . $annexeParameters->getPicture() . '" alt="trim_' . $annexeParameters->getPicture() . '" class="avatar_etiquette" />';
                echo '</div>';
            }
        echo '</div>';
    echo '</div>';
?>