<?php
  if ($_GET['action'] == 'goAjouter')
    echo '<form method="post" action="missions.php?action=doAjouter" enctype="multipart/form-data" class="form_saisie_mission">';
  else
  {
    echo '<form method="post" action="missions.php?action=doModifier" enctype="multipart/form-data" class="form_saisie_mission form_saisie_mission_70">';
      echo '<input type="hidden" name="id_mission" value="' . $detailsMission->getId() . '" />';
  }

    // Taille maximale images
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

    echo '<table class="table_mission">';
      // Image & titre
      echo '<tr>';
        echo '<td colspan="3" class="zone_saisie_image_mission">';
          // Titre
          echo '<input type="text" value="' . $detailsMission->getMission() . '" name="mission" placeholder="Titre de la mission" maxlength="255" class="input_mission_title" required />';

          // Image
          if ($_GET['action'] == 'goAjouter')
          {
            echo '<div class="zone_image_mission">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

              echo '<div class="zone_parcourir_missions">';
                echo '<div class="label_parcourir">Bannière (1920 x 800 px)</div>';
                echo '<input type="file" accept=".png" name="mission_image" class="bouton_parcourir_mission loadBanner" required />';
              echo '</div>';

              echo '<img id="banner" alt="" class="preview_image_mission" />';
            echo '</div>';
          }
          else
          {
            echo '<div class="zone_image_mission">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

              echo '<div class="zone_parcourir_missions">';
                echo '<div class="label_parcourir">Bannière (1920 x 800 px)</div>';
                echo '<input type="file" accept=".png" name="mission_image" class="bouton_parcourir_mission loadBanner" />';
              echo '</div>';

              echo '<img src="../../includes/images/missions/banners/' . $detailsMission->getReference() . '.png" id="banner" alt="' . $detailsMission->getReference() . '" class="preview_image_mission" />';
            echo '</div>';
          }
        echo '</td>';
      echo '</tr>';

      // Icônes
      echo '<tr>';
        // Gauche
        echo '<td class="icons_mission">';
          echo '<div class="zone_parcourir_mission_icones">';
            echo '<div class="info_icon">Icône gauche (500 x 500 px)</div>';
            if ($_GET['action'] == 'goAjouter')
              echo '<input type="file" accept=".png" name="mission_icone_g" class="bouton_parcourir_mission_icones loadLeft" required />';
            else
              echo '<input type="file" accept=".png" name="mission_icone_g" class="bouton_parcourir_mission_icones loadLeft" />';

            if (!empty($detailsMission->getReference()))
              echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_g.png" alt="' . $detailsMission->getReference() . '_g" id="button_g" class="preview_icon_mission" />';
            else
              echo '<img id="button_g" alt="" class="preview_icon_mission" />';
          echo '</div>';
        echo '</td>';

        // Milieu
        echo '<td class="icons_mission">';
          echo '<div class="zone_parcourir_mission_icones">';
            echo '<div class="info_icon">Icône milieu (500 x 500 px)</div>';
            if ($_GET['action'] == 'goAjouter')
              echo '<input type="file" accept=".png" name="mission_icone_m" class="bouton_parcourir_mission_icones loadMiddle" required />';
            else
              echo '<input type="file" accept=".png" name="mission_icone_m" class="bouton_parcourir_mission_icones loadMiddle" />';

            if (!empty($detailsMission->getReference()))
              echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_m.png" alt="' . $detailsMission->getReference() . '_m" id="button_m" class="preview_icon_mission" />';
            else
              echo '<img id="button_m" alt="" class="preview_icon_mission" />';
          echo '</div>';
        echo '</td>';

        // Droite
        echo '<td class="icons_mission">';
          echo '<div class="zone_parcourir_mission_icones">';
            echo '<div class="info_icon">Icône droite (500 x 500 px)</div>';
            if ($_GET['action'] == 'goAjouter')
              echo '<input type="file" accept=".png" name="mission_icone_d" class="bouton_parcourir_mission_icones loadRight" required />';
            else
              echo '<input type="file" accept=".png" name="mission_icone_d" class="bouton_parcourir_mission_icones loadRight" />';

            if (!empty($detailsMission->getReference()))
              echo '<img src="../../includes/images/missions/buttons/' . $detailsMission->getReference() . '_d.png" alt="' . $detailsMission->getReference() . '_d" id="button_d" class="preview_icon_mission" />';
            else
              echo '<img id="button_d" alt="" class="preview_icon_mission" />';
          echo '</div>';
        echo '</td>';
      echo '</tr>';

      // Rift
      echo '<tr>';
        echo '<td colspan="3" class="rift_saisie_mission"></td>';
      echo '</tr>';

      // Dates
      echo '<tr>';
        echo '<td colspan="3" class="dates_saisie_mission">';
          echo 'Du <input type="text" name="date_deb" value="' . formatDateForDisplay($detailsMission->getDate_deb()) . '" placeholder="Date de début" maxlength="10" autocomplete="off" id="datepicker_saisie_deb" class="input_mission_date" required />';
          echo ' au <input type="text" name="date_fin" value="' . formatDateForDisplay($detailsMission->getDate_fin()) . '" placeholder="Date de fin" maxlength="10" autocomplete="off" id="datepicker_saisie_fin" class="input_mission_date" required />';
        echo '</td>';
      echo '</tr>';

      // Rift
      echo '<tr>';
        echo '<td colspan="3" class="rift_saisie_mission"></td>';
      echo '</tr>';

      // Heure de lancement
      echo '<tr>';
        echo '<td colspan="3" class="heure_saisie_mission">';
          echo '&Agrave; partir de ';

          // Heures
          echo '<select name="heures" class="select_time_mission" required>';
            if (empty($detailsMission->getHeure()))
              echo '<option value="" disabled selected hidden>hh</option>';
            else
              echo '<option value="" disabled hidden>hh</option>';

            for ($i = 0; $i <= 23; $i++)
            {
              if (!empty($detailsMission->getHeure()) AND substr($detailsMission->getHeure(), 0, 2) == $i)
              {
                if ($i < 10)
                  echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
                else
                  echo '<option value="' . $i . '" selected>' . $i . '</option>';
              }
              else
              {
                if (substr($detailsMission->getHeure(), 0, 2) == '  ')
                  echo '<option value="" disabled selected hidden>hh</option>';

                if ($i < 10)
                  echo '<option value="0' . $i . '">0' . $i . '</option>';
                else
                  echo '<option value="' . $i . '">' . $i . '</option>';
              }
            }
          echo '</select>';

          // Minutes
          echo '<select name="minutes" class="select_time_mission" required>';
            if (empty($detailsMission->getHeure()))
              echo '<option value="" disabled selected hidden>mm</option>';
            else
              echo '<option value="" disabled hidden>mm</option>';

            for ($i = 0; $i <= 11; $i++)
            {
              if (!empty($detailsMission->getHeure()) AND (substr($detailsMission->getHeure(), 2, 2) / 5) == $i)
              {
                if ($i < 2)
                  echo '<option value="0' . 5*$i . '" selected>0' . 5*$i . '</option>';
                else
                  echo '<option value="' . 5*$i . '" selected>' . 5*$i . '</option>';
              }
              else
              {
                if (substr($detailsMission->getHeure(), 2, 2) == '  ')
                  echo '<option value="" disabled selected hidden>mm</option>';

                if ($i < 2)
                  echo '<option value="0' . 5*$i . '">0' . 5*$i . '</option>';
                else
                  echo '<option value="' . 5*$i . '">' . 5*$i . '</option>';
              }
            }
          echo '</select>';
        echo '</td>';
      echo '</tr>';

      // Description / contexte
      echo '<tr>';
        echo '<td colspan="3" class="description_saisie_mission">';
          echo '<textarea placeholder="Description" name="description" class="textarea_description_mission" required>' . $detailsMission->getDescription() . '</textarea>';
        echo '</td>';
      echo '</tr>';

      // Référence
      echo '<tr>';
        echo '<td class="td_saisie_mission_left">';
          echo 'Référence';
        echo '</td>';

        echo '<td colspan="2" class="td_saisie_mission_right">';
          if ($_GET['action'] == 'goAjouter')
            echo '<input type="text" placeholder="Référence" name="reference" value="' . $detailsMission->getReference() . '" maxlength="255" class="input_mission_reference" required />';
          else
          {
            echo '<input type="hidden" name="reference" value="' . $detailsMission->getReference() . '" />';
            echo '<div class="reference_mission">' . $detailsMission->getReference() . '</div>';
          }
        echo '</td>';
      echo '</tr>';

      // Rift
      echo '<tr>';
        echo '<td class="rift_saisie_mission_left"></td>';
        echo '<td colspan="2" class="rift_saisie_mission_right"></td>';
      echo '</tr>';

      // Objectif
      echo '<tr>';
        echo '<td class="td_saisie_mission_left">';
          echo 'Objectif';
        echo '</td>';

        echo '<td colspan="2" class="td_saisie_mission_right">';
          if ($_GET['action'] == 'goAjouter')
            echo '<input type="text" placeholder="Objectif" name="objectif" value="' . $detailsMission->getObjectif() . '" class="input_mission_objectif" required />';
          else
            echo '<input type="text" placeholder="Objectif" name="objectif" value="' . $detailsMission->getObjectif() . '" class="input_mission_objectif" required />';
        echo '</td>';
      echo '</tr>';

      // Rift
      echo '<tr>';
        echo '<td class="rift_saisie_mission_left"></td>';
        echo '<td colspan="2" class="rift_saisie_mission_right"></td>';
      echo '</tr>';

      // Explications
      echo '<tr>';
        echo '<td class="td_saisie_mission_left">';
          echo 'Explications';
        echo '</td>';

        echo '<td colspan="2" class="td_saisie_mission_right">';
          echo '<textarea name="explications" placeholder="Explications (utiliser %objectif%)" class="textarea_right_mission" required>' . $detailsMission->getExplications() . '</textarea>';
        echo '</td>';
      echo '</tr>';

      // Rift
      echo '<tr>';
        echo '<td class="rift_saisie_mission_left"></td>';
        echo '<td colspan="2" class="rift_saisie_mission_right"></td>';
      echo '</tr>';

      // Conclusion
      echo '<tr>';
        echo '<td class="td_saisie_mission_left no_border">';
          echo 'Conclusion';
        echo '</td>';

        echo '<td colspan="2" class="td_saisie_mission_right no_border">';
          echo '<textarea name="conclusion" placeholder="Conclusion (fin de mission)" class="textarea_right_mission" required>' . $detailsMission->getConclusion() . '</textarea>';
        echo '</td>';
      echo '</tr>';
    echo '</table>';

    // Bouton ajout ou modification
    if ($_GET['action'] == 'goAjouter')
      echo '<input type="submit" name="create_mission" value="Créer la mission" class="submit_mission" />';
    else
      echo '<input type="submit" name="update_mission" value="Modifier la mission" class="submit_mission" />';

  echo '</form>';

  // Classement (uniquement sur les missions existantes)
  if ($_GET['action'] == 'goModifier')
  {
    echo '<div class="zone_classement_mission">';
      echo '<div class="titre_classement">Classement</div>';

      if (!empty($listeParticipantsParEquipes))
      {
        foreach ($listeParticipantsParEquipes as $equipe => $participantsParEquipes)
        {
          echo '<div class="titre_equipe">' . $listeEquipesParticipants[$equipe]->getTeam() . '</div>';

          foreach ($participantsParEquipes as $participant)
          {
            echo '<div class="classement_user">';
              echo '<div class="pseudo_classement">' . $participant->getRank() . '. ' . formatUnknownUser($participant->getPseudo(), true, false) . '</div>';
              echo '<div class="total_classement">' . $participant->getTotal() . '</div>';
            echo '</div>';
          }
        }
      }
      else
        echo '<div class="empty">Pas encore de participants...</div>';
    echo '</div>';
  }
?>
