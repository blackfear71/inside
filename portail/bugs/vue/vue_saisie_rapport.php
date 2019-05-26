<?php
  /**************************/
  /* Zone de saisie rapport */
  /**************************/
  echo '<div id="zone_add_report" class="fond_saisie_report">';
    echo '<div class="zone_saisie_report">';
      // Titre
      echo '<div class="titre_saisie_report">Rapporter un bug ou une évolution</div>';

      // Bouton fermeture
      echo '<a id="fermerRapport" class="close_index"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

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
          echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

          echo '<div class="zone_parcourir_image">';
            echo '<div class="symbole_saisie_image">+</div>';
            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieReport" />';
          echo '</div>';

          echo '<div class="mask_image">';
            echo '<img id="image_report" alt="" class="image" />';
          echo '</div>';
        echo '</div>';

        // Zone saisie rapport
        echo '<div class="zone_saisie_right">';
          echo '<input type="text" name="subject_bug" placeholder="Objet" maxlength="255" class="saisie_objet" required />';

          echo '<select name="type_bug" class="saisie_type" required>';
            echo '<option value="" hidden>Type de demande</option>';
            echo '<option value="B">Bug</option>';
            echo '<option value="E">Evolution</option>';
          echo '</select>';

          echo '<input type="submit" name="report" value="Soumettre" class="saisie_bouton" />';

          echo '<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu"></textarea>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
