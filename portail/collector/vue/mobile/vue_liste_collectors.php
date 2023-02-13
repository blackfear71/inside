<?php
    echo '<div class="zone_collectors">';
        if ($nombrePages > 0)
        {
            foreach ($listeCollectors as $collector)
            {
                echo '<div class="zone_collector">';
                    echo '<div id="zone_shadow_' . $collector->getId() . '" class="zone_shadow">';
                        if ($collector->getNb_votes() >= $minGolden)
                            echo '<div class="zone_collector_haut_golden" id="' . $collector->getId() . '">';
                        else
                            echo '<div class="zone_collector_haut" id="' . $collector->getId() . '">';

                            // Boutons d'action
                            echo '<div class="zone_boutons_actions">';
                                // Modification
                                echo '<a id="modifier_collector_' . $collector->getId() . '" title="Modifier" class="icone_update_collector modifierCollector"></a>';

                                // Suppression
                                if ($collector->getType_collector() == 'I')
                                {
                                    echo '<form id="delete_image_' . $collector->getId() . '" method="post" action="collector.php?action=doSupprimer&page=' . $_GET['page'] . '" class="form_delete_collector">';
                                        echo '<input type="hidden" name="id_collector" value="' . $collector->getId() . '" />';
                                        echo '<input type="hidden" name="team_collector" value="' . $collector->getTeam() . '" />';
                                        echo '<input type="submit" name="delete_collector" value="" title="Supprimer l\'image" class="icon_delete_collector eventConfirm" />';
                                        echo '<input type="hidden" value="Supprimer cette image ?" class="eventMessage" />';
                                    echo '</form>';
                                }
                                else
                                {
                                    echo '<form id="delete_collector_' . $collector->getId() . '" method="post" action="collector.php?action=doSupprimer&page=' . $_GET['page'] . '" class="form_delete_collector">';
                                        echo '<input type="hidden" name="id_collector" value="' . $collector->getId() . '" />';
                                        echo '<input type="hidden" name="team_collector" value="' . $collector->getTeam() . '" />';
                                        echo '<input type="submit" name="delete_collector" value="" title="Supprimer la phrase culte" class="icon_delete_collector eventConfirm" />';
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
                            echo '<a id="link_form_vote_' . $collector->getId() . '" class="link_current_vote afficherSaisieVote">';
                                echo '<img src="../../includes/icons/common/smileys/' . $collector->getVote_user() . '.png" alt="smiley" class="current_vote" />';
                            echo '</a>';

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
                                    echo '<img src="../../includes/images/collector/' . $collector->getCollector() . '" alt="' . $collector->getCollector() . '" class="image_collector" />';
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
                            echo '<a id="link_details_votes_' . $collector->getId() . '" class="zone_votes_users afficherDetailsVotes">';
                                // Smileys
                                foreach ($collector->getVotes() as $keySmiley => $votesParSmiley)
                                {
                                    echo '<img src="../../includes/icons/common/smileys/' . $keySmiley . '.png" alt="smiley" class="smiley_votes" />';
                                }
                            echo '</a>';
                        }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Aucune phrase ou image culte dans cette catégorie...</div>';
    echo '</div>';
?>