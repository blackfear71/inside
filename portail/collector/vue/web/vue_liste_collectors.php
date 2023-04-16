<?php
    echo '<div class="zone_collectors">';
        if ($nombrePages > 0)
        {
            foreach ($listeCollectors as $collector)
            {
                /*********************************************/
                /* Visualisation normale (sans modification) */
                /*********************************************/
                echo '<div class="zone_collector" id="visualiser_collector_' . $collector->getId() . '">';
                    echo '<div id="zone_shadow_' . $collector->getId() . '" class="zone_shadow">';
                        if ($collector->getNb_votes() >= $minGolden)
                            echo '<div class="zone_collector_haut_golden" id="' . $collector->getId() . '">';
                        else
                            echo '<div class="zone_collector_haut" id="' . $collector->getId() . '">';

                            // Boutons d'action
                            echo '<div class="zone_bouton_validation">';
                                // Modification
                                echo '<a id="modifier_' . $collector->getId() . '" title="Modifier" class="icone_modifier_collector modifierCollector"></a>';

                                // Suppression
                                if ($collector->getType_collector() == 'I')
                                {
                                    echo '<form id="delete_image_' . $collector->getId() . '" method="post" action="collector.php?action=doSupprimerCollector&page=' . $_GET['page'] . '" class="form_delete_collector">';
                                        echo '<input type="hidden" name="id_collector" value="' . $collector->getId() . '" />';
                                        echo '<input type="hidden" name="team_collector" value="' . $collector->getTeam() . '" />';
                                        echo '<input type="submit" name="delete_collector" value="" title="Supprimer l\'image" class="icone_supprimer_collector eventConfirm" />';
                                        echo '<input type="hidden" value="Supprimer cette image ?" class="eventMessage" />';
                                    echo '</form>';
                                }
                                else
                                {
                                    echo '<form id="delete_collector_' . $collector->getId() . '" method="post" action="collector.php?action=doSupprimerCollector&page=' . $_GET['page'] . '" class="form_delete_collector">';
                                        echo '<input type="hidden" name="id_collector" value="' . $collector->getId() . '" />';
                                        echo '<input type="hidden" name="team_collector" value="' . $collector->getTeam() . '" />';
                                        echo '<input type="submit" name="delete_collector" value="" title="Supprimer la phrase culte" class="icone_supprimer_collector eventConfirm" />';
                                        echo '<input type="hidden" value="Supprimer cette phrase culte ?" class="eventMessage" />';
                                    echo '</form>';
                                }
                            echo '</div>';

                            // Avatar
                            $avatarFormatted = formatAvatar($collector->getAvatar_speaker(), $collector->getPseudo_speaker(), 2, 'avatar');

                            echo '<div class="zone_avatar_collector">';
                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_collector" />';
                            echo '</div>';

                            // Vote utilisateur
                            echo '<a id="link_form_vote_' . $collector->getId() . '" class="link_current_vote modifierVote">';
                                echo '<img src="../../includes/icons/common/smileys/' . $collector->getVote_user() . '.png" alt="smiley" class="current_vote" />';
                            echo '</a>';

                            // Formulaire vote
                            echo '<form method="post" action="collector.php?action=doVoterCollector&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" name="form_vote_user" id="modifier_vote_' . $collector->getId() . '" class="zone_smileys" style="display: none;">';
                                echo '<input type="hidden" name="id_collector" value="' . $collector->getId() . '" />';

                                // Gestion smileys vote
                                for ($j = 0; $j <= 8; $j++)
                                {
                                    if ($j == $collector->getVote_user())
                                        echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley smiley_' . $j . ' smiley_selected" />';
                                    else
                                        echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley smiley_' . $j . '" />';
                                }
                            echo '</form>';

                            // Pseudo
                            echo '<div class="pseudo_collector">';
                                echo formatUnknownUser($collector->getPseudo_speaker(), true, true);
                            echo '</div>';

                            // Date
                            echo '<div class="zone_date_collector">';
                                echo '<img src="../../includes/icons/collector/date.png" alt="date" class="icone_collector" />' . formatDateForDisplay($collector->getDate_collector());
                            echo '</div>';
                        echo '</div>';

                        echo '<div class="zone_collector_bas">';
                            if (!empty($collector->getCollector()))
                            {
                                if ($collector->getType_collector() == 'I')
                                {
                                    // Image
                                    echo '<a class="agrandirImage"><img src="../../includes/images/collector/' . $collector->getCollector() . '" alt="' . $collector->getCollector() . '" class="image_collector" /></a>';
                                }
                                else
                                {
                                    // Apostrophe gauche
                                    echo '<img src="../../includes/icons/collector/quote_1.png" alt="quote_1" class="quote_1" />';

                                    // Citation
                                    echo '<div class="text_collector">' . nl2br(formatCollector($collector->getCollector())) . '</div>';

                                    // Apostrophe droite
                                    echo '<img src="../../includes/icons/collector/quote_2.png" alt="quote_2" class="quote_2" />';
                                }

                                // Rapporteur
                                echo '<div class="author_collector">Par ' . formatUnknownUser($collector->getPseudo_author(), false, false) . '</div>';
                            }

                            // Contexte
                            if (!empty($collector->getContext()))
                            {
                                if ($collector->getNb_votes() >= $minGolden)
                                    echo '<div class="text_context_golden">' . nl2br(formatCollector($collector->getContext())) . '</div>';
                                else
                                    echo '<div class="text_context">' . nl2br(formatCollector($collector->getContext())) . '</div>';
                            }

                            // Votes tous utilisateurs
                            if (!empty($collector->getVotes()))
                            {
                                echo '<div class="zone_votes_users">';
                                    // Smileys
                                    foreach ($collector->getVotes() as $keySmiley => $votesParSmiley)
                                    {
                                        echo '<img src="../../includes/icons/common/smileys/' . $keySmiley . '.png" alt="smiley" class="smiley_votes_' . $keySmiley . '" />';
                                    }

                                    // Pseudos
                                    foreach ($collector->getVotes() as $keySmiley => $votesParSmiley)
                                    {
                                        $listeUsersSmiley = '';

                                        foreach ($votesParSmiley as $vote)
                                        {
                                            $listeUsersSmiley .= formatUnknownUser($vote, true, false) . ', ';
                                        }

                                        echo '<span class="noms_votes_' . $keySmiley . '">';
                                            echo substr($listeUsersSmiley, 0, -2);
                                        echo '</span>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

                /***************************/
                /* Caché pour modification */
                /***************************/
                echo '<div class="zone_collector zone_collector_update" id="modifier_collector_' . $collector->getId() . '" style="display: none;">';
                    echo '<form method="post" action="collector.php?action=doModifierCollector&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" enctype="multipart/form-data" class="zone_shadow">';
                        if ($collector->getNb_votes() >= $minGolden)
                            echo '<div class="zone_collector_haut_golden">';
                        else
                            echo '<div class="zone_collector_haut">';
                            echo '<input type="hidden" name="id_collector" value="' . $collector->getId() . '" />';

                            // Boutons d'action
                            echo '<div id="zone_bouton_validation_' . $collector->getId() . '" class="zone_bouton_validation">';
                                // Validation modification
                                echo '<input type="submit" name="update_collector" value="" title="Valider" id="bouton_validation_collector_' . $collector->getId() . '" class="icone_valider_collector" />';

                                // Annulation modification
                                echo '<a id="annuler_update_collector_' . $collector->getId() . '" title="Annuler" class="icone_annuler_collector annulerCollector"></a>';
                            echo '</div>';

                            // Avatar
                            $avatarFormatted = formatAvatar($collector->getAvatar_speaker(), $collector->getPseudo_speaker(), 2, 'avatar');

                            echo '<div class="zone_avatar_collector">';
                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_collector" />';
                            echo '</div>';

                            // Modification speaker
                            echo '<div class="zone_update_speaker">';
                                echo '<select name="speaker" id="speaker_' . $collector->getId() . '" class="update_speaker changeSpeaker" required>';
                                    echo '<option value="" hidden>Choisissez...</option>';

                                    foreach ($listeUsers as $identifiant => $user)
                                    {
                                        // Lors de la modification on affiche tout de même l'utilisateur d'une autre équipe si c'est le speaker
                                        if ($user['team'] == $_SESSION['user']['equipe'] OR $identifiant == $collector->getSpeaker())
                                        {
                                            if ($identifiant == $collector->getSpeaker())
                                                echo '<option value="' . $collector->getSpeaker() . '" selected>' . $user['pseudo'] . '</option>';
                                            else
                                                echo '<option value="' . $identifiant . '">' . $user['pseudo'] . '</option>';
                                        }
                                    }

                                    if ($collector->getType_speaker() == 'other')
                                        echo '<option value="other" selected>Autre</option>';
                                    else
                                        echo '<option value="other">Autre</option>';
                                echo '</select>';
                            echo '</div>';

                            // Modification "Autre"
                            if ($collector->getType_speaker() == 'other')
                                echo '<input type="text" name="other_speaker" value="' . $collector->getPseudo_speaker() . '" placeholder="Nom" maxlength="255" id="other_speaker_' . $collector->getId() . '" class="update_other_speaker" />';
                            else
                                echo '<input type="text" name="other_speaker" placeholder="Nom" maxlength="255" id="other_speaker_' . $collector->getId() . '" class="update_other_speaker" style="display: none;" />';

                            // Modification date
                            echo '<div class="zone_update_date">';
                                echo '<input type="text" name="date_collector" value="' . formatDateForDisplay($collector->getDate_collector()) . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker[' . $collector->getId() . ']" class="update_date_collector" required />';
                            echo '</div>';
                        echo '</div>';

                        echo '<div class="zone_collector_bas">';
                            if ($collector->getType_collector() == 'I')
                            {
                                // Type de saisie
                                echo '<input type="hidden" name="type_collector" value="I" />';

                                // Image
                                echo '<div>';
                                    echo '<div class="zone_parcourir_update" id="zone_parcourir_' . $collector->getId() . '">';
                                        echo '<input id="fichier_' . $collector->getId() . '" type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_update loadModifierCollector" />';
                                    echo '</div>';

                                    echo '<div class="mask_update" id="mask_collector_' . $collector->getId() . '">';
                                        echo '<img src="../../includes/images/collector/' . $collector->getCollector() . '" id="image_collector_' . $collector->getId() . '" alt="' . $collector->getCollector() . '" class="image_update loadImage" />';
                                    echo '</div>';
                                echo '</div>';
                            }
                            else
                            {
                                // Type de saisie
                                echo '<input type="hidden" name="type_collector" value="T" />';

                                // Apostrophe gauche
                                echo '<img src="../../includes/icons/collector/quote_1.png" alt="quote_1" class="quote_1" />';

                                // Modification citation
                                echo '<textarea name="collector" placeholder="Phrase culte" class="update_text_collector">' . $collector->getCollector() . '</textarea>';

                                // Apostrophe droite
                                echo '<img src="../../includes/icons/collector/quote_2.png" alt="quote_2" class="quote_2" />';
                            }

                            // Contexte
                            if ($collector->getNb_votes() >= $minGolden)
                                echo '<div class="text_context_golden_update">';
                            else
                                echo '<div class="text_context_update">';
                                echo '<textarea name="context" placeholder="Contexte (facultatif)" class="update_context_collector">' . $collector->getContext() . '</textarea>';
                            echo '</div>';
                        echo '</div>';
                    echo '</form>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Aucune phrase ou image culte dans cette catégorie...</div>';
    echo '</div>';
?>