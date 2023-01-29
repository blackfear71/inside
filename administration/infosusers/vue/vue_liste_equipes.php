<?php
    echo '<div class="zone_infos_equipes">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section" /><div class="texte_titre_section">Les équipes</div></div>';

        // Liste des équipes
        if (!empty($listeEquipes))
        {
            echo '<div class="zone_gestion_equipes">';
                foreach ($listeEquipes as $equipe)
                {
                    echo '<div class="zone_gestion_equipe">';
                        // Nom de l'équipe (sans modification)
                        echo '<div id="visualiser_equipe_' . $equipe->getId() . '" class="titre_gestion_equipe">' . $equipe->getTeam() . '</div>';

                        // Boutons d'action
                        echo '<div id="modifier_' . $equipe->getId() . '" class="zone_boutons_actions_equipe">';
                            // Modification
                            echo '<a title="Modifier" class="icone_modifier_equipe modifierEquipe"></a>';

                            // Suppression
                            if ($equipe->getNombre_users() == 0)
                            {
                                echo '<form id="delete_team_' . $equipe->getReference() . '" method="post" action="infosusers.php?action=doSupprimer">';
                                    echo '<input type="hidden" name="team" value="' . $equipe->getReference() . '" />';
                                    echo '<input type="submit" name="delete_team" value="" class="icone_supprimer_equipe eventConfirm" />';
                                    echo '<input type="hidden" value="Supprimer cette équipe ? Ceci supprime également toutes les données liées, il est conseillé de faire une sauvegarde avant de confirmer." class="eventMessage" />';
                                echo '</form>';
                            }
                        echo '</div>';

                        // Nom de l'équipe (en modification)
                        echo '<div id="modifier_equipe_' . $equipe->getId() . '" style="display: none;">';
                            echo '<form method="post" action="infosusers.php?action=doModifier">';
                                echo '<input type="hidden" name="reference" value="' . $equipe->getReference() . '" />';
                                echo '<input type="text" name="team" value="' . $equipe->getTeam() . '" class="saisie_titre_gestion_equipe" />';

                                // Boutons d'action
                                echo '<div class="zone_boutons_actions_equipe">';
                                    // Validation modification
                                    echo '<input type="submit" name="update_team" value="" title="Valider" class="icone_valider_equipe" />';

                                    // Annulation modification
                                    echo '<a id="annuler_' . $equipe->getId() . '" title="Annuler" class="icone_annuler_equipe annulerEquipe"></a>';
                                echo '</div>';
                            echo '</form>';
                        echo '</div>';

                        // Nombre de membres
                        echo '<div class="zone_membres_equipe">';
                            echo '<div class="nombre_membres_equipes">' . $equipe->getNombre_users() . '</div>';

                            if ($equipe->getNombre_users() == 1)
                                echo '<div class="texte_membres_equipe">membre</div>';
                            else
                                echo '<div class="texte_membres_equipe">membres</div>';
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';
        }
        else
        {
            echo '<div class="empty">Il n\'existe encore aucune équipe...</div>';
        }
    echo '</div>';
?>