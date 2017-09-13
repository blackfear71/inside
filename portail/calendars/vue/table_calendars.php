<?php
  if (!empty($calendriers))
  {
    $nombre_colonnes = 3;
    $i               = 1;

    echo '<table class="table_calendars">';
      foreach ($calendriers as $calendrier)
      {
          if ($i % $nombre_colonnes == 1) // modulo est le reste de la division de GAUCHE par DROITE
            echo '<tr>';

              echo '<td class="table_calendars_td">';
                echo '<div class="zone_calendars_totale">';
                  echo '<div class="zone_calendar">';
                    echo '<img src="images/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="' . $calendrier->getTitle() . '" title="' . $calendrier->getTitle() . '" class="calendar" />';

                    echo '<div class="mask_calendar_square"></div>';

                    echo '<div class="title_calendar">';
                      echo $calendrier->getTitle();
                    echo '</div>';
                  echo '</div>';

                  echo '<a href="images/' . $calendrier->getYear() . '/' . $calendrier->getCalendar() . '" class="download_calendar" download>Télécharger</a>';

                  echo '<form method="post" action="calendars.php?year=' . $_GET['year'] . '&id_cal=' . $calendrier->getId() . '&action=doSupprimer">';
                    echo '<input type="submit" name="delete_calendar" value="" title="Supprimer le calendrier" onclick="if(!confirm(\'Demander la suppression de ce calendrier ?\')) return false;" class="delete_calendar" />';
                  echo '</form>';
                echo '</div>';
            echo '</td>';

          if ($i % $nombre_colonnes == 0)
            echo '</tr>';

          $i++;
      }
    echo '</table>';
  }
  else
  {
    echo '<div class="zone_no_calendars">';
      echo '<span class="no_calendars">Pas encore de calendriers pour cette année...</span>';
    echo '</div>';
  }
?>
