<?php
  echo '<div id="zone_saisie_preference" class="fond_saisie">';
    echo '<div class="div_saisie">';
      // Titre
      echo '<div class="zone_titre_saisie"></div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          // Préférence
          echo '<form method="post" action="" class="form_saisie_preference">';
            echo '<input type="hidden" name="id_film" value="" />';

            for ($i = 0; $i <= 5; $i++)
            {
              echo '<img src="../../includes/icons/moviehouse/stars/star' . $i . '.png" alt="star' . $i . '" class="icone_preference" />';
              echo '<input type="submit" name="preference_' . $i . '" value="" class="input_preference" />';
            }
          echo '</form>';

          // Participation / vue
          echo '<form method="post" action="" class="form_saisie_participation">';
            echo '<input type="hidden" name="id_film" value="" />';

            // Je participe
            echo '<input type="submit" name="participate" value="" title="Je participe !" class="input_participate_no" />';

            // J'ai vu
            echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="input_seen_no" />';
          echo '</form>';
        echo '</div>';
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerSaisiePreference" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
