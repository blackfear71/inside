<?php
  echo '<table cellpadding="0" class="table_mission">';
    // Image & titre
    echo '<tr>';
      echo '<td colspan="2">';
        echo '<div class="titre_mission_first">' . $detailsMission->getMission() . '</div>';
        echo '<img src="images/' . $detailsMission->getReference() . '.png" alt="' . $detailsMission->getReference() . '" title="' . $detailsMission->getMission() . '" class="img_details_mission" />';
      echo '</td>';
    echo '</tr>';

    // Date
    echo '<tr>';
      echo '<td colspan="2" class="dates_details_mission">';
        echo 'Evènement du <strong>' . formatDateForDisplay($detailsMission->getDate_deb()) . '</strong> au <strong>' . formatDateForDisplay($detailsMission->getDate_fin()) . '</strong>.';
      echo '</td>';
    echo '</tr>';

    // Rift
    echo '<tr>';
      echo '<td colspan="2" class="rift_details_mission"></td>';
    echo '</tr>';

    // Heure
    echo '<tr>';
      echo '<td colspan="2" class="heure_details_mission">';
        echo 'Chaque jour à partir de <strong>' . formatTimeForDisplayLight($detailsMission->getHeure()) . '</strong>.';
      echo '</td>';
    echo '</tr>';

    // Description / contexte
    echo '<tr>';
      echo '<td colspan="2" class="description_details_mission">';
        echo nl2br($detailsMission->getDescription());
      echo '</td>';
    echo '</tr>';

    // Objectif
    echo '<tr>';
      echo '<td class="td_details_mission_left">';
        echo 'Objectif journalier';
      echo '</td>';

      echo '<td class="td_details_mission_right">';
        echo formatExplanation($detailsMission->getExplications(), $detailsMission->getObjectif(), '%objectif%');
      echo '</td>';
    echo '</tr>';

    // Rift
    echo '<tr>';
      echo '<td class="rift_details_mission_left"></td>';
      echo '<td class="rift_details_mission_right"></td>';
    echo '</tr>';

    // Objectif du jour
    if (date('Ymd') <= $detailsMission->getDate_fin())
    {
      echo '<tr>';
        echo '<td class="td_details_mission_left">';
          echo 'Aujourd\'hui';
        echo '</td>';

        echo '<td class="td_details_mission_right">';
          echo '<div class="score_avancement">' . $missionUser['daily'] . '</div>';
          echo '<meter min="0" max="' . $detailsMission->getObjectif() . '" value="' . $missionUser['daily'] . '" class="avancement_details_mission"></meter>';
        echo '</td>';
      echo '</tr>';

      // Rift
      echo '<tr>';
        echo '<td class="rift_details_mission_left"></td>';
        echo '<td class="rift_details_mission_right"></td>';
      echo '</tr>';
    }

    // Objectif général
    echo '<tr>';
      $nb_jours_event = ecartDatesEvent($detailsMission->getDate_deb(), $detailsMission->getDate_fin());
      $objectif_total = $detailsMission->getObjectif() * $nb_jours_event;

      echo '<td class="td_details_mission_left">';
        echo 'Avancement mission';
      echo '</td>';

      echo '<td class="td_details_mission_right">';
        echo '<div class="score_avancement">' . $missionUser['event'] . '</div>';
        echo '<meter min="0" max="' . $objectif_total . '" value="' . $missionUser['event'] . '" class="avancement_details_mission"></meter>';
      echo '</td>';
    echo '</tr>';

    // Rift
    echo '<tr>';
      echo '<td class="rift_details_mission_left"></td>';
      echo '<td class="rift_details_mission_right"></td>';
    echo '</tr>';

    // Participants
    echo '<tr>';
      echo '<td class="td_details_mission_left" style="border: 0;">';
        echo 'Participants';
      echo '</td>';

      echo '<td class="td_details_mission_right" style="border: 0; padding-bottom: 0; padding-top: 0; padding-left: 10px;">';
        foreach ($participants as $participant)
        {
          echo '<div class="zone_avatar_details_mission">';
            if (!empty($participant->getAvatar()))
              echo '<img src="../../profil/avatars/' . $participant->getAvatar() . '" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_details_mission" />';
            else
              echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_details_mission" />';

            echo '<div class="pseudo_details_mission">' . $participant->getPseudo() . '</div>';
          echo '</div>';
        }
      echo '</td>';
    echo '</tr>';
  echo '</table>';
?>
