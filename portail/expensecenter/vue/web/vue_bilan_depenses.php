<?php
  /**********************/
  /*** Bilan & Années ***/
  /**********************/
  echo '<div class="zone_expenses_left">';
    // Bilan
    echo '<div class="titre_section"><img src="../../includes/icons/expensecenter/total_grey.png" alt="total_grey" class="logo_titre_section" /><div class="texte_titre_section">Bilan</div></div>';

    echo '<div class="zone_bilan_expenses">';
      foreach ($listeUsers as $user)
      {
        if ($user->getExpenses() <= -6)
          echo '<div class="zone_bilan_user bilan_red">';
        elseif ($user->getExpenses() <= -3 AND $user->getExpenses() > -6)
          echo '<div class="zone_bilan_user bilan_orange">';
        elseif ($user->getExpenses() < -0.01 AND $user->getExpenses() > -3)
          echo '<div class="zone_bilan_user bilan_jaune">';
        elseif ($user->getExpenses() > 0.01 AND $user->getExpenses() < 5)
          echo '<div class="zone_bilan_user bilan_vert">';
        elseif ($user->getExpenses() > 0.01 AND $user->getExpenses() >= 5)
          echo '<div class="zone_bilan_user bilan_vert_fonce">';
        else
          echo '<div class="zone_bilan_user">';
          // Avatar
          $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, "avatar");

          echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar" />';

          // Pseudo
          echo '<div class="pseudo_bilan">' . $user->getPseudo() . "</div>";

          // Total
          if ($user->getExpenses() > -0.01 AND $user->getExpenses() < 0.01)
            echo '<div class="total_bilan">' . formatBilanForDisplay(abs($user->getExpenses())) . '</div>';
          else
            echo '<div class="total_bilan">' . formatBilanForDisplay($user->getExpenses()) . '</div>';
        echo '</div>';
      }
    echo '</div>';

    // Années
    echo '<div class="titre_section"><img src="../../includes/icons/expensecenter/year_grey.png" alt="year_grey" class="logo_titre_section" /><div class="texte_titre_section">Années</div></div>';

    echo '<div class="zone_annees_expenses">';
      if (!empty($onglets))
      {
        $i            = 0;
        $previousYear = $onglets[0];
        $lastYear     = true;

        foreach ($onglets as $year)
        {
          // Année inexistante (première ou au milieu)
          if ($lastYear != false AND $anneeExistante == false AND (($_GET['year'] < $previousYear AND $_GET['year'] > $year) OR $_GET['year'] > $onglets[0]))
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
              echo '<a href="expensecenter.php?year=' . $year . '&action=goConsulter" class="year inactive margin_right_20">' . $year . '</a>';
          }
          else
          {
            if (isset($_GET['year']) AND $year == $_GET['year'])
              echo '<span class="year active">' . $year . '</span>';
            else
              echo '<a href="expensecenter.php?year=' . $year . '&action=goConsulter" class="year inactive">' . $year . '</a>';
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
  echo '</div>';
?>
