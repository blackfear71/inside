<?php
    /************************************/
    /*** Zone de saisie participation ***/
    /************************************/
    echo '<div id="zone_saisie_participation" class="fond_saisie">';
        echo '<form method="post" action="" class="form_saisie">';
            // Id parcours (ajout et modification)
            echo '<input type="hidden" name="id_parcours" value="" />';

            // Id participation (modification)
            echo '<input type="hidden" name="id_participation" value="" />';

            // Titre
            echo '<div class="zone_titre_saisie"></div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Titre (participation)
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/petitspedestres/proud_grey.png" alt="proud_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">Ma participation</div>';
                    echo '</div>';

                    // Saisies (participation)
                    echo '<div class="zone_saisie_lignes">';
                        // Date
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/date_grey.png" alt="parcours_grey" title="Nom du parcours" class="icone_saisie" />';
                            echo '<input type="date" name="date_participation" value="" placeholder="Date" maxlength="10" autocomplete="off" class="saisie_ligne" required />';
                        echo '</div>';

                        // Compétition
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/cup_grey.png" alt="cup_grey" title="Compétition" class="icone_saisie" />';

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
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_saisie" />';
                            echo '<input type="text" name="distance_participation" value="" placeholder="Distance" class="saisie_ligne_participation" maxlength="6" />';
                            echo '<div class="unite_saisie_participation">km</div>';
                        echo '</div>';

                        // Vitesse
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" title="Vitesse" class="icone_saisie" />';
                            echo '<input type="text" name="vitesse_participation" value="" placeholder="Vitesse" class="saisie_ligne_participation" maxlength="5" />';
                            echo '<div class="unite_saisie_participation">km/h</div>';
                        echo '</div>';

                        // Temps
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/time_grey.png" alt="time_grey" title="Temps" class="icone_saisie" />';
                            echo '<input type="text" name="heures_participation" value="" placeholder="Heures" class="saisie_temps_participation" maxlength="2" />';
                            echo '<div class="unite_saisie_participation">h</div>';
                            echo '<input type="text" name="minutes_participation" value="" placeholder="Minutes" class="saisie_temps_participation" maxlength="2" />';
                            echo '<div class="unite_saisie_participation">min</div>';
                            echo '<input type="text" name="secondes_participation" value="" placeholder="Secondes" class="saisie_temps_participation" maxlength="2" />';
                            echo '<div class="unite_saisie_participation">s</div>';
                        echo '</div>';

                        // Cardio
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/petitspedestres/cardio_grey.png" alt="cardio_grey" title="Cardio" class="icone_saisie" />';
                            echo '<input type="text" name="cardio_participation" value="" placeholder="Cardio" class="saisie_ligne_participation" maxlength="3" />';
                            echo '<div class="unite_saisie_participation">bpm</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_boutons_saisie">';
                // Valider
                echo '<input type="submit" name="insert_participation" value="Valider" id="validerSaisieParticipation" class="bouton_saisie_gauche" />';

                // Annuler
                echo '<a id="fermerSaisieParticipation" class="bouton_saisie_droite">Annuler</a>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>