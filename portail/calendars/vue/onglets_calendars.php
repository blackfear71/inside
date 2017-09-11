<?php
  echo '<div class="calendars_year">';
    foreach ($onglets as $year)
    {
      if ($year == $_GET['year'])
        echo '<span class="calendars_year_active">' . $year . '</span>';
      else
        echo '<a href="calendars.php?year=' . $year . '&action=goConsulter" class="calendars_year_inactive">' . $year . '</a>';
    }

    if ($anneeExistante == false)
      echo '<span class="calendars_year_active">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
