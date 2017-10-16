<?php
  $i = 0;

  echo '<table class="zone_ranking">';
    foreach ($listeSuccess as $success)
    {
      if (isset($successUser[$success->getId()]) AND $success->getLimit_success() > 1)
      {
        if ($i % 2 == 0)
          echo '<tr class="ranking_line">';
        else
          echo '<tr class="ranking_line" style="background-color: #d3d3d3;">';

            echo '<td class="ranking_line_left">';
              // Logo succès
              if ($successUser[$success->getId()] >= $success->getLimit_success())
                echo '<img src="../includes/icons/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_rank" style="background-color: #ffad01;" />';
              else
                echo '<img src="../includes/icons/hidden_success.png" alt="hidden_success" class="logo_rank" />';

              // Titre succès
              echo '<div class="titre_rank">' . $success->getTitle() . '</div>';
            echo '</td>';

            echo '<td class="ranking_line_right">';
              // Médailles
              foreach ($classementUsers as $classement)
              {
                if ($classement['id'] == $success->getId())
                {
                  // Or
                  if (isset($classement['podium'][0]))
                  {
                    echo '<img src="../includes/icons/medals/or.png" alt="or" class="medal_rank" />';
                    echo '<div class="ranking_pseudo">' . $classement['podium'][0]['pseudo'] . '</div>';
                  }

                  // Argent
                  if (isset($classement['podium'][1]))
                  {
                    echo '<img src="../includes/icons/medals/argent.png" alt="argent" class="medal_rank" />';
                    echo '<div class="ranking_pseudo">' . $classement['podium'][1]['pseudo'] . '</div>';
                  }

                  // Bronze
                  if (isset($classement['podium'][2]))
                  {
                    echo '<img src="../includes/icons/medals/bronze.png" alt="bronze" class="medal_rank" />';
                    echo '<div class="ranking_pseudo">' . $classement['podium'][2]['pseudo'] . '</div>';
                  }

                  break;
                }
              }
            echo '</td>';

        echo '</tr>';
      }

      $i++;
    }
  echo '</table>';
?>
