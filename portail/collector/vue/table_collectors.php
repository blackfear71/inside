<?php
  if ($nbPages > 0)
  {
    $i = 0;

    foreach ($listeCollectors as $collector)
    {
      if ($i % 2 == 0)
      {
        echo '<table class="zone_collector">';
          echo '<tr>';
            // Citation (gauche)
            echo '<td class="collector">';
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
              echo '<div class="text_collector">' . $collector->getCollector() . '</div>';
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</td>';

            echo '<td class="speaker">';
              // Avatar (droite)
              if (!empty($collector->getAvatar_s()))
              {
                echo '<div class="circle_avatar">';
                  echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                echo '</div>';
              }
              else
              {
                echo '<div class="circle_avatar">';
                  echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                echo '</div>';
              }

              // Nom & date (droite)
              echo '<div class="name_collector">';
                echo $collector->getName_s();
                echo '<br />-<br />';
                echo formatDateForDisplay($collector->getDate());
              echo '</div>';

              // Suppression
              echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_delete_collector" style="float: right;" />';
              echo '</form>';
            echo '</td>';
          echo '</tr>';
        echo '</table>';
      }
      else
      {
        echo '<table class="zone_collector">';
          echo '<tr>';
            echo '<td class="speaker">';
              // Avatar (gauche)
              if (!empty($collector->getAvatar_s()))
              {
                echo '<div class="circle_avatar">';
                  echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                echo '</div>';
              }
              else
              {
                echo '<div class="circle_avatar">';
                  echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                echo '</div>';
              }

              // Nom & date (gauche)
              echo '<div class="name_collector">';
                echo $collector->getName_s();
                echo '<br />-<br />';
                echo formatDateForDisplay($collector->getDate());
              echo '</div>';

              // Suppression
              echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_delete_collector" style="float: left;" />';
              echo '</form>';
            echo '</td>';

            // Citation (droite)
            echo '<td class="collector">';
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
              echo '<div class="text_collector">' . $collector->getCollector() . '</div>';
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</td>';
          echo '<tr>';
        echo '</table>';
      }

      $i++;
    }
  }
?>
