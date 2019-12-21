<?php
  echo '<div class="zone_cooking_left">';
    // Années
    echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/recent_grey.png" alt="recent_grey" class="logo_titre_section" /><div class="texte_titre_section">Années</div></div>';

    // Onglets
    if (!empty($ongletsYears))
    {
      $i            = 0;
      $previousYear = $ongletsYears[0];
      $lastYear     = true;

      foreach ($ongletsYears as $year)
      {
        // Année inexistante (première ou au milieu)
        if ($lastYear != false AND $anneeExistante == false AND (($_GET['year'] < $previousYear AND $_GET['year'] > $year) OR $_GET['year'] > $ongletsYears[0]))
        {
          if ($i % 2 == 0)
            echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
          else
            echo '<span class="year active">' . $_GET['year'] . '</span>';

          $lastYear = false;
          $i++;
        }

        // Année existante
        if ($i % 2 == 0)
        {
          if (isset($_GET['year']) AND $year == $_GET['year'])
            echo '<span class="year active margin_right_20">' . $year . '</span>';
          else
            echo '<a href="cookingbox.php?year=' . $year . '&action=goConsulter" class="year inactive margin_right_20">' . $year . '</a>';
        }
        else
        {
          if (isset($_GET['year']) AND $year == $_GET['year'])
            echo '<span class="year active">' . $year . '</span>';
          else
            echo '<a href="cookingbox.php?year=' . $year . '&action=goConsulter" class="year inactive">' . $year . '</a>';
        }

        $previousYear = $year;
        $i++;
      }

      // Année inexistante (dernière)
      if ($lastYear == true AND $anneeExistante == false)
        echo '<span class="year active">' . $_GET['year'] . '</span>';
    }
    else
      echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
