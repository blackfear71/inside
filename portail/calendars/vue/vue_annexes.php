<?php
  if (!empty($annexes))
  {
    foreach ($annexes as $annexe)
    {
      echo '<div class="zone_annexe">';
        // Image
        echo '<img src="../../includes/images/calendars/annexes/mini/' . $annexe->getAnnexe() . '" alt="' . $annexe->getAnnexe() . '" title="' . $annexe->getTitle() . '" class="img_annexe" />';

        // Nom
        echo '<div class="text_annexe">' . $annexe->getTitle() . '</div>';

        // Actions
        if ($preferences->getManage_calendars() == "Y")
        {
          echo '<a href="../../includes/images/calendars/annexes/' . $annexe->getAnnexe() . '" class="download_calendar" download>Télécharger</a>';

          echo '<form method="post" action="calendars.php?id_annexe=' . $annexe->getId() . '&action=doSupprimerAnnexe">';
            echo '<input type="submit" name="delete_annexe" value="" title="Supprimer l\'annexe" onclick="if(!confirm(\'Demander la suppression de cette annexe ?\')) return false;" class="delete_calendar" />';
          echo '</form>';
        }
        else
          echo '<a href="../../includes/images/calendars/annexes/' . $annexe->getAnnexe() . '" class="download_calendar_2" download>Télécharger</a>';
      echo '</div>';
    }
  }
?>
