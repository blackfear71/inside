<?php
  echo '<div class="zone_movies_left">';
    // Vues
    echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/view_grey.png" alt="view_grey" class="logo_titre_section" />Vues</div>';

    $i         = 0;
    $listeVues = array('home'  => 'Accueil',
                       'cards' => 'Fiches',
                       'main'  => 'Synthèse',
                       'user'  => 'Détails'
                      );

    foreach ($listeVues as $view => $vue)
    {
      if ($i % 2 == 0)
      {
        if ($_GET['view'] == $view)
          echo '<span class="view active margin_right">' . $vue . '</span>';
        else
          echo '<a href="moviehouse.php?view=' . $view . '&year=' . $_GET['year'] . '&action=goConsulter" class="view inactive margin_right">' . $vue . '</a>';
      }
      else
      {
        if ($_GET['view'] == $view)
          echo '<span class="view active">' . $vue . '</span>';
        else
          echo '<a href="moviehouse.php?view=' . $view . '&year=' . $_GET['year'] . '&action=goConsulter" class="view inactive">' . $vue . '</a>';
      }

      $i++;
    }

    // Années
    echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/recent_grey.png" alt="recent_grey" class="logo_titre_section" />Années</div>';

    // Date du jour
    echo '<div class="date_jour">Aujourd\'hui le ' . date("d/m/Y") . '</div>';

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
            echo '<span class="year active margin_right">' . $_GET['year'] . '</span>';
          else
            echo '<span class="year active">' . $_GET['year'] . '</span>';

          $lastYear = false;
          $i++;
        }

        // Année existante
        if ($i % 2 == 0)
        {
          if (isset($_GET['year']) AND $year == $_GET['year'])
            echo '<span class="year active margin_right">' . $year . '</span>';
          else
            echo '<a href="moviehouse.php?view=' . $_GET['view'] . '&year=' . $year . '&action=goConsulter" class="year inactive margin_right">' . $year . '</a>';
        }
        else
        {
          if (isset($_GET['year']) AND $year == $_GET['year'])
            echo '<span class="year active">' . $year . '</span>';
          else
            echo '<a href="moviehouse.php?view=' . $_GET['view'] . '&year=' . $year . '&action=goConsulter" class="year inactive">' . $year . '</a>';
        }

        $previousYear = $year;
        $i++;
      }

      // Année inexistante (dernière)
      if ($lastYear == true AND $anneeExistante == false)
        echo '<span class="year active">' . $_GET['year'] . '</span>';
    }
    else
      echo '<span class="year active margin_right">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
