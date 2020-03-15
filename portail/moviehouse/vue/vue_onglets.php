<?php
  echo '<div class="zone_movies_left">';
    // Vues
    echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/view_grey.png" alt="view_grey" class="logo_titre_section" /><div class="texte_titre_section">Vues</div></div>';

    $i         = 0;
    $listeVues = array('home'  => 'Accueil',
                       'cards' => 'Fiches'
                      );

    foreach ($listeVues as $view => $vue)
    {
      if ($i % 2 == 0)
      {
        if ($_GET['view'] == $view)
          echo '<span class="view active margin_right_20">' . $vue . '</span>';
        else
          echo '<a href="moviehouse.php?view=' . $view . '&year=' . $_GET['year'] . '&action=goConsulter" class="view inactive margin_right_20">' . $vue . '</a>';
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
    echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/recent_grey.png" alt="recent_grey" class="logo_titre_section" /><div class="texte_titre_section">Années</div></div>';

    // Date du jour
    if ($_GET['view'] == "cards" AND $_GET['year'] == date('Y'))
      echo '<a class="date_jour pointer naviguerMois">Aujourd\'hui le ' . date("d/m/Y") . '</a>';
    else
      echo '<div class="date_jour">Aujourd\'hui le ' . date("d/m/Y") . '</div>';

    // Onglets
    if (!empty($ongletsYears))
    {
      $i            = 0;
      $previousYear = $ongletsYears[0];
      $lastYear     = true;

      foreach ($ongletsYears as $year)
      {
        if (!empty($year))
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
              echo '<a href="moviehouse.php?view=' . $_GET['view'] . '&year=' . $year . '&action=goConsulter" class="year inactive margin_right_20">' . $year . '</a>';
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
      }

      // Année inexistante (dernière)
      if ($lastYear == true AND $anneeExistante == false)
      {
        if ($i % 2 == 0)
          echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
        else
          echo '<span class="year active">' . $_GET['year'] . '</span>';
      }

      // Date non communiquée
      if (end($ongletsYears) == "")
      {
        if (isset($_GET['year']) AND $_GET['year'] == "none")
          echo '<span class="year active">N. C.</span>';
        else
          echo '<a href="moviehouse.php?view=cards&year=none&action=goConsulter" class="year inactive">N. C.</a>';
      }
    }
    else
      echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
  echo '</div>';
?>
