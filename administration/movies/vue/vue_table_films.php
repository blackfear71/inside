<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression de films</div></div>';

    // Tableau des films à supprimer
    echo '<table class="table_admin">';
        // Entête du tableau
        echo '<tr>';
            echo '<td class="width_10">';
                echo 'Affiche';
            echo '</td>';

            echo '<td class="width_20">';
                echo 'Film';
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
                echo 'Personnes intéressées';
            echo '</td>';

            echo '<td class="width_20">';
                echo 'Actions';
            echo '</td>';
        echo '</tr>';

        // Contenu du tableau
        if (!empty($listeSuppression))
        {
            foreach ($listeSuppression as $film)
            {
                echo '<tr>';
                    echo '<td class="td_table_admin_premier">';
                        if (!empty($film->getPoster()))
                            echo '<img src="' . $film->getPoster() . '" alt="' . $film->getPoster() . '" title="' . $film->getFilm() . '" class="image_table_admin" />';
                    echo '</td>';

                    echo '<td class="td_table_admin_important_centre">';
                        echo $film->getFilm();
                    echo '</td>';

                    echo '<td class="td_table_admin_centre">';
                        echo $listeEquipes[$film->getTeam()]->getTeam() . ' (' . $film->getTeam() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_normal">';
                        echo formatString(formatUnknownUser($film->getPseudo_del(), true, true), 50) . ' (' . $film->getIdentifiant_del() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_normal">';
                        echo formatString(formatUnknownUser($film->getPseudo_add(), true, true), 50) . ' (' . $film->getIdentifiant_add() . ')';
                    echo '</td>';

                    echo '<td class="td_table_admin_centre">';
                        echo $film->getNb_users();
                    echo '</td>';

                    echo '<td class="td_table_admin_actions">';
                        echo '<form method="post" action="movies.php?action=doDeleteFilm" class="lien_action_table_admin">';
                            echo '<input type="hidden" name="id_film" value="' . $film->getId() . '" />';
                            echo '<input type="hidden" name="team_film" value="' . $film->getTeam() . '" />';
                            echo '<input type="submit" name="accepter_suppression_film" value="" title="Accepter" class="icone_valider_table_admin" />';
                        echo '</form>';

                        echo '<form method="post" action="movies.php?action=doResetFilm" class="lien_action_table_admin">';
                            echo '<input type="hidden" name="id_film" value="' . $film->getId() . '" />';
                            echo '<input type="hidden" name="team_film" value="' . $film->getTeam() . '" />';
                            echo '<input type="submit" name="annuler_suppression_film" value="" title="Refuser" class="icone_annuler_table_admin" />';
                        echo '</form>';
                    echo '</td>';
                echo '</tr>';
            }
        }
        else
        {
            echo '<tr class="tr_table_admin_empty">';
                echo '<td colspan="7" class="empty">Pas de films à supprimer...</td>';
            echo '</tr>';
        }
    echo '</table>';
?>