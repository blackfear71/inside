<?php
  // Ce fichier génère les boutons de missions. Ceux-ci sont déterminés dans les fonctions communes (controlUser) et récupérés ici. Cette page est incluse dans les zones où l'on souhaite
  // voir les boutons (header, article & footer). Leur position est déterminée par le style généré précédemment.

  if (isset($_SESSION['missions']) AND !empty($_SESSION['missions']))
  {
    // Page courante
    $page_courante = $_SERVER['PHP_SELF'];

    foreach ($_SESSION['missions'] as $missionUnique)
    {
      foreach ($missionUnique as $ligneMission)
      {
        // Récupération des données
        $ref_mission        = $ligneMission['ref_mission'];
        $key_mission        = $ligneMission['key_mission'];
        $zone_mission       = $ligneMission['zone'];
        $nom_page_mission   = $ligneMission['page'];
        $position_mission   = $ligneMission['position'];
        $icon_mission       = $ligneMission['icon'];
        $class_icon_mission = $ligneMission['class'];

        // Affichage bouton mission
        if ($nom_page_mission == $page_courante)
        {
          if ($zone_mission == $zone_inside)
          {
            echo '<form method="post" action="/inside/portail/missions/missions.php?ref_mission=' . $ref_mission . '&key_mission=' . $key_mission . '&action=doMission" class="' . $class_icon_mission . '">';
              echo '<input type="submit" name="' . $ref_mission . '" value="" style="background-image: url(/inside/includes/images/missions/buttons/' . $icon_mission . '.png);" />';
            echo '</form>';
          }
        }
      }
    }
  }
?>
