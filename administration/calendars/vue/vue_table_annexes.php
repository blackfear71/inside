<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/annexes_grey.png" alt="annexes_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression des annexes</div></div>';

    // Tableau des annexes à supprimer
    echo '<table class="table_admin">';
        // Entête du tableau
        echo '<tr>';
            echo '<td class="width_10">';
                echo 'Annexe';
            echo '</td>';

            echo '<td class="width_40">';
                echo 'Titre';
            echo '</td>';

            echo '<td class="width_30">';
                echo 'Equipe';
            echo '</td>';

            echo '<td class="width_20">';
                echo 'Actions';
            echo '</td>';
        echo '</tr>';

        // Contenu du tableau
        if (!empty($listeSuppressionAnnexes))
        {
            foreach ($listeSuppressionAnnexes as $annexes)
            {
                echo '<tr>';
                    echo '<td class="td_table_admin_premier">';
                        echo '<img src="../../includes/images/calendars/annexes/mini/' . $annexes->getAnnexe() . '" alt="annexe" title="' . $annexes->getTitle() . '" class="image_table_admin" />';
                    echo '</td>';

                    echo '<td class="td_table_admin_important_centre">';
                        echo $annexes->getTitle();
                    echo '</td>';

                    echo '<td class="td_table_admin_centre">';
                        echo $listeEquipes[$annexes->getTeam()]->getTeam() . ' (' . $annexes->getTeam() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_actions">';
                        echo '<form method="post" action="calendars.php?action=doSupprimerAnnexe" class="lien_action_table_admin">';
                            echo '<input type="hidden" name="id_annexe" value="' . $annexes->getId() . '" />';
                            echo '<input type="hidden" name="team_annexe" value="' . $annexes->getTeam() . '" />';
                            echo '<input type="submit" name="accepter_suppression_annexe" value="" title="Accepter" class="icone_valider_table_admin" />';
                        echo '</form>';

                        echo '<form method="post" action="calendars.php?action=doReinitialiserAnnexe" class="lien_action_table_admin">';
                            echo '<input type="hidden" name="id_annexe" value="' . $annexes->getId() . '" />';
                            echo '<input type="hidden" name="team_annexe" value="' . $annexes->getTeam() . '" />';
                            echo '<input type="submit" name="annuler_suppression_annexe" value="" title="Refuser" class="icone_annuler_table_admin" />';
                        echo '</form>';
                    echo '</td>';
                echo '</tr>';
            }
        }
        else
        {
            echo '<tr class="tr_table_admin_empty">';
                echo '<td colspan="4" class="empty">Pas d\'annexes à supprimer...</td>';
            echo '</tr>';
        }
    echo '</table>';
?>