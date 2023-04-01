<?php
    /***********************/
    /*** Tableau de bord ***/
    /***********************/
    echo '<div class="zone_tableau_de_bord">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" class="logo_titre_section" /><div class="texte_titre_section">Tableau de bord</div></div>';

        // Distance moyenne
        echo '<div class="zone_donnee_tableau_de_bord">';
            echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance moyenne" class="icone_tableau_de_bord" />';

            echo '<div class="titre_donnee_tableau_de_bord">Distance moyenne</div>';   

            if (!empty($tableauDeBord->getDistanceMoyenne()))
                echo '<div class="donnee_tableau_de_bord">' . formatDistanceForDisplay($tableauDeBord->getDistanceMoyenne()) . '</div>';   
            else
                echo '<div class="donnee_tableau_de_bord">N/A</div>';   
        echo '</div>';

        // Temps moyen
        echo '<div class="zone_donnee_tableau_de_bord">';
            echo '<img src="../../includes/icons/petitspedestres/time_grey.png" alt="time_grey" title="Temps moyen" class="icone_tableau_de_bord" />';

            echo '<div class="titre_donnee_tableau_de_bord">Temps moyen</div>';   

            if (!empty($tableauDeBord->getTempsMoyen()))
                echo '<div class="donnee_tableau_de_bord">' . formatSecondsForDisplay($tableauDeBord->getTempsMoyen()) . '</div>';   
            else
                echo '<div class="donnee_tableau_de_bord">N/A</div>';   
        echo '</div>';

        // Vitesse moyenne
        echo '<div class="zone_donnee_tableau_de_bord">';
            echo '<img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" title="Vitesse moyenne" class="icone_tableau_de_bord" />';

            echo '<div class="titre_donnee_tableau_de_bord">Vitesse moyenne</div>';   

            if (!empty($tableauDeBord->getVitesseMoyenne()))
                echo '<div class="donnee_tableau_de_bord">' . formatSpeedForDisplay($tableauDeBord->getVitesseMoyenne()) . '</div>';   
            else
                echo '<div class="donnee_tableau_de_bord">N/A</div>';   
        echo '</div>';

        // Cardio moyen
        echo '<div class="zone_donnee_tableau_de_bord">';
            echo '<img src="../../includes/icons/petitspedestres/cardio_grey.png" alt="cardio_grey" title="Cardio moyen" class="icone_tableau_de_bord" />';

            echo '<div class="titre_donnee_tableau_de_bord">Cardio moyen</div>';   

            if (!empty($tableauDeBord->getCardioMoyen()))
                echo '<div class="donnee_tableau_de_bord">' . formatCardioForDisplay($tableauDeBord->getCardioMoyen()) . '</div>';   
            else
                echo '<div class="donnee_tableau_de_bord">N/A</div>';   
        echo '</div>';
    echo '</div>';
?>