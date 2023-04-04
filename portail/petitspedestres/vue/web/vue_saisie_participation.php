<?php
    /************************************/
    /*** Zone de saisie participation ***/
    /************************************/
    echo '<div id="zone_saisie_participation" style="display: none;" class="fond_saisie_participation">';
        echo '<div class="zone_saisie_participation">';
            // Titre
            echo '<div class="titre_saisie_participation"></div>';

            // Bouton fermeture
            echo '<a id="annulerParticipation" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';
        
            // Saisie participation
            echo '<form method="post" action="petitspedestres.php?action=doAjouterParticipation" class="form_saisie_participation">';
                // Id parcours
                echo '<input type="hidden" name="id_parcours" value="" />';

                // Participation
                echo '<div class="zone_saisie_lignes_participation">';
                    // Date
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/date_grey.png" alt="date_grey" title="Date" class="icone_saisie_participation" />';
                        echo '<input type="text" name="date_participation" value="" placeholder="Date (jj//mm/aaaa)" maxlength="10" autocomplete="off" id="datepicker_parcours" class="saisie_ligne" required />';
                    echo '</div>';

                    // Compétition
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/cup_grey.png" alt="cup_grey" title="Compétition" class="icone_saisie_participation" />';

                        echo '<div class="zone_radio_saisie_participation">';
                            echo '<input id="competition_non" type="radio" name="competition_participation" value="N" class="radio_saisie_participation" checked required />';
                            echo '<label for="competition_non" class="label_saisie_participation">Classique</label>';
                        echo '</div>';

                        echo '<div class="zone_radio_saisie_participation">';
                            echo '<input id="competition_oui" type="radio" name="competition_participation" value="Y" class="radio_saisie_participation" required />';
                            echo '<label for="competition_oui" class="label_saisie_participation">Compétition</label>';
                        echo '</div>';
                    echo '</div>';

                    // Distance
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_saisie_participation" />';
                        echo '<input type="text" name="distance_participation" value="" placeholder="Distance" class="saisie_ligne_participation" maxlength="6" />';
                        echo '<div class="unite_saisie_participation">km</div>';
                    echo '</div>';

                    // Vitesse
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" title="Vitesse" class="icone_saisie_participation" />';
                        echo '<input type="text" name="vitesse_participation" value="" placeholder="Vitesse" class="saisie_ligne_participation" maxlength="5" />';
                        echo '<div class="unite_saisie_participation">km/h</div>';
                    echo '</div>';

                    // Temps
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/time_grey.png" alt="time_grey" title="Temps" class="icone_saisie_participation" />';
                        echo '<input type="text" name="heures_participation" value="" placeholder="Heures" class="saisie_temps_participation" maxlength="2" />';
                        echo '<div class="unite_saisie_participation">h</div>';
                        echo '<input type="text" name="minutes_participation" value="" placeholder="Minutes" class="saisie_temps_participation" maxlength="2" />';
                        echo '<div class="unite_saisie_participation">min</div>';
                        echo '<input type="text" name="secondes_participation" value="" placeholder="Secondes" class="saisie_temps_participation" maxlength="2" />';
                        echo '<div class="unite_saisie_participation">s</div>';
                    echo '</div>';

                    // Cardio
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/cardio_grey.png" alt="cardio_grey" title="Cardio" class="icone_saisie_participation" />';
                        echo '<input type="text" name="cardio_participation" value="" placeholder="Cardio" class="saisie_ligne_participation" maxlength="3" />';
                        echo '<div class="unite_saisie_participation">bpm</div>';
                    echo '</div>';

                    // Boutons d'action
                    echo '<div class="zone_bouton_saisie">';
                        echo '<input type="submit" name="insert_participation" value="Ajouter la participation" id="bouton_saisie_participation" class="saisie_bouton" />';
                    echo '</div>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>