<?php
  /*********************************/
  /*** Zone de saisie de dépense ***/
  /*********************************/
  echo '<div id="zone_add_depense" style="display: none;" class="fond_saisie_depense">';
    echo '<div class="zone_saisie_depense">';
      // Titre
      echo '<div class="titre_saisie_depense">Saisir une dépense</div>';

      // Bouton fermeture
      echo '<a onclick="resetSaisie(\'zone_add_depense\', \'' . $_GET['year'] . '\');" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      // Saisie dépense
      echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doInserer" class="form_saisie_depense">';
        // Achat
        echo '<div class="zone_saisie_left">';
          // Acheteur
          echo '<select id="select_user" name="buyer_user" class="saisie_buyer" required>';
            echo '<option value="" hidden>Choisissez...</option>';

            foreach ($listeUsers as $user)
            {
              if ($user->getIdentifiant() == $_SESSION['save']['buyer'])
                echo '<option value="' . $_SESSION['save']['buyer'] . '" selected>' . $user->getPseudo() . '</option>';
              else
                echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
            }
          echo '</select>';

          // Prix
          echo '<input type="text" name="depense" value="' . $_SESSION['save']['price'] . '" placeholder="Prix" maxlength="6" class="saisie_prix" required /><div class="euro">€</div>';

          // Commentaire
          echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

          // Bouton validation
          echo '<input type="submit" name="add_depense" value="Valider" class="bouton_validation" />';
        echo '</div>';

        // Parts utilisateurs
        echo '<div class="zone_saisie_right">';
          echo '<div class="zone_saisie_utilisateurs">';
            foreach ($listeUsers as $user)
            {
              $saved_parts = false;

              if (isset($_SESSION['save']['tableau_parts']) AND !empty($_SESSION['save']['tableau_parts']))
              {
                if (isset($_SESSION['save']['tableau_parts'][$user->getIdentifiant()]) AND $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] > 0)
                  $saved_parts = true;
              }

              if ($saved_parts == true)
                echo '<div class="zone_saisie_utilisateur_parts" id="zone_user_' . $user->getId() . '">';
              else
                echo '<div class="zone_saisie_utilisateur" id="zone_user_' . $user->getId() . '">';
                // Pseudo
                echo '<div class="pseudo_depense">' . $user->getPseudo() . '</div>';

                // Avatar
                if (!empty($user->getAvatar()))
                  echo '<img src="../../includes/images/profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_depense" />';
                else
                  echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_depense" />';

                // Identifiant (caché)
                echo '<input type="hidden" name="identifiant_qte[' . $user->getId() . ']" value="' . $user->getIdentifiant() . '" />';

                // Bouton -
                echo '<div onclick="addPart(\'zone_user_' . $user->getId() . '\', \'quantite_user_' . $user->getId() . '\', -1)" class="bouton_qte">-</div>';

                // Quantité
                if ($saved_parts == true)
                  echo '<input type="text" name="quantite_user[' . $user->getId() . ']" value="' . $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] . '" id="quantite_user_' . $user->getId() . '" class="qte" readonly />';
                else
                  // Quantité
                  echo '<input type="text" name="quantite_user[' . $user->getId() . ']" value="0" id="quantite_user_' . $user->getId() . '" class="qte" readonly />';

                // Bouton +
                echo '<div onclick="addPart(\'zone_user_' . $user->getId() . '\', \'quantite_user_' . $user->getId() . '\', 1)" class="bouton_qte">+</div>';
              echo '</div>';
            }
          echo '</div>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>