<?php
    /************************/
    /*** Détails parcours ***/
    /************************/
    echo '<div class="zone_details_parcours">';
        // Titre
        echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">' . $detailsParcours->getName() . '</div>';
        echo '</div>';

        // Image
        if (!empty($detailsParcours->getPicture()))
        {
            echo '<div class="zone_image_details_parcours">';
                echo '<img src="../../includes/images/petitspedestres/pictures/' . $detailsParcours->getPicture() . '" alt="' . $detailsParcours->getPicture() . '" title="' . $detailsParcours->getName() . '" class="image_details_parcours" />';
            echo '</div>';
        }

        // Parcours
        echo '<div class="zone_details_parcours_left">';
            switch ($detailsParcours->getType())
            {
                case 'document':
                    echo '<embed src="../../includes/datas/petitspedestres/' . $detailsParcours->getDocument() . '" type="application/pdf" class="document_details_document" />';
                    break;

                case 'picture':
                    echo '<img src="../../includes/images/petitspedestres/documents/' . $detailsParcours->getDocument() . '" alt="' . $detailsParcours->getDocument() . '" title="' . $detailsParcours->getName() . '" class="image_details_document" />';
                    break;

                default:
                    break;
            }
        echo '</div>';

        // Informations
        echo '<div class="zone_details_parcours_right">';
            // Distance
            echo '<div class="zone_donnee_details_parcours">';
                echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_donnee_details_parcours" />';
                echo '<div class="donnee_details_parcours">' . formatDistanceForDisplay($detailsParcours->getDistance()) . '</div>';   
            echo '</div>';
            
            // Lieu
            echo '<div class="zone_donnee_details_parcours">';
                echo '<img src="../../includes/icons/petitspedestres/location_grey.png" alt="location_grey" title="Lieu" class="icone_donnee_details_parcours" />';
                echo '<div class="donnee_details_parcours">' . $detailsParcours->getLocation() . '</div>';   
            echo '</div>';
            
            // Nombre de courses réalisées
            echo '<div class="zone_donnee_details_parcours">';
                echo '<img src="../../includes/icons/petitspedestres/runs_grey.png" alt="runs_grey" title="Nombre de courses réalisées" class="icone_donnee_details_parcours" />';

                if ($detailsParcours->getRuns() == 1)
                    echo '<div class="donnee_details_parcours">' . $detailsParcours->getRuns() . ' course réalisée</div>';   
                else
                    echo '<div class="donnee_details_parcours">' . $detailsParcours->getRuns() . ' courses réalisées</div>';   
            echo '</div>';
            
            // Téléchargement
            switch ($detailsParcours->getType())
            {
                case 'document':
                    $path = '../../includes/datas/petitspedestres/' . $detailsParcours->getDocument();
                    break;

                case 'picture':
                    $path = '../../includes/images/petitspedestres/documents/' . $detailsParcours->getDocument();
                    break;

                default:
                    break;
            }

            echo '<a href="' . $path . '" class="zone_donnee_details_parcours" download>';
                echo '<img src="../../includes/icons/petitspedestres/download_grey.png" alt="download_grey" title="Télécharger la fiche" class="icone_donnee_details_parcours" />';
                echo '<div class="donnee_details_parcours">Télécharger la fiche du parcours</div>';   
            echo '</a>';

            // Participation
            echo '<div class="zone_donnee_details_parcours">';
                echo '<img src="../../includes/icons/petitspedestres/participate_grey.png" alt="participate_grey" title="Participer" class="icone_donnee_details_parcours" />';
                echo '<div class="donnee_details_parcours">Participer à cette course</div>';   
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>