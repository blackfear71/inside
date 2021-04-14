<?php
  // Génération des boutons de missions déterminés automatiquement dans le métier commun (fonction controlsUser) et récupéré par les différentes pages appelant ce fichier
  if (isset($_SESSION['missions']) AND !empty($_SESSION['missions']))
  {
    // Page courante
    $pageCourante = $_SERVER['PHP_SELF'];

    // Recherche des boutons pour chaque mission
    foreach ($_SESSION['missions'] as $missionGeneree)
    {
      // Recherche des boutons de missions sur la page courante
      foreach ($missionGeneree as $boutonMission)
      {
        // Récupération des données
        $referenceMission  = $boutonMission['ref_mission'];
        $keyMission        = $boutonMission['key_mission'];
        $zoneMission       = $boutonMission['zone'];
        $nomPageMission    = $boutonMission['page'];
        $positionMission   = $boutonMission['position'];
        $iconeMission      = $boutonMission['icon'];
        $classIconeMission = $boutonMission['class'];

        // Affichage du bouton de mission
        if ($nomPageMission == $pageCourante)
        {
          // Affichage sur la zone correspondante
          if ($zoneMission == $zoneInside)
          {
            echo '<form method="post" action="/inside/portail/missions/missions.php?action=doMission" class="' . $classIconeMission . '">';
              echo '<input type="hidden" name="ref_mission" value="' . $referenceMission . '" />';
              echo '<input type="hidden" name="key_mission" value="' . $keyMission . '" />';
              echo '<input type="image" name="' . $referenceMission . '" src="/inside/includes/images/missions/buttons/' . $iconeMission . '.png" alt="' . $iconeMission . '" />';
            echo '</form>';
          }
        }
      }
    }
  }
?>
