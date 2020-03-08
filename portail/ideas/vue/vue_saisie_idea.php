<?php
  /**************************/
  /* Zone de saisie rapport */
  /**************************/
  echo '<div id="zone_add_idea" class="fond_saisie_idea">';
    echo '<div class="zone_saisie_idea">';
      // Titre
      echo '<div class="titre_saisie_idea">Proposer une idée</div>';

      // Bouton fermeture
      echo '<a id="fermerIdee" class="zone_close"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      echo '<form method="post" action="ideas.php?view=' . $_GET['view'] . '&action=doInserer" class="form_saisie_idea">';
        // Explications
        echo '<div class="zone_explications">';
          echo '<div class="titre_saisie_idee">';
            echo 'Le cycle de vie d\'une idée';
          echo '</div>';

          echo '<img src="../../includes/images/ideas/cycle_idee.png" alt="cycle_idee" class="cycle_vie" />';
        echo '</div>';

        // Zone saisie idée
        echo '<div class="zone_saisie">';
          echo '<input type="text" name="subject_idea" placeholder="Titre" maxlength="255" class="saisie_titre" required />';

          echo '<div class="zone_bouton_saisie">';
            echo '<input type="submit" name="new_idea" value="Soumettre" id="bouton_saisie_idea" class="saisie_bouton" />';
          echo '</div>';

          echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_contenu"></textarea>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
