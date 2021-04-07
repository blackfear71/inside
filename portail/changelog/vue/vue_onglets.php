<?php
  echo '<div class="zone_changelog_left">';
    // Années
    echo '<div class="titre_section"><img src="../../includes/icons/changelog/recent_grey.png" alt="recent_grey" class="logo_titre_section" /><div class="texte_titre_section">Années</div></div>';

    // Histoire du site
    if ($_GET['action'] == 'goConsulterHistoire')
      echo '<span class="history active">Histoire du site</span>';
    else
      echo '<a href="changelog.php?action=goConsulterHistoire" class="history inactive">Histoire du site</a>';

    // Onglets
    if (!empty($onglets))
    {
      $i            = 0;
      $previousYear = $onglets[0];
      $lastYear     = true;

      foreach ($onglets as $year)
      {
        // Année inexistante (première ou au milieu)
        if ($_GET['action'] != 'goConsulterHistoire')
        {
          if ($lastYear != false AND $anneeExistante == false AND (($_GET['year'] < $previousYear AND $_GET['year'] > $year) OR $_GET['year'] > $onglets[0]))
          {
            if ($i % 2 == 0)
              echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
            else
              echo '<span class="year active">' . $_GET['year'] . '</span>';

            $lastYear = false;
            $i++;
          }
        }

        // Année existante
        if ($i % 2 == 0)
        {
          if (isset($_GET['year']) AND $year == $_GET['year'])
            echo '<span class="year active margin_right_20">' . $year . '</span>';
          else
            echo '<a href="changelog.php?year=' . $year . '&action=goConsulter" class="year inactive margin_right_20">' . $year . '</a>';
        }
        else
        {
          if (isset($_GET['year']) AND $year == $_GET['year'])
            echo '<span class="year active">' . $year . '</span>';
          else
            echo '<a href="changelog.php?year=' . $year . '&action=goConsulter" class="year inactive">' . $year . '</a>';
        }

        $previousYear = $year;
        $i++;
      }

      // Année inexistante (dernière)
      if ($_GET['action'] != 'goConsulterHistoire')
      {
        if ($lastYear == true AND $anneeExistante == false)
          echo '<span class="year active">' . $_GET['year'] . '</span>';
      }
    }
    else
    {
      if ($_GET['action'] != 'goConsulterHistoire')
        echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
    }
  echo '</div>';
?>
