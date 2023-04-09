<?php
    $lvl = 0;

    echo '<div class="zone_succes_admin" style="display: none;">';
        foreach ($listeSuccess as $keySuccess => $success)
        {
            if ($success->getLevel() != $lvl)
            {
                // Formatage du titre du niveau
                echo formatLevelTitle($success->getLevel());
                $lvl = $success->getLevel();

                // Définit une zone pour appliquer la Masonry
                echo '<div class="zone_niveau_succes_admin">';
            }

            /*********************************************/
            /* Visualisation normale (sans modification) */
            /*********************************************/
            echo '<div id="visualiser_succes_' . $success->getId() . '" class="zone_ensemble_succes">';
                echo '<div id="zone_shadow_' . $success->getId() . '" class="zone_shadow">';
                    // Modification
                    echo '<a id="modifier_' . $success->getId() . '" title="Modifier" class="icone_modifier_succes modifierSucces"></a>';

                    // Suppression
                    echo '<form method="post" id="delete_success_' . $success->getId() . '" action="success.php?action=doSupprimerSucces" class="form_suppression_succes">';
                        echo '<input type="hidden" name="id_success" value="' . $success->getId() . '" />';
                        echo '<input type="submit" name="delete_success" value="" title="Supprimer le succès" class="icone_supprimer_succes eventConfirm" />';
                        echo '<input type="hidden" value="Supprimer le succès &quot;' . formatOnclick($success->getTitle()) . '&quot; ?" class="eventMessage" />';
                    echo '</form>';

                    if ($success->getDefined() == 'Y')
                        echo '<div class="succes_liste_defini" id="' . $success->getId() . '">';
                    else
                        echo '<div class="succes_liste_non_defini" id="' . $success->getId() . '">';

                        echo '<div class="zone_succes_gauche">';
                            // Logo succès
                            echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes" />';

                            // Titre succès
                            echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

                            // Description succès
                            echo '<div class="description_succes">' . $success->getDescription() . '</div>';
                        echo '</div>';

                        echo '<div class="zone_succes_droite">';
                            if ($success->getDefined() == 'N')
                                $classeCodeDefini = 'code_defini_succes';
                            else
                                $classeCodeDefini = '';

                            // Niveau
                            echo '<div class="titre_succes">Niveau : <div class="niveau_ordre_succes">' . $success->getLevel() . '</div></div>';

                            // Ordre
                            echo '<div class="titre_succes">Ordre : <div class="niveau_ordre_succes">' . $success->getOrder_success() . '</div></div>';

                            // Condition
                            echo '<div class="titre_succes">Condition : <div class="condition_succes ' . $classeCodeDefini . '">' . formatNumericForDisplay($success->getLimit_success()) . '</div></div>';

                            // Mission liée
                            $nomMission = $success->getMission();

                            if (!empty($success->getMission()))
                            {
                                foreach ($listeMissions as $mission)
                                {
                                    if ($mission->getReference() == $success->getMission())
                                    {
                                        $nomMission = $mission->getMission();
                                        break;
                                    }
                                }

                                echo '<div class="titre_succes">Mission liée : <div class="mission_succes ' . $classeCodeDefini . '">' . $nomMission . '</div></div>';
                            }
                            else
                                echo '<div class="titre_succes">Mission liée : <div class="mission_succes ' . $classeCodeDefini . '">Aucune</div></div>';

                            // Unicité
                            if ($success->getUnicity() == 'Y')
                                echo '<div class="titre_succes">Unicité : <div class="condition_succes ' . $classeCodeDefini . '">Oui</div></div>';
                            else
                                echo '<div class="titre_succes">Unicité : <div class="condition_succes ' . $classeCodeDefini . '">Non</div></div>';

                            // Code défini
                            if ($success->getDefined() == 'Y')
                                echo '<div class="titre_succes">Code défini : <div class="condition_succes ' . $classeCodeDefini . '">Oui</div></div>';
                            else
                                echo '<div class="titre_succes">Code défini : <div class="condition_succes ' . $classeCodeDefini . '">Non</div></div>';
                        echo '</div>';

                        echo '<div class="zone_succes_bas">';
                            // Explications succès
                            if ($success->getDefined() == 'Y')
                                echo '<div class="explications_succes_defini">' . formatExplanation($success->getExplanation(), formatNumericForDisplay($success->getLimit_success()), '%limit%') . '</div>';
                            else
                                echo '<div class="explications_succes_non_defini">' . formatExplanation($success->getExplanation(), formatNumericForDisplay($success->getLimit_success()), '%limit%') . '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            /***************************/
            /* Caché pour modification */
            /***************************/
            echo '<div id="modifier_succes_' . $success->getId() . '" class="zone_ensemble_succes" style="display: none;">';
                echo '<form method="post" action="success.php?action=doModifierSucces">';
                    // Valider
                    echo '<input type="submit" name="update_success" value="" title="Valider" class="icone_valider_succes" />';

                    // Annuler
                    echo '<a id="annuler_modifier_succes_' . $success->getId() . '" title="Modifier" class="icone_annuler_succes annulerSucces"></a>';

                    // Id succès (caché)
                    echo '<input type="hidden" name="id_succes" value="' . $success->getId() . '" />';

                    // Référence succès (caché)
                    echo '<input type="hidden" name="reference_succes" value="' . $success->getReference() . '" />';

                    // Saisies
                    if ($success->getDefined() == 'Y')
                        echo '<div class="succes_liste_defini">';
                    else
                        echo '<div class="succes_liste_non_defini">';

                        echo '<div class="zone_succes_gauche">';
                            // Logo succès
                            echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes" />';

                            // Référence
                            echo '<div class="reference_succes">Ref. ' . $success->getReference() . '</div>';

                            // Titre succès
                            echo '<div class="titre_succes">Titre :</div>';
                            echo '<input type="text" value="' . $success->getTitle() . '" name="titre_succes" class="saisie_modification_succes" required />';

                            // Description succès
                            echo '<div class="titre_succes">Description :</div>';
                            echo '<textarea name="description_succes" class="textarea_modification_succes" required>' . $success->getDescription() . '</textarea>';
                        echo '</div>';

                        echo '<div class="zone_succes_droite">';
                            // Niveau
                            echo '<div class="titre_succes">Niveau :</div>';
                            echo '<input type="text" value="' . $success->getLevel() . '" name="niveau_succes" maxlength="4" class="saisie_modification_succes" required />';

                            // Ordonnancement
                            echo '<div class="titre_succes">Ordre :</div>';
                            echo '<input type="text" value="' . $success->getOrder_success() . '" name="ordre_succes" maxlength="3" class="saisie_modification_succes" required />';
                        
                            // Condition succès
                            echo '<div class="titre_succes">Condition :</div>';
                            echo '<input type="text" value="' . formatNumericForDisplay($success->getLimit_success()) . '" name="limite_succes" maxlength="10" class="saisie_modification_succes" required />';

                            // Mission liée
                            echo '<div class="titre_succes">Mission liée :</div>';
                            echo '<select name="mission_succes" class="select_modification_succes">';
                                // Choix par défaut
                                if (empty($success->getMission()))
                                    echo '<option value="" selected>Aucune mission liée</option>';
                                else
                                    echo '<option value="">Aucune mission liée</option>';

                                // Liste des missions
                                echo '<optgroup label="Missions non terminées">';
                                    $indicateurMissionsTerminees = false;

                                    foreach ($listeMissions as $mission)
                                    {
                                        if ($indicateurMissionsTerminees == false AND $mission->getDate_fin() < date('Ymd'))
                                        {
                                            echo '</optgroup>';
                                            echo '<optgroup label="Missions terminées">';

                                            $indicateurMissionsTerminees = true;
                                        }

                                        if (!empty($success->getMission()) AND $success->getMission() == $mission->getReference())
                                            echo '<option value="' . $mission->getReference() . '" selected>' . $mission->getMission() . '</option>';
                                        else
                                            echo '<option value="' . $mission->getReference() . '">' . $mission->getMission() . '</option>';
                                    }
                                echo '</optgroup>';
                            echo '</select>';

                            // Unicité
                            echo '<div class="titre_succes">Unique :</div>';
                            echo '<div class="label_radio_succes">';
                                if ($success->getUnicity() == 'Y')
                                {
                                    echo '<input type="radio" name="unicite_succes" value="Y" checked /><div class="marge_radio_succes">Oui</div>';
                                    echo '<input type="radio" name="unicite_succes" value="N" />Non';
                                }
                                else
                                {
                                    echo '<input type="radio" name="unicite_succes" value="Y" /><div class="marge_radio_succes">Oui</div>';
                                    echo '<input type="radio" name="unicite_succes" value="N" checked />Non';
                                }
                            echo '</div>';
                                                            
                            // Code défini
                            echo '<div class="titre_succes">Code défini :</div>';
                            echo '<div class="label_radio_succes">';
                                if ($success->getDefined() == 'Y')
                                {
                                    echo '<input type="radio" name="code_succes" value="Y" checked /><div class="marge_radio_succes">Oui</div>';
                                    echo '<input type="radio" name="code_succes" value="N" />Non';
                                }
                                else
                                {
                                    echo '<input type="radio" name="code_succes" value="Y" /><div class="marge_radio_succes">Oui</div>';
                                    echo '<input type="radio" name="code_succes" value="N" checked />Non';
                                }
                            echo '</div>';
                        echo '</div>';

                        echo '<div class="zone_succes_bas">';
                            // Explications
                            echo '<div class="titre_succes">Explications :</div>';
                            echo '<textarea name="explications_succes" class="textarea_modification_succes_2" required>' . $success->getExplanation() . '</textarea>';
                        echo '</div>';
                    echo '</div>';
                echo '</form>';
            echo '</div>';

            if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
            {
                // Termine la zone Masonry du niveau
                echo '</div>';
            }
        }
    echo '</div>';
?>