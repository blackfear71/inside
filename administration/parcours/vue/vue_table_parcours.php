<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/parcours_grey.png" alt="parcours_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression de parcours</div></div>';

    // Tableau des parcours à supprimer
    echo '<table class="table_admin">';
        // Entête du tableau
        echo '<tr>';
            echo '<td class="width_20">';
                echo 'Parcours';
            echo '</td>';

            echo '<td class="width_10">';
                echo 'Lieu';
            echo '</td>';

            echo '<td class="width_10">';
                echo 'Equipe';
            echo '</td>';

            echo '<td class="width_15">';
                echo 'Demande par';
            echo '</td>';

            echo '<td class="width_15">';
                echo 'Ajouté par';
            echo '</td>';

            echo '<td class="width_10">';
                echo 'Participations';
            echo '</td>';

            echo '<td class="width_20">';
                echo 'Actions';
            echo '</td>';
        echo '</tr>';

        // Contenu du tableau
        if (!empty($listeSuppression))
        {
            foreach ($listeSuppression as $parcours)
            {
                echo '<tr>';
                    echo '<td class="td_table_admin_premier">';
                        echo $parcours->getName();
                    echo '</td>';

                    echo '<td class="td_table_admin_important_centre">';
                        echo $parcours->getLocation();
                    echo '</td>';

                    echo '<td class="td_table_admin_centre">';
                        echo $listeEquipes[$parcours->getTeam()]->getTeam() . ' (' . $parcours->getTeam() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_normal">';
                        echo formatString(formatUnknownUser($parcours->getPseudo_del(), true, true), 50) . ' (' . $parcours->getIdentifiant_del() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_normal">';
                        echo formatString(formatUnknownUser($parcours->getPseudo_add(), true, true), 50) . ' (' . $parcours->getIdentifiant_add() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_centre">';
                        echo $parcours->getRuns();
                    echo '</td>';

                    echo '<td class="td_table_admin_actions">';
                        echo '<form method="post" action="parcours.php?action=doSupprimerParcours" class="lien_action_table_admin">';
                            echo '<input type="hidden" name="id_parcours" value="' . $parcours->getId() . '" />';
                            echo '<input type="hidden" name="team_parcours" value="' . $parcours->getTeam() . '" />';
                            echo '<input type="submit" name="accepter_suppression_parcours" value="" title="Accepter" class="icone_valider_table_admin" />';
                        echo '</form>';

                        echo '<form method="post" action="parcours.php?action=doReinitialiserParcours" class="lien_action_table_admin">';
                            echo '<input type="hidden" name="id_parcours" value="' . $parcours->getId() . '" />';
                            echo '<input type="hidden" name="team_parcours" value="' . $parcours->getTeam() . '" />';
                            echo '<input type="submit" name="annuler_suppression_parcours" value="" title="Refuser" class="icone_annuler_table_admin" />';
                        echo '</form>';
                    echo '</td>';
                echo '</tr>';
            }
        }
        else
        {
            echo '<tr class="tr_table_admin_empty">';
                echo '<td colspan="7" class="empty">Pas de parcours à supprimer...</td>';
            echo '</tr>';
        }
    echo '</table>';
?>