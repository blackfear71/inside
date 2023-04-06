<?php
    /**********************************/
    /*** Zone de saisie de parcours ***/
    /**********************************/
    echo '<div id="zone_saisie_parcours" class="fond_saisie">';
        echo '<form method="post" action="petitspedestres.php?action=doAjouterParcours" enctype="multipart/form-data" class="form_saisie">';
            // Id parcours (modification)
            echo '<input type="hidden" name="id_parcours" value="" />';

            // Titre
            echo '<div class="zone_titre_saisie">Ajouter un parcours</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Titre (parcours)
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">Le parcours</div>';
                    echo '</div>';

                    // Saisie image
                    echo '<div class="zone_image_saisie">';
                        echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                        echo '<div class="zone_parcourir_image">';
                            echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image_parcours" class="bouton_parcourir_image loadSaisieParcours" />';
                        echo '</div>';

                        echo '<div class="mask_image">';
                            echo '<img id="image_parcours" alt="" class="image" />';
                        echo '</div>';
                    echo '</div>';

                    // Saisies (parcours)
                    echo '<div class="zone_saisie_lignes">';
                        // Nom du parcours
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" title="Nom du parcours" class="icone_saisie" />';
                            echo '<input type="text" name="nom_parcours" value="' . $_SESSION['save']['nom_parcours_saisie'] . '" placeholder="Nom du parcours" class="saisie_ligne" required />';
                        echo '</div>';

                        // Distance
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_saisie" />';
                            echo '<input type="text" name="distance_parcours" value="' . $_SESSION['save']['distance_parcours_saisie'] . '" placeholder="Distance (km)" class="saisie_ligne" maxlength="6" required />';
                        echo '</div>';

                        // Lieu
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/location_grey.png" alt="location_grey" title="Lieu" class="icone_saisie" />';
                            echo '<input type="text" name="lieu_parcours" value="' . $_SESSION['save']['lieu_parcours_saisie'] . '" placeholder="Lieu" class="saisie_ligne" required />';
                        echo '</div>';

                        // Document
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/document_grey.png" alt="document_grey" title="Document" class="icone_saisie" />';

                            echo '<div class="zone_parcourir_document">';
                                echo 'Image ou PDF du parcours';
                                echo '<input type="file" accept=".jpg, .jpeg, .png, .pdf" name="document_parcours" class="bouton_parcourir_document loadDocumentParcours" required />';
                            echo '</div>';

                            echo '<div id="document_parcours" class="texte_parcourir_document">Aucun fichier sélectionné</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_boutons_saisie">';
                // Valider
                echo '<input type="submit" name="insert_parcours" value="Valider" id="validerSaisieParcours" class="bouton_saisie_gauche" />';

                // Annuler
                echo '<a id="fermerSaisieParcours" class="bouton_saisie_droite">Annuler</a>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>