<?php
    /****************/
    /*** Parcours ***/
    /****************/
    echo '<div class="zone_liste_parcours">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="logo_titre_section" /><div class="texte_titre_section">Les parcours</div></div>';

        // Parcours
        if (!empty($listeParcours))
        {
            foreach ($listeParcours as $parcours)
            {
                echo '<div class="zone_parcours">';
                    echo '<a href="details.php?id_parcours=' . $parcours->getId() . '&action=goConsulter" class="lien_parcours">';
                        // Nom
                        echo '<div class="nom_parcours">' . $parcours->getName() . '</div>';

                        // Distance
                        echo '<div class="distance_parcours">' . formatDistanceForDisplay($parcours->getDistance()) . '</div>';

                        // Localisation
                        echo '<div class="lieu_parcours">' . $parcours->getLocation() . '</div>';
                    echo '</a>';

                    // Participation
                    echo '<a id="ajouter_participation_' . $parcours->getId() . '" title="Participer" class="lien_participer_parcours ajouterParticipation">';
                        echo '<img src="../../includes/icons/petitspedestres/participate_grey.png" alt="participate_grey" class="icone_parcours" />';
                    echo '</a>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Aucun parcours ajouté...</div>';
    echo '</div>';
?>