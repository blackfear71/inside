<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/expenses.php');

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

    $reponse = $bdd->query('SELECT id, identifiant, full_name, avatar FROM users WHERE identifiant != "admin"  AND reset != "I" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $user->getId(),
                      'identifiant' => $user->getIdentifiant(),
                      'full_name'   => $user->getFull_name(),
                      'avatar'      => $user->getAvatar()
                    );

      // On ajoute la ligne au tableau
      array_push($listeUsers, Profile::withData($myUser));
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture bilans dépenses
  // RETOUR : Liste des bilans par utilisateur
  function getBilans($list_users)
  {
    // Initialisation tableau des bilans
    $listeBilans = array();

    global $bdd;

    foreach ($list_users as $user)
    {
      // Calcul des bilans
      $req1 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

      $bilan = 0;

      while($data1 = $req1->fetch())
      {
        $expense = Expenses::withData($data1);

        // Prix d'achat
        $prix_achat = $expense->getPrice();

        // Identifiant de l'acheteur
        $acheteur = $expense->getBuyer();

        // echo 'prix achat : ' . $prix_achat . '<br />';
        // echo 'acheteur : ' . $acheteur . '<br />';

        // Nombre de parts et prix par parts
        $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $expense->getId());

        $nb_parts_total = 0;
        $nb_parts_user = 0;

        while($data2 = $req2->fetch())
        {
          // Nombre de parts total
          $nb_parts_total = $nb_parts_total + $data2['parts'];

          // Nombre de parts de l'utilisateur
          if ($user->getIdentifiant() == $data2['identifiant'])
            $nb_parts_user = $data2['parts'];
        }

        if ($nb_parts_total != 0)
          $prix_par_part = $prix_achat / $nb_parts_total;
        else
          $prix_par_part = 0;

        // echo 'nb parts total : ' . $nb_parts_total . '<br />';
        // echo 'nb parts user : ' . $nb_parts_user . '<br />';
        // echo 'prix par part : ' . $prix_par_part . '<br />';

        // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
        if ($expense->getBuyer() == $user->getIdentifiant())
          $bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_user);
        else
          $bilan = $bilan - ($prix_par_part * $nb_parts_user);

        // echo '<br />';
        // echo '<br />';

        $req2->closeCursor();
      }
      $req1->closeCursor();

      // echo 'BILAN : ' . $bilan . '<br />';
      $bilan_format = str_replace('.', ',', number_format($bilan, 2));

      // On construit un tableau des bilans
      $myBilan = array('identifiant'  => $user->getIdentifiant(),
                       'full_name'    => $user->getFull_name(),
                       'avatar'       => $user->getAvatar(),
                       'bilan'        => $bilan,
                       'bilan_format' => $bilan_format
                      );

      // On ajoute la ligne au tableau
      array_push($listeBilans, Bilans::withData($myBilan));
    }

    return $listeBilans;
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
    $reponse = $bdd->query('SELECT * FROM expense_center WHERE SUBSTR(date, 1, 4) = ' . $year . ' ORDER BY id DESC');

    while($donnees = $reponse->fetch())
    {
      // Ajout d'un objet parcours (instancié à partir des données de la base) au tableau de dépenses
      array_push($listeExpenses, Expenses::withData($donnees));
    }

    $reponse->closeCursor();

    // Récupération d'une liste des parts
    $reponse2 = $bdd->query('SELECT * FROM expense_center_users ORDER BY id_expense DESC, identifiant ASC');

    while($donnees2 = $reponse2->fetch())
    {
      // Ajout d'un objet parcours (instancié à partir des données de la base) au tableau de dépenses
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
                               'part'        => $part->getParts(),
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
                           'part'        => 0,
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
            $name_b = $user->getFull_name();
            $pseudo_trouve = true;
          }
        }

        if ($pseudo_trouve == false)
          $name_b = "un ancien<br />utilisateur";

        // On génère une ligne dans le tableau final
        $myResume = array('id_expense' => $listeExpenses[$i]->getId(),
                          'price'      => str_replace('.', ',', number_format($listeExpenses[$i]->getPrice(), 2)),
                          'buyer'      => $listeExpenses[$i]->getBuyer(),
                          'name_b'     => $name_b,
                          'date'       => formatDateForDisplay($listeExpenses[$i]->getDate()),
                          'tableParts' => $tableauParts,
                          'comment'    => $listeExpenses[$i]->getComment()
                         );

         // var_dump($myResume);

         array_push($tableauResume, $myResume);
      }

      $i++;
    }

    return $tableauResume;
  }

  // METIER : Insertion d'une dépense
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
      $id_expense = $bdd -> lastInsertId();

      // On stocke le tableau des parts en fonction de l'utilisateur si la part n'est pas à 0
      $list_parts_users = array();
      $i = 0;

      foreach ($list_users as $user)
      {
        if ($post['depense_user'][$i] != 0)
        {
          $myList_parts_users = array('identifiant' => $user->getIdentifiant(),
                                      'part'        => $post['depense_user'][$i]
                                   );

          array_push($list_parts_users, $myList_parts_users);
        }

        $i++;
      }

      // Insertions dans la table expense_center_users
      foreach ($list_parts_users as $ligne)
      {
        $req2 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
        $req2->execute(array(
          'id_expense'  => $id_expense,
          'identifiant' => $ligne['identifiant'],
          'parts'       => $ligne['part']
            ));
        $req2->closeCursor();
      }

      // Message insertion effectuée
      $_SESSION['depense_added'] = true;
    }
    // Sinon on sauve les données et on affiche une erreur
    else
    {
      // On stocke le prix, l'acheteur et la liste des parts en cas d'erreur pour les réinsérer
      $list_parts = array();
      $i = 0;

      $_SESSION['price']   = $_POST['depense'];
      $_SESSION['buyer']   = $buyer;
      $_SESSION['comment'] = $comment;

      // Stockage de la liste des parts en fonction de l'utilisateur
      for ($i = 0; $i < $nb_users; $i++)
      {
        $list_parts[$i] = $post['depense_user'][$i];
      }

      $_SESSION['tableau_parts'] = $list_parts;

      // Message prix non numérique
      $_SESSION['not_numeric'] = true;
    }
  }

  // METIER : Modification d'une dépense
  // RETOUR : Aucun
  function modifyExpense($id_modify, $post, $list_users)
  {
    global $bdd;

    // Mise à jour du prix si modifié
    $price = str_replace(',', '.', htmlspecialchars($post['depense']));

    // Mise à jour de l'acheteur si modifié
    $buyer = $post['buyer_user'];

    // Mise à jour du commentaire si modifié
    $comment = $post['comment'];

    $req0 = $bdd->prepare('UPDATE expense_center SET price=:price, buyer=:buyer, comment=:comment WHERE id=' . $id_modify);
    $req0->execute(array(
      'price'   => $price,
      'buyer'   => $buyer,
      'comment' => $comment
    ));
    $req0->closeCursor();

    // On stocke le tableau des parts en fonction de l'utilisateur si la part n'est pas à 0
    $list_parts_users = array();
    $i = 0;

    foreach ($list_users as $user)
    {
      $myList_parts_users = array('identifiant' => $user->getIdentifiant(),
                                  'part'        => $post['depense_user'][$i]
                               );

      array_push($list_parts_users, $myList_parts_users);
      $i++;
    }

    foreach($list_parts_users as $ligne)
    {
      // Si la ligne est mise à 0, on la supprime
      if ($ligne['part'] == 0)
      {
        $req1 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense = ' . $id_modify . ' AND identifiant = "' . $ligne['identifiant'] . '"');
      }
      else
      {
        $a_modifier = false;
        $a_inserer  = false;
        $count      = 0;

        // On cherche si la ligne existe
        $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $id_modify);
        while($data2 = $req2->fetch())
        {
          if ($ligne['identifiant'] == $data2['identifiant'])
          {
            if ($ligne['part'] != $data2['parts'])
              $a_modifier = true;

            $count++;
          }
        }
        $req2->closeCursor();

        if ($count == 0)
          $a_inserer = true;

        // Si la ligne existe déjà et qu'elle a changé, on met à jour, sinon on ne fait rien
        if ($a_modifier == true)
        {
          $req3 = $bdd->prepare('UPDATE expense_center_users SET parts=:parts WHERE id_expense = ' . $id_modify . ' AND identifiant = "' . $ligne['identifiant'] . '"');
          $req3->execute(array(
            'parts' => $ligne['part']
          ));
          $req3->closeCursor();
        }
        // Si la ligne n'existe pas, on l'insère
        elseif ($a_inserer == true)
        {
          $req3 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
          $req3->execute(array(
            'id_expense'  => $id_modify,
            'identifiant' => $ligne['identifiant'],
            'parts'       => $ligne['part']
              ));
          $req3->closeCursor();
        }
      }
    }

    // Message modification effectuée
    $_SESSION['depense_modified'] = true;
  }

  // METIER : Suppression d'une dépense
  // RETOUR : Aucun
  function deleteExpense($id_delete)
  {
    global $bdd;

    // Suppression des parts
    $req1 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense = ' . $id_delete);

    // Supression de la dépense
    $req2 = $bdd->exec('DELETE FROM expense_center WHERE id = ' . $id_delete);

    // Message suppression effectuée
    $_SESSION['depense_deleted'] = true;
  }
?>
