<?php
  /**********************/
  /* Tableau des bilans */
  /**********************/
  echo '<div class="zone_total_depenses">';
    echo '<table class="table_total_depenses">';
      // Titres
      echo '<tr>';
        echo '<td class="td_init_depenses">Utilisateurs</td>';
        echo '<td class="td_init_depenses" style="width: 100px;">Bilan</td>';
      echo '</tr>';

      $i = 0;

      // Lignes utilisateurs
      foreach ($listeUsers as $bilan)
      {
        if ($_SESSION['user']['identifiant'] == $bilan->getIdentifiant())
          echo '<tr style="background-color: #fffde8;">';
        elseif ($i % 2 != 0)
          echo '<tr style="background-color: #f3f3f3;">';
        else
          echo '<tr>';

          echo '<td class="td_depenses">';
            // Avatars
            echo '<div class="zone_avatar_total_depenses">';
              if (!empty($bilan->getAvatar()))
                echo '<img src="../../profil/avatars/' . $bilan->getAvatar() . '" alt="avatar" title="' . $bilan->getPseudo() . '" class="avatar_total_depenses" />';
              else
                echo '<img src="../../includes/icons/profile.png" alt="avatar" title="' . $bilan->getPseudo() . '" class="avatar_total_depenses" />';
            echo '</div>';

            // Pseudos
            echo '<div class="pseudo_total_depenses">' . $bilan->getPseudo() . "</div>";
          echo '</td>';

          if ($bilan->getExpenses() <= -6)
            echo '<td class="td_depenses" style="background-color: #ee4949;">';
          elseif ($bilan->getExpenses() <= -3 AND $bilan->getExpenses() > -6)
            echo '<td class="td_depenses" style="background-color: #ff9147;">';
          elseif ($bilan->getExpenses() < -0.01 AND $bilan->getExpenses() > -3)
            echo '<td class="td_depenses" style="background-color: #fffd4c;">';
          elseif ($bilan->getExpenses() > 0.01 AND $bilan->getExpenses() < 5)
            echo '<td class="td_depenses" style="background-color: #b6fc78;">';
          elseif ($bilan->getExpenses() > 0.01 AND $bilan->getExpenses() >= 5)
            echo '<td class="td_depenses" style="background-color: #71d058;">';
          else
            echo '<td class="td_depenses">';
              echo '<span class="somme_bilan">' . formatBilanForDisplay($bilan->getExpenses()) . '</span>';
            echo '</td>';
        echo '</tr>';

        $i++;
      }
    echo '</table>';
  echo '</div>';
?>
