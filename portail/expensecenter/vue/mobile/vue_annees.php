<?php
  echo '<div id="zone_saisie_annee" class="fond_saisie">';
    echo '<div class="div_saisie">';
      // Titre
      echo '<div class="zone_titre_saisie">';
        echo 'Voir une autre année';
      echo '</div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          foreach ($onglets as $annee)
          {
            if ($annee == date('Y'))
              echo '<a href="expensecenter.php?year=' . $annee . '&action=goConsulter" class="lien_saisie lien_courant">' . $annee . '</a>';
            else
              echo '<a href="expensecenter.php?year=' . $annee . '&action=goConsulter" class="lien_saisie">' . $annee . '</a>';
          }
        echo '</div>';
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerSaisieAnnee" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>