<?php
    // Génération du calendrier au format HTML
    $entetesTableau = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
    $jourAAjouter   = 0;

    echo '<div class="zone_calendrier_generator_hidden">';
        echo '<div class="zone_calendrier_generator">';
            // Entête du calendrier
            echo '<div class="zone_entete_calendrier_generator">';
                // Mois
                $search  = array('é', 'û');
                $replace = array('e', 'u');
                $nomMois = mb_strtoupper(str_replace($search, $replace, $listeMois[$calendarParameters->getMonth()]));

                echo '<div class="mois_calendrier_generator">' . $nomMois . '</div>';
                echo '<div class="rift_mois_calendrier_generator"></div>';

                // Semaines et numéro du mois
                echo '<div class="zone_semaines_calendrier_generator">';
                    // Semaines
                    echo '<div class="semaines_calendrier_generator">Semaines ' . $donneesCalendrier['semaine_debut_mois'] . ' à ' . $donneesCalendrier['semaine_fin_mois'] . '</div>';

                    // Numéro du mois
                    echo '<div class="zone_numero_mois_calendrier_generator">';
                        echo '<img src="../../includes/icons/calendars/calendar_generator.png" alt="calendar_generator" class="image_mois_calendrier_generator" />';
                        echo '<div class="numero_mois_calendrier_generator">' . $calendarParameters->getMonth() . '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            // Corps du calendrier
            echo '<div class="zone_corps_calendrier_generator">';
                // Image de fond
                if (!empty($calendarParameters->getPicture()))
                    echo '<img src="../../includes/images/calendars/temp/trim_' . $calendarParameters->getPicture() . '" alt="trim_' . $calendarParameters->getPicture() . '" class="fond_corps_calendrier_generator" />';

                // Tableau des jours
                echo '<table class="table_calendrier_generator">';
                    // Jours de la semaine
                    echo '<tr class="ligne_jours_calendrier_generator">';
                        // Jours
                        for ($k = 0; $k < $donneesCalendrier['nombre_jours_par_ligne']; $k++)
                        {
                            echo '<td>' . $entetesTableau[$k] . '</td>';
                        }

                        // Gâteau
                        echo '<td><img src="../../includes/icons/calendars/cake_generator.png" alt="cake_generator" class="image_gateau_calendrier_generator" /></td>';
                    echo '</tr>';

                    // Numéros de chaque semaine
                    for ($i = 1; $i <= $donneesCalendrier['nombre_lignes_calendrier']; $i++)
                    {
                        // Affichage d'une ligne
                        echo '<tr class="ligne_calendrier_generator">';
                            // Jours
                            for ($j = 1; $j <= $donneesCalendrier['nombre_jours_par_ligne']; $j++) 
                            {
                                if (($i == 1                                              AND $j >= $donneesCalendrier['numero_premier_jour_a_afficher'])
                                OR  ($i == $donneesCalendrier['nombre_lignes_calendrier'] AND $j <= $donneesCalendrier['numero_dernier_jour_a_afficher'])
                                OR  ($i != 1                                              AND $i != $donneesCalendrier['nombre_lignes_calendrier']))
                                {
                                    // Calcul du numéro du jour à afficher
                                    $numeroJourAAfficher = $donneesCalendrier['numero_premier_jour_semaine'] + $jourAAjouter;

                                    // Détermination si jour férié
                                    if ($numeroJourAAfficher < 10)
                                        $jourFerie = isJourFerie($calendarParameters->getYear() . $calendarParameters->getMonth() . '0' . $numeroJourAAfficher);
                                    else
                                        $jourFerie = isJourFerie($calendarParameters->getYear() . $calendarParameters->getMonth() . $numeroJourAAfficher);

                                    // Détermination si vacances
                                    if ($numeroJourAAfficher < 10)
                                        $jourVacances = isVacances($calendarParameters->getYear() . $calendarParameters->getMonth() . '0' . $numeroJourAAfficher, $vacances, 'vacances_zone_b');
                                    else
                                        $jourVacances = isVacances($calendarParameters->getYear() . $calendarParameters->getMonth() . $numeroJourAAfficher, $vacances, 'vacances_zone_b');

                                    // Affichage du numéro de jour et du jour férié si besoin
                                    echo '<td class="jour_calendrier_generator">';
                                        // Numéro du jour
                                        if ($jourVacances == true)
                                        {
                                            if (!empty($calendarParameters->getPicture()))
                                                echo '<div class="numero_jour_calendrier_generator numero_jour_calendrier_generator_red_light">' . $numeroJourAAfficher . '</div>';
                                            else
                                                echo '<div class="numero_jour_calendrier_generator numero_jour_calendrier_generator_red">' . $numeroJourAAfficher . '</div>';
                                        }
                                        else
                                        {
                                            if (!empty($calendarParameters->getPicture()))
                                                echo '<div class="numero_jour_calendrier_generator numero_jour_calendrier_generator_grey_light">' . $numeroJourAAfficher . '</div>';
                                            else
                                                echo '<div class="numero_jour_calendrier_generator numero_jour_calendrier_generator_grey">' . $numeroJourAAfficher . '</div>';
                                        }

                                        // Jour férié
                                        if (!empty($jourFerie))
                                        {
                                            echo '<div class="zone_jour_ferie_calendrier_generator">';
                                                echo '<img src="../../includes/images/calendars/backgrounds/' . $jourFerie['reference'] . '.jpg" class="fond_jour_ferie"/>';
                                                echo '<div class="jour_ferie_calendrier_generator">' . mb_strtoupper($jourFerie['nom_jour']) . '</div>';
                                            echo '</div>';
                                        }
                                    echo '</td>';

                                    // Incrémentation du nombre de jours à ajouter pour la case suivante
                                    $jourAAjouter++;
                                }
                                else
                                    echo '<td class="jour_calendrier_generator"></td>';

                                // Gâteau
                                if ($j == $donneesCalendrier['nombre_jours_par_ligne'])
                                    echo '<td class="jour_calendrier_generator"></td>';
                            }
                        echo '</tr>';

                        // On ajoute 2 jours pour sauter le week-end
                        $jourAAjouter += 7 - $donneesCalendrier['nombre_jours_par_ligne'];
                    }
                echo '</table>';
            echo '</div>';

            // Pied du calendrier
            echo '<div class="zone_pied_calendrier_generator">';
                // Année
                echo '<div class="annee_calendrier_generator">' . $calendarParameters->getYear() . '</div>';

                // Signature
                echo '<div class="zone_signature_calendrier_generator">';
                    echo '<div class="triangle_1"></div>';
                    echo '<div class="triangle_2"></div>';

                    echo '<div class="triangle_1"></div>';
                    echo '<div class="signature_calendrier_generator"></div>';
                    echo '<div class="triangle_2"></div>';

                    echo '<div class="triangle_1"></div>';
                    echo '<div class="signature_calendrier_generator">';
                        echo '<div class="texte_signature_calendrier_generator">INSIDE<br />DESIGN</div>';
                    echo '</div>';
                    echo '<div class="triangle_2"></div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>