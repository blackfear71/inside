<?php
  if (isset($messagesMissions) AND !empty($messagesMissions))
  {
    foreach ($messagesMissions as $keyMission => $mission)
    {
      echo '<div class="zone_resume_mission">';
        // Mission > 1 jour (heure OK)
        if (isset($_SESSION['tableau_missions'][$keyMission]) AND !empty($_SESSION['tableau_missions'][$keyMission]) AND $mission->getDate_deb() != $mission->getDate_fin())
        {
          $nbRestants = count($_SESSION['tableau_missions'][$keyMission]);

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
        elseif (empty($_SESSION['tableau_missions'][$keyMission]) AND date('Ymd') < $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...';
          echo '</a>';
        }
        // Mission > 1 jour (terminée)
        elseif (empty($_SESSION['tableau_missions'][$keyMission]) AND date('Ymd') < $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> est terminée pour aujourd\'hui ! Reviens demain pour continuer...';
          echo '</a>';
        }
        // Mission > 1 jour (terminée, jour de fin)
        elseif (empty($_SESSION['tableau_missions'][$keyMission]) AND date('Ymd') == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui. Tu as trouvé tous les objectifs, reviens demain pour voir les scores !';
          echo '</a>';
        }
        // Mission > 1 jour (heure KO, jour de fin)
        elseif (empty($_SESSION['tableau_missions'][$keyMission]) AND date('Ymd') == $mission->getDate_fin() AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui. Trouve les derniers objectifs à partir de ' . formatTimeForDisplayLight($mission->getHeure()) . '.';
          echo '</a>';
        }
        // Mission > 1 jour (terminée, jour de fin + 7 jours)
        elseif (empty($_SESSION['tableau_missions'][$keyMission]) AND (date('Ymd') >= date('Ymd', strtotime($mission->getDate_fin() . ' + 1 day'))) AND (date('Ymd') <= date('Ymd', strtotime($mission->getDate_fin() . ' + 7 days'))))
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=ranking&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> est terminée. Va voir les résultats en cliquant sur ce message.';
          echo '</a>';
        }
        // Mission 1 jour (heure OK)
        elseif (isset($_SESSION['tableau_missions'][$keyMission]) AND !empty($_SESSION['tableau_missions'][$keyMission]) AND $mission->getDate_deb() == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['tableau_missions'][$keyMission]);

          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            if ($nbRestants == 1)
              echo 'La mission <strong>' . $mission->getMission() . '</strong> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectif à trouver !';
            else
              echo 'La mission <strong>' . $mission->getMission() . '</strong> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectifs à trouver !';
          echo '</a>';
        }
        // Mission 1 jour (heure KO)
        elseif (empty($_SESSION['tableau_missions'][$keyMission]) AND $mission->getDate_deb() == $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&view=mission&action=goConsulter" class="link_mission">';
            echo 'La mission <strong>' . $mission->getMission() . '</strong> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...';
          echo '</a>';
        }
      echo '</div>';
    }
  }
?>
