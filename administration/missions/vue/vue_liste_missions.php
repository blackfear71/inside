<?php
    if (!empty($listeMissions))
    {
        $titreAVenir  = false;
        $titreEnCours = false;
        $titrePassees = false;

        echo '<div class="zone_missions">';
            foreach ($listeMissions as $keyMission => $mission)
            {
                // Missions futures
                if ($mission->getStatut() == 'V' AND $titreAVenir != true)
                {
                    // Titre
                    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" /><div class="texte_titre_section">Missions à venir</div></div>';
                    $titreAVenir = true;

                    // Définit une zone pour appliquer la Masonry
                    echo '<div class="zone_missions_accueil">';
                }
                // Missions en cours
                elseif ($mission->getStatut() == 'C' AND $titreEnCours != true)
                {
                    // Titre
                    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Missions en cours</div></div>';
                    $titreEnCours = true;

                    // Définit une zone pour appliquer la Masonry
                    echo '<div class="zone_missions_accueil">';
                }
                // Missions précédentes
                elseif ($mission->getStatut() == 'A' AND $titrePassees != true)
                {
                    // Titre
                    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_ended.png" alt="missions_ended" class="logo_titre_section" /><div class="texte_titre_section">Anciennes missions</div></div>';
                    $titrePassees = true;

                    // Définit une zone pour appliquer la Masonry
                    echo '<div class="zone_missions_accueil">';
                }

                // Affichage de la mission
                echo '<div class="zone_mission_accueil">';
                    echo '<a href="missions.php?id_mission=' . $mission->getId() . '&action=goModifier">';
                        echo '<img src="../../includes/images/missions/banners/' . $mission->getReference() . '.png" alt="' . $mission->getReference() . '" title="' . $mission->getMission() . '" class="img_mission" />';
                    echo '</a>';

                    if ($mission->getDate_deb() == $mission->getDate_fin())
                        echo '<div class="titre_mission">' . $mission->getMission() . ' - le ' . formatDateForDisplay($mission->getDate_deb()) . '</div>';
                    else
                        echo '<div class="titre_mission">' . $mission->getMission() . ' - du ' . formatDateForDisplay($mission->getDate_deb()) . ' au ' . formatDateForDisplay($mission->getDate_fin()) . '</div>';

                    echo '<form id="delete_mission_' . $mission->getId() . '" method="post" action="missions.php?action=doSupprimer" class="form_suppression_mission">';
                        echo '<input type="hidden" name="id_mission" value="' . $mission->getId() . '" />';
                        echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" class="bouton_delete_mission eventConfirm" />';
                        echo '<input type="hidden" value="Supprimer la mission &quot;' . $mission->getMission() . '&quot; ?" class="eventMessage" />';
                    echo '</form>';
                echo '</div>';

                // Termine la zone Masonry du niveau
                if (!isset($listeMissions[$keyMission + 1]) OR $mission->getStatut() != $listeMissions[$keyMission + 1]->getStatut())
                    echo '</div>';
            }
        echo '</div>';
    }
    else
    {
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Rien à signaler</div></div>';
        
        echo '<div class="empty">Pas encore de missions...</div>';
    }
?>