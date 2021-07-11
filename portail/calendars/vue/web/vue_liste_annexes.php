<?php
  /***********/
  /* Annexes */
  /***********/
  echo '<div class="zone_calendars">';
    echo '<div class="titre_section"><img src="../../includes/icons/calendars/annexes_grey.png" alt="annexes_grey" class="logo_titre_section" /><div class="texte_titre_section">Les annexes</div></div>';

    if (!empty($annexes))
    {
      echo '<div class="zone_calendriers">';
        foreach ($annexes as $annexe)
        {
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
                echo '<form id="delete_annexe_' . $annexe->getId() . '" method="post" action="calendars.php?action=doSupprimerAnnexe" class="download_calendar" >';
                  echo '<input type="hidden" name="id_annexe" value="' . $annexe->getId() . '" />';
                  echo '<input type="hidden" name="team_annexe" value="' . $annexe->getTeam() . '" />';
                  echo '<input type="submit" name="delete_annexe" value="" title="Supprimer l\'annexe" class="delete_calendar eventConfirm" />';
                  echo '<input type="hidden" value="Demander la suppression de cette annexe ?" class="eventMessage" />';
                echo '</form>';
              }
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas d\'annexes présentes...</div>';
  echo '</div>';
?>
