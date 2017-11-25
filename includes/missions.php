<?php
  // Page courante
  $page_courante = $_SERVER['PHP_SELF'];

  foreach ($_SESSION['tableau_missions'] as $ligneMission)
  {
    // Récupération des données
    $ref_mission        = $ligneMission['ref_mission'];
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
        echo '<form method="post" action="/inside/portail/missions/missions.php?id_mission=' . $ref_mission . '&action=doMission" class="' . $class_icon_mission . '">';
          echo '<input type="submit" name="' . $ref_mission . '" value="" style="background-image: url(/inside/portail/missions/icons/' . $icon_mission . '.png);" />';
        echo '</form>';
      }
    }
  }

  /*echo'<div style="clear: both; line-height: 50px; margin-left: 100px;">';
    var_dump($_SESSION['tableau_missions']);
  echo '</div>';*/
?>
