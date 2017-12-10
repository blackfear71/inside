<?php
  if (isset($messagesMissions) AND !empty($messagesMissions))
  {
    foreach ($messagesMissions as $keyMission => $mission)
    {
      // Association message mission à sa session
      foreach ($_SESSION['missions'] as $key_session => $ligneCurrentMission)
      {
        foreach ($ligneCurrentMission as $ligneMission)
        {
          if ($mission->getId() == $ligneMission['id_mission'])
          {
            $id_current_mission  = $ligneMission['id_mission'];
            $key_current_mission = $key_session;
          }
          break;
        }

        if (isset($id_current_mission) AND isset($key_current_mission))
          break;
      }

      // Affichage des messages
      echo '<div class="zone_resume_mission">';
        // Mission > 1 jour (heure OK)
        if (isset($id_current_mission) AND $mission->getId() == $id_current_mission AND isset($_SESSION['missions'][$key_current_mission]) AND !empty($_SESSION['missions'][$key_current_mission]) AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['missions'][$key_current_mission]);

          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            if (date('Ymd') == $mission->getDate_deb())
              echo 'La mission <strong>' . $mission->getMission() . '</strong> commence aujourd\'hui, trouve vite les objectifs avant les autres !<br />';

            if ($nbRestants == 1)
              echo 'Il reste encore ' . $nbRestants . ' objectif à trouver aujourd\'hui pour terminer la mission <strong>' . $mission->getMission() . '</strong>.';
            else
              echo 'Il reste encore ' . $nbRestants . ' objectifs à trouver aujourd\'hui pour terminer la mission <strong>' . $mission->getMission() . '</strong>.';

            if (date('Ymd') == $mission->getDate_fin())
              echo '<br />La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui, trouve vite les derniers objectifs !';
          echo '</a>';
        }
        // Mission > 1 jour (heure KO)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') < $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...';
          echo '</a>';
        }
        // Mission > 1 jour (terminée)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') < $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> est terminée pour aujourd\'hui ! Reviens demain pour continuer...';
          echo '</a>';
        }
        // Mission > 1 jour (terminée, jour de fin)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui. Tu as trouvé tous les objectifs, reviens demain pour voir les scores !';
          echo '</a>';
        }
        // Mission > 1 jour (heure KO, jour de fin)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') == $mission->getDate_fin() AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui. Trouve les derniers objectifs à partir de ' . formatTimeForDisplayLight($mission->getHeure()) . '.';
          echo '</a>';
        }
        // Mission > 1 jour (terminée, jour de fin + 7 jours)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND (date('Ymd') >= date('Ymd', strtotime($mission->getDate_fin() . ' + 1 day'))) AND (date('Ymd') <= date('Ymd', strtotime($mission->getDate_fin() . ' + 7 days'))))
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=ranking&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> est terminée. Va voir les résultats en cliquant sur ce message.<br />';

            // Noms des gagnants
            if (!empty($gagnantsMissions))
            {
              $liste_gagnants = array();

              foreach ($gagnantsMissions as $gagnants)
              {
                foreach ($gagnants as $gagnant)
                {
                  if ($gagnant['id_mission'] == $mission->getId())
                    array_push($liste_gagnants, $gagnant['pseudo']);
                }
              }

              echo formatGagnants($liste_gagnants);
            }
          echo '</a>';
        }
        // Mission 1 jour (heure OK)
        elseif (isset($key_current_mission) AND isset($_SESSION['missions'][$key_current_mission]) AND !empty($_SESSION['missions'][$key_current_mission]) AND $mission->getDate_deb() == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['missions'][$key_current_mission]);

          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            if ($nbRestants == 1)
              echo 'La mission <strong>' . $mission->getMission() . '</strong> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectif à trouver !';
            else
              echo 'La mission <strong>' . $mission->getMission() . '</strong> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectifs à trouver !';
          echo '</a>';
        }
        // Mission 1 jour (heure KO)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND $mission->getDate_deb() == $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...';
          echo '</a>';
        }
      echo '</div>';

      unset($id_current_mission);
      unset($key_current_mission);
    }
  }
?>
