<?php
    echo '<div id="zone_saisie_rapport" class="fond_saisie">';
        echo '<form method="post" action="bugs.php?action=doAjouterRapport" enctype="multipart/form-data" class="form_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">Faire un rapport</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Titre
                    echo '<div id="titre_informations_bug_evolution" class="titre_section">';
                        echo '<img src="../../includes/icons/reports/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section_fleche">Informations</div>';
                        echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
                    echo '</div>';

                    // Informations
                    echo '<div id="afficher_informations_bug_evolution" class="texte_informations_bug_evolution" style="display: none;">';
                        echo 'Cette page vous permet de remonter d\'éventuelles <strong>évolutions techniques</strong> à apporter au site et les rapports seront envoyés à l\'administrateur. Vous pouvez inclure une image pour plus de précision.';

                        echo '<br /><br />';

                        echo 'Pour toute demande d\'<strong>évolution fonctionnelle</strong>, veuillez utiliser la page #TheBox.';
                    echo '</div>';

                    // Titre
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/common/alert_grey.png" alt="alert_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">Rapporter un bug ou une évolution</div>';
                    echo '</div>';

                    // Objet
                    echo '<input type="text" name="subject_bug" placeholder="Objet" value="' . $_SESSION['save']['subject_bug'] . '" maxlength="255" class="saisie_objet" required />';

                    // Type de demande
                    echo '<select name="type_bug" class="saisie_type_demande" required>';
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

                    // Image
                    echo '<div class="zone_image_saisie">';
                        // Saisie image
                        echo '<div class="zone_parcourir_image">';
                            echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieRapport" />';
                        echo '</div>';

                        echo '<div class="mask_image">';
                            echo '<img id="image_report" alt="" class="image" />';
                        echo '</div>';
                    echo '</div>';

                    // Description du problème
                    echo '<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu" required>' . $_SESSION['save']['content_bug'] . '</textarea>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_boutons_saisie">';
                // Valider
                echo '<input type="submit" name="submit_report" value="Valider" id="validerSaisieRapport" class="bouton_saisie_gauche" />';

                // Annuler
                echo '<a id="fermerSaisieRapport" class="bouton_saisie_droite">Annuler</a>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>