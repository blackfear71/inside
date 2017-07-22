<?php
  session_start();
  include('../../includes/appel_bdd.php');

  if (isset($_POST['add_depense']))
  {
    // Récupération des données
    $date       = date("mdY");
    $price      = str_replace(',', '.', htmlspecialchars($_POST['depense']));
    $buyer      = $_POST['buyer_user'];

    // Test si prix numérique et positif
    if (is_numeric($price) AND $price > 0)
    {
      // Insertion dans la table expense_center
      $req0 = $bdd->prepare('INSERT INTO expense_center(date, price, buyer) VALUES(:date, :price, :buyer)');
      $req0->execute(array(
        'date'  => $date,
        'price' => $price,
        'buyer' => $buyer
          ));
      $req0->closeCursor();

      // On récupère le numéro permettant d'identifier la dépenses
      $id_expense = $bdd -> lastInsertId();

      // On stocke le tableau des parts en fonction de l'utilisateur si la part n'est pas à 0
      $list_parts_users = array();
      $i = 0;

      $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
      while($data1 = $req1->fetch())
      {
        if ($_POST['depense_user'][$i] != 0)
        {
          // Identifiant utilisateur
          $list_parts_users[$i][1] = $data1['identifiant'];
          // Part utilisateur
          $list_parts_users[$i][2] = $_POST['depense_user'][$i];
        }
        $i++;
      }
      $req1->closeCursor();

      // Insertions dans la table expense_center_users
      foreach ($list_parts_users as $ligne)
      {
        $req2 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
        $req2->execute(array(
          'id_expense'  => $id_expense,
          'identifiant' => $ligne[1],
          'parts' => $ligne[2]
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

      $_SESSION['price']       = $_POST['depense'];
      $_SESSION['buyer']       = $buyer;

      // Stockage de la liste des parts en fonction de l'utilisateur
      $reponse = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
      while($donnees = $reponse->fetch())
      {
        $list_parts[$i] = $_POST['depense_user'][$i];
        $i++;
      }
      $reponse->closeCursor();

      $_SESSION['tableau_parts'] = $list_parts;

      $_SESSION['not_numeric'] = true;
    }
  }
  elseif (isset($_POST['modify_depense']) AND isset($_GET['id_modify']))
  {
    $id_modify = $_GET['id_modify'];

    // On stocke le tableau des parts en fonction de l'utilisateur si la part n'est pas à 0
    $list_parts_users = array();
    $i = 0;

    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($data1 = $req1->fetch())
    {
      // Identifiant utilisateur
      $list_parts_users[$i][1] = $data1['identifiant'];
      // Part utilisateur
      $list_parts_users[$i][2] = $_POST['depense_user'][$i];

      echo $list_parts_users[$i][1] . ' - ' . $list_parts_users[$i][2] . '<br />';
      $i++;
    }
    $req1->closeCursor();

    foreach($list_parts_users as $ligne)
    {
      // Si la ligne est mise à 0, on la supprime
      if ($ligne[2] == 0)
      {
        $req2 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense=' . $id_modify . ' AND identifiant = "' . $ligne[1] . '"');
      }
      else
      {
        $non_modifiee = false;
        $a_modifier  = false;
        $a_inserer   = false;

        // On cherche si la ligne existe
        $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense=' . $id_modify);
        while($data2 = $req2->fetch())
        {
          if ($ligne[1] == $data2['identifiant'] AND $ligne[2] == $data2['parts'])
            $non_modifiee = true;
          elseif ($ligne[1] == $data2['identifiant'] AND $ligne[2] != $data2['parts'])
            $a_modifier = true;
          else
            $a_inserer = true;
        }
        $req2->closeCursor();

        // Si la ligne existe déjà mais qu'elle n'a pas changé, on ne fait rien
        if ($non_modifiee == true)
        {
          echo NULL;
        }
        // Si la ligne existe déjà et qu'elle a changé, on met à jour
        elseif ($a_modifier == true)
        {
          $req3 = $bdd->prepare('UPDATE expense_center_users SET parts=:parts WHERE id_expense=' . $id_modify . ' AND identifiant = "' . $ligne[1] . '"');
      		$req3->execute(array(
      			'parts' => $ligne[2]
      		));
      		$req3->closeCursor();
        }
        // Si la ligne n'existe pas, on l'insère
        elseif ($a_inserer == true)
        {
          $req3 = $bdd->prepare('INSERT INTO expense_center_users(id_expense, identifiant, parts) VALUES(:id_expense, :identifiant, :parts)');
          $req3->execute(array(
            'id_expense'  => $id_modify,
            'identifiant' => $ligne[1],
            'parts' => $ligne[2]
              ));
          $req3->closeCursor();
        }
      }
    }

    // Message modification effectuée
    $_SESSION['depense_modified'] = true;
  }
  elseif (isset($_POST['delete_depense']) AND isset($_GET['id_delete']))
  {
    $id_delete = $_GET['id_delete'];

    // Suppression des parts
    $req1 = $bdd->exec('DELETE FROM expense_center_users WHERE id_expense=' . $id_delete);

    // Supression de la dépense
    $req2 = $bdd->exec('DELETE FROM expense_center WHERE id=' . $id_delete);

    // Message suppression effectuée
    $_SESSION['depense_deleted'] = true;
  }

  // Redirection
  header('location: ../expensecenter.php');
?>
