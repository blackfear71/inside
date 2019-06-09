<?php
  include_once('../../includes/functions/appel_bdd.php');
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

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar, expenses FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
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

    $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4) FROM expense_center ORDER BY SUBSTR(date, 1, 4) DESC');
    while($donnees = $reponse->fetch())
    {
      array_push($onglets, $donnees['SUBSTR(date, 1, 4)']);
    }
    $reponse->closeCursor();

    return $onglets;
  }

  // METIER : Lecture liste des parts pour chaque dépense
  // RETOUR : Tableau des parts
  function getExpenses($year)
  {
    $listeExpenses = array();

    global $bdd;

    // Récupération d'une liste des dépenses
    $reponse1 = $bdd->query('SELECT * FROM expense_center WHERE SUBSTR(date, 1, 4) = ' . $year . ' ORDER BY date DESC, id DESC');
    while($donnees1 = $reponse1->fetch())
    {
      $listeParts = array();
      $myExpense  = Expenses::withData($donnees1);

      $myExpense->setPrice(str_replace('.', ',', $myExpense->getPrice()));

      // Recherche pseudo et avatar Acheteur
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myExpense->getBuyer() . '"');
      $donnees2 = $reponse2->fetch();

      $myExpense->setPseudo($donnees2['pseudo']);
      $myExpense->setAvatar($donnees2['avatar']);

      $reponse2->closeCursor();

      // Recherche des parts associées à la dépense
      $reponse3 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $myExpense->getId() . ' ORDER BY identifiant ASC');
      while($donnees3 = $reponse3->fetch())
      {
        $myParts = Parts::withData($donnees3);

        // Recherche pseudo et avatar utilisateur
        $reponse4 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myParts->getIdentifiant() . '"');
        $donnees4 = $reponse4->fetch();

        $myParts->setId_identifiant($donnees4['id']);
        $myParts->setPseudo($donnees4['pseudo']);
        $myParts->setAvatar($donnees4['avatar']);

        $reponse4->closeCursor();

        // Ajout d'un objet Parts (instancié à partir des données de la base) au tableau des parts
        array_push($listeParts, $myParts);
      }
      $reponse3->closeCursor();

      $myExpense->setParts($listeParts);

      // Ajout d'un objet Expenses (instancié à partir des données de la base) au tableau de dépenses
      array_push($listeExpenses, $myExpense);
    }
    $reponse1->closeCursor();

    return $listeExpenses;
  }

  // METIER : Conversion du tableau d'objets des dépenses et des parts en tableau simple pour JSON
  // RETOUR : Tableau des dépenses
  function convertForJson($listeDepenses)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listeDepensesAConvertir = array();

    foreach ($listeDepenses as $depense)
    {
      $myDepense = array('id'      => $depense->getId(),
                         'date'    => $depense->getDate(),
                         'price'   => $depense->getPrice(),
                         'buyer'   => $depense->getBuyer(),
                         'pseudo'  => $depense->getPseudo(),
                         'avatar'  => $depense->getAvatar(),
                         'comment' => $depense->getComment(),
                         'parts'   => array()
                        );

      $myParts = array();

      foreach ($depense->getParts() as $parts)
      {
        $myParts[$parts->getIdentifiant()] = array('id_identifiant' => $parts->getId_identifiant(),
                                                   'parts'          => $parts->getParts()
                                                  );
      }

      $myDepense['parts']                         = $myParts;
      $listeDepensesAConvertir[$depense->getId()] = $myDepense;
    }

    return $listeDepensesAConvertir;
  }

  // METIER : Insertion d'une dépense & mise à jour des dépenses utilisateur
  // RETOUR : Aucun
  function insertExpense($post)
  {
    $control_ok = true;

    // Récupération des données
    $date    = date("Ymd");
    $price   = str_replace(',', '.', htmlspecialchars($post['depense']));
    $buyer   = $post['buyer_user'];
    $comment = $post['comment'];

    if (is_numeric($price))
      $price = number_format($price, 2, '.', '');

    $list_parts = array();

    foreach ($post['identifiant_qte'] as $id => $identifiant)
    {
      if ($post['quantite_user'][$id] != 0)
        $list_parts[$identifiant] = $post['quantite_user'][$id];
    }

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['price']         = $post['depense'];
    $_SESSION['save']['buyer']         = $buyer;
    $_SESSION['save']['comment']       = $comment;
    $_SESSION['save']['tableau_parts'] = $list_parts;

    global $bdd;

    // Vérification si régul négative sans parts
    $regul_no_parts = true;

    if (!empty($list_parts))
      $regul_no_parts = false;

    // Contrôle régul sans parts
    if (is_numeric($price) AND $price < 0 AND $regul_no_parts == false)
    {
      $_SESSION['alerts']['regul_no_parts'] = true;
      $control_ok                           = false;
    }

    // Contrôle si prix numérique et non nul (négatif = régul, positif = régul ou dépense, nul = aucun sens)
    if ($control_ok == true)
    {
      if (!is_numeric($price) OR $price == 0)
      {
        $_SESSION['alerts']['depense_not_numeric'] = true;
        $control_ok                                = false;
      }
    }

    // Insertion
    if ($control_ok == true)
    {
      // Insertion de la dépense dans la table expense_center
      $req0 = $bdd->prepare('INSERT INTO expense_center(date, price, buyer, comment) VALUES(:date, :price, :buyer, :comment)');
      $req0->execute(array(
        'date'    => $date,
        'price'   => $price,
        'buyer'   => $buyer,
        'comment' => $comment
          ));
      $req0->closeCursor();

      // On récupère l'id permettant d'identifier la dépense
      $id_expense = $bdd->lastInsertId();

      // Lecture bilan actuel de l'acheteur
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

      // Vérification si part acheteur nulle (dépense positive hors régul)
      $buyer_no_parts = true;

      if ($regul_no_parts == false AND $price > 0 AND isset($list_parts[$buyer]) AND $list_parts[$buyer] > 0)
        $buyer_no_parts = false;

      // Génération succès (total max pour l'acheteur s'il n'a pas de parts)
      if ($buyer_no_parts == true)
        insertOrUpdateSuccesValue('greedy', $buyer, $expense_buyer);

      // Nombre de parts total
      $nb_parts_total = array_sum($list_parts);

      // Insertions des parts & mise à jour du bilan pour chaque utilisateur seulement pour une dépense positive avec parts
      if ($price > 0 AND $regul_no_parts == false)
      {
        foreach ($list_parts as $identifiant => $parts)
        {
          // Insertion dans la table expense_center_users
          $req4 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
          $req4->execute(array(
            'id_expense'  => $id_expense,
            'identifiant' => $identifiant,
            'parts'       => $parts
              ));
          $req4->closeCursor();

          // Lecture bilan actuel utilisateur
          $req5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $identifiant . '"');
          $data5 = $req5->fetch();
          $expense_user = $data5['expenses'];
          $req5->closeCursor();

          // Mise à jour du bilan pour chaque utilisateur (on retire au total)
          $expense_user -= ($price / $nb_parts_total) * $parts;

          $req6 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $identifiant . '"');
          $req6->execute(array(
            'expenses' => $expense_user
          ));
          $req6->closeCursor();

          // Génération succès (ajout des parts)
          insertOrUpdateSuccesValue('eater', $identifiant, $parts);

          // Génération succès (total max)
          insertOrUpdateSuccesValue('greedy', $identifiant, $expense_user);
        }

        // Génération succès (pour l'acheteur)
        insertOrUpdateSuccesValue('buyer', $buyer, 1);

        // Génération succès (dépense sans parts)
        if ($buyer_no_parts == true)
          insertOrUpdateSuccesValue('generous', $buyer, 1);
      }

      // Ajout expérience
      insertExperience($_SESSION['user']['identifiant'], 'add_expense');

      // Message insertion effectuée
      $_SESSION['alerts']['depense_added'] = true;
    }
  }

  // METIER : Modification d'une dépense
  // RETOUR : Id dépense
  function updateExpense($post)
  {
    $control_ok = true;

    // Récupération des données
    $id_expense  = $post['id_expense'];
    $price_new   = str_replace(',', '.', htmlspecialchars($post['depense']));
    $buyer_new   = $post['buyer_user'];
    $comment_new = $post['comment'];

    if (is_numeric($price_new))
      $price = number_format($price_new, 2, '.', '');

    $list_parts_new = array();

    foreach ($post['identifiant_qte'] as $id => $identifiant)
    {
      if ($post['quantite_user'][$id] != 0)
        $list_parts_new[$identifiant] = $post['quantite_user'][$id];
    }

    global $bdd;

    // Vérification si régul négative sans parts
    $regul_no_parts = true;

    if (!empty($list_parts_new))
      $regul_no_parts = false;

    // Contrôle régul sans parts
    if (is_numeric($price_new) AND $price_new < 0 AND $regul_no_parts == false)
    {
      $_SESSION['alerts']['regul_no_parts'] = true;
      $control_ok                           = false;
    }

    // Contrôle si prix numérique et non nul (négatif = régul, positif = régul ou dépense, nul = aucun sens)
    if ($control_ok == true)
    {
      if (!is_numeric($price_new) OR $price_new == 0)
      {
        $_SESSION['alerts']['depense_not_numeric'] = true;
        $control_ok                                = false;
      }
    }

    // Mise à jour
    if ($control_ok == true)
    {
      /*****************************/
      /*** Retrait ancienne part ***/
      /*****************************/
      // Lecture dépense (avant mise à jour)
      $req1 = $bdd->query('SELECT * FROM expense_center WHERE id = ' . $id_expense);
      $data1 = $req1->fetch();
      $myOldExpense = Expenses::withData($data1);
      $req1->closeCursor();

      // Lecture bilan actuel acheteur
      $req2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $myOldExpense->getBuyer() . '"');
      $data2 = $req2->fetch();
      $expense_buyer = $data2['expenses'];
      $req2->closeCursor();

      // Mise à jour du bilan pour l'acheteur (on retire l'ancienne dépense)
      $expense_buyer -= $myOldExpense->getPrice();

      $req3 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $myOldExpense->getBuyer() . '"');
      $req3->execute(array(
        'expenses' => $expense_buyer
      ));
      $req3->closeCursor();

      // Lecture des utilisateurs ayant déjà une part
      $old_list_parts     = array();
      $nb_parts_total_old = 0;

      $req4 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $myOldExpense->getId() . ' ORDER BY identifiant ASC');
      while($data4 = $req4->fetch())
      {
        $old_list_parts[$data4['identifiant']] = $data4['parts'];
      }
      $req4->closeCursor();

      // Nombre de parts total existantes
      $nb_parts_total_old = array_sum($old_list_parts);

      // Vérification si régul négative sans parts
      $old_regul_no_parts = true;

      if (!empty($nb_parts_total_old))
        $old_regul_no_parts = false;

      // Vérification si ancienne part acheteur nulle (dépense positive hors régul)
      $old_buyer_no_parts = true;

      if ($old_regul_no_parts == false AND $myOldExpense->getPrice() > 0 AND isset($old_list_parts[$myOldExpense->getBuyer()]) AND $old_list_parts[$myOldExpense->getBuyer()] > 0)
        $old_buyer_no_parts = false;

      // Mise à jour du bilan pour chaque utilisateur (retour arrière sur la dépense)
      foreach ($old_list_parts as $identifiant => $parts)
      {
        // Lecture bilan actuel utilisateur
        $req5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $identifiant . '"');
        $data5 = $req5->fetch();
        $expense_user = $data5['expenses'];
        $req5->closeCursor();

        // Mise à jour du bilan pour chaque utilisateur (on ajoute au bilan)
        $expense_user += ($myOldExpense->getPrice() / $nb_parts_total_old) * $parts;

        $req6 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $identifiant . '"');
        $req6->execute(array(
          'expenses' => $expense_user
        ));
        $req6->closeCursor();

        // Génération succès (retrait des parts)
        insertOrUpdateSuccesValue('eater', $identifiant, -$parts);
      }

      // Génération succès (pour l'acheteur si modifié)
      if ($old_regul_no_parts == false AND ($buyer_new != $myOldExpense->getBuyer() OR $regul_no_parts == true))
        insertOrUpdateSuccesValue('buyer', $myOldExpense->getBuyer(), -1);

      // Génération succès (dépense sans parts)
      if ($old_regul_no_parts == false AND $old_buyer_no_parts == true)
        insertOrUpdateSuccesValue('generous', $myOldExpense->getBuyer(), -1);

      /*********************************/
      /*** Mise à jour nouvelle part ***/
      /*********************************/
      // Mise à jour de la dépense
      $req7 = $bdd->prepare('UPDATE expense_center SET price = :price, buyer = :buyer, comment = :comment WHERE id = ' . $id_expense);
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

      $expense_buyer += $price_new;

      $req9 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $buyer_new . '"');
      $req9->execute(array(
        'expenses' => $expense_buyer
      ));
      $req9->closeCursor();

      // Vérification si part acheteur nulle (dépense positive hors régul)
      $buyer_no_parts = true;

      if ($regul_no_parts == false AND $price_new > 0 AND isset($list_parts_new[$buyer_new]) AND $list_parts_new[$buyer_new] > 0)
        $buyer_no_parts = false;

      // Nombre de parts total
      $nb_parts_total_new = array_sum($list_parts_new);

      // Génération succès (total max pour l'acheteur s'il n'a pas de parts)
      if ($buyer_no_parts == true)
        insertOrUpdateSuccesValue('greedy', $buyer_new, $expense_buyer);

      // Suppression de toutes les anciennes parts
      $req10 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense = ' . $id_expense);

      // Insertions des nouvelles parts & mise à jour du bilan pour chaque utilisateur seulement si dépense positive avec parts
      if ($price_new > 0 AND $regul_no_parts == false)
      {
        foreach ($list_parts_new as $identifiant => $parts)
        {
          // Insertion dans la table expense_center_users
          $req11 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
          $req11->execute(array(
            'id_expense'  => $myOldExpense->getId(),
            'identifiant' => $identifiant,
            'parts'       => $parts
              ));
          $req11->closeCursor();

          // Lecture bilan actuel utilisateur
          $req11 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $identifiant . '"');
          $data11 = $req11->fetch();
          $expense_user = $data11['expenses'];
          $req11->closeCursor();

          // Mise à jour du bilan pour chaque utilisateur (on retire au total)
          $expense_user -= ($price_new / $nb_parts_total_new) * $parts;

          $req12 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $identifiant . '"');
          $req12->execute(array(
            'expenses' => $expense_user
          ));
          $req12->closeCursor();

          // Génération succès (ajout des parts)
          insertOrUpdateSuccesValue('eater', $identifiant, $parts);

          // Génération succès (total max)
          insertOrUpdateSuccesValue('greedy', $identifiant, $expense_user);
        }

        // Génération succès (pour l'acheteur si modifié)
        if ($buyer_new != $myOldExpense->getBuyer() OR $old_regul_no_parts == true)
          insertOrUpdateSuccesValue('buyer', $buyer_new, 1);

        // Génération succès (dépense sans parts)
        if ($buyer_no_parts == true)
          insertOrUpdateSuccesValue('generous', $buyer_new, 1);
      }

      // Message modification effectuée
      $_SESSION['alerts']['depense_modified'] = true;
    }

    return $id_expense;
  }

  // METIER : Suppression d'une dépense
  // RETOUR : Aucun
  function deleteExpense($post)
  {
    $id_expense = $post['id_expense'];

    global $bdd;

    // Lecture dépense
    $req1 = $bdd->query('SELECT * FROM expense_center WHERE id = ' . $id_expense);
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
    $list_parts = array();

    $req4 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $myExpense->getId() . ' ORDER BY identifiant ASC');
    while($data4 = $req4->fetch())
    {
      $list_parts[$data4['identifiant']] = $data4['parts'];
    }
    $req4->closeCursor();

    // Nombre de parts total
    $nb_parts_total = array_sum($list_parts);

    // Vérification si régul négative sans parts
    $regul_no_parts = true;

    if (!empty($list_parts))
      $regul_no_parts = false;

    // Vérification si part acheteur nulle (dépense positive hors régul)
    $buyer_no_parts = true;

    if ($regul_no_parts == false AND $myExpense->getPrice() > 0 AND isset($list_parts[$myExpense->getBuyer()]) AND $list_parts[$myExpense->getBuyer()] > 0)
      $buyer_no_parts = false;

    // Suppression des parts & mise à jour du bilan pour chaque utilisateur
    foreach ($list_parts as $identifiant => $parts)
    {
      // Lecture bilan actuel utilisateur
      $req5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $identifiant . '"');
      $data5 = $req5->fetch();
      $expense_user = $data5['expenses'];
      $req5->closeCursor();

      // Mise à jour du bilan pour chaque utilisateur (on ajoute au bilan)
      $expense_user += ($myExpense->getPrice() / $nb_parts_total) * $parts;

      $req6 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $identifiant . '"');
      $req6->execute(array(
        'expenses' => $expense_user
      ));
      $req6->closeCursor();

      // Suppression des parts
      $req7 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense = ' . $id_expense);

      // Génération succès (suppression des parts)
      insertOrUpdateSuccesValue('eater', $identifiant, -$parts);
    }

    // Suppression de la dépense
    $req8 = $bdd->exec('DELETE FROM expense_center WHERE id = ' . $id_expense);

    // Génération succès (pour l'acheteur)
    if ($myExpense->getPrice() > 0 AND $regul_no_parts == false)
      insertOrUpdateSuccesValue('buyer', $myExpense->getBuyer(), -1);

    // Génération succès (dépense sans parts)
    if ($regul_no_parts == false AND $buyer_no_parts == true)
      insertOrUpdateSuccesValue('generous', $myExpense->getBuyer(), -1);

    // Message suppression effectuée
    $_SESSION['alerts']['depense_deleted'] = true;
  }
?>
