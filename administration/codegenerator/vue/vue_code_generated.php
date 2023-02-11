<?php
    if (!empty($generatorParameters->getNom_section()) AND !empty($generatorParameters->getNom_technique()))
    {
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Code généré</div></div>';

        // Code généré
        echo '<div class="margin_bottom_moins_30">';
            // Partie Métier
            echo '<div class="zone_generated_left margin_right_20">';
                // Zone Métier
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                        // Type et actions
                        echo '<div class="entete_fichier_generator">';
                            echo 'Fichier : métier';
                            echo '<input type="hidden" name="file_name" value="' . $metier['filename'] . '" />';

                            // Boutons
                            echo '<div class="zone_boutons_generator">';
                                // Bouton Télécharger
                                echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                                // Bouton Copier
                                echo '<a id="metier" class="bouton_action_generator copyCode">Copier</a>';
                            echo '</div>';
                        echo '</div>';

                        // Nom du fichier
                        echo '<div class="nom_fichier_generator">';
                            echo 'Nom : ' . $metier['filename'];
                        echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea name="download_zone" id="code_metier" class="code_generator_metier">' . $metier['content'] . '</textarea>';
                echo '</form>';

                // Zone Contrôles
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                        // Type et actions
                        echo '<div class="entete_fichier_generator">';
                            echo 'Fichier : contrôles';
                            echo '<input type="hidden" name="file_name" value="' . $controles['filename'] . '" />';

                            // Boutons
                            echo '<div class="zone_boutons_generator">';
                                // Bouton Télécharger
                                echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                                // Bouton Copier
                                echo '<a id="controles" class="bouton_action_generator copyCode">Copier</a>';
                            echo '</div>';
                        echo '</div>';

                        // Nom du fichier
                        echo '<div class="nom_fichier_generator">';
                            echo 'Nom : ' . $controles['filename'];
                        echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea name="download_zone" id="code_controles" class="code_generator_metier">' . $controles['content'] . '</textarea>';
                echo '</form>';

                // Zone Physique
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                        // Type et actions
                        echo '<div class="entete_fichier_generator">';
                            echo 'Fichier : physique';
                            echo '<input type="hidden" name="file_name" value="' . $physique['filename'] . '" />';

                            // Boutons
                            echo '<div class="zone_boutons_generator">';
                                // Bouton Télécharger
                                echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                                // Bouton Copier
                                echo '<a id="physique" class="bouton_action_generator copyCode">Copier</a>';
                            echo '</div>';
                        echo '</div>';

                        // Nom du fichier
                        echo '<div class="nom_fichier_generator">';
                            echo 'Nom : ' . $physique['filename'];
                        echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea name="download_zone" id="code_physique" class="code_generator_metier">' . $physique['content'] . '</textarea>';
                echo '</form>';
            echo '</div>';

            // Partie Vue
            echo '<div class="zone_generated_middle margin_right_20">';
                // Zone Vue (web)
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                        // Type et actions
                        echo '<div class="entete_fichier_generator">';
                            echo 'Fichier : vue (web)';
                            echo '<input type="hidden" name="file_name" value="' . $listeVues['vue_web']['filename'] . '" />';

                            // Boutons
                            echo '<div class="zone_boutons_generator">';
                                // Bouton Télécharger
                                echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                                // Bouton Copier
                                echo '<a id="vue_web" class="bouton_action_generator copyCode">Copier</a>';
                            echo '</div>';
                        echo '</div>';

                        // Nom du fichier
                        echo '<div class="nom_fichier_generator">';
                            echo 'Nom : ' . $listeVues['vue_web']['filename'];
                        echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    if (!empty($listeVues['vue_mobile']))
                        echo '<textarea name="download_zone" id="code_vue_web" class="code_generator_vue_mobile">';
                    else
                        echo '<textarea name="download_zone" id="code_vue_web" class="code_generator_vue">';
                        echo $listeVues['vue_web']['content'];
                    echo '</textarea>';
                echo '</form>';

                // Zone Vue (mobile)
                if (!empty($listeVues['vue_mobile']))
                {
                    echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                        // Entête du fichier
                        echo '<div class="zone_entete_fichier_generator">';
                            // Type et actions
                            echo '<div class="entete_fichier_generator">';
                                echo 'Fichier : vue (mobile)';
                                echo '<input type="hidden" name="file_name" value="' . $listeVues['vue_mobile']['filename'] . '" />';

                                // Boutons
                                echo '<div class="zone_boutons_generator">';
                                    // Bouton Télécharger
                                    echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                                    // Bouton Copier
                                    echo '<a id="vue_mobile" class="bouton_action_generator copyCode">Copier</a>';
                                echo '</div>';
                            echo '</div>';

                            // Nom du fichier
                            echo '<div class="nom_fichier_generator">';
                                echo 'Nom : ' . $listeVues['vue_mobile']['filename'];
                            echo '</div>';
                        echo '</div>';

                        // Contenu du fichier
                        echo '<textarea name="download_zone" id="code_vue_mobile" class="code_generator_vue_mobile">' . $listeVues['vue_mobile']['content'] . '</textarea>';
                    echo '</form>';
                }
            echo '</div>';

            // Partie Contrôleur
            echo '<div class="zone_generated_right">';
                // Zone contrôleur
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                        // Type et actions
                        echo '<div class="entete_fichier_generator">';
                            echo 'Fichier : contrôleur';
                            echo '<input type="hidden" name="file_name" value="' . $controler['filename'] . '" />';

                            // Boutons
                            echo '<div class="zone_boutons_generator">';
                                // Bouton Télécharger
                                echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                                // Bouton Copier
                                echo '<a id="controler" class="bouton_action_generator copyCode">Copier</a>';
                            echo '</div>';
                        echo '</div>';

                        // Nom du fichier
                        echo '<div class="nom_fichier_generator">';
                            echo 'Nom : ' . $controler['filename'];
                        echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    if (!empty($generatorParameters->getScript_specifique()))
                        echo '<textarea name="download_zone" id="code_controler" class="code_generator_controler_js">';
                    else
                        echo '<textarea name="download_zone" id="code_controler" class="code_generator_controler">';
                        echo $controler['content'];
                    echo '</textarea>';
                echo '</form>';

                // Zone Javascript
                if (!empty($generatorParameters->getScript_specifique()))
                {
                    echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                        // Entête du fichier
                        echo '<div class="zone_entete_fichier_generator">';
                            // Type et actions
                            echo '<div class="entete_fichier_generator">';
                                echo 'Fichier : javascript';
                                echo '<input type="hidden" name="file_name" value="' . $javascript['filename'] . '" />';

                                // Boutons
                                echo '<div class="zone_boutons_generator">';
                                    // Bouton Télécharger
                                    echo '<input type="submit" name="download_js" value="Télécharger" class="bouton_action_generator" />';

                                    // Bouton Copier
                                    echo '<a id="javascript" class="bouton_action_generator copyCode">Copier</a>';
                                echo '</div>';
                            echo '</div>';

                            // Nom du fichier
                            echo '<div class="nom_fichier_generator">';
                                echo 'Nom : ' . $javascript['filename'];
                            echo '</div>';
                        echo '</div>';

                        // Contenu du fichier
                        echo '<textarea name="download_zone" id="code_javascript" class="code_generator_controler_js">' . $javascript['content'] . '</textarea>';
                    echo '</form>';
                }
            echo '</div>';
        echo '</div>';
    }
?>