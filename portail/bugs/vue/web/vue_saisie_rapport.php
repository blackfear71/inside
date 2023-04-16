<?php
    /*****************************/
    /* Zone de saisie de rapport */
    /*****************************/
    echo '<div id="zone_saisie_rapport" class="fond_saisie">';
        echo '<div class="zone_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                // Texte
                echo '<div class="texte_titre_saisie">Rapporter un bug ou une évolution</div>';

                // Bouton fermeture
                echo '<a id="fermerRapport" class="bouton_fermeture_saisie"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="image_fermeture_saisie" /></a>';
            echo '</div>';

            // Saisie rapport
            echo '<form method="post" action="bugs.php?action=doAjouterRapport" enctype="multipart/form-data" class="form_saisie">';
                // Explications
                echo '<div class="zone_explications_rapport">';
                    echo '<div class="texte_explications_rapport">';
                        echo 'Cette page vous permet de remonter d\'éventuelles <strong>évolutions techniques</strong> à apporter au site et les rapports seront envoyés à l\'administrateur. Vous pouvez inclure
                              une image pour plus de précision.';
                    echo '</div>';

                    echo '<div class="texte_explications_rapport">';
                        echo 'Pour toute demande d\'<strong>évolution fonctionnelle</strong>, veuillez utiliser la page #TheBox.';
                    echo '</div>';
                echo '</div>';

                // Image
                echo '<div class="zone_saisie_left">';
                    // Saisie image
                    echo '<div class="zone_parcourir_image">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieReport" />';
                    echo '</div>';

                    echo '<div class="mask_image">';
                        echo '<img id="image_report" alt="" class="image" />';
                    echo '</div>';
                echo '</div>';

                // Rapport
                echo '<div class="zone_saisie_right">';
                    // Sujet
                    echo '<input type="text" name="subject_bug" placeholder="Objet" value="' . $_SESSION['save']['subject_bug'] . '" maxlength="255" class="saisie_objet" required />';

                    // Type
                    echo '<select name="type_bug" class="saisie_type" required>';
                        echo '<option value="" hidden>Type de demande</option>';

                        if (isset($_SESSION['save']['type_bug']) AND $_SESSION['save']['type_bug'] == 'B')
                            echo '<option value="B" selected>Bug</option>';
                        else
                            echo '<option value="B">Bug</option>';

                        if (isset($_SESSION['save']['type_bug']) AND $_SESSION['save']['type_bug'] == 'E')
                            echo '<option value="E" selected>Evolution</option>';
                        else
                            echo '<option value="E">Evolution</option>';
                    echo '</select>';

                    // Boutons d'action
                    echo '<div class="zone_bouton_saisie">';
                        // Ajouter
                        echo '<input type="submit" name="report" value="Soumettre la demande" id="bouton_saisie_bug" class="saisie_bouton" />';
                    echo '</div>';

                    // Description
                    echo '<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu" required>' . $_SESSION['save']['content_bug'] . '</textarea>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>