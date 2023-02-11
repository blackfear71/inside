<?php
    echo '<div class="zone_edition_changelog">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Journal à éditer</div></div>';

        switch ($changeLogParameters->getAction())
        {
            case 'M':
                echo '<form method="post" action="changelog.php?action=doModifier" class="form_edition_changelog">';
                    // Période
                    echo '<input type="hidden" name="saisie_annee_changelog" value="' . $changeLog->getYear() . '" />';
                    echo '<input type="hidden" name="saisie_semaine_changelog" value="' . $changeLog->getWeek() . '" />';

                    // Notes libres
                    echo '<textarea name="saisie_notes_changelog" placeholder="Notes libres" class="saisie_notes_libres_changelog">' . $changeLog->getNotes() . '</textarea>';

                    // Entrées
                    echo '<div class="zone_saisie_entrees_changelog">';
                    $i = 1;

                    foreach ($changeLog->getLogs() as $keyLogsByCategories => $logsByCategories)
                    {
                        foreach ($logsByCategories as $logByCategory)
                        {
                            echo '<div class="zone_saisie_entree_changelog">';
                                // Saisie entrée
                                echo '<input type="text" name="saisies_entrees[' . $i . ']" placeholder="Entrée n°' . $i . '" value="' . $logByCategory . '" class="saisie_entree_changelog" />';

                                // Choix catégorie
                                echo '<select name="categories_entrees[' . $i . ']" class="categorie_entree_changelog">';
                                    foreach ($categoriesChangeLog as $keyCategorie => $categorie)
                                    {
                                        if ($keyCategorie == $keyLogsByCategories)
                                            echo '<option value="' . $keyCategorie . '" selected>' . $categorie . '</option>';
                                        else
                                            echo '<option value="' . $keyCategorie . '">' . $categorie . '</option>';
                                    }
                                echo '</select>';
                            echo '</div>';

                            $i++;
                        }
                    }
                    echo '</div>';

                    // Boutons
                    echo '<div class="zone_boutons_changelog">';
                        // Ajouter une entrée
                        echo '<a id="ajouter_entree_changelog" class="bouton_ajouter_entree">';
                            echo '<span class="fond_plus">+</span>';
                            echo 'Ajouter une entrée au journal';
                        echo '</a>';

                        // Modifier le journal
                        echo '<div class="zone_bouton_valider_journal">';
                            echo '<input type="submit" name="update_changelog" value="Modifier le journal" id="bouton_saisie_journal" class="bouton_validation_journal" />';
                        echo '</div>';
                    echo '</div>';
                echo '</form>';
                break;

            case 'S':
                echo '<form method="post" action="changelog.php?action=doSupprimer">';
                    // Période
                    echo '<input type="hidden" name="saisie_annee_changelog" value="' . $changeLog->getYear() . '" />';
                    echo '<input type="hidden" name="saisie_semaine_changelog" value="' . $changeLog->getWeek() . '" />';

                    // Notes libres
                    echo '<div class="notes_changelog">';
                        echo $changeLog->getNotes();
                    echo '</div>';

                    // Entrées
                    echo '<div class="zone_logs_edition">';
                        foreach ($changeLog->getLogs() as $keyLogs => $logsCategorie)
                        {
                            echo '<div class="zone_logs_categorie">';
                                // Titre catégorie
                                echo '<div class="titre_categorie">';
                                    if (isset($categoriesChangeLog[$keyLogs]))
                                    {
                                        echo '<img src="../../includes/icons/changelog/' . $keyLogs . '_grey.png" alt="' . $keyLogs . '" class="logo_titre_categorie" />';
                                        echo $categoriesChangeLog[$keyLogs];
                                    }
                                    else
                                        echo $keyLogs;
                                echo '</div>';

                                // Entrées
                                echo '<ul class="logs_categorie">';
                                    foreach ($logsCategorie as $logCategorie)
                                    {
                                        echo '<li class="log_categorie">' . $logCategorie . '</li>';
                                    }
                                echo '</ul>';
                            echo '</div>';
                        }
                    echo '</div>';

                    // Boutons
                    echo '<div class="zone_boutons_changelog">';
                        // Supprimer le journal
                        echo '<div class="zone_bouton_valider_journal">';
                            echo '<input type="submit" name="delete_changelog" value="Supprimer le journal" id="bouton_suppression_journal" class="bouton_validation_journal bouton_validation_journal_gris" />';
                        echo '</div>';
                    echo '</div>';
                echo '</form>';
                break;

            case 'A':
            default:
                echo '<form method="post" action="changelog.php?action=doAjouter" class="form_edition_changelog">';
                    // Période
                    echo '<input type="hidden" name="saisie_annee_changelog" value="' . $changeLogParameters->getYear() . '" />';
                    echo '<input type="hidden" name="saisie_semaine_changelog" value="' . $changeLogParameters->getWeek() . '" />';

                    // Notes libres
                    echo '<textarea name="saisie_notes_changelog" placeholder="Notes libres" class="saisie_notes_libres_changelog"></textarea>';

                    // Entrées
                    echo '<div class="zone_saisie_entrees_changelog">';
                    for ($i = 1; $i <= 5; $i++)
                    {
                        echo '<div class="zone_saisie_entree_changelog">';
                            // Saisie entrée
                            echo '<input type="text" name="saisies_entrees[' . $i . ']" placeholder="Entrée n°' . $i . '" value="" class="saisie_entree_changelog" />';

                            // Choix catégorie
                            echo '<select name="categories_entrees[' . $i . ']" class="categorie_entree_changelog">';
                                echo '<option value="" hidden>Choisir une catégorie</option>';

                                foreach ($categoriesChangeLog as $keyCategorie => $categorie)
                                {
                                    echo '<option value="' . $keyCategorie . '">' . $categorie . '</option>';
                                }
                            echo '</select>';
                        echo '</div>';
                    }
                    echo '</div>';

                // Boutons
                echo '<div class="zone_boutons_changelog">';
                    // Ajouter une entrée
                    echo '<a id="ajouter_entree_changelog" class="bouton_ajouter_entree">';
                        echo '<span class="fond_plus">+</span>';
                        echo 'Ajouter une entrée au journal';
                    echo '</a>';

                    // Ajouter le journal
                    echo '<div class="zone_bouton_valider_journal">';
                        echo '<input type="submit" name="insert_changelog" value="Ajouter le journal" id="bouton_saisie_journal" class="bouton_validation_journal" />';
                    echo '</div>';
                echo '</div>';
                echo '</form>';
                break;
        }
    echo '</div>';
?>