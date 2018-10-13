<?php
  echo '<div class="zone_collectors">';

  if ($nbPages > 0)
  {
    foreach ($listeCollectors as $collector)
    {
      /*********************************************/
      /* Visualisation normale (sans modification) */
      /*********************************************/
      echo '<div class="zone_collector" id="modifier_collector_2[' . $collector->getId() . ']">';
        echo '<div class="zone_collector_haut">';
          // Modification
          echo '<a onclick="afficherMasquerRow(\'modifier_collector[' . $collector->getId() . ']\'); afficherMasquerRow(\'modifier_collector_2[' . $collector->getId() . ']\');" title="Modifier la phrase culte" class="icone_modify_collector"></a>';

          // Suppression
          echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
            echo '<input type="submit" name="delete_collector" value="" title="Supprimer la phrase culte" class="icon_delete_collector" />';
          echo '</form>';

          // Avatar
          if (!empty($collector->getAvatar_s()))
            echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
          else
            echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';

          // Vote
          echo '<a onclick="afficherMasquer(\'modifier_vote[' . $collector->getId() . ']\'); afficherMasquer(\'link_form_vote[' . $collector->getId() . ']\');" id="link_form_vote[' . $collector->getId() . ']" name="vote_user" class="link_current_vote">';
            $founded = false;

            foreach ($listeVotesUsers as $vote)
            {
              if ($vote->getId_collector() == $collector->getId())
              {
                echo '<img src="../../includes/icons/smileys/' . $vote->getVote() . '.png" alt="smiley" class="current_vote" />';
                $founded = true;
                break;
              }
            }

            if ($founded == false)
              echo '<img src="../../includes/icons/smileys/0.png" alt="smiley" class="current_vote" />';
          echo '</a>';

          // Formulaire vote
          echo '<form method="post" action="collector.php?id=' . $collector->getId() . '&action=doVoter&page=' . $_GET['page'] . '" name="form_vote_user" id="modifier_vote[' . $collector->getId() . ']" class="zone_smileys" style="display: none;">';
            // Gestion smiley par défaut
            $no_vote = true;
            foreach ($listeVotesUsers as $vote)
            {
              if ($vote->getId_collector() == $collector->getId() AND !empty($vote->getVote()))
                $no_vote = false;
            }

            if ($no_vote == true)
              echo '<input type="submit" name="smiley_0" value="" class="smiley_0" style="background-size: 30px; width: 30px; height: 30px;" />';
            else
              echo '<input type="submit" name="smiley_0" value="" class="smiley_0" />';

            // Gestion autres smileys
            for ($j = 1; $j <= 8; $j++)
            {
              $founded = false;
              foreach ($listeVotesUsers as $vote)
              {
                if ($vote->getId_collector() == $collector->getId() AND $vote->getVote() == $j)
                {
                  echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley_' . $j . '" style="background-size: 30px; width: 30px; height: 30px;" />';
                  $founded = true;
                }
              }

              if ($founded == false)
                echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley_' . $j . '" />';
            }
          echo '</form>';

          // Pseudo & date
          echo '<div class="pseudo">';
            echo $collector->getName_s();
            echo '<br />';
            echo formatDateForDisplay($collector->getDate_collector());
          echo '</div>';
        echo '</div>';

        echo '<div class="zone_collector_bas">';
          if (!empty($collector->getCollector()))
          {
            // Apostrophe gauche
            echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';

            // Citation
            echo '<div class="text_collector">' . nl2br(formatCollector($collector->getCollector())) . '</div>';

            // Apostrophe droite
            echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';

            // Rapporteur
            echo '<div class="author_collector">Par ' . $collector->getName_a() . '.</div>';
          }

          // Contexte
          if (!empty($collector->getContext()))
            echo '<div class="text_context">' . nl2br(formatContext($collector->getContext())) . '</div>';

          // Votes tous utilisateurs
          if ($listeVotes != null)
          {
            echo '<div class="zone_votes_users">';
              foreach ($listeVotes as $votes)
              {
                if ($votes['id'] == $collector->getId())
                {
                  for ($k = 1; $k <= 8; $k++)
                  {
                    if ($votes['smileys'][$k] != 0)
                    {
                      echo '<img src="../../includes/icons/smileys/' . $k . '.png" alt="smiley" class="smiley_votes_' . $k . '" />';

                      // Pseudos
                      $listeUsersSmiley = '';
                      foreach ($votes['identifiants'][$k] as $identifiants)
                      {
                        $listeUsersSmiley .= $identifiants['pseudo'] . ', ';
                      }

                      echo '<span class="noms_votes_' . $k . '">';
                        echo substr($listeUsersSmiley, 0, -2);
                      echo '</span>';
                    }
                  }
                }
              }
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      /***************************/
      /* Caché pour modification */
      /***************************/
      echo '<div class="zone_collector" id="modifier_collector[' . $collector->getId() . ']" style="display: none;">';
        echo '<form method="post" action="collector.php?modify_id=' . $collector->getId() . '&action=doModifier&page=' . $_GET['page'] . '">';
          echo '<div class="zone_collector_haut">';
            // Validation modification
            echo '<input type="submit" name="delete_collector" value="" title="Valider" class="icon_validate_collector" />';

            // Annulation modification
            echo '<a onclick="afficherMasquerRow(\'modifier_collector[' . $collector->getId() . ']\'); afficherMasquerRow(\'modifier_collector_2[' . $collector->getId() . ']\');" title="Annuler" class="icone_cancel_collector"></a>';

            // Avatar
            if (!empty($collector->getAvatar_s()))
              echo '<img src="../../profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
            else
              echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';

            // Modification speaker
            if (!empty($collector->getSpeaker()))
            {
              echo '<select name="speaker" id="speaker[' . $collector->getId() . ']" onchange="afficherModifierOther(\'speaker[' . $collector->getId() . ']\', \'other_speaker[' . $collector->getId() . ']\');" class="modify_speaker" required>';
                echo '<option value="" hidden>Choisissez...</option>';

                foreach ($listeUsers as $user)
                {
                  if ($user->getIdentifiant() == $collector->getSpeaker())
                    echo '<option value="' . $collector->getSpeaker() . '" selected>' . $user->getPseudo() . '</option>';
                  else
                    echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                }

                if ($collector->getType_s() == "other")
                  echo '<option value="other" selected>Autre</option>';
                else
                  echo '<option value="other">Autre</option>';
              echo '</select>';
            }
            else
            {
              echo '<div class="old_user">';
                echo $collector->getName_s();
              echo '</div>';
            }

            // Modification "Autre"
            if ($collector->getType_s() == "other")
              echo '<input type="text" name="other_speaker" value="' . $collector->getName_s() . '" placeholder="Nom" maxlength="100" id="other_speaker[' . $collector->getId() . ']" class="modify_other" />';
            else
              echo '<input type="text" name="other_speaker" placeholder="Nom" maxlength="100" id="other_speaker[' . $collector->getId() . ']" class="modify_other" style="display: none;" />';

            // Modification date
            echo '<input type="text" name="date_collector" value="' . formatDateForDisplay($collector->getDate_collector()) . '" placeholder="Date" maxlength="10" id="datepicker[' . $collector->getId() . ']" class="modify_date_collector" required />';
          echo '</div>';

          echo '<div class="zone_collector_bas">';
            echo '<div class="zone_modify_text_collector">';
              // Apostrophe gauche
              echo '<img src="icons/quote_1.png" alt="quote_1" class="quote_1" />';

              // Modification citation
              echo '<textarea name="collector" placeholder="Phrase culte" class="modify_text_collector">' . $collector->getCollector() . '</textarea>';

              // Apostrophe droite
              echo '<img src="icons/quote_2.png" alt="quote_2" class="quote_2" />';
            echo '</div>';

            // Contexte
            echo '<div class="text_context">';
              echo '<textarea name="context" placeholder="Contexte (facultatif)" class="modify_context_collector">' . $collector->getContext() . '</textarea>';
            echo '</div>';
          echo '</form>';
        echo '</div>';
      echo '</div>';
    }
  }
  echo '</div>';
?>
