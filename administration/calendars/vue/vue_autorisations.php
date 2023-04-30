<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/download_grey.png" alt="download_grey" class="logo_titre_section" /><div class="texte_titre_section">Autorisations de gestion des calendriers</div></div>';

    if (!empty($listeAutorisationsParEquipe))
    {
        // Formulaire de gestion des autorisations
        echo '<form method="post" action="calendars.php?action=doModifierAutorisations">';
            echo '<div class="zone_autorisations_equipes">';
                foreach ($listeAutorisationsParEquipe as $referenceEquipe => $equipeAutorisations)
                {
                    echo '<div class="zone_autorisations_equipe">';
                        // Nom de l'équipe
                        echo '<div class="titre_autorisations_equipe">' . $listeEquipes[$referenceEquipe]->getTeam() . '</div>';

                        // Autorisations
                        echo '<div class="zone_autorisations_membres">';
                            foreach ($equipeAutorisations as $autorisation)
                            {
                                if ($autorisation->getManage_calendars() == 'Y')
                                {
                                    echo '<div id="bouton_autorisation_' . $autorisation->getIdentifiant() . '" class="switch_saisie_admin switch_checked">';
                                        echo '<input id="autorisation_' . $autorisation->getIdentifiant() . '" type="checkbox" name="autorization[' . $autorisation->getIdentifiant() . ']" checked />';
                                        echo '<label for="autorisation_' . $autorisation->getIdentifiant() . '" class="label_switch">' . formatString($autorisation->getPseudo(), 50) . '</label>';
                                    echo '</div>';
                                }
                                else
                                {
                                    echo '<div id="bouton_autorisation_' . $autorisation->getIdentifiant() . '" class="switch_saisie_admin">';
                                        echo '<input id="autorisation_' . $autorisation->getIdentifiant() . '" type="checkbox" name="autorization[' . $autorisation->getIdentifiant() . ']" />';
                                        echo '<label for="autorisation_' . $autorisation->getIdentifiant() . '" class="label_switch">' . formatString($autorisation->getPseudo(), 50) . '</label>';
                                    echo '</div>';
                                }
                            }
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';

            // Bouton validation
            echo '<input type="submit" name="saisie_autorisations" value="Mettre à jour" class="bouton_saisie_gris" />';
        echo '</form>';
    }
    else
    {
        echo '<div class="empty">Pas d\'utilisateurs existants...</div>';
    }
?>