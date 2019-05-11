<?php
  if (!empty($ranking))
  {
    echo '<table class="table_classement">';
      // Podium
      echo '<tr class="background_podium">';
        // 2nds
        echo '<td class="td_argent">';
          echo '<div class="names_argent">';
            $total_classement = 0;

            foreach ($ranking as $rankUser)
            {
              if ($rankUser['rank'] == 2)
              {
                echo '<div class="zone_avatar_details_mission second">';
                  if (!empty($rankUser['avatar']))
                    echo '<img src="../../includes/images/profil/avatars/' . $rankUser['avatar'] . '" alt="avatar" title="' . $rankUser['pseudo'] . '" class="avatar_details_mission" />';
                  else
                    echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $rankUser['pseudo'] . '" class="avatar_details_mission" />';

                  echo '<div class="pseudo_details_mission">' . $rankUser['pseudo'] . '</div>';
                echo '</div>';

                $total_classement = $rankUser['total'];
              }
            }
          echo '</div>';

          echo '<div class="podium_argent">';
            echo '<img src="../../includes/icons/common/medals/argent.png" alt="argent" class="medaille_podium" />';

            if ($total_classement > 0)
              echo '<div class="total_classement">' . $total_classement . '</div>';
          echo '</div>';
        echo '</td>';

        // 1ers
        echo '<td class="td_or">';
          echo '<div class="names_or">';
            $total_classement = 0;

            foreach ($ranking as $rankUser)
            {
              if ($rankUser['rank'] == 1)
              {
                echo '<div class="zone_avatar_details_mission first">';
                  if (!empty($rankUser['avatar']))
                    echo '<img src="../../includes/images/profil/avatars/' . $rankUser['avatar'] . '" alt="avatar" title="' . $rankUser['pseudo'] . '" class="avatar_details_mission" />';
                  else
                    echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $rankUser['pseudo'] . '" class="avatar_details_mission" />';

                  echo '<div class="pseudo_details_mission">' . $rankUser['pseudo'] . '</div>';
                echo '</div>';

                $total_classement = $rankUser['total'];
              }
            }
          echo '</div>';

          echo '<div class="podium_or">';
            echo '<img src="../../includes/icons/common/medals/or.png" alt="or" class="medaille_podium" />';

            if ($total_classement > 0)
              echo '<div class="total_classement">' . $total_classement . '</div>';
          echo '</div>';
        echo '</td>';

        // 3èmes
        echo '<td class="td_bronze">';
          $total_classement = 0;

          echo '<div class="names_bronze">';
            foreach ($ranking as $rankUser)
            {
              if ($rankUser['rank'] == 3)
              {
                echo '<div class="zone_avatar_details_mission third">';
                  if (!empty($rankUser['avatar']))
                    echo '<img src="../../includes/images/profil/avatars/' . $rankUser['avatar'] . '" alt="avatar" title="' . $rankUser['pseudo'] . '" class="avatar_details_mission" />';
                  else
                    echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $rankUser['pseudo'] . '" class="avatar_details_mission" />';

                  echo '<div class="pseudo_details_mission">' . $rankUser['pseudo'] . '</div>';
                echo '</div>';

                $total_classement = $rankUser['total'];
              }
            }
          echo '</div>';

          echo '<div class="podium_bronze">';
            echo '<img src="../../includes/icons/common/medals/bronze.png" alt="bronze" class="medaille_podium" />';

            if ($total_classement > 0)
              echo '<div class="total_classement">' . $total_classement . '</div>';
          echo '</div>';
        echo '</td>';
      echo '</tr>';

      echo '<tr style="height: 20px;"><td colspan="3"></td></tr>';

      // Autres participants
      foreach ($ranking as $rankUser)
      {
        if ($rankUser['rank'] > 3)
        {
          echo '<tr>';
            echo '<td colspan="3">';
              echo '<div class="other_classement">';
                echo $rankUser['rank'] . '. ' . $rankUser['pseudo'];
                echo '<div class="total_other_classement">' . $rankUser['total'] . '</div>';
              echo '</div>';
            echo '</td>';
          echo '</tr>';
        }
      }
    echo '</table>';
  }
  else
  {
    echo '<div class="titre_accueil_mission">Pas de classement sur cette mission...</div>';
  }
?>
