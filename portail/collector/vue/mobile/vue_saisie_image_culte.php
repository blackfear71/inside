<?php
  echo '<div id="zone_saisie_image" class="fond_saisie">';
    echo '<form method="post" action="collector.php?action=doAjouterMobile&page=' . $_GET['page'] . '" enctype="multipart/form-data" class="form_saisie">';
      // Type de saisie
      echo '<input type="hidden" name="type_collector" value="I" />';

      // Id dépense (modification)
      echo '<input type="hidden" name="id_saisie_image" value="" />';

      // Titre
      echo '<div class="zone_titre_saisie">Saisir une image culte</div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/collector/collector_grey.png" alt="collector_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">L\'image culte</div>';
          echo '</div>';

          // Speaker
          echo '<select name="speaker" id="speaker" class="saisie_speaker" required>';
            echo '<option value="" hidden>Choisissez...</option>';

            foreach ($listeUsers as $identifiant => $user)
            {
              if ($identifiant == $_SESSION['save']['speaker'])
                echo '<option value="' . $identifiant . '" selected>' . $user['pseudo'] . '</option>';
              else
                echo '<option value="' . $identifiant . '">' . $user['pseudo'] . '</option>';
            }

            if (!empty($_SESSION['save']['other_speaker']))
              echo '<option value="other" selected>Autre</option>';
            else
              echo '<option value="other">Autre</option>';
          echo '</select>';

          // Saisie "Autre"
          if (!empty($_SESSION['save']['other_speaker']))
            echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" />';
          else
            echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" style="display: none;" />';

          // Date
          echo '<input type="date" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" autocomplete="off" class="saisie_date_collector" required />';

          // Saisie image
          echo '<div class="zone_image_saisie">';
            // Saisie image
            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

            echo '<div class="zone_parcourir_image">';
              echo '<div class="symbole_saisie_image">+</div>';
              echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieCollector" required />';
            echo '</div>';

            echo '<div class="mask_image">';
              echo '<img id="image_collector" alt="" class="image" />';
            echo '</div>';
          echo '</div>';

          // Saisie contexte
          echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['save']['context'] . '</textarea>';
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_collector" value="Valider" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisieImageCulte" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
