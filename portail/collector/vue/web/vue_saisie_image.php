<?php
  /**************************/
  /* Zone de saisie d'image */
  /**************************/
  echo '<div id="zone_add_image" class="fond_saisie_collector">';
    echo '<div class="zone_saisie_collector">';
      // Titre
      echo '<div class="titre_saisie_collector">Ajouter une image</div>';

      // Bouton fermeture
      echo '<a id="fermerImage" class="zone_close"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" enctype="multipart/form-data" class="form_saisie_image">';
        // Type de saisie
        echo '<input type="hidden" name="type_collector" value="I" />';

        // Zone saisie image
        echo '<div class="zone_image_left">';
          // Saisie image
          echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

          echo '<div class="zone_parcourir_image">';
            echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieCollector" required />';
          echo '</div>';

          echo '<div class="mask_image">';
            echo '<img id="image_collector" alt="" class="image" />';
          echo '</div>';
        echo '</div>';

        // Zone saisie infos
        echo '<div class="zone_image_right">';
          // Saisie speaker
          if (!empty($_SESSION['save']['other_speaker']))
            echo '<select name="speaker" id="speaker_2" class="saisie_speaker speaker_autre" required>';
          else
            echo '<select name="speaker" id="speaker_2" class="saisie_speaker" required>';
              echo '<option value="" hidden>Choisissez...</option>';

              foreach ($listeUsers as $identifiant => $user)
              {
                if ($user['team'] == $_SESSION['user']['equipe'])
                {
                  if ($identifiant == $_SESSION['save']['speaker'])
                    echo '<option value="' . $identifiant . '" selected>' . $user['pseudo'] . '</option>';
                  else
                    echo '<option value="' . $identifiant . '">' . $user['pseudo'] . '</option>';
                }
              }

              if (!empty($_SESSION['save']['other_speaker']))
                echo '<option value="other" selected>Autre</option>';
              else
                echo '<option value="other">Autre</option>';
            echo '</select>';

          // Saisie "Autre"
          if (!empty($_SESSION['save']['other_speaker']))
            echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name_2" class="saisie_other_collector" />';
          else
            echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name_2" class="saisie_other_collector" style="display: none;" />';

          // Saisie date
          echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker_image" class="saisie_date_collector" required />';

          // Bouton
          echo '<div class="zone_bouton_saisie_image">';
            echo '<input type="submit" name="insert_collector" value="Ajouter" id="bouton_saisie_image" class="saisie_bouton" />';
          echo '</div>';

          // Saisie contexte
          echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte_image">' . $_SESSION['save']['context'] . '</textarea>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
