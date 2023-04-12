<?php
    /****************/
    /*** Parcours ***/
    /****************/
    echo '<div class="zone_liste_parcours">';
        // Titre
        echo '<div id="titre_liste_parcours" class="titre_section">';
            echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Les parcours</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
        echo '</div>';

        // Parcours
        echo '<div id="afficher_liste_parcours">';
            if (!empty($listeParcours))
            {
                foreach ($listeParcours as $parcours)
                {
                    echo '<div class="zone_parcours">';
                        echo '<a href="details.php?id_parcours=' . $parcours->getId() . '&action=goConsulter" class="lien_parcours">';
                            // Nom
                            echo '<div class="nom_parcours">' . formatString($parcours->getName(), 30) . '</div>';

                            // Distance
                            echo '<div class="distance_parcours">' . formatDistanceForDisplay($parcours->getDistance()) . '</div>';

                            // Localisation
                            echo '<div class="lieu_parcours">' . formatString($parcours->getLocation(), 15) . '</div>';

                            // Nombre de participations
                            echo '<div class="zone_nombre_participations_parcours">';
                                echo '<img src="../../includes/icons/petitspedestres/users_grey.png" alt="users_grey" title="Nombre de participations" class="icone_nombre_participations" />';
                                echo '<span class="nombre_participations">' . $parcours->getRuns() . '</span>';
                            echo '</div>';
                        echo '</a>';

                        // Participation
                        echo '<a id="ajouter_participation_' . $parcours->getId() . '" title="Participer" class="lien_participer_parcours afficherSaisieParticipation">';
                            echo '<img src="../../includes/icons/petitspedestres/participate_grey.png" alt="participate_grey" class="icone_parcours" />';
                        echo '</a>';
                    echo '</div>';
                }
            }
            else
                echo '<div class="empty">Aucun parcours ajouté...</div>';
        echo '</div>';
    echo '</div>';
?>