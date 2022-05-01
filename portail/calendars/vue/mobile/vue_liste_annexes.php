<?php
  /***********/
  /* Annexes */
  /***********/
  echo '<div class="zone_calendars">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/calendars/annexes_grey.png" alt="annexes_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Les annexes</div>';
    echo '</div>';

    if (!empty($annexes))
    {
      $i = 0;

      echo '<div class="zone_calendriers">';
        foreach ($annexes as $annexe)
        {
          if ($i % 2 == 0)
            echo '<div class="zone_calendrier zone_calendrier_margin">';
          else
            echo '<div class="zone_calendrier">';
            // Image
            echo '<img src="../../includes/images/calendars/annexes/mini/' . $annexe->getAnnexe() . '" alt="' . $annexe->getAnnexe() . '" title="' . $annexe->getTitle() . '" class="calendrier" />';

            // Nom
            echo '<div class="titre_calendrier">' . $annexe->getTitle() . '</div>';

            // Boutons
            echo '<div class="zone_boutons">';
              // Télécharger
              echo '<a href="../../includes/images/calendars/annexes/' . $annexe->getAnnexe() . '" class="download_calendar" download><img src="../../includes/icons/calendars/download_grey.png" alt="download_grey" title="Télécharger" class="download_icon" /></a>';

              // Supprimer
              if ($preferences->getManage_calendars() == 'Y')
              {
                echo '<form id="delete_annexe_' . $annexe->getId() . '" method="post" action="calendars.php?action=doSupprimerAnnexe" class="delete_calendar">';
                  echo '<input type="hidden" name="id_annexe" value="' . $annexe->getId() . '" />';
                  echo '<input type="hidden" name="team_annexe" value="' . $annexe->getTeam() . '" />';
                  echo '<input type="submit" name="delete_annexe" value="" title="Supprimer l\'annexe" class="delete_calendar_icon eventConfirm" />';
                  echo '<input type="hidden" value="Demander la suppression de cette annexe ?" class="eventMessage" />';
                echo '</form>';
              }
            echo '</div>';
          echo '</div>';

          $i++;
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas d\'annexes présentes...</div>';
  echo '</div>';
?>
