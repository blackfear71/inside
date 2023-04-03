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



                    // TODO : afficher l'unité à côté de chaque champ de saisie (km, km/h, h min s, bpm)




                    // TODO : datepicker + gestion ajout mobile (format différent)





                    // Date
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/date_grey.png" alt="date_grey" title="Date" class="icone_saisie" />';
                        echo '<input type="text" name="date_participation" value="" placeholder="Date" class="saisie_ligne" required />';
                    echo '</div>';



                    // TODO : radio boutons classique (sélectionné par défaut) / compétition




                    // Compétition
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/cup_grey.png" alt="cup_grey" title="Compétition" class="icone_saisie" />';
                        echo '<input type="text" name="competition_participation" value="" placeholder="Compétition" class="saisie_ligne" required />';
                    echo '</div>';

                    // Distance
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" title="Distance" class="icone_saisie" />';
                        echo '<input type="text" name="distance_participation" value="" placeholder="Distance (km)" class="saisie_ligne" required />';
                    echo '</div>';

                    // Vitesse
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/speed_grey.png" alt="speed_grey" title="Vitesse" class="icone_saisie" />';
                        echo '<input type="text" name="vitesse_participation" value="" placeholder="Vitesse (km/h)" class="saisie_ligne" required />';
                    echo '</div>';



                    // TODO : séparer en 3 champs heures / minutes / secondes et convertir en secondes




                    // Temps
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/time_grey.png" alt="time_grey" title="Temps" class="icone_saisie" />';
                        echo '<input type="text" name="temps_participation" value="" placeholder="Temps (h/m/s)" class="saisie_ligne" required />';
                    echo '</div>';

                    // Cardio
                    echo '<div class="zone_saisie_ligne_participation">';
                        echo '<img src="../../includes/icons/petitspedestres/cardio_grey.png" alt="cardio_grey" title="Cardio" class="icone_saisie" />';
                        echo '<input type="text" name="cardio_participation" value="" placeholder="Cardio (bpm)" class="saisie_ligne" required />';
                    echo '</div>';

                    // Boutons d'action
                    echo '<div class="zone_bouton_saisie">';
                        echo '<input type="submit" name="insert_participation" value="Ajouter la participation" id="bouton_saisie_parcours" class="saisie_bouton" />';
                    echo '</div>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>