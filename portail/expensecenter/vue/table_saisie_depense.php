<?php
  /*********************/
  /* Tableau de saisie */
  /*********************/
  echo '<div class="zone_saisie_depense">';
    echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doInserer">';
      echo '<table class="table_saisie_depense">';
        // Nombre de colonnes par ligne
        $nb_colonnes = 5;

        // Nombre de Lignes
        $nb_lignes = ceil($nbUsers / $nb_colonnes);

        // Initalisation position ligne tableau
        $ligne = 1;

        for($i = 0; $i < $nb_lignes; $i++)
        {
          // Ligne titre & users
          echo '<tr>';
            // Si on est sur la première ligne on affiche le titre sinon on affiche une case vide
            if ($ligne == 1)
            {
              echo '<td class="init_table_dates">';
                echo 'Prix et acheteur';
              echo '</td>';
            }

            // On calcule la première entrée à lire par rapport à la précédente
            $premiere_entree = ($ligne - 1) * $nb_colonnes;

            // Initialisation nombre d'utilisateurs par ligne
            $nb_users_line = 0;

            foreach (array_slice($listeUsers, $premiere_entree, $nb_colonnes) as $user)
            {
              echo '<td class="titre_user_depense">';
                echo '<div class="zone_avatar_films">';
                  if (!empty($user->getAvatar()))
                    echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_films" />';
                  else
                    echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_films" />';
                echo '</div>';

                echo '<span class="pseudo_films">' . $user->getPseudo() . '</span>';
              echo '</td>';

              $nb_users_line++;
            }
          echo '</tr>';

          // Lignes de saisie
          echo '<tr>';
            // Si on est sur la première ligne on affiche le champ de saisie du prix sinon on affiche une case vide
            if ($ligne == 1)
            {
              echo '<td rowspan="' . ($nb_lignes * 2 - 1) . '" class="zone_saisie_prix">';
                // Saisie prix
                echo '<input type="text" name="depense" value="' . $_SESSION['price'] . '" placeholder="Prix" maxlength="6" class="saisie_prix" required /> <span class="euro">€</span>';

                // Saisie acheteur
                echo '<select name="buyer_user" class="buyer" required>';
                  echo '<option value="" hidden>Choisissez...</option>';

                    foreach ($listeUsers as $user)
                    {
                      if ($user->getIdentifiant() == $_SESSION['buyer'])
                        echo '<option value="' . $_SESSION['buyer'] . '" selected>' . $user->getPseudo() . '</option>';
                      else
                        echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                    }
                echo '</select>';

                // Saisie Commentaire
                echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire_depense" />' . $_SESSION['comment'] . '</textarea>';
              echo '</td>';
            }

            for($j = 0; $j < $nb_users_line; $j++)
            {
              $nb_max_parts = 5;

              echo '<td>';
                echo '<select name="depense_user[]" class="parts">';
                  for($k = 0; $k <= $nb_max_parts; $k++)
                  {
                    // On calcule l'indice où commencer à récupérer le tableau des parts en fonction de chaque ligne. Le premier numéro de ligne est 1, le premier indice 0 et on a 5 utilisateurs par ligne
                    $l = $j + 5 * ($ligne - 1);

                    // On affiche les parts en mémoire si il y a eu une erreur de saisie
                    if (isset($_SESSION['tableau_parts']) AND is_numeric($_SESSION['tableau_parts'][$l]) AND $_SESSION['tableau_parts'][$l] == $k)
                      echo '<option value="' . $k . '" selected>' . $k . '</option>';
                    else
                      echo '<option value="' . $k . '">' . $k . '</option>';
                  }
                echo '</select>';
              echo '</td>';
            }
          echo '</tr>';

          $ligne++;
        }

      echo '</table>';

      // Validation dépense
      echo '<input type="submit" name="add_depense" value="Rajouter une ligne aux dépenses" class="add_depense" />';
    echo '</form>';
  echo '</div>';
?>
