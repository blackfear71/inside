<?php
    // Bugs
    echo '<div class="zone_bugs">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/reports/bug.png" alt="bug" class="logo_titre_section" /><div class="texte_titre_section">Bugs</div></div>';

        // Liste des bugs
        if (!empty($listeBugs))
        {
            foreach ($listeBugs as $bug)
            {
                echo '<div class="zone_report">';
                    echo '<div id="zone_shadow_' . $bug->getId() . '" class="zone_shadow">';
                        // Titre
                        echo '<div class="zone_report_top" id="' . $bug->getId() . '">';
                            // Supprimer
                            echo '<form id="delete_report_' . $bug->getId() . '" method="post" action="reports.php?view=' . $_GET['view'] . '&action=doSupprimerRapport" class="form_delete_rapport">';
                                echo '<input type="hidden" name="id_report" value="' . $bug->getId() . '" />';
                                echo '<input type="submit" name="delete_bug" value="" title="Supprimer" class="icone_supprimer_rapport eventConfirm" />';
                                echo '<input type="hidden" value="Supprimer le rapport <strong>#' . $bug->getId() . '</strong> ?" class="eventMessage" />';
                            echo '</form>';

                            // Libellé
                            echo '<div class="zone_report_titre">' . $bug->getSubject() . '</div>';

                            // Numéro
                            echo '<div class="zone_report_id">#' . $bug->getId() . '</div>';
                        echo '</div>';

                        // Infos
                        echo '<div class="zone_report_middle">';
                            // Avatar
                            $avatarFormatted = formatAvatar($bug->getAvatar(), $bug->getPseudo(), 2, 'avatar');

                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_report" />';

                            // Pseudo
                            echo '<div class="pseudo_report">' . formatUnknownUser($bug->getPseudo(), true, true) . '</div>';

                            // Equipe
                            echo '<div class="team_report">';
                                echo '<img src="../../includes/icons/reports/team.png" alt="team" class="icone_report" />';
                                echo $bug->getTeam();
                            echo '</div>';

                            // Date
                            echo '<div class="date_report">';
                                echo '<img src="../../includes/icons/reports/date.png" alt="date" class="icone_report" />';
                                echo formatDateForDisplay($bug->getDate());
                            echo '</div>';

                            // Statut
                            switch ($bug->getResolved())
                            {
                                case 'Y':
                                    echo '<div class="report_ended">Terminé</div>';
                                    break;

                                case 'R':
                                    echo '<div class="report_in_progress">Rejeté</div>';
                                    break;

                                case 'N':
                                default:
                                    echo '<div class="report_in_progress">En cours</div>';
                                    break;
                            }
                        echo '</div>';

                        // Contenu
                        echo '<div class="zone_report_bottom">';
                            // Sous-titre
                            echo '<div class="subtitle_report">Demande</div>';

                            // Image
                            if (!empty($bug->getPicture()))
                                echo '<a class="agrandirImage"><img src="../../includes/images/reports/' . $bug->getPicture() . '" alt="' . $bug->getPicture() . '" class="image_report" /></a>';

                            // Contenu
                            echo '<div class="content_report">' . nl2br($bug->getContent()) . '</div>';

                            if (!empty($bug->getPicture()))
                                echo '<div class="clear_report"></div>';
                        echo '</div>';

                        // Solution et actions
                        echo '<form method="post" action="reports.php?view=' . $_GET['view'] . '&action=doModifierStatutRapport">';
                            // Solution
                            if ($bug->getResolved() == 'N')
                            {
                                echo '<div class="zone_report_bottom">';
                                    // Sous-titre
                                    echo '<div class="subtitle_report">Solution</div>';

                                    // Contenu
                                    echo '<textarea name="resolution" class="resolution_report">' . $bug->getResolution() . '</textarea>';
                                echo '</div>';
                            }
                            else
                            {
                                if (!empty($bug->getResolution()))
                                {
                                    echo '<div class="zone_report_bottom">';
                                        // Sous-titre
                                        echo '<div class="subtitle_report">Solution</div>';

                                        // Contenu
                                        echo '<div class="content_report">' . nl2br($bug->getResolution()) . '</div>';
                                    echo '</div>';
                                }
                            }

                            // Actions
                            echo '<div class="zone_report_actions">';
                                // Résoudre, rejeter ou remettre en cours
                                echo '<input type="hidden" name="id_report" value="' . $bug->getId() . '" />';

                                if ($bug->getResolved() == 'N')
                                {
                                    echo '<input type="submit" name="resolve_bug" value="Résoudre" class="saisie_bouton_rapport" />';
                                    echo '<input type="submit" name="reject_bug" value="Rejeter" class="saisie_bouton_rapport" />';
                                }
                                else
                                    echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="saisie_bouton_rapport" />';
                            echo '</div>';
                        echo '</form>';
                    echo '</div>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Pas de bugs, tout va bien...</div>';
    echo '</div>';

    // Evolutions
    echo '<div class="zone_evolutions">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/reports/evolution.png" alt="evolution" class="logo_titre_section" /><div class="texte_titre_section">Evolutions</div></div>';

        // Liste des évolutions
        if (!empty($listeEvolutions))
        {
            foreach ($listeEvolutions as $evolution)
            {
                echo '<div class="zone_report">';
                    echo '<div id="zone_shadow_' . $evolution->getId() . '" class="zone_shadow">';
                        // Titre
                        echo '<div class="zone_report_top" id="' . $evolution->getId() . '">';
                            // Supprimer
                            echo '<form id="delete_report_' . $evolution->getId() . '" method="post" action="reports.php?view=' . $_GET['view'] . '&action=doSupprimerRapport" class="form_delete_rapport">';
                                echo '<input type="hidden" name="id_report" value="' . $evolution->getId() . '" />';
                                echo '<input type="submit" name="delete_evolution" value="" title="Supprimer" class="icone_supprimer_rapport eventConfirm" />';
                                echo '<input type="hidden" value="Supprimer le rapport <strong>#' . $evolution->getId() . '</strong> ?" class="eventMessage" />';
                            echo '</form>';

                            // Libellé
                            echo '<div class="zone_report_titre">' . $evolution->getSubject() . '</div>';

                            // Numéro
                            echo '<div class="zone_report_id">#' . $evolution->getId() . '</div>';
                        echo '</div>';

                        // Infos
                        echo '<div class="zone_report_middle">';
                            // Avatar
                            $avatarFormatted = formatAvatar($evolution->getAvatar(), $evolution->getPseudo(), 2, 'avatar');

                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_report" />';

                            // Pseudo
                            echo '<div class="pseudo_report">' . formatUnknownUser($evolution->getPseudo(), true, true) . '</div>';

                            // Equipe
                            echo '<div class="team_report">';
                                echo '<img src="../../includes/icons/reports/team.png" alt="team" class="icone_report" />';
                                echo $evolution->getTeam();
                            echo '</div>';

                            // Date
                            echo '<div class="date_report">';
                                echo '<img src="../../includes/icons/reports/date.png" alt="date" class="icone_report" />';
                                echo formatDateForDisplay($evolution->getDate());
                            echo '</div>';

                            // Statut
                            switch ($evolution->getResolved())
                            {
                                case 'Y':
                                    echo '<div class="report_ended">Terminée</div>';
                                    break;

                                case 'R':
                                    echo '<div class="report_in_progress">Rejetée</div>';
                                    break;

                                case 'N':
                                default:
                                    echo '<div class="report_in_progress">En cours</div>';
                                    break;
                            }
                        echo '</div>';

                        // Contenu
                        echo '<div class="zone_report_bottom">';
                            // Sous-titre
                            echo '<div class="subtitle_report">Demande</div>';

                            // Image
                            if (!empty($evolution->getPicture()))
                                echo '<a class="agrandirImage"><img src="../../includes/images/reports/' . $evolution->getPicture() . '" alt="' . $evolution->getPicture() . '" class="image_report" /></a>';

                            // Contenu
                            echo '<div class="content_report">' . nl2br($evolution->getContent()) . '</div>';

                            if (!empty($evolution->getPicture()))
                                echo '<div class="clear_report"></div>';
                        echo '</div>';

                        // Solution et actions
                        echo '<form method="post" action="reports.php?view=' . $_GET['view'] . '&action=doModifierStatutRapport">';
                            // Solution
                            if ($evolution->getResolved() == 'N')
                            {
                                echo '<div class="zone_report_bottom">';
                                    // Sous-titre
                                    echo '<div class="subtitle_report">Solution</div>';

                                    // Contenu
                                    echo '<textarea name="resolution" class="resolution_report">' . $evolution->getResolution() . '</textarea>';
                                echo '</div>';
                            }
                            else
                            {
                                if (!empty($evolution->getResolution()))
                                {
                                    echo '<div class="zone_report_bottom">';
                                        // Sous-titre
                                        echo '<div class="subtitle_report">Solution</div>';

                                        // Contenu
                                        echo '<div class="content_report">' . nl2br($evolution->getResolution()) . '</div>';
                                    echo '</div>';
                                }
                            }

                            // Actions
                            echo '<div class="zone_report_actions">';
                                // Résoudre, rejeter ou remettre en cours
                                echo '<input type="hidden" name="id_report" value="' . $evolution->getId() . '" />';

                                if ($evolution->getResolved() == 'N')
                                {
                                    echo '<input type="submit" name="resolve_bug" value="Résoudre" class="saisie_bouton_rapport" />';
                                    echo '<input type="submit" name="reject_bug" value="Rejeter" class="saisie_bouton_rapport" />';
                                }
                                else
                                    echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="saisie_bouton_rapport" />';
                            echo '</div>';
                        echo '</form>';
                    echo '</div>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Pas d\'évolutions proposées...</div>';
    echo '</div>';
?>