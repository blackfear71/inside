<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/holidays_grey.png" alt="holidays_grey" class="logo_titre_section" /><div class="texte_titre_section">Créer une période de vacances pour le générateur de calendriers</div></div>';

    // Périodes disponibles
    echo '<div class="zone_periodes_vacances">';
        // Titre
        echo '<div class="titre_periodes_vacances">Périodes disponibles</div>';

        // Périodes
        if (!empty($periodesVacances))
        {
            foreach ($periodesVacances as $periodeVacances)
            {
                echo '<div class="periode_vacances">' . $periodeVacances . '</div>';
            }

            // Zone d'affichage des périodes
            echo '<div id="zone_affichage_periodes_vacances"></div>';
        }
        else
            echo '<div class="empty">Pas de périodes de vacances renseignées...</div>';

        // Titre
        echo '<div class="titre_periodes_vacances">Référence de mise à jour des dates</div>';

        // Lien dates officielles de vacances
        echo '<div class="zone_lien_periodes_vacances">';
            echo '<a href="https://www.education.gouv.fr/calendrier-scolaire-100148" target="_blank" class="lien_periodes_vacances">https://www.education.gouv.fr/calendrier-scolaire-100148</a>';
        echo '</div>';

        // Titre
        echo '<div class="titre_periodes_vacances">Ajouter une nouvelle période</div>';

        // Alerte si on est en Décembre et que le fichier de périodes de vacances scolaires n'xiste pas
        if (date('m') == 12 AND $periodesPresentes != true)
            echo '<div class="alerte_periodes_vacances">La prochaine période de vacances scolaires ' . date('Y') . '-' . (date('Y') + 1) . ' n\'a pas encore été saisie.</div>';

        // Formulaire de saisie des vacances scolaires
        echo '<form method="post" action="calendars.php?action=doInsererVacances">';
            // Tableau des vacances par zone
            echo '<table class="table_periodes_vacances">';
                // Zones géographiques
                echo '<tr>';
                    // Saisie de l'année
                    echo '<td class="annee_scolaire">';
                        echo 'Année scolaire';

                        echo '<select name="annee_vacances" class="select_annee_scolaire" required>';
                            echo '<option value="" hidden selected>Choisir...</option>';

                            for ($i = date('Y') + 1; $i >= 2016; $i--)
                            {
                                echo '<option value="' . $i . '">' . $i . '-' . ($i + 1) . '</option>';
                            }
                        echo '</select>';
                    echo '</td>';

                    // Titres des zones
                    echo '<td class="zone_a">Zone A</td>';
                    echo '<td class="zone_b">Zone B</td>';
                    echo '<td class="zone_c">Zone C</td>';
                echo '</tr>';

                // Affichage des saisies pour chaque vacances
                $dates = array('debut' => 'début', 'fin' => 'fin');
                $zones = array('zone_a', 'zone_b', 'zone_c');

                foreach ($saisiesVacances as $saisieVacances)
                {
                    foreach ($dates as $key => $date)
                    {
                        echo '<tr class="ligne_periodes_vacances">';
                        // Nom des vacances
                        if ($key == 'debut')
                            echo '<td rowspan="2">' . $saisieVacances['nom'] . '</td>';

                            // Saisies des dates par zones
                            foreach ($zones as $zone)
                            {
                                echo '<td>';
                                    // Jour
                                    if ($saisieVacances['required'] == true)
                                        echo '<select name="vacances[' . $saisieVacances['reference'] . '][' . $key . '][' . $zone . '][jour]" class="select_jour_periode_vacances" required>';
                                    else
                                        echo '<select name="vacances[' . $saisieVacances['reference'] . '][' . $key . '][' . $zone . '][jour]" class="select_jour_periode_vacances">';
                                        echo '<option value="" disabled selected hidden>Jour (' . $date . ')</option>';

                                        for ($j = 1; $j <= 31; $j++)
                                        {
                                            if ($j < 10)
                                                echo '<option value="0' . $j . '">0' . $j . '</option>';
                                            else
                                                echo '<option value="' . $j . '">' . $j . '</option>';
                                        }
                                    echo '</select>';

                                    // Mois
                                    if ($saisieVacances['required'] == true)
                                        echo '<select name="vacances[' . $saisieVacances['reference'] . '][' . $key . '][' . $zone . '][mois]" class="select_mois_periode_vacances" required>';
                                    else
                                        echo '<select name="vacances[' . $saisieVacances['reference'] . '][' . $key . '][' . $zone . '][mois]" class="select_mois_periode_vacances">';
                                        echo '<option value="" disabled selected hidden>Mois (' . $date . ')</option>';

                                        for ($j = 1; $j <= 12; $j++)
                                        {
                                            if ($j < 10)
                                                echo '<option value="0' . $j . '">' . $listeMois['0' . $j] . '</option>';
                                            else
                                                echo '<option value="' . $j . '">' . $listeMois[$j] . '</option>';
                                        }
                                    echo '</select>';
                                echo '</td>';
                            }
                        echo '</tr>';
                    }
                }
            echo '</table>';

            // Bouton validation
            echo '<input type="submit" name="saisie_vacances" value="Valider les périodes de vacances" class="bouton_saisie_blanc" />';
        echo '</form>';
    echo '</div>';
?>