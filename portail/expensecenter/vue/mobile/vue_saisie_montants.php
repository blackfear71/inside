<?php
  echo '<div id="zone_saisie_montants" class="fond_saisie">';
    echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doInsererMontants" class="form_saisie">';
      // Id dépense (modification)
      echo '<input type="hidden" name="id_expense" value="" />';

      // Titre
      echo '<div class="zone_titre_saisie">Saisir des montants</div>';

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

          // Frais additionnels
          echo '<div class="zone_saisie_prix">';
            echo '<input type="text" name="depense" value="' . $_SESSION['save']['price'] . '" autocomplete="off" placeholder="Frais additionnels" maxlength="6" class="saisie_prix" />';
            echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="euros" class="euro" />';
          echo '</div>';

          // Commentaire
          echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/users_grey.png" alt="users_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Répartition utilisateurs</div>';
          echo '</div>';

          // Montants utilisateurs
          echo '<div class="zone_saisie_utilisateurs">';
            foreach ($listeUsers as $user)
            {
              $savedAmounts = false;

              if (isset($_SESSION['save']['tableau_montants']) AND !empty($_SESSION['save']['tableau_montants']))
              {
                if (isset($_SESSION['save']['tableau_montants'][$user->getIdentifiant()]))
                  $savedAmounts = true;
              }

              echo '<div class="zone_saisie_part">';
                // Avatar
                echo '<div class="zone_saisie_part_avatar">';
                  $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';
                echo '</div>';

                // Identifiant (caché)
                echo '<input type="hidden" name="identifiant_montant[' . $user->getId() . ']" value="' . $user->getIdentifiant() . '" />';

                // Montant
                echo '<div class="zone_montant">';
                  // Saisie
                  if ($savedAmounts == true)
                    echo '<input type="text" name="montant_user[' . $user->getId() . ']" maxlength="6" value="' . $_SESSION['save']['tableau_montants'][$user->getIdentifiant()] . '" class="montant" />';
                  else
                    echo '<input type="text" name="montant_user[' . $user->getId() . ']" maxlength="6" value="" class="montant" />';

                  // Symbole
                  echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="euros" class="euro_saisie" />';
                echo '</div>';
              echo '</div>';
            }
          echo '</div>';
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_expense" value="Valider" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisieMontants" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
