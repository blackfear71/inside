<?php
  /**************************/
  /* Zone de saisie rapport */
  /**************************/
  echo '<div id="zone_add_report" class="fond_saisie_report">';
    echo '<div class="zone_saisie_report">';
      // Titre
      echo '<div class="titre_saisie_report">Rapporter un bug ou une évolution</div>';

      // Bouton fermeture
      echo '<a id="fermerRapport" class="zone_close"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      echo '<form method="post" action="bugs.php?action=doSignaler" enctype="multipart/form-data" class="form_saisie_report">';
        // Explications
        echo '<div class="explications">';
          echo '<div class="text_saisie_report">';
            echo 'Le site ne présente aucun bug. Si toutefois vous pensez être tombé sur ce qui prétend en être un, vous pouvez le signaler via le formulaire ci-dessous.
            Ce que nous appellerons désormais "évolution" sera traitée dans les plus brefs délais par une équipe exceptionnelle, toujours à votre écoute pour vous
            servir au mieux.';
          echo '</div>';

          echo '<div class="text_saisie_report">';
            echo 'Cette page vous permet de remonter d\'éventuelles <strong>évolutions techniques</strong> à apporter au site et les rapports seront envoyés à l\'administrateur. Vous pouvez inclure
            une image pour plus de précision.';
          echo '</div>';

          echo '<div class="text_saisie_report">';
            echo 'Pour toute demande d\'<strong>évolution fonctionnelle</strong>, veuillez utiliser la page #TheBox.';
          echo '</div>';
        echo '</div>';

        // Zone saisie image
        echo '<div class="zone_saisie_left">';
          // Saisie image
          echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

          echo '<div class="zone_parcourir_image">';
            echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieReport" />';
          echo '</div>';

          echo '<div class="mask_image">';
            echo '<img id="image_report" alt="" class="image" />';
          echo '</div>';
        echo '</div>';

        // Zone saisie rapport
        echo '<div class="zone_saisie_right">';
          echo '<input type="text" name="subject_bug" placeholder="Objet" value="' . $_SESSION['save']['subject_bug'] . '" maxlength="255" class="saisie_objet" required />';

          echo '<select name="type_bug" class="saisie_type" required>';
            echo '<option value="" hidden>Type de demande</option>';

            if (isset($_SESSION['save']['type_bug']) AND $_SESSION['save']['type_bug'] == 'B')
              echo '<option value="B" selected>Bug</option>';
            else
              echo '<option value="B">Bug</option>';

            if (isset($_SESSION['save']['type_bug']) AND $_SESSION['save']['type_bug'] == 'E')
              echo '<option value="E" selected>Evolution</option>';
            else
              echo '<option value="E">Evolution</option>';
          echo '</select>';

          // Bouton d'ajout
          echo '<div class="zone_bouton_saisie">';
            echo '<input type="submit" name="report" value="Soumettre" id="bouton_saisie_bug" class="saisie_bouton width_100" />';
          echo '</div>';

          echo '<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu" required>' . $_SESSION['save']['content_bug'] . '</textarea>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
