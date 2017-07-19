<?php
  if (isset($_POST['add_depense']))
  {
    // Récupération des données
    $date  = date("mdY");
    $price = str_replace(',', '.', htmlspecialchars($_POST['depense']));
    $buyer = $_POST['buyer_user'];

    echo $date . '<br />';
    echo $price . '<br />';
    echo $buyer . '<br />';

    // Test si prix numérique et positif

    // Insertion dans la table expense_center

    // Insertions dans la table expense_center_users

    // Redirection
    header('location: ../expensecenter.php');
  }
?>
