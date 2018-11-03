<?php
  echo '<div class="calendars_year">';
    if ($_GET['action'] == "goConsulterAnnexes")
      echo '<span class="calendars_year_active">Annexes</span>';
    else
      echo '<a href="calendars.php?action=goConsulterAnnexes" class="calendars_year_inactive">Annexes</a>';

    foreach ($onglets as $year)
    {
      if (isset($_GET['year']) AND $year == $_GET['year'])
        echo '<span class="calendars_year_active">' . $year . '</span>';
      else
        echo '<a href="calendars.php?year=' . $year . '&action=goConsulter" class="calendars_year_inactive">' . $year . '</a>';
    }

    if ($_GET['action'] != "goConsulterAnnexes" AND $anneeExistante == false)
      echo '<span class="calendars_year_active">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
