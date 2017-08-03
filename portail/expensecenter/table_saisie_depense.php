<?php
  include('../includes/appel_bdd.php');

  // Nombre utilisateurs
  $reponse = $bdd->query('SELECT COUNT(id) AS nb_users FROM users WHERE identifiant != "admin"');
  $donnees = $reponse->fetch();
  $nb_users = $donnees['nb_users'];
  $reponse->closeCursor();

  echo '<div class="zone_saisie_depense">';

    echo '<form method="post" action="expensecenter/actions.php?year=' . $_GET['year'] . '">';

      echo '<table class="table_saisie_depense">';

        // Nombre de colonnes par ligne
        $nb_colonnes = 5;

        // Nombre de Lignes
        $nb_lignes = ceil($nb_users / $nb_colonnes);

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

            $reponse = $bdd->query('SELECT id, identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC LIMIT ' . $premiere_entree . ', ' . $nb_colonnes);

            while($donnees = $reponse->fetch())
            {
              echo '<td class="titre_user_depense">';
                echo '<div class="zone_avatar_films">';
                  if (isset($donnees['avatar']) AND !empty($donnees['avatar']))
                    echo '<img src="../connexion/avatars/' . $donnees['avatar'] . '" alt="avatar" title="' . $donnees['full_name'] . '" class="avatar_films" />';
                  else
                    echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $donnees['full_name'] . '" class="avatar_films" />';
                echo '</div>';

                echo '<span class="full_name_films">' . $donnees['full_name'] . '</span>';
              echo '</td>';

              $nb_users_line++;
            }

            $reponse->closeCursor();
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
                $reponse = $bdd->query('SELECT id, identifiant, full_name FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

                echo '<select name="buyer_user" class="buyer" required>';
                  echo '<option value="" hidden>Choisissez...</option>';

                  while($donnees = $reponse->fetch())
                  {
                    if ($donnees['identifiant'] == $_SESSION['buyer'])
                      echo '<option value="' . $_SESSION['buyer'] . '" selected>' . $donnees['full_name'] . '</option>';
                    else
                      echo '<option value="' . $donnees['identifiant'] . '">' . $donnees['full_name'] . '</option>';
                  }

                  echo '</select>';

                  $reponse->closeCursor();

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
