<?php
  echo '<div id="zoneSaisieDepense" class="fond_saisie" style="display: none;">';
    echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doInserer" class="form_saisie">';
      // Titre
      echo '<div class="zone_titre_saisie">';
        echo 'Saisir une dépense';
      echo '</div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/expenses_grey.png" alt="expenses_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">La dépense</div>';
          echo '</div>';

          // Acheteur
          echo '<select name="buyer_user" class="saisie_acheteur" required>';
            echo '<option value="" hidden>Choisissez un acheteur...</option>';

            foreach ($listeUsers as $user)
            {
              if ($user->getIdentifiant() == $_SESSION['save']['buyer'])
                echo '<option value="' . $_SESSION['save']['buyer'] . '" selected>' . $user->getPseudo() . '</option>';
              else
                echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
            }
          echo '</select>';

          // Prix
          echo '<div class="zone_saisie_prix">';
            echo '<input type="text" name="depense" value="' . $_SESSION['save']['price'] . '" autocomplete="off" placeholder="Prix" maxlength="6" class="saisie_prix" required />';
            echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="euros" class="euro" />';
          echo '</div>';

          // Commentaire
          echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/users_grey.png" alt="users_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les parts utilisateurs</div>';
          echo '</div>';

          // Parts utilisateurs
          foreach ($listeUsers as $user)
          {
            $savedParts = false;

            if (isset($_SESSION['save']['tableau_parts']) AND !empty($_SESSION['save']['tableau_parts']))
            {
              if (isset($_SESSION['save']['tableau_parts'][$user->getIdentifiant()]) AND $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] > 0)
                $savedParts = true;
            }

            if ($savedParts == true)
              echo '<div class="zone_saisie_part part_selected" id="zone_user_' . $user->getId() . '">';
            else
              echo '<div class="zone_saisie_part" id="zone_user_' . $user->getId() . '">';
              // Avatar
              echo '<div class="zone_saisie_part_avatar">';
                $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, "avatar");

                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';
              echo '</div>';

              // Identifiant (caché)
              echo '<input type="hidden" name="identifiant_quantite[' . $user->getId() . ']" value="' . $user->getIdentifiant() . '" />';

              // Bouton -
              echo '<div id="retirer_part_' . $user->getId() . '" class="bouton_quantite retirerPart">-</div>';

              // Quantité
              if ($savedParts == true)
                echo '<input type="text" name="quantite_user[' . $user->getId() . ']" value="' . $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] . '" id="quantite_user_' . $user->getId() . '" class="quantite part_selected" readonly />';
              else
                echo '<input type="text" name="quantite_user[' . $user->getId() . ']" value="0" id="quantite_user_' . $user->getId() . '" class="quantite" readonly />';

              // Bouton +
              echo '<div id="ajouter_part_' . $user->getId() . '" class="bouton_quantite ajouterPart">+</div>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_choices" value="Valider" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisieDepense" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
