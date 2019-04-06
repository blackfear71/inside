<?php
  echo '<div class="movie_year">';
    foreach ($ongletsYears as $onglet)
    {
      if ($onglet == $_GET['year'])
        echo '<span class="movie_year_active">' . $onglet . '</span>';
      else
        echo '<a href="moviehouse.php?view=' . $_GET['view'] . '&year=' . $onglet . '&action=goConsulter" class="movie_year_inactive">' . $onglet . '</a>';
    }

    if ($anneeExistante == false)
      echo '<span class="movie_year_active">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
