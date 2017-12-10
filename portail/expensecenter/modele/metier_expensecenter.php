<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/expenses.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $annee_existante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4) FROM expense_center ORDER BY SUBSTR(date, 1, 4) ASC');
      while($donnees = $reponse->fetch())
      {
        if ($year == $donnees['SUBSTR(date, 1, 4)'])
          $annee_existante = true;
      }
      $reponse->closeCursor();
    }

    return $annee_existante;
  }

  // METIER : Lecture nombre d'utilisateurs inscrits
  // RETOUR : Nombre d'utilisateurs
  function countUsers()
  {
    global $bdd;

    $reponse = $bdd->query('SELECT COUNT(id) AS nb_users FROM users WHERE identifiant != "admin" AND reset != "I"');
    $donnees = $reponse->fetch();

    $nb_users = $donnees['nb_users'];

    $reponse->closeCursor();

    return $nb_users;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar, expenses FROM users WHERE identifiant != "admin" AND reset != "I" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $user->getId(),
                      'identifiant' => $user->getIdentifiant(),
                      'pseudo'      => $user->getPseudo(),
                      'avatar'      => $user->getAvatar(),
                      'expenses'    => $user->getExpenses()
                    );

      // On ajoute la ligne au tableau
      array_push($listeUsers, Profile::withData($myUser));
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture années distinctes
  // RETOUR : Liste des années existantes
  function getOnglets()
  {
    $onglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4) FROM expense_center ORDER BY SUBSTR(date, 1, 4) ASC');
    while($donnees = $reponse->fetch())
    {
      array_push($onglets, $donnees['SUBSTR(date, 1, 4)']);
    }
    $reponse->closeCursor();

    return $onglets;
  }

  // METIER : Lecture liste des parts pour chaque dépense
  // RETOUR : Tableau des parts
  function getExpenses($year, $list_users, $nb_users)
  {
    // Initialisation tableaux des dépenses
    $listeExpenses = array();
    $listeParts    = array();
    $tableauResume = array();

    global $bdd;

    // Récupération d'une liste des dépenses
    $reponse = $bdd->query('SELECT * FROM expense_center WHERE SUBSTR(date, 1, 4) = ' . $year . ' ORDER BY date DESC, id DESC');
    while($donnees = $reponse->fetch())
    {
      // Ajout d'un objet Expenses (instancié à partir des données de la base) au tableau de dépenses
      array_push($listeExpenses, Expenses::withData($donnees));
    }
    $reponse->closeCursor();

    // Récupération d'une liste des parts
    $reponse2 = $bdd->query('SELECT * FROM expense_center_users ORDER BY id_expense DESC, identifiant ASC');
    while($donnees2 = $reponse2->fetch())
    {
      // Ajout d'un objet Parts (instancié à partir des données de la base) au tableau de dépenses
      array_push($listeParts, Parts::withData($donnees2));
    }
    $reponse2->closeCursor();

    // On consolide un nouveau tableau repésentant chaque ligne du tableau résumé
    $i = 0;

    foreach ($listeExpenses as $expense)
    {
      $tableauParts = array();
      $part_trouve = false;

      foreach ($list_users as $user)
      {
        foreach ($listeParts as $part)
        {
          if ($part->getId_expense() == $expense->getId())
          {
            if ($part->getIdentifiant() == $user->getIdentifiant())
            {
              $myParts = array('identifiant' => $user->getIdentifiant(),
                               'part'        => $part->getParts()
                              );

              $part_trouve = true;
            }
          }

          if ($part_trouve == true)
            break;
        }

        if ($part_trouve == false)
        {
          $myParts = array('identifiant' => $user->getIdentifiant(),
                           'part'        => 0
                          );
        }
        else
          $part_trouve = false;

        // On ajoute la ligne au sous-tableau de parts
        array_push($tableauParts, $myParts);
      }

      // var_dump($tableauParts);

      // On compte le nombre d'utilisateurs et on remplit le tableau final seulement si on a atteint le nombre total d'utilisateurs inscrits
      if (count($tableauParts) == $nb_users)
      {
        // On cherche le pseudo si l'utilisateur est toujours inscrit
        $pseudo_trouve = false;

        foreach ($list_users as $user)
        {
          if ($listeExpenses[$i]->getBuyer() == $user->getIdentifiant())
          {
            $name_b        = $user->getPseudo();
            $oldUser       = false;
            $pseudo_trouve = true;
          }
        }

        if ($pseudo_trouve == false)
        {
          $name_b  = "un ancien utilisateur";
          $oldUser = true;
        }

        // On génère une ligne dans le tableau final
        $myResume = array('id_expense' => $listeExpenses[$i]->getId(),
                          'price'      => str_replace('.', ',', number_format($listeExpenses[$i]->getPrice(), 2)),
                          'buyer'      => $listeExpenses[$i]->getBuyer(),
                          'name_b'     => $name_b,
                          'oldUser'    => $oldUser,
                          'date'       => formatDateForDisplay($listeExpenses[$i]->getDate()),
                          'tableParts' => $tableauParts,
                          'comment'    => $listeExpenses[$i]->getComment()
                         );

         // var_dump($myResume);

         array_push($tableauResume, $myResume);
      }

      $i++;
    }

    // var_dump($tableauResume);

    return $tableauResume;
  }

  // METIER : Insertion d'une dépense & mise à jour des dépenses utilisateur
  // RETOUR : Aucun
  function insertExpense($post, $list_users, $nb_users)
  {
    // Récupération des données
    $date    = date("Ymd");
    $price   = str_replace(',', '.', htmlspecialchars($post['depense']));
    $buyer   = $post['buyer_user'];
    $comment = $post['comment'];

    global $bdd;

    // Test si prix numérique
    if (is_numeric($price))
    {
      // Insertion dans la table expense_center
      $req0 = $bdd->prepare('INSERT INTO expense_center(date, price, buyer, comment) VALUES(:date, :price, :buyer, :comment)');
      $req0->execute(array(
        'date'    => $date,
        'price'   => $price,
        'buyer'   => $buyer,
        'comment' => $comment
          ));
      $req0->closeCursor();

      // On récupère le numéro permettant d'identifier la dépenses
      $id_expense = $bdd->lastInsertId();

      // Lecture bilan actuel acheteur
      $req2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $buyer . '"');
      $data2 = $req2->fetch();
      $expense_buyer = $data2['expenses'];
      $req2->closeCursor();

      // Mise à jour du bilan pour l'acheteur (on ajoute la dépense)
      $expense_buyer += $price;

      $req3 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $buyer . '"');
      $req3->execute(array(
        'expenses' => $expense_buyer
      ));
      $req3->closeCursor();

      // On stocke le tableau des parts en fonction de l'utilisateur si la part n'est pas à 0
      $list_parts_users = array();
      $nb_parts_total   = 0;
      $i                = 0;

      foreach ($list_users as $user)
      {
        if ($post['depense_user'][$i] != 0)
        {
          $myList_parts_users = array('identifiant' => $user->getIdentifiant(),
                                      'part'        => $post['depense_user'][$i]
                                   );

          array_push($list_parts_users, $myList_parts_users);

          $nb_parts_total += $post['depense_user'][$i];
        }

        $i++;
      }

      // Insertions des parts & mise à jour du bilan pour chaque utilisateur
      foreach ($list_parts_users as $ligne)
      {
        // Insertion dans la table expense_center_users
        $req4 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
        $req4->execute(array(
          'id_expense'  => $id_expense,
          'identifiant' => $ligne['identifiant'],
          'parts'       => $ligne['part']
            ));
        $req4->closeCursor();

        // Lecture bilan actuel utilisateur
        $req5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $ligne['identifiant'] . '"');
        $data5 = $req5->fetch();
        $expense_user = $data5['expenses'];
        $req5->closeCursor();

        // Mise à jour du bilan pour chaque utilisateur (on retire au total)
        $expense_user -= ($price / $nb_parts_total) * $ligne['part'];

        $req6 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $ligne['identifiant'] . '"');
        $req6->execute(array(
          'expenses' => $expense_user
        ));
        $req6->closeCursor();
      }

      // Message insertion effectuée
      $_SESSION['alerts']['depense_added'] = true;
    }
    // Sinon on sauve les données et on affiche une erreur
    else
    {
      // On stocke le prix, l'acheteur et la liste des parts en cas d'erreur pour les réinsérer
      $list_parts = array();
      $i = 0;

      $_SESSION['price']   = $post['depense'];
      $_SESSION['buyer']   = $buyer;
      $_SESSION['comment'] = $comment;

      // Stockage de la liste des parts en fonction de l'utilisateur
      for ($i = 0; $i < $nb_users; $i++)
      {
        $list_parts[$i] = $post['depense_user'][$i];
      }

      $_SESSION['tableau_parts'] = $list_parts;

      // Message prix non numérique
      $_SESSION['alerts']['not_numeric'] = true;
    }
  }

  // METIER : Modification d'une dépense
  // RETOUR : Aucun
  function updateExpense($id_modify, $post, $list_users)
  {
    $price_new   = str_replace(',', '.', htmlspecialchars($post['depense']));
    $buyer_new   = $post['buyer_user'];
    $comment_new = $post['comment'];

    if (is_numeric($price_new))
    {
      global $bdd;

      // Lecture dépense (avant mise à jour)
      $req1 = $bdd->query('SELECT * FROM expense_center WHERE id = ' . $id_modify);
      $data1 = $req1->fetch();
      $myOldExpense = Expenses::withData($data1);
      $req1->closeCursor();

      //var_dump($myOldExpense);

      // Lecture bilan actuel acheteur
      $req2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $myOldExpense->getBuyer() . '"');
      $data2 = $req2->fetch();
      $expense_buyer = $data2['expenses'];
      $req2->closeCursor();

      //echo 'Ancien acheteur : ' . $myOldExpense->getBuyer() . '<br />';
      //echo 'Ancien bilan acheteur : ' . $expense_buyer . '<br />';

      // Mise à jour du bilan pour l'acheteur (on retire l'ancienne dépense)
      $expense_buyer -= $myOldExpense->getPrice();

      $req3 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $myOldExpense->getBuyer() . '"');
      $req3->execute(array(
        'expenses' => $expense_buyer
      ));
      $req3->closeCursor();

      //echo 'Nouveau bilan acheteur : ' . $expense_buyer . '<br />';

      // Lecture des utilisateurs ayant une part
      $old_list_parts_users = array();
      $nb_parts_total_old   = 0;

      $req4 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $myOldExpense->getId() . ' ORDER BY identifiant ASC');
      while($data4 = $req4->fetch())
      {
        $myOld_list_parts_users = array('identifiant' => $data4['identifiant'],
                                        'part'        => $data4['parts']
                                       );

        array_push($old_list_parts_users, $myOld_list_parts_users);

        $nb_parts_total_old += $data4['parts'];
      }
      $req4->closeCursor();

      //var_dump($old_list_parts_users);
      //var_dump($nb_parts_total_old);

      // Mise à jour du bilan pour chaque utilisateur
      foreach ($old_list_parts_users as $ligne)
      {
        // Lecture bilan actuel utilisateur
        $req5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $ligne['identifiant'] . '"');
        $data5 = $req5->fetch();
        $expense_user = $data5['expenses'];
        $req5->closeCursor();

        // Mise à jour du bilan pour chaque utilisateur (on ajoute au bilan)
        $expense_user += ($myOldExpense->getPrice() / $nb_parts_total_old) * $ligne['part'];

        $req6 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $ligne['identifiant'] . '"');
        $req6->execute(array(
          'expenses' => $expense_user
        ));
        $req6->closeCursor();

        //echo 'ancienne dépense : ' . $myOldExpense->getPrice() . '<br />';
        //echo 'parts total : ' . $nb_parts_total_old . '<br />';
        //echo 'identifiant en cours : ' . $ligne['identifiant'] . '<br />';
        //echo 'parts en cours : ' . $ligne['part'] . '<br />';
        //echo 'depense en cours : ' . $expense_user . '<br />';
      }

      // Mise à jour de la dépense
      $req7 = $bdd->prepare('UPDATE expense_center SET price = :price, buyer = :buyer, comment = :comment WHERE id = ' . $id_modify);
      $req7->execute(array(
        'price'   => $price_new,
        'buyer'   => $buyer_new,
        'comment' => $comment_new
      ));
      $req7->closeCursor();

      // On va lire le nouveau bilan du nouvel acheteur
      $req8 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $buyer_new . '"');
      $data8 = $req8->fetch();
      $expense_buyer = $data8['expenses'];
      $req8->closeCursor();

      //echo 'Ancien bilan acheteur 2 : ' . $expense_buyer . '<br />';

      // Mise à jour du bilan pour le nouvel acheteur (on ajoute la dépense)
      $expense_buyer += $price_new;

      $req9 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $buyer_new . '"');
      $req9->execute(array(
        'expenses' => $expense_buyer
      ));
      $req9->closeCursor();

      //echo 'Nouveau bilan acheteur 2 : ' . $expense_buyer . '<br />';

      // On stocke le nouveau tableau des parts en fonction de l'utilisateur si la part n'est pas à 0
      $new_list_parts_users = array();
      $nb_parts_total_new   = 0;
      $i                    = 0;

      foreach ($list_users as $user)
      {
        if ($post['depense_user'][$i] != 0)
        {
          $myNew_list_parts_users = array('identifiant' => $user->getIdentifiant(),
                                          'part'        => $post['depense_user'][$i]
                                         );

          array_push($new_list_parts_users, $myNew_list_parts_users);

          $nb_parts_total_new += $post['depense_user'][$i];
        }

        $i++;
      }

      //var_dump($new_list_parts_users);
      //var_dump($nb_parts_total_new);

      // Suppression de toutes les anciennes parts
      $req10 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense = ' . $id_modify);

      // Insertions des nouvelles parts & mise à jour du bilan pour chaque utilisateur
      foreach ($new_list_parts_users as $ligne)
      {
        // Insertion dans la table expense_center_users
        $req11 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
        $req11->execute(array(
          'id_expense'  => $myOldExpense->getId(),
          'identifiant' => $ligne['identifiant'],
          'parts'       => $ligne['part']
            ));
        $req11->closeCursor();

        // Lecture bilan actuel utilisateur
        $req11 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $ligne['identifiant'] . '"');
        $data11 = $req11->fetch();
        $expense_user = $data11['expenses'];
        $req11->closeCursor();

        // Mise à jour du bilan pour chaque utilisateur (on retire au total)
        $expense_user -= ($price_new / $nb_parts_total_new) * $ligne['part'];

        //var_dump($expense_user);

        $req12 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $ligne['identifiant'] . '"');
        $req12->execute(array(
          'expenses' => $expense_user
        ));
        $req12->closeCursor();
      }

      // Message modification effectuée
      $_SESSION['alerts']['depense_modified'] = true;
    }
    else
      $_SESSION['alerts']['not_numeric'] = true;
  }

  // METIER : Suppression d'une dépense
  // RETOUR : Aucun
  function deleteExpense($id_delete)
  {
    global $bdd;

    // Lecture dépense
    $req1 = $bdd->query('SELECT * FROM expense_center WHERE id = ' . $id_delete);
    $data1 = $req1->fetch();
    $myExpense = Expenses::withData($data1);
    $req1->closeCursor();

    // Lecture bilan actuel acheteur
    $req2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $myExpense->getBuyer() . '"');
    $data2 = $req2->fetch();
    $expense_buyer = $data2['expenses'];
    $req2->closeCursor();

    // Mise à jour du bilan pour l'acheteur (on retire la dépense)
    $expense_buyer -= $myExpense->getPrice();

    $req3 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $myExpense->getBuyer() . '"');
    $req3->execute(array(
      'expenses' => $expense_buyer
    ));
    $req3->closeCursor();

    // Lecture des utilisateurs ayant une part
    $list_parts_users = array();
    $nb_parts_total   = 0;

    $req4 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $myExpense->getId() . ' ORDER BY identifiant ASC');
    while($data4 = $req4->fetch())
    {
      $myList_parts_users = array('identifiant' => $data4['identifiant'],
                                  'part'        => $data4['parts']
                                 );

      array_push($list_parts_users, $myList_parts_users);

      $nb_parts_total += $data4['parts'];
    }
    $req4->closeCursor();

    // Suppression des parts & mise à jour du bilan pour chaque utilisateur
    foreach ($list_parts_users as $ligne)
    {
      // Lecture bilan actuel utilisateur
      $req5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $ligne['identifiant'] . '"');
      $data5 = $req5->fetch();
      $expense_user = $data5['expenses'];
      $req5->closeCursor();

      // Mise à jour du bilan pour chaque utilisateur (on ajoute au bilan)
      $expense_user += ($myExpense->getPrice() / $nb_parts_total) * $ligne['part'];

      $req6 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $ligne['identifiant'] . '"');
      $req6->execute(array(
        'expenses' => $expense_user
      ));
      $req6->closeCursor();

      // Suppression des parts
      $req7 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense = ' . $id_delete);

      // Suppression de la dépense
      $req8 = $bdd->exec('DELETE FROM expense_center WHERE id = ' . $id_delete);
    }

    // Message suppression effectuée
    $_SESSION['alerts']['depense_deleted'] = true;
  }
?>
