<?php
    if ($_GET['action'] == 'goAjouter')
        echo '<form method="post" action="missions.php?action=doAjouter" enctype="multipart/form-data" class="form_saisie_mission">';
    else
    {
        echo '<form method="post" action="missions.php?action=doModifier" enctype="multipart/form-data" class="form_saisie_mission_terminee">';
            echo '<input type="hidden" name="id_mission" value="' . $detailsMission->getId() . '" />';
    }

        // Taille maximale images
        echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

        // Titre
        echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" />';
            echo '<input type="text" value="' . $detailsMission->getMission() . '" name="mission" placeholder="Titre de la mission" maxlength="255" class="saisie_titre_mission" required />';
        echo '</div>';

        // Bannière
        echo '<div class="zone_saisie_image_mission">';
            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';
            echo '<div class="info_image_mission">Bannière (1920 x 800 px)</div>';

            if ($_GET['action'] == 'goAjouter')
            {
                echo '<input type="file" accept=".png" name="mission_image" class="bouton_parcourir_banniere_mission loadBanner" required />';
                echo '<img id="banner" alt="" class="preview_image_mission" />';
            }
            else
            {
                echo '<input type="file" accept=".png" name="mission_image" class="bouton_parcourir_banniere_mission loadBanner" />';
                echo '<img src="../../includes/images/missions/banners/' . $detailsMission->getReference() . '.png" id="banner" alt="' . $detailsMission->getReference() . '" class="preview_image_mission" />';
            }
        echo '</div>';

        // Icône gauche
        echo '<div class="zone_saisie_icone_mission">';
            echo '<div class="saisie_icone_mission_gauche">';
                echo '<div class="info_icone_mission">Icône gauche (500 x 500 px)</div>';

                if ($_GET['action'] == 'goAjouter')
                {
                    echo '<input type="file" accept=".png" name="mission_icone_g" class="bouton_parcourir_icone_mission loadLeft" required />';
                    echo '<img id="button_g" alt="" class="preview_icone_mission" />';
                }
                else
                {
                    echo '<input type="file" accept=".png" name="mission_icone_g" class="bouton_parcourir_icone_mission loadLeft" />';
                    echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_g.png" alt="' . $detailsMission->getReference() . '_g" id="button_g" class="preview_icone_mission" />';
                }
            echo '</div>';
        echo '</div>';

        // Icône milieu
        echo '<div class="zone_saisie_icone_mission">';
            echo '<div class="saisie_icone_mission_milieu">';
                echo '<div class="info_icone_mission">Icône milieu (500 x 500 px)</div>';

                if ($_GET['action'] == 'goAjouter')
                {
                    echo '<input type="file" accept=".png" name="mission_icone_m" class="bouton_parcourir_icone_mission loadMiddle" required />';
                    echo '<img id="button_m" alt="" class="preview_icone_mission" />';
                }
                else
                {
                    echo '<input type="file" accept=".png" name="mission_icone_m" class="bouton_parcourir_icone_mission loadMiddle" />';
                    echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_m.png" alt="' . $detailsMission->getReference() . '_m" id="button_m" class="preview_icone_mission" />';
                }
            echo '</div>';
        echo '</div>';

        // Icône droite
        echo '<div class="zone_saisie_icone_mission">';
            echo '<div class="saisie_icone_mission_droite">';
                echo '<div class="info_icone_mission">Icône droite (500 x 500 px)</div>';

                if ($_GET['action'] == 'goAjouter')
                {
                    echo '<input type="file" accept=".png" name="mission_icone_d" class="bouton_parcourir_icone_mission loadRight" required />';
                    echo '<img id="button_d" alt="" class="preview_icone_mission" />';
                }
                else
                {
                    echo '<input type="file" accept=".png" name="mission_icone_d" class="bouton_parcourir_icone_mission loadRight" />';
                    echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_d.png" alt="' . $detailsMission->getReference() . '_d" id="button_d" class="preview_icone_mission" />';
                }
            echo '</div>';
        echo '</div>';

        // Informations
        echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/missions/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Informations</div>';
        echo '</div>';

        echo '<div class="zone_saisie_informations_mission">';
            // Référence
            if ($_GET['action'] == 'goAjouter')
                echo '<input type="text" placeholder="Référence" name="reference" value="' . $detailsMission->getReference() . '" maxlength="255" class="saisie_information_mission margin_right_10" required />';
            else
            {
                echo '<input type="hidden" name="reference" value="' . $detailsMission->getReference() . '" />';
                echo '<div class="reference_mission">' . $detailsMission->getReference() . '</div>';
            }

            // Objectif
            echo '<input type="text" placeholder="Objectif" name="objectif" value="' . $detailsMission->getObjectif() . '" class="saisie_information_mission" required />';

            // Dates
            echo '<div class="texte_saisie_date_mission">Du</div>';
            echo '<input type="text" name="date_deb" value="' . formatDateForDisplay($detailsMission->getDate_deb()) . '" placeholder="Date de début" maxlength="10" autocomplete="off" id="datepicker_saisie_deb" class="saisie_date_mission" required />';

            echo '<div class="texte_saisie_date_mission">au</div>';
            echo '<input type="text" name="date_fin" value="' . formatDateForDisplay($detailsMission->getDate_fin()) . '" placeholder="Date de fin" maxlength="10" autocomplete="off" id="datepicker_saisie_fin" class="saisie_date_mission" required />';

            // Heure
            echo '<div class="texte_saisie_heure_mission">à partir de</div>';
            
            echo '<select name="heures" class="select_heure_mission" required>';
                if (empty($detailsMission->getHeure()))
                    echo '<option value="" disabled selected hidden>hh</option>';
                else
                    echo '<option value="" disabled hidden>hh</option>';

                for ($i = 0; $i <= 23; $i++)
                {
                    if (!empty($detailsMission->getHeure()) AND substr($detailsMission->getHeure(), 0, 2) == $i)
                    {
                        if ($i < 10)
                            echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
                        else
                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                    }
                    else
                    {
                        if (substr($detailsMission->getHeure(), 0, 2) == '  ')
                            echo '<option value="" disabled selected hidden>hh</option>';

                        if ($i < 10)
                            echo '<option value="0' . $i . '">0' . $i . '</option>';
                        else
                            echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                }
            echo '</select>';

            echo '<select name="minutes" class="select_heure_mission" required>';
                if (empty($detailsMission->getHeure()))
                    echo '<option value="" disabled selected hidden>mm</option>';
                else
                    echo '<option value="" disabled hidden>mm</option>';

                for ($i = 0; $i <= 11; $i++)
                {
                    if (!empty($detailsMission->getHeure()) AND (substr($detailsMission->getHeure(), 2, 2) / 5) == $i)
                    {
                        if ($i < 2)
                            echo '<option value="0' . 5 * $i . '" selected>0' . 5 * $i . '</option>';
                        else
                            echo '<option value="' . 5 * $i . '" selected>' . 5 * $i . '</option>';
                    }
                    else
                    {
                        if (substr($detailsMission->getHeure(), 2, 2) == '  ')
                            echo '<option value="" disabled selected hidden>mm</option>';

                        if ($i < 2)
                            echo '<option value="0' . 5 * $i . '">0' . 5 * $i . '</option>';
                        else
                            echo '<option value="' . 5 * $i . '">' . 5 * $i . '</option>';
                    }
                }
            echo '</select>';

            // Explications
            echo '<textarea name="explications" placeholder="Explications (utiliser %objectif%)" class="saisie_explications_mission margin_top_10" required>' . $detailsMission->getExplications() . '</textarea>';
        echo '</div>';

        // Description
        echo '<div class="zone_saisie_mission_gauche">';
            echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/missions/story_grey.png" alt="story_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Il était une fois...</div>';
            echo '</div>';

            echo '<div class="zone_saisie_informations_mission">';
                echo '<textarea placeholder="Description" name="description" class="saisie_description_mission" required>' . $detailsMission->getDescription() . '</textarea>';
            echo '</div>';
        echo '</div>';

        // Conclusion
        echo '<div class="zone_saisie_mission_droite">';
            echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/missions/end_grey.png" alt="end_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Le fin mot de l\'histoire</div>';
            echo '</div>';

            echo '<div class="zone_saisie_informations_mission">';
                echo '<textarea name="conclusion" placeholder="Conclusion (apparait en fin de mission)" class="saisie_conclusion_mission" required>' . $detailsMission->getConclusion() . '</textarea>';
            echo '</div>';
        echo '</div>';

        // Bouton ajout ou modification
        if ($_GET['action'] == 'goAjouter')
            echo '<input type="submit" name="create_mission" value="Créer la mission" class="bouton_saisie_gris" />';
        else
            echo '<input type="submit" name="update_mission" value="Modifier la mission" class="bouton_saisie_gris" />';
    echo '</form>';

    // Succès et classement (sur les missions existantes)
    if ($_GET['action'] == 'goModifier')
    {
        echo '<div class="zone_mission_droite">';
            // Succès associés
            echo '<div class="zone_succes_associes_mission">';
                echo '<div class="titre_section">';
                    echo '<img src="../../includes/icons/missions/success_grey.png" alt="success_grey" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section">Succès associés</div>';
                echo '</div>';

                if (!empty($succesMission))
                {
                    echo '<div class="zone_succes_mission">';
                        $i         = 0;
                        $keySucces = array_keys($succesMission);
                        $lastKey   = end($keySucces);

                        foreach ($succesMission as $succes)
                        {
                            // Logo succès
                            if ($i % 2 == 0)
                            {
                                if ($i == $lastKey)
                                    echo '<div class="succes_mission" title="' . $succes->getTitle() . '">';
                                else
                                    echo '<div class="succes_mission margin_right_10" title="' . $succes->getTitle() . '">';
                            }
                            else
                                echo '<div class="succes_mission margin_left_10" title="' . $succes->getTitle() . '">';

                                echo '<img src="../../includes/images/profil/success/' . $succes->getReference() . '.png" alt="' . $succes->getReference() . '" class="logo_succes_mission" />';
                            echo '</div>';

                            $i++;
                        }
                    echo '</div>';
                }
                else
                    echo '<div class="empty">Aucun succès associé à cette mission...</div>';
            echo '</div>';

            // Classement
            echo '<div class="zone_classement_mission">';
                echo '<div class="titre_section">';
                    echo '<img src="../../includes/icons/missions/podium_grey.png" alt="podium_grey" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section">Classement</div>';
                echo '</div>';

                if (!empty($listeParticipantsParEquipes))
                {
                    foreach ($listeParticipantsParEquipes as $equipe => $participantsParEquipes)
                    {
                        echo '<div class="zone_titre_equipe">';
                            echo '<img src="../../includes/icons/admin/users.png" alt="users" class="image_titre_equipe" />';
                            echo '<div class="texte_titre_equipe">' . $listeEquipesParticipants[$equipe]->getTeam() . '</div>';
                        echo '</div>';

                        foreach ($participantsParEquipes as $participant)
                        {
                            echo '<div class="classement_user">';
                                echo '<div class="rang_classement">' . $participant->getRank() . '</div>';
                                echo '<div class="pseudo_classement">' . formatUnknownUser($participant->getPseudo(), true, false) . '</div>';
                                echo '<div class="total_classement">' . $participant->getTotal() . '</div>';
                            echo '</div>';
                        }
                    }
                }
                else
                    echo '<div class="empty">Pas encore de participants...</div>';
            echo '</div>';
        echo '</div>';
    }
?>