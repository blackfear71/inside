<?php
  // Ce fichier génère les boutons de missions. Ceux-ci sont déterminés dans les fonctions communes (controlUser) et récupérés ici. Cette page est incluse dans les zones où l'on souhaite
  // voir les boutons (header, article & footer). Leur position est déterminée par le style généré précédemment.

  if (isset($_SESSION['missions']) AND !empty($_SESSION['missions']))
  {
    // Page courante
    $pageCourante = $_SERVER['PHP_SELF'];

    foreach ($_SESSION['missions'] as $missionUnique)
    {
      foreach ($missionUnique as $ligneMission)
      {
        // Récupération des données
        $refMission        = $ligneMission['ref_mission'];
        $keyMission        = $ligneMission['key_mission'];
        $zoneMission       = $ligneMission['zone'];
        $nomPageMission    = $ligneMission['page'];
        $positionMission   = $ligneMission['position'];
        $iconeMission      = $ligneMission['icon'];
        $classIconeMission = $ligneMission['class'];

        // Affichage bouton mission
        if ($nomPageMission == $pageCourante)
        {
          if ($zoneMission == $zoneInside)
          {
            echo '<form method="post" action="/inside/portail/missions/missions.php?action=doMission" class="' . $classIconeMission . '">';
              echo '<input type="hidden" name="ref_mission" value="' . $refMission . '" />';
              echo '<input type="hidden" name="key_mission" value="' . $keyMission . '" />';
              echo '<input type="image" name="' . $refMission . '" src="/inside/includes/images/missions/buttons/' . $iconeMission . '.png" alt="' . $iconeMission . '" />';
            echo '</form>';
          }
        }
      }
    }
  }
?>
