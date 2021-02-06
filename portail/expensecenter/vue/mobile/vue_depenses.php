<?php
  echo '<div class="zone_depenses">';
    // Titre
    echo '<div id="titre_depenses_utilisateurs" class="titre_section">';
      echo '<img src="../../includes/icons/expensecenter/expenses_grey.png" alt="expenses_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Les dépenses de ' . $_GET['year'] . '</div>';
      echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
    echo '</div>';

    echo '<div id="afficher_depenses_utilisateurs" class="zone_depenses_users" style="display: none;">';
      if (!empty($listeDepenses))
      {
        foreach ($listeDepenses as $depense)
        {
          // Dépense
          echo '<a id="details_depense_' . $depense->getId() . '" class="zone_depense afficherDetailsDepense">';
            // Date
            echo '<div class="zone_depense_date">';
              // Jour
              echo '<div class="zone_depense_date_jour">' . substr($depense->getDate(), 6, 2) . '</div>';

              // Mois
              echo '<div class="zone_depense_date_mois">' . formatMonthForDisplayLight(substr($depense->getDate(), 4, 2)) . '</div>';
            echo '</div>';

            // Acheteur
            $avatarFormatted = formatAvatar($depense->getAvatar(), $depense->getPseudo(), 2, 'avatar');

            echo '<div class="zone_depense_avatar">';
              echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';
            echo '</div>';

            // Prix
            echo '<div class="zone_depense_prix prix_depense">';
              echo formatAmountForDisplay($depense->getPrice());
            echo '</div>';

            // Parts
            echo '<div class="zone_depense_users">';
              // Nombre d'utilisateurs ou régularisation
              if (!empty($depense->getParts()))
              {
                echo '<div class="zone_depense_icone_nombre">';
                  // Image
                  echo '<img src="../../includes/icons/expensecenter/users_grey.png" alt="users_grey" title="Nombre d\'utilisateurs" class="icone_depense" />';

                  // Nombre de bénéficiaires
                  echo '<div class="nombre_users_depense">' . $depense->getNb_users() . '</div>';
                echo '</div>';
              }
              else
                echo '<div class="zone_depense_regularisation">RÉGUL</div>';
            echo '</div>';
          echo '</a>';
        }
      }
      else
        echo '<div class="empty">Aucune dépense pour cette année ou ce filtre...</div>';
    echo '</div>';
  echo '</div>';
?>
