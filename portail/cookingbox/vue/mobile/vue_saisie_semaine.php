<?php
    echo '<div id="zone_saisie_semaine" class="fond_saisie">';
        echo '<div class="div_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie"></div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Saisie semaine
                    echo '<form method="post" action="" class="form_saisie_semaine">';
                        // Numéro de semaine
                        echo '<input type="hidden" name="week" value="" />';

                        // Utilisateurs
                        echo '<select name="select_user" class="listbox_users" required>';
                            echo '<option value="" hidden>Choisissez...</option>';

                            foreach ($listeCookers as $identifiant => $user)
                            {
                                echo '<option value="' . $identifiant . '">' . $user['pseudo'] . '</option>';
                            }
                        echo '</select>';

                        // Bouton validation
                        echo '<input type="submit" name="submit_week" value="Valider" class="lien_saisie_form" />';
                    echo '</form>';

                    // Suppression
                    echo '<form id="delete_week" method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doSupprimerSemaine" class="form_saisie_suppression">';
                        echo '<input type="hidden" name="week_cake" value="" />';
                        echo '<input type="hidden" name="year_cake" value="" />';
                        echo '<input type="submit" name="delete_cake" value="Supprimer" title="Supprimer" class="lien_saisie_form eventConfirm" />';
                        echo '<input type="hidden" name="message" value="" class="eventMessage" />';
                    echo '</form>';
                    
                    // Je l'ai fait
                    echo '<div class="cake_done">Le gâteau a été fait pour cette semaine !</div>';

                    echo '<form method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doValiderSemaine" class="form_saisie_realisation">';
                        echo '<input type="hidden" name="week_cake" value="" />';
                        echo '<input type="hidden" name="year_cake" value="" />';
                        echo '<input type="submit" name="validate_cake" value="Je l\'ai fait" class="lien_saisie_form" />';
                    echo '</form>';

                    // Annulation
                    echo '<form method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doAnnulerSemaine" class="form_saisie_annulation">';
                        echo '<input type="hidden" name="week_cake" value="" />';
                        echo '<input type="hidden" name="year_cake" value="" />';
                        echo '<input type="submit" name="cancel_cake" value="Annuler" class="lien_saisie_form" />';
                    echo '</form>';
                echo '</div>';
            echo '</div>';

            // Bouton fermeture
            echo '<div class="zone_boutons_saisie">';
                echo '<a id="fermerSaisieSemaine" class="bouton_saisie_fermer">Fermer</a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>