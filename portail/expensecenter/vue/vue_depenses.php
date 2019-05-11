<?php
  /****************/
  /*** Dépenses ***/
  /****************/
  echo '<div class="zone_expenses_right">';
    // Dépenses
    echo '<div class="titre_section"><img src="../../includes/icons/expensecenter/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_section" />Les dépenses</div>';

    if (!empty($listeDepenses))
    {
      foreach ($listeDepenses as $depense)
      {
        echo '<div class="zone_depense" id="' . $depense->getId() . '">';
          echo '<div id="zone_shadow_' . $depense->getId() . '" class="zone_shadow">';
            // Partie supérieure
            echo '<div class="zone_depense_top">';
              echo '<div class="zone_achat">';
                // Avatar acheteur
                if (!empty($depense->getAvatar()))
                  echo '<img src="../../includes/images/profil/avatars/' . $depense->getAvatar() . '" alt="avatar" title="' . $depense->getPseudo() . '" class="avatar" />';
                else
                  echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $depense->getPseudo() . '" class="avatar" />';

                // Pseudo acheteur
                echo '<div class="pseudo_achat">' . $depense->getPseudo() . '</div>';

                // Date achat
                echo '<div class="date_achat">' . formatDateForDisplay($depense->getDate()) . '</div>';

                // Prix
                echo '<div class="valeur_achat">' . formatBilanForDisplay($depense->getPrice()) . '</div>';
              echo '</div>';

              // Liste des parts utilisateurs
              echo '<div class="zone_parts">';
                if (!empty($depense->getParts()))
                {
                  foreach ($depense->getParts() as $parts)
                  {
                    echo '<div class="zone_parts_utilisateur">';
                      // Avatar utilisateur
                      if (!empty($parts->getAvatar()))
                        echo '<img src="../../includes/images/profil/avatars/' . $parts->getAvatar() . '" alt="avatar" title="' . $parts->getPseudo() . '" class="avatar_depense" />';
                      else
                        echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $parts->getPseudo() . '" class="avatar_depense" />';

                      // Nombre de parts
                      echo '<div class="parts_depense">' . $parts->getParts() . '</div>';
                    echo '</div>';
                  }
                }
                else
                  echo '<div class="regularisation">Régularisation</div>';
              echo '</div>';
            echo '</div>';

            // Partie inférieure
            echo '<div class="zone_depense_bottom">';
              // Commentaire
              echo '<div class="commentaire_depense">' . $depense->getComment() . '</div>';

              // Modifier
              echo '<a onclick="updateExpense(\'' . $depense->getId() . '\', \'' . $_GET['year'] . '\');" title="Modifier" class="lien_depense"><img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="icone_depense" /></a>';

              // Supprimer
              echo '<form id="delete_expense_' . $depense->getId() . '" method="post" action="expensecenter.php?year=' . $_GET['year'] . '&delete_id=' . $depense->getId() . '&action=doSupprimer" class="form_supprimer_depense">';
                echo '<input type="submit" name="delete_depense" value="" onclick="if(!confirmAction(\'delete_expense_' . $depense->getId() . '\', \'Supprimer la dépense de ' . formatOnclick($depense->getPseudo()) . ' du ' . formatDateForDisplay($depense->getDate()) . ' et d&rsquo;un montant de ' . $depense->getPrice() . ' € ?\')) return false;" title="Supprimer" class="icone_supprimer_depense" />';
              echo '</form>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas de dépenses pour cette année...</div>';
  echo '</div>';
?>
