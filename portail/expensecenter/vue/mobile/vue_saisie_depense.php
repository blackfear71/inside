<?php
  echo '<div id="zone_saisie_depense" class="fond_saisie">';
    echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&filter=' . $_GET['filter'] . '&action=doInsererMobile" class="form_saisie">';
      // Id dépense (modification)
      echo '<input type="hidden" name="id_expense_saisie" value="" />';

      // Titre
      echo '<div class="zone_titre_saisie">Saisir une dépense</div>';

      // Saisie
      echo '<div class="zone_contenu_saisie">';
        echo '<div class="contenu_saisie">';
          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_section" />';
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
            echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro" />';
          echo '</div>';

          // Date de saisie
          echo '<input type="date" name="date_expense" value="' . $_SESSION['save']['date_expense'] . '" placeholder="Date" maxlength="10" autocomplete="off" class="saisie_date_depense" required />';

          // Commentaire
          echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

          // Titre
          echo '<div id="titre_explications_depense" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Informations</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
          echo '</div>';

          // Explications
          echo '<div id="afficher_explications_depense" class="texte_explications_montants" style="display: none;">';
            echo 'Vous pouvez saisir ici une dépense dont le coût sera ensuite réparti équitablement sur chaque participant en fonction du nombre de parts. <strong>Les parts sont limitées à 5 maximum par personne</strong>.';

            echo '<br /><br />';

            echo '2 types de saisies peuvent être effectuées :';

            echo '<ul>';
              echo '<li>Une dépense : <strong>le prix doit être positif et des parts doivent être présentes sur au moins un utilisateur</strong>. Le coût est réparti proportionnellement entre chaque participant.</li>';
              echo '<li>Une régularisation : <strong>le prix est soit positif soit négatif, mais le nombre de parts doit être nul pour tous</strong>. Le coût est simplement ajouté au bilan de l\'acheteur.</li>';
            echo '</ul>';
          echo '</div>';

          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/users_grey.png" alt="users_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les parts utilisateurs</div>';
          echo '</div>';

          // Parts utilisateurs
          echo '<div class="zone_saisie_utilisateurs">';
            foreach ($listeUsers as $user)
            {
              $savedParts = false;

              if (isset($_SESSION['save']['tableau_parts']) AND !empty($_SESSION['save']['tableau_parts']))
              {
                if (isset($_SESSION['save']['tableau_parts'][$user->getIdentifiant()]) AND $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] > 0)
                  $savedParts = true;
              }

              if ($savedParts == true)
                echo '<div class="zone_saisie_utilisateur part_selected" id="zone_user_' . $user->getId() . '">';
              else
                echo '<div class="zone_saisie_utilisateur" id="zone_user_' . $user->getId() . '">';
                // Avatar
                echo '<div class="zone_saisie_utilisateur_avatar">';
                  $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';
                echo '</div>';

                // Identifiant (caché)
                echo '<input type="hidden" name="identifiant_quantite[]" value="' . $user->getIdentifiant() . '" />';

                // Bouton -
                echo '<div id="retirer_part_' . $user->getId() . '" class="bouton_quantite retirerPart">-</div>';

                // Quantité
                echo '<div class="zone_quantite">';
                  if ($savedParts == true)
                    echo '<input type="text" name="quantite_user[]" value="' . $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] . '" id="quantite_user_' . $user->getId() . '" class="quantite part_selected" readonly />';
                  else
                    echo '<input type="text" name="quantite_user[]" value="0" id="quantite_user_' . $user->getId() . '" class="quantite" readonly />';
                echo '</div>';

                // Bouton +
                echo '<div id="ajouter_part_' . $user->getId() . '" class="bouton_quantite ajouterPart">+</div>';
              echo '</div>';
            }
          echo '</div>';
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_expense" value="Valider" id="validerSaisieDepense" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisieDepense" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
