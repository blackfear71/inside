<?php
    // Semaine en cours
    echo '<div class="zone_semaines_left">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" /><div class="texte_titre_section">Le gâteau de la semaine</div></div>';

        // Zone semaine courante
        echo '<div class="zone_semaine">';
            // Numéro semaine
            echo '<div class="numero_week">' . formatWeekForDisplay(date('W')) . '</div>';

            if (!empty($currentWeek->getIdentifiant()))
            {
                // Avatar
                $avatarFormatted = formatAvatar($currentWeek->getAvatar(), $currentWeek->getPseudo(), 2, 'avatar');

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_week" />';

                // Pseudo
                echo '<div class="pseudo_week">' . formatUnknownUser(formatString($currentWeek->getPseudo(), 50), true, false) . '</div>';

                // Boutons d'action
                echo '<div class="zone_boutons" id="zone_current_week">';
                    if ($currentWeek->getCooked() == 'N')
                    {
                        echo '<div id="boutons_current_week">';
                            // Ajout utilisateur
                            echo '<a id="choix_semaine_courante_' . date('W') . '" class="bouton_semaine afficherUtilisateursCurrent">';
                                echo 'Modifier';
                            echo '</a>';

                            // Suppression utilisateur
                            if (empty($currentWeek->getName())
                            AND empty($currentWeek->getPicture())
                            AND empty($currentWeek->getIngredients())
                            AND empty($currentWeek->getRecipe())
                            AND empty($currentWeek->getTips()))
                            {
                                echo '<form id="delete_current_week" method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doSupprimerSemaine">';
                                    echo '<input type="hidden" name="week_cake" value="' . $currentWeek->getWeek() . '" />';
                                    echo '<input type="hidden" name="year_cake" value="' . $currentWeek->getYear() . '" />';
                                    echo '<input type="submit" name="delete_cake" value="Supprimer" title="Supprimer" class="bouton_semaine_2 eventConfirm" />';
                                    echo '<input type="hidden" value="Supprimer ' . $currentWeek ->getPseudo() . ' de la semaine ' . formatWeekForDisplay(date('W')) . ' ?" class="eventMessage" />';
                                echo '</form>';
                            }

                            // Validation utilisateur
                            if ($currentWeek->getIdentifiant() == $_SESSION['user']['identifiant'])
                            {
                                echo '<form method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doValiderSemaine">';
                                    echo '<input type="hidden" name="week_cake" value="' . $currentWeek->getWeek() . '" />';
                                    echo '<input type="hidden" name="year_cake" value="' . $currentWeek->getYear() . '" />';
                                    echo '<input type="submit" name="validate_cake" value="Je l\'ai fait" class="bouton_semaine_2" />';
                                echo '</form>';
                            }
                        echo '</div>';
                    }
                    else
                    {
                        echo '<div class="cake_done">Le gâteau a été fait pour cette semaine !</div>';

                        // Annulation utilisateur
                        if ($currentWeek->getIdentifiant() == $_SESSION['user']['identifiant'])
                        {
                            echo '<form method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doAnnulerSemaine">';
                                echo '<input type="hidden" name="week_cake" value="' . $currentWeek->getWeek() . '" />';
                                echo '<input type="hidden" name="year_cake" value="' . $currentWeek->getYear() . '" />';
                                echo '<input type="submit" name="cancel_cake" value="Annuler" class="bouton_semaine_2" />';
                            echo '</form>';
                        }
                    }
                echo '</div>';
            }
            else
            {
                echo '<div class="empty_week">';
                    echo 'Encore personne d\'affecté...';
                echo '</div>';

                // Bouton d'action
                echo '<div class="zone_boutons_2" id="zone_current_week">';
                    echo '<div id="boutons_current_week">';
                        // Ajout utilisateur
                        echo '<a id="choix_semaine_courante_' . date('W') . '" class="bouton_semaine afficherUtilisateursCurrent">';
                            echo 'Modifier';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
    echo '</div>';

    // Semaine suivante
    echo '<div class="zone_semaines_right">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">Pour la semaine prochaine</div></div>';
            
        // Zone semaine suivante
        echo '<div class="zone_semaine">';
            // Numéro semaine
            echo '<div class="numero_week">' . formatWeekForDisplay(date('W', strtotime('+ 1 week'))) . '</div>';

            $weekNext = date('W', strtotime('+ 1 week'));

            if (!empty($nextWeek->getIdentifiant()))
            {
                // Avatar
                $avatarFormatted = formatAvatar($nextWeek->getAvatar(), $nextWeek->getPseudo(), 2, 'avatar');

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_week" />';

                // Pseudo
                echo '<div class="pseudo_week">' . formatUnknownUser(formatString($nextWeek->getPseudo(), 50), true, false) . '</div>';

                // Bouton d'action
                echo '<div class="zone_boutons" id="zone_next_week">';
                    echo '<div id="boutons_next_week">';
                        // Ajout utilisateur
                        echo '<a id="choix_semaine_suivante_' . $weekNext . '" class="bouton_semaine afficherUtilisateursNext">';
                            echo 'Modifier';
                        echo '</a>';

                        // Suppression utilisateur
                        if (empty($nextWeek->getName())
                        AND empty($nextWeek->getPicture())
                        AND empty($nextWeek->getIngredients())
                        AND empty($nextWeek->getRecipe())
                        AND empty($nextWeek->getTips()))
                        {
                            echo '<form id="delete_next_week" method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doSupprimerSemaine">';
                                echo '<input type="hidden" name="week_cake" value="' . $nextWeek->getWeek() . '" />';
                                echo '<input type="hidden" name="year_cake" value="' . $nextWeek->getYear() . '" />';
                                echo '<input type="submit" name="delete_cake" value="Supprimer" title="Supprimer" class="bouton_semaine_2 eventConfirm" />';
                                echo '<input type="hidden" value="Supprimer ' . $nextWeek ->getPseudo() . ' de la semaine ' . $weekNext . ' ?" class="eventMessage" />';
                            echo '</form>';
                        }
                    echo '</div>';
                echo '</div>';
            }
            else
            {
                echo '<div class="empty_week">';
                    echo 'Encore personne d\'affecté...';
                echo '</div>';

                // Bouton d'action
                echo '<div class="zone_boutons_2" id="zone_next_week">';
                    echo '<div id="boutons_next_week">';
                        // Ajout utilisateur
                        echo '<a id="choix_semaine_suivante_' . $weekNext . '" class="bouton_semaine afficherUtilisateursNext">';
                            echo 'Modifier';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
    echo '</div>';
?>