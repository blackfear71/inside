<?php
  if (!empty($tabMissions))
  {
    $i              = 0;
    $titre_en_cours = false;
    $titre_a_venir  = false;
    $titre_passees  = false;

    echo '<div class="zone_missions">';
      foreach ($tabMissions as $ligneMission)
      {
        // Missions future
        if ($ligneMission->getStatut() == "V")
        {
          if ($titre_a_venir != true)
          {
            echo '<div class="titre_accueil_mission">Mission à venir</div>';
            $titre_a_venir = true;
          }

          echo '<div class="zone_presentation_mission">';
            echo '<a href="manage_missions.php?id_mission=' . $ligneMission->getId() . '&action=goModifier">';
              echo '<img src="../portail/missions/images/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
            echo '</a>';

            echo '<div class="titre_mission">' . $ligneMission->getMission() . ' - du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' au ' . formatDateForDisplay($ligneMission->getDate_fin()) . '</div>';

            echo '<form method="post" action="manage_missions.php?id_mission=' . $ligneMission->getId() . '&action=doSupprimer" class="form_suppression_mission">';
              echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" onclick="if(!confirm(\'Supprimer la mission &quot;' . $ligneMission->getMission() . '&quot; ?\')) return false;" class="bouton_delete_mission" />';
            echo '</form>';
          echo '</div>';
        }
        // Missions en cours
        elseif ($ligneMission->getStatut() == "C")
        {
          if ($titre_en_cours != true)
          {
            echo '<div class="titre_accueil_mission">Mission en cours</div>';
            $titre_en_cours = true;
          }

          echo '<div class="zone_presentation_mission">';
            echo '<a href="manage_missions.php?id_mission=' . $ligneMission->getId() . '&action=goModifier">';
              echo '<img src="../portail/missions/images/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
            echo '</a>';

            echo '<div class="titre_mission">' . $ligneMission->getMission() . ' - du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' au ' . formatDateForDisplay($ligneMission->getDate_fin()) . '</div>';

            echo '<form method="post" action="manage_missions.php?id_mission=' . $ligneMission->getId() . '&action=doSupprimer" class="form_suppression_mission">';
              echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" onclick="if(!confirm(\'Supprimer la mission &quot;' . $ligneMission->getMission() . '&quot; ?\')) return false;" class="bouton_delete_mission" />';
            echo '</form>';
          echo '</div>';
        }
        // Missions précédentes
        elseif ($ligneMission->getStatut() == "A")
        {
          if ($titre_passees != true)
          {
            echo '<div class="titre_accueil_mission">Anciennes missions</div>';
            $titre_passees = true;
          }

          echo '<div class="zone_presentation_mission">';
            echo '<a href="manage_missions.php?id_mission=' . $ligneMission->getId() . '&action=goModifier">';
              echo '<img src="../portail/missions/images/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
            echo '</a>';

            echo '<div class="titre_mission">' . $ligneMission->getMission() . ' - du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' au ' . formatDateForDisplay($ligneMission->getDate_fin()) . '</div>';

            echo '<form method="post" action="manage_missions.php?id_mission=' . $ligneMission->getId() . '&action=doSupprimer" class="form_suppression_mission">';
              echo '<input type="submit" name="delete_mission" value="" title="Supprimer la mission" onclick="if(!confirm(\'Supprimer la mission &quot;' . $ligneMission->getMission() . '&quot; ?\')) return false;" class="bouton_delete_mission" />';
            echo '</form>';
          echo '</div>';
        }

        $i++;
      }
    echo '</div>';
  }
  else
  {
    echo '<div class="titre_accueil_mission">Pas encore de missions !</div>';
  }
?>
