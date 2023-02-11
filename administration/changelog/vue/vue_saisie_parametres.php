<?php
    echo '<div class="zone_parametres_changelog">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/admin/settings_grey.png" alt="settings_grey" class="logo_titre_section" /><div class="texte_titre_section">Paramètres d\'édition</div></div>';

        echo '<form method="post" action="changelog.php?action=doGenerer">';
            // Action
            echo '<div class="zone_parameter_changelog">';
                echo '<div class="titre_parameter_changelog">Action</div>';

                // Ajouter
                if ($changeLogParameters->getAction() == 'A')
                {
                    echo '<div id="bouton_ajouter" class="switch_action_changelog bouton_checked">';
                        echo '<input id="ajouter" type="radio" name="action_changelog" value="A" checked required />';
                        echo '<label for="ajouter" class="label_radio">Ajouter un journal</label>';
                    echo '</div>';
                }
                else
                {
                    echo '<div id="bouton_ajouter" class="switch_action_changelog">';
                        echo '<input id="ajouter" type="radio" name="action_changelog" value="A" required />';
                        echo '<label for="ajouter" class="label_radio">Ajouter un journal</label>';
                    echo '</div>';
                }

                // Modifier
                if ($changeLogParameters->getAction() == 'M')
                {
                    echo '<div id="bouton_modifier" class="switch_action_changelog bouton_checked">';
                        echo '<input id="modifier" type="radio" name="action_changelog" value="M" checked required />';
                        echo '<label for="modifier" class="label_radio">Modifier un journal</label>';
                    echo '</div>';
                }
                else
                {
                    echo '<div id="bouton_modifier" class="switch_action_changelog">';
                        echo '<input id="modifier" type="radio" name="action_changelog" value="M" required />';
                        echo '<label for="modifier" class="label_radio">Modifier un journal</label>';
                    echo '</div>';
                }

                // Supprimer
                if ($changeLogParameters->getAction() == 'S')
                {
                    echo '<div id="bouton_supprimer" class="switch_action_changelog bouton_checked">';
                        echo '<input id="supprimer" type="radio" name="action_changelog" value="S" checked required />';
                        echo '<label for="supprimer" class="label_radio">Supprimer un journal</label>';
                    echo '</div>';
                }
                else
                {
                    echo '<div id="bouton_supprimer" class="switch_action_changelog">';
                        echo '<input id="supprimer" type="radio" name="action_changelog" value="S" required />';
                        echo '<label for="supprimer" class="label_radio">Supprimer un journal</label>';
                    echo '</div>';
                }
            echo '</div>';

            // Période
            echo '<div class="zone_parameter_changelog">';
                echo '<div class="titre_parameter_changelog">Période</div>';

                // Année
                echo '<div class="zone_listbox_changelog">';
                    echo '<div class="titre_listbox_changelox">Année : </div>';

                    echo '<select name="annee_changelog" class="listbox_periode" required>';
                        $anneeInitiale = intval(date('Y'));
                        $anneeFin      = 2017;

                        if (!empty($changeLogParameters->getYear()))
                        {
                            for ($i = $anneeInitiale; $i >= $anneeFin; $i--)
                            {
                                if ($i == $changeLogParameters->getYear())
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                else
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                        }
                        else
                        {
                            for ($i = $anneeInitiale; $i >= $anneeFin; $i--)
                            {
                                if ($i == $anneeInitiale)
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                else
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                        }
                    echo '</select>';
                echo '</div>';

                // Semaine
                echo '<div class="zone_listbox_changelog">';
                    echo '<div class="titre_listbox_changelox">Semaine : </div>';

                    echo '<select name="semaine_changelog" class="listbox_periode" required>';
                    $semaineInitiale = intval(date('W'));

                    if (!empty($changeLogParameters->getWeek()))
                    {
                        for ($j = 1; $j <= 53; $j++)
                        {
                            if ($j == $changeLogParameters->getWeek())
                                echo '<option value="' . $j . '" selected>' . $j . '</option>';
                            else
                                echo '<option value="' . $j . '">' . $j . '</option>';
                        }
                    }
                    else
                    {
                        for ($j = 1; $j <= 53; $j++)
                        {
                            if ($j == $semaineInitiale)
                                echo '<option value="' . $j . '" selected>' . $j . '</option>';
                            else
                                echo '<option value="' . $j . '">' . $j . '</option>';
                        }
                    }
                    echo '</select>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_parameter_changelog">';
                echo '<div class="titre_parameter_changelog">Générer le journal</div>';
                
                echo '<input type="submit" id="init_changelog" name="generate_changelog" value="Initialiser le journal" class="bouton_changelog" />';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>