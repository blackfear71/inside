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
        if (!empty($listeParticipantsParDate))
        {
            echo '<div class="zone_participations_parcours">';
                foreach ($listeParticipantsParDate as $dateParticipations => $participantsParDate)
                {
                    echo '<div class="zone_participants_parcours">';
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
                            foreach ($participantsParDate as $participant)
                            {
                                // Utilisateur
                                echo '<div class="zone_participant">';
                                    // Avatar
                                    $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, 'avatar');

                                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_participant" />';

                                    if ($participant->getIdentifiant() == $_SESSION['user']['identifiant'])
                                    {
                                        // Pseudo
                                        echo '<div class="pseudo_participant">' . $participant->getPseudo() . '</div>';

                                        // Modification
                                        echo '<span class="lien_actions_participation">';
                                            echo '<a id="modifier_participation_' . $participant->getId() . '" title="Modifier la participation" class="icone_modifier_participation modifierParticipation"></a>';
                                        echo '</span>';

                                        // Suppression
                                        echo '<form id="supprimer_participation_' . $participant->getId() . '" method="post" action="details.php?action=doSupprimerParticipation" class="lien_actions_participation">';
                                            echo '<input type="hidden" name="id_parcours" value="' . $participant->getId_parcours() . '" />';
                                            echo '<input type="hidden" name="id_participation" value="' . $participant->getId() . '" />';
                                            echo '<input type="submit" name="delete_participation" value="" title="Supprimer la participation" class="icone_supprimer_participation eventConfirm" />';
                                            echo '<input type="hidden" value="Supprimer cette participation ?" class="eventMessage" />';
                                        echo '</form>';
                                    }
                                    else
                                    {
                                        // Pseudo
                                        echo '<div class="pseudo_participant_full">' . $participant->getPseudo() . '</div>';
                                    }
                                echo '</div>';

                                // Données course
                                echo '<div class="zone_donnees_participant">';
                                    // Distance
                                    echo '<div class="zone_donnee_participant">';
                                        echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_donnee_participant" />';

                                        if (!empty($participant->getDistance()))
                                            echo '<div class="donnee_participant">' . formatDistanceForDisplay($participant->getDistance()) . '</div>';
                                        else
                                            echo '<div class="donnee_participant">N/A</div>';
                                    echo '</div>';

                                    // Temps
                                    echo '<div class="zone_donnee_participant">';
                                        echo '<img src="../../includes/icons/petitspedestres/time_grey.png" alt="time_grey" title="Temps" class="icone_donnee_participant" />';

                                        if (!empty($participant->getTime()))
                                            echo '<div class="donnee_participant">' . formatSecondsForDisplay($participant->getTime()) . '</div>';
                                        else
                                            echo '<div class="donnee_participant">N/A</div>';
                                    echo '</div>';

                                    // Vitesse
                                    echo '<div class="zone_donnee_participant">';
                                        echo '<img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" title="Vitesse" class="icone_donnee_participant" />';

                                        if (!empty($participant->getSpeed()))
                                            echo '<div class="donnee_participant">' . formatSpeedForDisplay($participant->getSpeed()) . '</div>';
                                        else
                                            echo '<div class="donnee_participant">N/A</div>';
                                    echo '</div>';

                                    // Cardio
                                    echo '<div class="zone_donnee_participant">';
                                        echo '<img src="../../includes/icons/petitspedestres/cardio_grey.png" alt="cardio_grey" title="Cardio" class="icone_donnee_participant" />';

                                        if (!empty($participant->getCardio()))
                                            echo '<div class="donnee_participant">' . formatCardioForDisplay($participant->getCardio()) . '</div>';
                                        else
                                            echo '<div class="donnee_participant">N/A</div>';
                                    echo '</div>';

                                    // Compétition
                                    echo '<div class="zone_donnee_participant">';
                                        if ($participant->getCompetition() == 'Y')
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
                }
            echo '</div>';
        }
        else
            echo '<div class="empty">Aucune participation sur ce parcours...</div>';
    echo '</div>';
?>