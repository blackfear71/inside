<?php
  if ($nbPages > 0)
  {
    $i = 0;

    foreach ($listeCollectors as $collector)
    {
      if ($i % 2 == 0)
      {
        /***************************************************/
        /* Ligne visualisation normale (sans modification) */
        /***************************************************/
        echo '<table class="zone_collector" id="modifier_collector_2[' . $collector->getId() . ']">';
          echo '<tr>';
            // Citation (gauche)
            echo '<td rowspan="100%" class="collector">';
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
              echo '<div class="text_collector">' . $collector->getCollector() . '</div>';
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</td>';

            echo '<td colspan="2" class="speaker">';
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
                echo formatDateForDisplay($collector->getDate_collector());
              echo '</div>';
            echo '</td>';
          echo '</tr>';

          echo '<tr>';
            echo '<td class="speaker_actions" style="border-right: solid 1px white;">';
              // Modification
              echo '<a onclick="afficherMasquer(\'modifier_collector[' . $collector->getId() . ']\'); afficherMasquer(\'modifier_collector_2[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_modify_collector"></a>';
            echo '</td>';

            echo '<td class="speaker_actions">';
              // Suppression
              echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_delete_collector" />';
              echo '</form>';
            echo '</td>';
          echo '</tr>';
        echo '</table>';

        /**********************************/
        /* Ligne cachée pour modification */
        /**********************************/
        echo '<table class="zone_collector" id="modifier_collector[' . $collector->getId() . ']" style="display: none;">';
          echo '<form method="post" action="collector.php?modify_id=' . $collector->getId() . '&action=doModifier&page=' . $_GET['page'] . '">';
            echo '<tr>';
              // Modification citation (gauche)
              echo '<td rowspan="100%" class="collector">';
                echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
                echo '<textarea name="collector" class="modify_text_collector">' . $collector->getCollector() . '</textarea>';
                echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
              echo '</td>';

              echo '<td colspan="2" class="speaker">';
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

                // Modification speaker
                if (!empty($collector->getSpeaker()))
                {
                  echo '<select name="speaker" class="modify_speaker" required>';
                    echo '<option value="" hidden>Choisissez...</option>';

                    foreach ($listeUsers as $user)
                    {
                      if ($user->getIdentifiant() == $_SESSION['speaker'])
                        echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
                      else
                      {
                        if ($user->getIdentifiant() == $collector->getSpeaker())
                          echo '<option value="' . $collector->getSpeaker() . '" selected>' . $user->getPseudo() . '</option>';
                        else
                          echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                      }
                    }
                  echo '</select>';
                }
                else
                {
                  echo '<div class="name_collector">';
                    echo $collector->getName_s();
                  echo '</div>';
                }

                // Modification date
                echo '<input type="text" name="date_collector" value="' . formatDateForDisplay($collector->getDate_collector()) . '" placeholder="Date" maxlength="10" id="datepicker[' . $collector->getId() . ']" class="modify_date_collector" required />';
              echo '</td>';
            echo '</tr>';

            echo '<tr>';
              echo '<td class="speaker_actions" style="border-right: solid 1px white;">';
                // Annulation modification
                echo '<a onclick="afficherMasquer(\'modifier_collector[' . $collector->getId() . ']\'); afficherMasquer(\'modifier_collector_2[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_cancel_collector"></a>';
              echo '</td>';

              echo '<td class="speaker_actions">';
                // Validation modification
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_validate_collector" />';
              echo '</td>';
            echo '</tr>';
          echo '</form>';
        echo '</table>';
      }
      else
      {
        /***************************************************/
        /* Ligne visualisation normale (sans modification) */
        /***************************************************/
        echo '<table class="zone_collector" id="modifier_collector_4[' . $collector->getId() . ']">';
          echo '<tr>';
            echo '<td colspan="2" class="speaker">';
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
                echo formatDateForDisplay($collector->getDate_collector());
              echo '</div>';
            echo '</td>';

            // Citation (droite)
            echo '<td rowspan="100%" class="collector">';
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
              echo '<div class="text_collector">' . $collector->getCollector() . '</div>';
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</td>';
          echo '<tr>';

          echo '<tr>';
            echo '<td class="speaker_actions" style="border-right: solid 1px white;">';
              // Suppression
              echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_delete_collector" />';
              echo '</form>';
            echo '</td>';

            echo '<td class="speaker_actions">';
              // Modification
              echo '<a onclick="afficherMasquer(\'modifier_collector_3[' . $collector->getId() . ']\'); afficherMasquer(\'modifier_collector_4[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_modify_collector"></a>';
            echo '</td>';
          echo '</tr>';
        echo '</table>';

        /**********************************/
        /* Ligne cachée pour modification */
        /**********************************/
        echo '<table class="zone_collector" id="modifier_collector_3[' . $collector->getId() . ']" style="display: none;">';
          echo '<form method="post" action="collector.php?modify_id=' . $collector->getId() . '&action=doModifier&page=' . $_GET['page'] . '">';
            echo '<tr>';
              echo '<td colspan="2" class="speaker">';
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

                // Modification speaker
                if (!empty($collector->getSpeaker()))
                {
                  echo '<select name="speaker" class="modify_speaker" required>';
                    echo '<option value="" hidden>Choisissez...</option>';

                    foreach ($listeUsers as $user)
                    {
                      if ($user->getIdentifiant() == $_SESSION['speaker'])
                        echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
                      else
                      {
                        if ($user->getIdentifiant() == $collector->getSpeaker())
                          echo '<option value="' . $collector->getSpeaker() . '" selected>' . $user->getPseudo() . '</option>';
                        else
                          echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                      }
                    }
                  echo '</select>';
                }
                else
                {
                  echo '<div class="name_collector">';
                    echo $collector->getName_s();
                  echo '</div>';
                }

                // Modification date
                echo '<input type="text" name="date_collector" value="' . formatDateForDisplay($collector->getDate_collector()) . '" placeholder="Date" maxlength="10" id="datepicker[' . $collector->getId() . ']" class="modify_date_collector" required />';
              echo '</td>';

              // Modification citation (gauche)
              echo '<td rowspan="100%" class="collector">';
                echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
                echo '<textarea name="collector" class="modify_text_collector">' . $collector->getCollector() . '</textarea>';
                echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
              echo '</td>';
            echo '</tr>';

            echo '<tr>';
              echo '<td class="speaker_actions" style="border-right: solid 1px white;">';
                // Annulation modification
                echo '<a onclick="afficherMasquer(\'modifier_collector_3[' . $collector->getId() . ']\'); afficherMasquer(\'modifier_collector_4[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_cancel_collector"></a>';
              echo '</td>';

              echo '<td class="speaker_actions">';
                // Validation modification
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_validate_collector" />';
              echo '</td>';
            echo '</tr>';
          echo '</form>';
        echo '</table>';
      }

      $i++;
    }
  }
?>
