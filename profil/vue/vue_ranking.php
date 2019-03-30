<?php
  $i = 0;
  $lvl = 0;

  echo '<table class="zone_ranking">';
    foreach ($listeSuccess as $success)
    {
      if ($success->getLevel() != $lvl AND $success->getLimit_success() > 1)
      {
        echo '<tr class="title_ranking_line"><td colspan="2">' . formatTitleLvl($success->getLevel()) . '</td></tr>';
        $lvl = $success->getLevel();
      }

      if ($success->getLimit_success() > 1)
      {
        if ($i % 2 == 0)
          echo '<tr class="ranking_line">';
        else
          echo '<tr class="ranking_line" style="background-color: #d3d3d3;">';

          if ($success->getDefined() == "Y")
          {
            echo '<td class="ranking_line_left">';
              // Logo succès
              if ($success->getValue_user() >= $success->getLimit_success())
                echo '<img src="../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_rank" style="background-color: #ffad01;" />';
              else
                echo '<img src="../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_rank" />';

              // Titre succès
              echo '<div class="titre_rank">' . $success->getTitle() . '</div>';
            echo '</td>';

            echo '<td class="ranking_line_right">';
              // Médailles
              foreach ($classementUsers as $classement)
              {
                $gold   = false;
                $silver = false;
                $bronze = false;

                if ($classement['id'] == $success->getId())
                {
                  foreach ($classement['podium'] as $podium)
                  {
                    // Or
                    if ($podium['rank'] == 1)
                    {
                      echo '<div class="zone_medals">';
                        if ($gold == false)
                        {
                          echo '<div class="zone_medals_img"><img src="../includes/icons/common/medals/or.png" alt="or" class="medal_rank" /></div>';
                          $gold = true;
                        }

                        echo '<div class="ranking_pseudo">' . $podium['pseudo'] . '</div>';
                      echo '</div>';
                    }

                    // Argent
                    if ($podium['rank'] == 2)
                    {
                      echo '<div class="zone_medals">';
                        if ($silver == false)
                        {
                          echo '<div class="zone_medals_img"><img src="../includes/icons/common/medals/argent.png" alt="argent" class="medal_rank" /></div>';
                          $silver = true;
                        }

                        echo '<div class="ranking_pseudo">' . $podium['pseudo'] . '</div>';
                      echo '</div>';
                    }

                    // Bronze
                    if ($podium['rank'] == 3)
                    {
                      echo '<div class="zone_medals">';
                        if ($bronze == false)
                        {
                          echo '<div class="zone_medals_img"><img src="../includes/icons/common/medals/bronze.png" alt="bronze" class="medal_rank" /></div>';
                          $bronze = true;
                        }

                        echo '<div class="ranking_pseudo">' . $podium['pseudo'] . '</div>';
                      echo '</div>';
                    }
                  }

                  break;
                }
              }
            echo '</td>';
          }
          else
          {
            echo '<td class="ranking_line_left">';
              // Succès non défini
              echo '<img src="../includes/icons/profil/hidden_success.png" alt="hidden_success" class="logo_rank" />';

              // Titre
              echo '<div class="titre_rank">Succès non défini</div>';
            echo '</td>';

            echo '<td class="ranking_line_right"></td>';
          }
        echo '</tr>';

        $i++;
      }
    }
  echo '</table>';
?>
