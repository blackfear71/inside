<?php
  echo '<div id="zone_saisie_montants" class="fond_saisie">';
    echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&filter=' . $_GET['filter'] . '&action=doInsererMontantsMobile" class="form_saisie">';
      // Id dépense (modification)
      echo '<input type="hidden" name="id_expense_saisie" value="" />';

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
              if ($user->getTeam() == $_SESSION['user']['equipe'])
              {
                if ($user->getIdentifiant() == $_SESSION['save']['buyer'])
                  echo '<option value="' . $_SESSION['save']['buyer'] . '" selected>' . $user->getPseudo() . '</option>';
                else
                  echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
              }
            }
          echo '</select>';

          // Frais additionnels
          echo '<div class="zone_saisie_prix">';
            echo '<input type="text" name="depense" value="' . $_SESSION['save']['price'] . '" autocomplete="off" placeholder="Frais additionnels" maxlength="6" class="saisie_prix" />';
            echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro" />';
          echo '</div>';

          // Réduction
          echo '<div class="zone_saisie_reduction">';
            echo '<input type="number" name="reduction" value="' . $_SESSION['save']['reduction'] . '" autocomplete="off" placeholder="Réduction" min="1" max="100" class="saisie_reduction" />';
            echo '<img src="../../includes/icons/expensecenter/percent_grey.png" alt="percent_grey" title="Pourcentage" class="euro" />';
          echo '</div>';

          // Date de saisie
          echo '<input type="date" name="date_expense" value="' . $_SESSION['save']['date_expense'] . '" placeholder="Date" maxlength="10" autocomplete="off" class="saisie_date_depense" required />';

          // Commentaire
          echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

          // Titre
          echo '<div id="titre_explications_montants" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Informations</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
          echo '</div>';

          // Explications
          echo '<div id="afficher_explications_montants" class="texte_explications_montants" style="display: none;">';
            echo 'Vous pouvez saisir ici une dépense en montants ainsi que les éventuels frais additonnels associés (frais de livraison, réduction).
            <strong>Seules des montants positifs peuvent être saisis et au moins 1 montant doit être saisi</strong>.';

            echo '<br /><br />';

            echo 'Les frais ne peuvent être également que positifs et seront répartis équitablement entre tous les participants. En cas de réduction du prix, lors du calcul de chaque part
            un arrondi est appliqué. Si une différence est constatée entre le montant réellement payé et le montant total calculé, la différence est ajoutée au montant de l\'acheteur ou s\'il
            ne participe pas au premier utilisateur ayant une part.';

            echo '<br /><br />';

            echo 'Il n\'est pas possible de faire de régularisation avec cette saisie, veuillez utiliser la saisie en parts pour cela.';
          echo '</div>';

          // Titre
          echo '<div class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/users_grey.png" alt="users_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Répartition utilisateurs</div>';
          echo '</div>';

          // Montants utilisateurs
          echo '<div class="zone_saisie_utilisateurs">';
            foreach ($listeUsers as $user)
            {
              if ($user->getTeam() == $_SESSION['user']['equipe'])
              {
                $savedAmounts = false;

                if (isset($_SESSION['save']['tableau_montants']) AND !empty($_SESSION['save']['tableau_montants']))
                {
                  if (isset($_SESSION['save']['tableau_montants'][$user->getIdentifiant()]))
                    $savedAmounts = true;
                }

                if ($savedAmounts == true)
                  echo '<div class="zone_saisie_utilisateur part_selected" id="zone_user_montant_' . $user->getId() . '">';
                else
                  echo '<div class="zone_saisie_utilisateur" id="zone_user_montant_' . $user->getId() . '">';
                  // Utilisateur
                  echo '<div class="zone_saisie_utilisateur_avatar">';
                    // Avatar
                    $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_saisie_depense" />';

                    // Pseudo
                    echo '<div class="pseudo_saisie_depense">' . formatString($user->getPseudo(), 8) . '</div>';
                  echo '</div>';

                  // Identifiant (caché)
                  echo '<input type="hidden" name="identifiant_montant[]" value="' . $user->getIdentifiant() . '" />';

                  // Montant
                  echo '<div class="zone_saisie_montant">';
                    // Saisie
                    if ($savedAmounts == true)
                      echo '<input type="text" name="montant_user[]" maxlength="6" value="' . $_SESSION['save']['tableau_montants'][$user->getIdentifiant()] . '" id="montant_user_' . $user->getId() . '" class="montant" />';
                    else
                      echo '<input type="text" name="montant_user[]" maxlength="6" value="" id="montant_user_' . $user->getId() . '" class="montant" />';

                    // Symbole
                    echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro_saisie" />';
                  echo '</div>';
                echo '</div>';
              }
            }
          echo '</div>';
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_expense" value="Valider" id="validerSaisieMontants" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisieMontants" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
