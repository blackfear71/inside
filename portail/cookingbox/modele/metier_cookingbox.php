<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/gateaux.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Récupère les données d'une semaine (N ou N+1)
  // RETOUR : Données semaine
  function getWeek($week)
  {
    $myWeek = new WeekCake();

    global $bdd;

    // Données semaine
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $myWeek = WeekCake::withData($data1);

      // Données utilisateur
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myWeek->getIdentifiant() . '"');
      $data2 = $req2->fetch();
      $myWeek->setPseudo($data2['pseudo']);
      $myWeek->setAvatar($data2['avatar']);
      $req2->closeCursor();
    }

    $req1->closeCursor();

    return $myWeek;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant != "admin" AND status != "I" AND status != "D" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $listeUsers[$user->getIdentifiant()] = $user->getPseudo();
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Insère ou met à jour l'utilisateur
  // RETOUR : Aucun
  function updateCake($post)
  {
    var_dump($post);

    $week         = $post['week'];
    $year         = date('Y');
    $identifiant  = $post['select_user'];
    $exist        = false;

    global $bdd;

    // Contrôle si enregistrement existant
    $req1 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . $week . '" AND year = "' . $year . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
      $exist = true;

    $req1->closeCursor();

    // Si non existant : insertion
    if ($exist == false)
    {
      $cooking = array('identifiant' => $identifiant,
                       'week'        => $week,
                       'year'        => $year,
                       'cooked'      => "N"
                     );

      $req2 = $bdd->prepare('INSERT INTO cooking_box(identifiant,
                                                     week,
                                                     year,
                                                     cooked
                                                    )
                                             VALUES(:identifiant,
                                                    :week,
                                                    :year,
                                                    :cooked
                                                   )');
      $req2->execute($cooking);
      $req2->closeCursor();
    }
    // Sinon : mise à jour
    else
    {
      $req2 = $bdd->prepare('UPDATE cooking_box SET identifiant = :identifiant WHERE week = "' . $week . '" AND year = "' . $year . '"');
      $req2->execute(array(
        'identifiant' => $identifiant
      ));
      $req2->closeCursor();
    }
  }

  // METIER : Valide le gâteau de la semaine
  // RETOUR : Aucun
  function validateCake($cooked)
  {
    global $bdd;

    $req = $bdd->prepare('UPDATE cooking_box SET cooked = :cooked WHERE week = "' . date('W') . '" AND year = "' . date('Y') . '"');
    $req->execute(array(
      'cooked' => $cooked
    ));
    $req->closeCursor();
  }
?>
