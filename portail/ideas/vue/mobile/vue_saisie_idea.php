<?php
  echo '<div id="zone_saisie_idee" class="fond_saisie">';
    echo '<form method="post" action="ideas.php?view=' . $_GET['view'] . '&action=doInserer" class="form_saisie">';
      // Titre
      echo '<div class="zone_titre_saisie">Proposer une idée</div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          // Titre
          echo '<div id="titre_cycle_de_vie_idee" class="titre_section">';
            echo '<img src="../../includes/icons/ideas/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Cycle de vie</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
          echo '</div>';

          // Cycle de vie d'une idée
          echo '<div id="afficher_cycle_de_vie_idee" style="display: none;">';
            echo '<img src="../../includes/images/ideas/cycle_idee.png" alt="cycle_idee" class="image_cycle_de_vie" />';
          echo '</div>';

          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Proposer une idée</div>';
          echo '</div>';

          // Titre de l'idée
          echo '<input type="text" name="subject_idea" placeholder="Titre" maxlength="255" class="saisie_titre_idee" required />';

          // Description de l'idée
          echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_description_idee" required></textarea>';
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_idea" value="Valider" id="validerSaisieIdee" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisieIdee" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
