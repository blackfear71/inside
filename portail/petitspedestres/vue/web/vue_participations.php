<?php
    /**********************/
    /*** Participations ***/
    /**********************/
    echo '<div class="zone_participations_parcours">';
        // Titre
        echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/petitspedestres/runs_grey.png" alt="runs_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les participations</div>';
        echo '</div>';

        // Participations
        if (!empty($listeParticipationsParDate))
        {
            echo '<div class="zone_participations_parcours">';
                foreach ($listeParticipationsParDate as $dateParticipations => $participationsParDate)
                {
                    echo '<div id="zone_shadow_' . $dateParticipations . '" class="zone_shadow">';
                        echo '<div id="' . $dateParticipations . '" class="zone_participants_parcours">';
                            // Données communes
                            echo '<div class="zone_participants_left">';
                                // Date
                                echo '<div class="date_participation">';
                                    echo '<img src="../../includes/icons/petitspedestres/date.png" alt="date" class="icone_date_participation" />';
                                    echo '<div class="texte_date_participation">' . formatDateForDisplay($dateParticipations) . '</div>';
                                echo '</div>';
                            echo '</div>';

                            // Données utilisateurs
                            echo '<div class="zone_participants_right">';
                                foreach ($participationsParDate as $participation)
                                {
                                    // Utilisateur
                                    echo '<div class="zone_participant">';
                                        // Avatar
                                        $avatarFormatted = formatAvatar($participation->getAvatar(), $participation->getPseudo(), 2, 'avatar');

                                        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_participant" />';

                                        if ($participation->getIdentifiant() == $_SESSION['user']['identifiant'])
                                        {
                                            // Pseudo
                                            echo '<div class="pseudo_participant">' . $participation->getPseudo() . '</div>';

                                            // Modification
                                            echo '<span class="lien_actions_participation">';
                                                echo '<a id="modifier_participation_' . $participation->getId() . '" title="Modifier la participation" class="icone_modifier_participation modifierParticipation"></a>';
                                            echo '</span>';

                                            // Suppression
                                            echo '<form id="supprimer_participation_' . $participation->getId() . '" method="post" action="details.php?action=doSupprimerParticipation" class="lien_actions_participation">';
                                                echo '<input type="hidden" name="id_parcours" value="' . $participation->getId_parcours() . '" />';
                                                echo '<input type="hidden" name="id_participation" value="' . $participation->getId() . '" />';
                                                echo '<input type="submit" name="delete_participation" value="" title="Supprimer la participation" class="icone_supprimer_participation eventConfirm" />';
                                                echo '<input type="hidden" value="Supprimer cette participation ?" class="eventMessage" />';
                                            echo '</form>';
                                        }
                                        else
                                        {
                                            // Pseudo
                                            echo '<div class="pseudo_participant_full">' . $participation->getPseudo() . '</div>';
                                        }
                                    echo '</div>';

                                    // Données course
                                    echo '<div class="zone_donnees_participant">';
                                        // Distance
                                        echo '<div class="zone_donnee_participant">';
                                            echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_donnee_participant" />';

                                            if (!empty($participation->getDistance()))
                                                echo '<div class="donnee_participant">' . formatDistanceForDisplay($participation->getDistance()) . '</div>';
                                            else
                                                echo '<div class="donnee_participant">N/A</div>';
                                        echo '</div>';

                                        // Temps
                                        echo '<div class="zone_donnee_participant">';
                                            echo '<img src="../../includes/icons/petitspedestres/time_grey.png" alt="time_grey" title="Temps" class="icone_donnee_participant" />';

                                            if (!empty($participation->getTime()))
                                                echo '<div class="donnee_participant">' . formatSecondsForDisplay($participation->getTime()) . '</div>';
                                            else
                                                echo '<div class="donnee_participant">N/A</div>';
                                        echo '</div>';

                                        // Vitesse
                                        echo '<div class="zone_donnee_participant">';
                                            echo '<img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" title="Vitesse" class="icone_donnee_participant" />';

                                            if (!empty($participation->getSpeed()))
                                                echo '<div class="donnee_participant">' . formatSpeedForDisplay($participation->getSpeed()) . '</div>';
                                            else
                                                echo '<div class="donnee_participant">N/A</div>';
                                        echo '</div>';

                                        // Cardio
                                        echo '<div class="zone_donnee_participant">';
                                            echo '<img src="../../includes/icons/petitspedestres/cardio_grey.png" alt="cardio_grey" title="Cardio" class="icone_donnee_participant" />';

                                            if (!empty($participation->getCardio()))
                                                echo '<div class="donnee_participant">' . formatCardioForDisplay($participation->getCardio()) . '</div>';
                                            else
                                                echo '<div class="donnee_participant">N/A</div>';
                                        echo '</div>';

                                        // Compétition
                                        echo '<div class="zone_donnee_participant">';
                                            if ($participation->getCompetition() == 'Y')
                                            {
                                                echo '<img src="../../includes/icons/petitspedestres/cup_grey.png" alt="cup_grey" title="Compétition" class="icone_donnee_participant" />';
                                                echo '<div class="donnee_participant">Compétition</div>';
                                            }
                                            else
                                            {
                                                echo '<img src="../../includes/icons/petitspedestres/cup_white.png" alt="cup_white" title="Classique" class="icone_donnee_participant" />';
                                                echo '<div class="donnee_participant">Classique</div>';
                                            }
                                            
                                        echo '</div>';
                                    echo '</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';
        }
        else
            echo '<div class="empty">Aucune participation sur ce parcours...</div>';
    echo '</div>';
?>