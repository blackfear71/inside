<?php
  session_start();
  include('../../includes/appel_bdd.php');

  if (isset($_POST['add_depense']))
  {
    // Récupération des données
    $date       = date("Ymd");
    $price      = str_replace(',', '.', htmlspecialchars($_POST['depense']));
    $buyer      = $_POST['buyer_user'];
    $comment    = htmlspecialchars($_POST['comment']);

    // Test si prix numérique
    if (is_numeric($price))
    {
      // Insertion dans la table expense_center
      $req0 = $bdd->prepare('INSERT INTO expense_center(date, price, buyer, comment) VALUES(:date, :price, :buyer, :comment)');
      $req0->execute(array(
        'date'  => $date,
        'price' => $price,
        'buyer' => $buyer,
        'comment' => $comment
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

      $_SESSION['price']   = $_POST['depense'];
      $_SESSION['buyer']   = $buyer;
      $_SESSION['comment'] = $comment;

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

    // Mise à jour du prix si modifié
    $price = str_replace(',', '.', htmlspecialchars($_POST['depense']));

    // Mise à jour de l'acheteur si modifié
    $buyer = $_POST['buyer_user'];

    // Mise à jour du commentaire si modifié
    $comment = htmlspecialchars($_POST['comment']);

    $req0 = $bdd->prepare('UPDATE expense_center SET price=:price, buyer=:buyer, comment=:comment WHERE id=' . $id_modify);
    $req0->execute(array(
      'price' => $price,
      'buyer' => $buyer,
      'comment' => $comment
    ));
    $req0->closeCursor();

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
        echo 'delete<br />';
      }
      else
      {
        echo 'modifié<br />';
        $a_modifier   = false;
        $a_inserer    = false;
        $count        = 0;

        // On cherche si la ligne existe
        $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense=' . $id_modify);
        while($data2 = $req2->fetch())
        {
          //echo $ligne[1] . ' - ' . $ligne[2] . '<br />';
          if ($ligne[1] == $data2['identifiant'])
          {
            if ($ligne[2] != $data2['parts'])
              $a_modifier = true;

            $count++;
          }
        }
        $req2->closeCursor();

        if ($count == 0)
          $a_inserer = true;

        echo '<br />' . $count;
        echo 'modif : ' . $a_modifier . '<br />';
        echo 'ins : ' . $a_inserer . '<br /><br />';

        // Si la ligne existe déjà et qu'elle a changé, on met à jour, sinon on ne fait rien
        if ($a_modifier == true)
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
  header('location: ../expensecenter.php?year=' . $_GET['year']);
?>
