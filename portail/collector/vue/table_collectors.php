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
            echo '<td rowspan="100%" class="collector" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;">';
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
              echo '<div class="text_collector">' . nl2br($collector->getCollector()) . '</div>';
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</td>';

            echo '<td colspan="2" class="speaker" style="border-top-right-radius: 5px;">';
              // Avatar (droite)
              echo '<div class="circle_avatar">';
                if (!empty($collector->getAvatar_s()))
                  echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                else
                  echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';

                // Vote
                echo '<a onclick="afficherMasquer(\'modifier_vote[' . $collector->getId() . ']\'); afficherMasquer(\'link_form_vote[' . $collector->getId() . ']\');" id="link_form_vote[' . $collector->getId() . ']" class="link_current_vote_right">';
                  $founded = false;

                  foreach ($listeVotes as $vote)
                  {
                    if ($vote->getId_collector() == $collector->getId())
                    {
                      echo '<img src="../../includes/icons/smileys/' . $vote->getVote() . '.png" alt="smiley" class="current_vote" />';
                      $founded = true;
                      break;
                    }
                  }

                  if ($founded == false)
                    echo '<img src="../../includes/icons/smileys/4.png" alt="smiley" class="current_vote" />';
                echo '</a>';

                // Formulaire vote
                echo '<form method="post" action="collector.php?id=' . $collector->getId() . '&action=doVoter&page=' . $_GET['page'] . '" id="modifier_vote[' . $collector->getId() . ']" class="zone_smileys_right" style="display: none;">';
                  for ($j = 1; $j <= 6; $j++)
                  {
                    $founded = false;
                    foreach ($listeVotes as $vote)
                    {
                      if ($vote->getId_collector() == $collector->getId() AND $vote->getVote() == $j)
                      {
                        echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley_' . $j . '" style="background-size: 40px; width: 40px; height: 40px;" />';
                        $founded = true;
                      }
                    }

                    if ($founded == false)
                      echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley_' . $j . '" />';
                  }
                echo '</form>';
              echo '</div>';

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
              echo '<a onclick="afficherMasquerRow(\'modifier_collector[' . $collector->getId() . ']\'); afficherMasquerRow(\'modifier_collector_2[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_modify_collector"></a>';
            echo '</td>';

            echo '<td class="speaker_actions" style="border-bottom-right-radius: 5px;">';
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
              echo '<td rowspan="100%" class="collector" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;">';
                echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
                echo '<textarea name="collector" class="modify_text_collector">' . $collector->getCollector() . '</textarea>';
                echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
              echo '</td>';

              echo '<td colspan="2" class="speaker" style="border-top-right-radius: 5px;">';
                // Avatar (droite)
                echo '<div class="circle_avatar">';
                  if (!empty($collector->getAvatar_s()))
                    echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                  else
                    echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                echo '</div>';

                // Modification speaker
                if (!empty($collector->getSpeaker()))
                {
                  echo '<select name="speaker" class="modify_speaker" required>';
                    echo '<option value="" hidden>Choisissez...</option>';

                    foreach ($listeUsers as $user)
                    {
                      if ($user->getIdentifiant() == $collector->getSpeaker())
                        echo '<option value="' . $collector->getSpeaker() . '" selected>' . $user->getPseudo() . '</option>';
                      else
                        echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
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
                echo '<a onclick="afficherMasquerRow(\'modifier_collector[' . $collector->getId() . ']\'); afficherMasquerRow(\'modifier_collector_2[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_cancel_collector"></a>';
              echo '</td>';

              echo '<td class="speaker_actions" style="border-bottom-right-radius: 5px;">';
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
            echo '<td colspan="2" class="speaker" style="border-top-left-radius: 5px;">';
              // Avatar (gauche)
              echo '<div class="circle_avatar">';
                if (!empty($collector->getAvatar_s()))
                  echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                else
                  echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';

                // Vote
                echo '<a onclick="afficherMasquer(\'modifier_vote[' . $collector->getId() . ']\'); afficherMasquer(\'link_form_vote[' . $collector->getId() . ']\');" id="link_form_vote[' . $collector->getId() . ']" class="link_current_vote_left">';
                  $founded = false;

                  foreach ($listeVotes as $vote)
                  {
                    if ($vote->getId_collector() == $collector->getId())
                    {
                      echo '<img src="../../includes/icons/smileys/' . $vote->getVote() . '.png" alt="smiley" class="current_vote" />';
                      $founded = true;
                      break;
                    }
                  }

                  if ($founded == false)
                    echo '<img src="../../includes/icons/smileys/4.png" alt="smiley" class="current_vote" />';
                echo '</a>';

                // Formulaire vote
                echo '<form method="post" action="collector.php?id=' . $collector->getId() . '&action=doVoter&page=' . $_GET['page'] . '" id="modifier_vote[' . $collector->getId() . ']" class="zone_smileys_left" style="display: none;">';
                  for ($j = 1; $j <= 6; $j++)
                  {
                    $founded = false;
                    foreach ($listeVotes as $vote)
                    {
                      if ($vote->getId_collector() == $collector->getId() AND $vote->getVote() == $j)
                      {
                        echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley_' . $j . '" style="background-size: 40px; width: 40px; height: 40px;" />';
                        $founded = true;
                      }
                    }

                    if ($founded == false)
                      echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley_' . $j . '" />';
                  }
                echo '</form>';
              echo '</div>';

              // Nom & date (gauche)
              echo '<div class="name_collector">';
                echo $collector->getName_s();
                echo '<br />-<br />';
                echo formatDateForDisplay($collector->getDate_collector());
              echo '</div>';
            echo '</td>';

            // Citation (droite)
            echo '<td rowspan="100%" class="collector" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">';
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
              echo '<div class="text_collector">' . nl2br($collector->getCollector()) . '</div>';
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</td>';
          echo '<tr>';

          echo '<tr>';
            echo '<td class="speaker_actions" style="border-right: solid 1px white; border-bottom-left-radius: 5px;">';
              // Suppression
              echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_delete_collector" />';
              echo '</form>';
            echo '</td>';

            echo '<td class="speaker_actions">';
              // Modification
              echo '<a onclick="afficherMasquerRow(\'modifier_collector_3[' . $collector->getId() . ']\'); afficherMasquerRow(\'modifier_collector_4[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_modify_collector"></a>';
            echo '</td>';
          echo '</tr>';
        echo '</table>';

        /**********************************/
        /* Ligne cachée pour modification */
        /**********************************/
        echo '<table class="zone_collector" id="modifier_collector_3[' . $collector->getId() . ']" style="display: none;">';
          echo '<form method="post" action="collector.php?modify_id=' . $collector->getId() . '&action=doModifier&page=' . $_GET['page'] . '">';
            echo '<tr>';
              echo '<td colspan="2" class="speaker" style="border-top-left-radius: 5px;">';
                // Avatar (gauche)
                echo '<div class="circle_avatar">';
                  if (!empty($collector->getAvatar_s()))
                  {
                    echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                  }
                  else
                  {
                    echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                  }
                echo '</div>';

                // Modification speaker
                if (!empty($collector->getSpeaker()))
                {
                  echo '<select name="speaker" class="modify_speaker" required>';
                    echo '<option value="" hidden>Choisissez...</option>';

                    foreach ($listeUsers as $user)
                    {
                      if ($user->getIdentifiant() == $collector->getSpeaker())
                        echo '<option value="' . $collector->getSpeaker() . '" selected>' . $user->getPseudo() . '</option>';
                      else
                        echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
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
              echo '<td rowspan="100%" class="collector" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">';
                echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';
                echo '<textarea name="collector" class="modify_text_collector">' . $collector->getCollector() . '</textarea>';
                echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
              echo '</td>';
            echo '</tr>';

            echo '<tr>';
              echo '<td class="speaker_actions" style="border-right: solid 1px white; border-bottom-left-radius: 5px;">';
                // Validation modification
                echo '<input type="submit" name="delete_collector" value="" title="Supprimer" class="icon_validate_collector" />';
              echo '</td>';

              echo '<td class="speaker_actions">';
                // Annulation modification
                echo '<a onclick="afficherMasquerRow(\'modifier_collector_3[' . $collector->getId() . ']\'); afficherMasquerRow(\'modifier_collector_4[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_cancel_collector"></a>';
              echo '</td>';
            echo '</tr>';
          echo '</form>';
        echo '</table>';
      }

      $i++;
    }
  }
?>
