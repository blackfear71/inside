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
          echo '<div id="zone_shadow_' . $collector->getId() . '" class="zone_shadow">';
            if ($collector->getNb_votes() >= $min_golden)
              echo '<div class="zone_collector_haut_golden" id="' . $collector->getId() . '">';
            else
              echo '<div class="zone_collector_haut" id="' . $collector->getId() . '">';

              // Modification
              echo '<a id="modifier_' . $collector->getId() . '" title="Modifier" class="icone_modify_collector modifierCollector"></a>';

              // Suppression
              if ($collector->getType_collector() == "T")
              {
                echo '<form id="delete_collector_' . $collector->getId() . '" method="post" action="collector.php?action=doSupprimer&page=' . $_GET['page'] . '">';
                  echo '<input type="hidden" name="id_col" value="' . $collector->getId() . '" />';
                  echo '<input type="submit" name="delete_collector" value="" title="Supprimer la phrase culte" class="icon_delete_collector eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer cette phrase culte ?" class="eventMessage" />';
                echo '</form>';
              }
              elseif ($collector->getType_collector() == "I")
              {
                echo '<form id="delete_image_' . $collector->getId() . '" method="post" action="collector.php?action=doSupprimer&page=' . $_GET['page'] . '">';
                  echo '<input type="hidden" name="id_col" value="' . $collector->getId() . '" />';
                  echo '<input type="submit" name="delete_collector" value="" title="Supprimer l\'image" class="icon_delete_collector eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer cette image ?" class="eventMessage" />';
                echo '</form>';
              }

              // Avatar
              $avatarFormatted = formatAvatar($collector->getAvatar_s(), $collector->getPseudo_s(), 2, "avatar");

              echo '<div class="zone_avatar_collector">';
                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_collector" />';
              echo '</div>';

              // Vote utilisateur
              echo '<a id="link_form_vote_' . $collector->getId() . '" class="link_current_vote modifierVote">';
                if (isset($listeVotesUsers[$collector->getId()]) AND !empty($listeVotesUsers[$collector->getId()]))
                  echo '<img src="../../includes/icons/common/smileys/' . $listeVotesUsers[$collector->getId()]->getVote() . '.png" alt="smiley" class="current_vote" />';
                else
                  echo '<img src="../../includes/icons/common/smileys/0.png" alt="smiley" class="current_vote" />';
              echo '</a>';

              // Formulaire vote
              echo '<form method="post" action="collector.php?action=doVoter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" name="form_vote_user" id="modifier_vote_' . $collector->getId() . '" class="zone_smileys" style="display: none;">';
                echo '<input type="hidden" name="id_col" value="' . $collector->getId() . '" />';

                // Gestion smileys vote
                if (isset($listeVotesUsers[$collector->getId()]) AND !empty($listeVotesUsers[$collector->getId()]))
                  echo '<input type="submit" name="smiley_0" value="" class="smiley smiley_0" />';
                else
                  echo '<input type="submit" name="smiley_0" value="" class="smiley smiley_0" style="background-size: 35px; width: 35px; height: 35px;" />';

                for ($j = 1; $j <= 8; $j++)
                {
                  if (isset($listeVotesUsers[$collector->getId()]) AND !empty($listeVotesUsers[$collector->getId()]) AND $j == $listeVotesUsers[$collector->getId()]->getVote())
                    echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley smiley_' . $j . '" style="background-size: 35px; width: 35px; height: 35px;" />';
                  else
                    echo '<input type="submit" name="smiley_' . $j . '" value="" class="smiley smiley_' . $j . '" />';
                }
              echo '</form>';

              // Pseudo
              echo '<div class="pseudo_collector">';
                echo formatUnknownUser($collector->getPseudo_s(), true, true);
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
                  echo '<a class="agrandirImage"><img src="../../includes/images/collector/' . $collector->getCollector() . '" alt="' . $collector->getCollector() . '" class="image_collector" /></a>';
                }

                // Rapporteur
                echo '<div class="author_collector">Par ' . formatUnknownUser($collector->getPseudo_a(), false, false) . '</div>';
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
                  if (isset($listeVotes[$collector->getId()]) AND !empty($listeVotes[$collector->getId()]))
                  {
                    // Smileys
                    foreach ($listeVotes[$collector->getId()] as $keySmiley => $votes)
                    {
                      if (!empty($votes))
                        echo '<img src="../../includes/icons/common/smileys/' . $keySmiley . '.png" alt="smiley" class="smiley_votes_' . $keySmiley . '" />';
                    }

                    // Pseudos
                    foreach ($listeVotes[$collector->getId()] as $keyVote => $votes)
                    {
                      if (!empty($votes))
                      {
                        $listeUsersSmiley = '';

                        foreach ($votes as $identifiants)
                        {
                          $listeUsersSmiley .= formatUnknownUser($identifiants['pseudo'], true, false) . ', ';
                        }

                        echo '<span class="noms_votes_' . $keyVote . '">';
                          echo substr($listeUsersSmiley, 0, -2);
                        echo '</span>';
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
          echo '<form method="post" action="collector.php?action=doModifier&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" enctype="multipart/form-data" class="zone_shadow">';
            if ($collector->getNb_votes() >= $min_golden)
              echo '<div class="zone_collector_haut_golden">';
            else
              echo '<div class="zone_collector_haut">';
              echo '<input type="hidden" name="id_col" value="' . $collector->getId() . '" />';

              // Validation modification
              echo '<input type="submit" name="modify_collector" value="" title="Valider" class="icon_validate_collector" />';

              // Annulation modification
              echo '<a id="annuler_' . $collector->getId() . '" title="Annuler" class="icone_cancel_collector annulerCollector"></a>';

              // Avatar
              $avatarFormatted = formatAvatar($collector->getAvatar_s(), $collector->getPseudo_s(), 2, "avatar");

              echo '<div class="zone_avatar_collector">';
                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_collector" />';
              echo '</div>';

              // Modification speaker
              echo '<div class="zone_modify_speaker">';
                echo '<select name="speaker" id="speaker_' . $collector->getId() . '" class="modify_speaker changeSpeaker" required>';
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

              // Modification "Autre"
              if ($collector->getType_s() == "other")
                echo '<input type="text" name="other_speaker" value="' . $collector->getPseudo_s() . '" placeholder="Nom" maxlength="100" id="other_speaker_' . $collector->getId() . '" class="modify_other" />';
              else
                echo '<input type="text" name="other_speaker" placeholder="Nom" maxlength="100" id="other_speaker_' . $collector->getId() . '" class="modify_other" style="display: none;" />';

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
                echo '<div>';
                  echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                  echo '<span class="zone_parcourir_update" id="zone_parcourir_' . $collector->getId() . '">';
                    echo '<input id="fichier_' . $collector->getId() . '" type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_update loadModifierCollector" />';
                  echo '</span>';

                  echo '<div class="mask_update" id="mask_collector_' . $collector->getId() . '">';
                    echo '<img src="../../includes/images/collector/' . $collector->getCollector() . '" id="image_collector_' . $collector->getId() . '" alt="' . $collector->getCollector() . '" class="image_update loadImage" />';
                  echo '</div>';
                echo '</div>';
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
