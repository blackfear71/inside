<?php
  if (!empty($tabMissions))
  {
    $titre_en_cours = false;
    $titre_a_venir  = false;
    $titre_passees  = false;

    echo '<div class="zone_missions">';
      foreach ($tabMissions as $keyMission => $ligneMission)
      {
        // Missions futures
        if ($ligneMission->getStatut() == "V")
        {
          if ($titre_a_venir != true)
          {
            echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" /><div class="texte_titre_section">Missions à venir</div></div>';
            $titre_a_venir = true;

            // Définit une zone pour appliquer la Masonry
            echo '<div class="zone_missions_accueil">';
          }

          echo '<div class="zone_mission_accueil">';
            echo '<a href="missions.php?id_mission=' . $ligneMission->getId() . '&action=goModifier">';
              echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
            echo '</a>';

            echo '<div class="titre_mission">' . $ligneMission->getMission() . ' - du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' au ' . formatDateForDisplay($ligneMission->getDate_fin()) . '</div>';

            echo '<form id="delete_mission_' . $ligneMission->getId() . '" method="post" action="missions.php?action=doSupprimer" class="form_suppression_mission">';
              echo '<input type="hidden" name="id_mission" value="' . $ligneMission->getId() . '" />';
              echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" class="bouton_delete_mission eventConfirm" />';
              echo '<input type="hidden" value="Supprimer la mission &quot;' . $ligneMission->getMission() . '&quot; ?" class="eventMessage" />';
            echo '</form>';
          echo '</div>';
        }
        // Missions en cours
        elseif ($ligneMission->getStatut() == "C")
        {
          if ($titre_en_cours != true)
          {
            echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Missions en cours</div></div>';
            $titre_en_cours = true;

            // Définit une zone pour appliquer la Masonry
            echo '<div class="zone_missions_accueil">';
          }

          echo '<div class="zone_mission_accueil">';
            echo '<a href="missions.php?id_mission=' . $ligneMission->getId() . '&action=goModifier">';
              echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
            echo '</a>';

            echo '<div class="titre_mission">' . $ligneMission->getMission() . ' - du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' au ' . formatDateForDisplay($ligneMission->getDate_fin()) . '</div>';

            echo '<form id="delete_mission_' . $ligneMission->getId() . '" method="post" action="missions.php?action=doSupprimer" class="form_suppression_mission">';
              echo '<input type="hidden" name="id_mission" value="' . $ligneMission->getId() . '" />';
              echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" class="bouton_delete_mission eventConfirm" />';
              echo '<input type="hidden" value="Supprimer la mission &quot;' . $ligneMission->getMission() . '&quot; ?" class="eventMessage" />';
            echo '</form>';
          echo '</div>';
        }
        // Missions précédentes
        elseif ($ligneMission->getStatut() == "A")
        {
          if ($titre_passees != true)
          {
            echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_ended.png" alt="missions_ended" class="logo_titre_section" /><div class="texte_titre_section">Anciennes missions</div></div>';
            $titre_passees = true;

            // Définit une zone pour appliquer la Masonry
            echo '<div class="zone_missions_accueil">';
          }

          echo '<div class="zone_mission_accueil">';
            echo '<a href="missions.php?id_mission=' . $ligneMission->getId() . '&action=goModifier">';
              echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
            echo '</a>';

            echo '<div class="titre_mission">' . $ligneMission->getMission() . ' - du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' au ' . formatDateForDisplay($ligneMission->getDate_fin()) . '</div>';

            echo '<form id="delete_mission_' . $ligneMission->getId() . '" method="post" action="missions.php?action=doSupprimer" class="form_suppression_mission">';
              echo '<input type="hidden" name="id_mission" value="' . $ligneMission->getId() . '" />';
              echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" class="bouton_delete_mission eventConfirm" />';
              echo '<input type="hidden" value="Supprimer la mission &quot;' . $ligneMission->getMission() . '&quot; ?" class="eventMessage" />';
            echo '</form>';
          echo '</div>';
        }

        if (!isset($tabMissions[$keyMission + 1])
        OR  $ligneMission->getStatut() != $tabMissions[$keyMission + 1]->getStatut())
        {
          // Termine la zone Masonry du niveau
          echo '</div>';
        }
      }
    echo '</div>';
  }
  else
  {
    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Rien à signaler</div></div>';
    echo '<div class="empty">Pas encore de missions...</div>';
  }
?>
