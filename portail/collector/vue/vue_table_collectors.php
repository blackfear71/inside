<?php
  $min_golden = 6;

  echo '<div class="zone_collectors">';
    if ($nbPages > 0)
    {
      foreach ($listeCollectors as $collector)
      {
        /*********************************************/
        /* Visualisation normale (sans modification) */
        /*********************************************/
        echo '<div class="zone_collector" id="visualiser_collector_' . $collector->getId() . '">';
          echo '<div class="zone_shadow" id="zone_shadow_' . $collector->getId() . '">';
            if ($collector->getNb_votes() >= $min_golden)
              echo '<div class="zone_collector_haut_golden" id="' . $collector->getId() . '">';
            else
              echo '<div class="zone_collector_haut" id="' . $collector->getId() . '">';

              // Modification
              echo '<a onclick="afficherMasquerNoDelay(\'modifier_collector_' . $collector->getId() . '\'); afficherMasquerNoDelay(\'visualiser_collector_' . $collector->getId() . '\'); initMasonry();" title="Modifier" class="icone_modify_collector"></a>';

              // Suppression
              if ($collector->getType_collector() == "T")
              {
                echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette phrase culte ?\')) return false;">';
                  echo '<input type="submit" name="delete_collector" value="" title="Supprimer la phrase culte" class="icon_delete_collector" />';
                echo '</form>';
              }
              elseif ($collector->getType_collector() == "I")
              {
                echo '<form method="post" action="collector.php?delete_id=' . $collector->getId() . '&action=doSupprimer&page=' . $_GET['page'] . '" onclick="if(!confirm(\'Supprimer cette image ?\')) return false;">';
                  echo '<input type="submit" name="delete_collector" value="" title="Supprimer l\'image" class="icon_delete_collector" />';
                echo '</form>';
              }

              // Avatar
              echo '<div class="zone_avatar_collector">';
                if (!empty($collector->getAvatar_s()))
                  echo '<img src="../../includes/images/profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                else
                  echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
              echo '</div>';

              // Vote
              echo '<a onclick="afficherMasquerNoDelay(\'modifier_vote_' . $collector->getId() . '\'); afficherMasquerNoDelay(\'link_form_vote_' . $collector->getId() . '\');" id="link_form_vote_' . $collector->getId() . '" class="link_current_vote">';
                $founded = false;

                foreach ($listeVotesUsers as $vote)
                {
                  if ($vote->getId_collector() == $collector->getId())
                  {
                    echo '<img src="../../includes/icons/common/smileys/' . $vote->getVote() . '.png" alt="smiley" class="current_vote" />';
                    $founded = true;
                    break;
                  }
                }

                if ($founded == false)
                  echo '<img src="../../includes/icons/common/smileys/0.png" alt="smiley" class="current_vote" />';
              echo '</a>';

              // Formulaire vote
              echo '<form method="post" action="collector.php?id=' . $collector->getId() . '&action=doVoter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" name="form_vote_user" id="modifier_vote_' . $collector->getId() . '" class="zone_smileys" style="display: none;">';
                // Gestion smiley par défaut
                $no_vote = true;

                foreach ($listeVotesUsers as $vote)
                {
                  if ($vote->getId_collector() == $collector->getId() AND !empty($vote->getVote()))
                    $no_vote = false;
                }

                if ($no_vote == true)
                  echo '<input type="submit" name="smiley_0" value="" class="smiley smiley_0" style="background-size: 35px; width: 35px; height: 35px;" />';
                else
                  echo '<input type="submit" name="smiley_0" value="" class="smiley smiley_0" />';

                // Gestion autres smileys
                for ($j = 1; $j <= 8; $j++)
                {
                  $founded = false;
                  foreach ($listeVotesUsers as $vote)
                  {
                    if ($vote->getId_collector() == $collector->getId() AND $vote->getVote() == $j)
                    {
                      echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley smiley_' . $j . '" style="background-size: 35px; width: 35px; height: 35px;" />';
                      $founded = true;
                    }
                  }

                  if ($founded == false)
                    echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley smiley_' . $j . '" />';
                }
              echo '</form>';

              // Pseudo
              echo '<div class="pseudo_collector">';
                echo $collector->getName_s();
              echo '</div>';

              // Date
              echo '<div class="zone_date_collector">';
                echo '<img src="../../includes/icons/collector/date.png" alt="date" class="icone_collector" />' . formatDateForDisplay($collector->getDate_collector());
              echo '</div>';
            echo '</div>';

            echo '<div class="zone_collector_bas">';
              if (!empty($collector->getCollector()))
              {
                if ($collector->getType_collector() == "T")
                {
                  // Apostrophe gauche
                  echo '<img src="../../includes/icons/collector/quote_1.png" alt="quote_1" class="quote_1" />';

                  // Citation
                  echo '<div class="text_collector">' . nl2br(formatCollector($collector->getCollector())) . '</div>';

                  // Apostrophe droite
                  echo '<img src="../../includes/icons/collector/quote_2.png" alt="quote_2" class="quote_2" />';
                }
                elseif ($collector->getType_collector() == "I")
                {
                  // Image
                  echo '<img src="../../includes/images/collector/' . $collector->getCollector() . '" alt="' . $collector->getCollector() . '" class="image_collector" />';
                }

                // Rapporteur
                echo '<div class="author_collector">Par ' . $collector->getName_a() . '</div>';
              }

              // Contexte
              if (!empty($collector->getContext()))
              {
                if ($collector->getNb_votes() >= $min_golden)
                  echo '<div class="text_context_golden">' . nl2br(formatContext($collector->getContext())) . '</div>';
                else
                  echo '<div class="text_context">' . nl2br(formatContext($collector->getContext())) . '</div>';
              }

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
                          // Smileys
                          echo '<img src="../../includes/icons/common/smileys/' . $k . '.png" alt="smiley" class="smiley_votes_' . $k . '" />';

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
        echo '</div>';

        /***************************/
        /* Caché pour modification */
        /***************************/
        echo '<div class="zone_collector" id="modifier_collector_' . $collector->getId() . '" style="display: none; position: relative; z-index: 2;">';
          echo '<form method="post" action="collector.php?update_id=' . $collector->getId() . '&action=doModifier&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" class="zone_shadow">';
            if ($collector->getNb_votes() >= $min_golden)
              echo '<div class="zone_collector_haut_golden">';
            else
              echo '<div class="zone_collector_haut">';
              // Validation modification
              echo '<input type="submit" name="modify_collector" value="" title="Valider" class="icon_validate_collector" />';

              // Annulation modification
              echo '<a onclick="afficherMasquerNoDelay(\'modifier_collector_' . $collector->getId() . '\'); afficherMasquerNoDelay(\'visualiser_collector_' . $collector->getId() . '\'); initMasonry();" title="Annuler" class="icone_cancel_collector"></a>';

              // Avatar
              echo '<div class="zone_avatar_collector">';
                if (!empty($collector->getAvatar_s()))
                  echo '<img src="../../includes/images/profil/avatars/' . $collector->getAvatar_s() . '" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
                else
                  echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $collector->getName_s() . '" class="avatar_collector" />';
              echo '</div>';

              // Modification speaker
              if (!empty($collector->getSpeaker()))
              {
                echo '<div class="zone_modify_speaker">';
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
                echo '</div>';
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
              echo '<div class="zone_modify_date">';
                echo '<input type="text" name="date_collector" value="' . formatDateForDisplay($collector->getDate_collector()) . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker[' . $collector->getId() . ']" class="modify_date_collector" required />';
              echo '</div>';
            echo '</div>';

            echo '<div class="zone_collector_bas">';
              if ($collector->getType_collector() == "T")
              {
                // Type de saisie
                echo '<input type="hidden" name="type_collector" value="T" />';

                // Apostrophe gauche
                echo '<img src="../../includes/icons/collector/quote_1.png" alt="quote_1" class="quote_1" />';

                // Modification citation
                echo '<textarea name="collector" placeholder="Phrase culte" class="modify_text_collector">' . $collector->getCollector() . '</textarea>';

                // Apostrophe droite
                echo '<img src="../../includes/icons/collector/quote_2.png" alt="quote_2" class="quote_2" />';
              }
              elseif ($collector->getType_collector() == "I")
              {
                // Type de saisie
                echo '<input type="hidden" name="type_collector" value="I" />';

                // Image
                echo '<img src="../../includes/images/collector/' . $collector->getCollector() . '" alt="' . $collector->getCollector() . '" class="image_collector" />';
              }

              // Contexte
              if ($collector->getNb_votes() >= $min_golden)
                echo '<div class="text_context_golden" style="padding-bottom: 10px;">';
              else
                echo '<div class="text_context" style="padding-bottom: 10px;">';
                  echo '<textarea name="context" placeholder="Contexte (facultatif)" class="modify_context_collector">' . $collector->getContext() . '</textarea>';
                echo '</div>';
            echo '</div>';
          echo '</form>';
        echo '</div>';
      }
    }
  echo '</div>';
?>
