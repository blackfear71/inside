<?php
    /**********************************/
    /*** Zone de saisie de parcours ***/
    /**********************************/
    echo '<div id="zone_saisie_parcours" style="display: none;" class="fond_saisie_parcours">';
        echo '<div class="zone_saisie_parcours">';
            // Titre
            echo '<div class="titre_saisie_parcours">Ajouter un parcours</div>';

            // Bouton fermeture
            echo '<a id="annulerParcours" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

            // Saisie parcours
            echo '<form method="post" action="petitspedestres.php?action=doAjouterParcours" enctype="multipart/form-data" class="form_saisie_parcours">';
                // Id parcours (modification)
                echo '<input type="hidden" name="id_parcours" value="" />';

                // Photo
                echo '<div class="zone_saisie_parcours_left">';
                    // Saisie image
                    echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                    echo '<div class="zone_parcourir_image">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image_parcours" class="bouton_parcourir_image loadSaisieParcours" />';
                    echo '</div>';

                    echo '<div class="mask_image">';
                        echo '<img id="image_parcours" alt="" class="image" />';
                    echo '</div>';
                echo '</div>';

                // Informations parcours
                echo '<div class="zone_saisie_parcours_right">';
                    // Nom du parcours
                    echo '<div class="zone_saisie_ligne">';
                        echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" title="Nom du parcours" class="icone_saisie_parcours" />';
                        echo '<input type="text" name="nom_parcours" value="' . $_SESSION['save']['nom_parcours_saisie'] . '" placeholder="Nom du parcours" class="saisie_ligne" required />';
                    echo '</div>';

                    // Distance
                    echo '<div class="zone_saisie_ligne">';
                        echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_saisie_parcours" />';
                        echo '<input type="text" name="distance_parcours" value="' . $_SESSION['save']['distance_parcours_saisie'] . '" placeholder="Distance (km)" class="saisie_ligne" maxlength="6" required />';
                    echo '</div>';

                    // Lieu
                    echo '<div class="zone_saisie_ligne">';
                        echo '<img src="../../includes/icons/petitspedestres/location_grey.png" alt="location_grey" title="Lieu" class="icone_saisie_parcours" />';
                        echo '<input type="text" name="lieu_parcours" value="' . $_SESSION['save']['lieu_parcours_saisie'] . '" placeholder="Lieu" class="saisie_ligne" required />';
                    echo '</div>';

                    // Document
                    echo '<div class="zone_saisie_ligne">';
                        echo '<img src="../../includes/icons/petitspedestres/document_grey.png" alt="document_grey" title="Document" class="icone_saisie_parcours" />';

                        echo '<div class="zone_parcourir_document">';
                            echo 'Image ou PDF du parcours';
                            echo '<input type="file" accept=".jpg, .jpeg, .png, .pdf" name="document_parcours" class="bouton_parcourir_document loadDocumentParcours" required />';
                        echo '</div>';

                        echo '<div id="document_parcours" class="texte_parcourir_document">Aucun fichier sélectionné</div>';
                    echo '</div>';
                echo '</div>';

                // Boutons d'action
                echo '<div class="zone_saisie_bottom">';
                    echo '<div class="zone_bouton_saisie_parcours">';
                        // Ajouter
                        echo '<input type="submit" name="insert_parcours" value="Ajouter le parcours" id="bouton_saisie_parcours" class="saisie_bouton" />';
                    echo '</div>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>