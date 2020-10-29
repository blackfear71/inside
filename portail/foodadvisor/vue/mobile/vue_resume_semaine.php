<?php
  echo '<div class="zone_propositions_resume">';
    // Titre
    echo '<div id="titre_propositions_resume" class="titre_section">';
      echo '<img src="../../includes/icons/foodadvisor/week_grey.png" alt="week_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Le résumé de la semaine</div>';
      echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
    echo '</div>';

    // Affichage du résulé de la semaine
    echo '<div id="afficher_propositions_resume" class="zone_propositions_resume_semaine">';
      $numeroJour = 0;

      // Choix
      foreach ($choixSemaine as $jour => $choixJour)
      {
        $numeroJour++;

        if (!empty($choixJour))
        {
          echo '<div class="zone_proposition proposition_normal">';
            // Jour
            echo '<div class="zone_resume_jour">' . formatDayForDisplayLight($jour) . '</div>';

            // Restaurant
            echo '<div class="nom_resume">' . formatString($choixJour->getName(), 20) . '</div>';

            // Réserveur
            if (!empty($choixJour->getCaller()))
            {
              $avatarFormatted = formatAvatar($choixJour->getAvatar(), $choixJour->getPseudo(), 2, 'avatar');

              echo '<div class="caller_normal">';
                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="caller_proposition" />';
              echo '</div>';
            }
            else
            {
              // Suppression si disponible
              if ($numeroJour < date('N') OR ($numeroJour == date('N') AND date('H') >= 13))
              {
                echo '<form id="delete_resume_' . $numeroJour . '" method="post" action="foodadvisor.php?action=doSupprimerResume" class="form_delete_choix">';
                  echo '<input type="hidden" name="id_resume" value="' . $choixJour->getId_restaurant() . '" />';
                  echo '<input type="hidden" name="date_resume" value="' . $choixJour->getDate() . '" />';
                  echo '<input type="submit" name="delete_resume" value="" title="Supprimer le choix" class="bouton_delete_choix eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer ce choix ?" class="eventMessage" />';
                echo '</form>';
              }
            }
          echo '</div>';
        }
        else
        {
          echo '<div class="zone_proposition proposition_normal">';
            // Jour
            echo '<div class="zone_resume_jour">' . formatDayForDisplayLight($jour) . '</div>';

            // Choix absent
            echo '<div class="no_proposal">Pas de proposition pour ce jour...</div>';
          echo '</div>';
        }
      }
    echo '</div>';
  echo '</div>';
?>
