<?php
  echo '<div class="expense_year">';
    foreach ($onglets as $year)
    {
      if ($year == $_GET['year'])
        echo '<span class="expense_year_active">' . $year . '</span>';
      else
        echo '<a href="expensecenter.php?year=' . $year . '&action=goConsulter" class="expense_year_inactive">' . $year . '</a>';
    }

    if ($anneeExistante == false)
      echo '<span class="expense_year_active">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
