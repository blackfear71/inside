<?php
    echo '<div id="zone_details_votes" class="fond_details">';
        echo '<div class="div_details">';
            echo '<div class="zone_contenu_details">';
                // Titre
                echo '<div class="titre_details">';
                    echo '<img src="../../includes/icons/collector/collector_grey.png" alt="collector_grey" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section"></div>';
                echo '</div>';

                // Votes utilisateurs
                echo '<div class="zone_details_votes_users"></div>';
            echo '</div>';

            // Bouton fermeture
            echo '<div class="zone_boutons_saisie">';
                echo '<a id="fermerDetailsVotes" class="bouton_saisie_fermer">Fermer</a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>