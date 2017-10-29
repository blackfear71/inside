<?php
  /********************************/
  /*** Liste des fonctions CRON ***/
  /********************************/

  // FONCTION : Insertion notification sortie cinéma du jour
  // RETOUR : aucun
  // FREQUENCE : tous les jours à 7h
  function isCinemaToday()
  {
    global $bdd;

    $req = $bdd->query('SELECT id, date_doodle FROM movie_house WHERE date_doodle = ' . date("Ymd") . ' ORDER BY id ASC');
    while($data = $req->fetch())
    {
      // Contrôle notification non existante
      $notification_cinema_exist = controlNotification('cinema', $data['id']);

      // Génération notification sortie cinéma
      if ($notification_cinema_exist != true)
        insertNotification('admin', 'cinema', $data['id']);
    }
    $req->closeCursor();
  }

  // FONCTION : Recalcul des dépenses pour tous les utilisateurs
  // RETOUR : aucun
  // Fréquence : tous les lundis à 7h
  function reinitializeExpenses()
  {
    global $bdd;

    // Initialisation tableau des utilisateurs
    $listeUsers = array();

    global $bdd;

    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant != "admin" AND reset != "I" ORDER BY identifiant ASC');
    while($data1 = $req1->fetch())
    {
      // On calcule le bilan des dépenses de l'utilisateur courant
      $bilan = 0;

      // Calcul des bilans
      $req2 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
      while($data2 = $req2->fetch())
      {
        // Prix d'achat
        $prix_achat = $data2['price'];

        // Identifiant de l'acheteur
        $acheteur   = $data2['buyer'];

        // Nombre de parts et prix par parts
        $nb_parts_total = 0;
        $nb_parts_user  = 0;

        $req3 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data2['id']);
        while($data3 = $req3->fetch())
        {
          // Nombre de parts total
          $nb_parts_total += $data3['parts'];

          // Nombre de parts de l'utilisateur
          if ($data1['identifiant'] == $data3['identifiant'])
            $nb_parts_user = $data3['parts'];
        }

        if ($nb_parts_total != 0)
          $prix_par_part = $prix_achat / $nb_parts_total;
        else
          $prix_par_part = 0;

        // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
        if ($data2['buyer'] == $data1['identifiant'])
          $bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_user);
        else
          $bilan = $bilan - ($prix_par_part * $nb_parts_user);

        $req3->closeCursor();
      }
      $req2->closeCursor();

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $data1['id'],
                      'identifiant' => $data1['identifiant'],
                      'bilan'       => $bilan,
                     );

      // On ajoute la ligne au tableau
      array_push($listeUsers, $myUser);
    }
    $req1->closeCursor();

    var_dump($listeUsers);

    // Mise à jour des utilisateurs
    foreach ($listeUsers as $user)
    {
      $req4 = $bdd->prepare('UPDATE users SET expenses = :expenses WHERE identifiant = "' . $user['identifiant'] . '"');
      $req4->execute(array(
        'expenses' => $user['bilan']
      ));
      $req4->closeCursor();
    }
  }
?>
