<?php
  echo '<div id="zone_saisie_vote" class="fond_saisie">';
    echo '<div class="div_saisie">';
      // Titre
      echo '<div class="zone_titre_saisie"></div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          // Formulaire de vote (smileys)
          echo '<form method="post" action="collector.php?action=doVoter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" class="zone_smileys">';
            // Identifiant collector
            echo '<input type="hidden" name="id_collector" value="" />';

            // Smileys
            for ($i = 0; $i <= 8; $i++)
            {
              echo '<input type="submit" name="smiley_' . $i . '" value="" class="smiley smiley_' . $i . ' validerSaisieVoteCollector" />';
            }
          echo '</form>';
        echo '</div>';
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerSaisieVote" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
