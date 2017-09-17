<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/collectors.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin"  AND reset != "I" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $user->getId(),
                      'identifiant' => $user->getIdentifiant(),
                      'pseudo'      => $user->getPseudo(),
                      'avatar'      => $user->getAvatar()
                    );

      // On ajoute la ligne au tableau
      array_push($listeUsers, Profile::withData($myUser));
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture nombre de pages
  // RETOUR : Nombre de pages
  function getPages()
  {
    $nb_pages    = 0;
    $nb_col      = 0;
    $nb_par_page = 10;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector');
    $data = $req->fetch();

    $nb_col = $data['nb_col'];

    $req->closeCursor();

    $nb_pages = ceil($nb_col / $nb_par_page);

    return $nb_pages;
  }

  // METIER : Lecture des phrases cultes
  // RETOUR : Liste phrases cultes
  function getCollectors($listUsers, $nb_pages, $page)
  {
    $listCollectors = array();
    $nb_par_page    = 10;

    // Contrôle dernière page
    if ($page > $nb_pages)
    {
      $page = $nb_pages;
    }

    // Calcul première entrée
    $premiere_entree = ($page - 1) * $nb_par_page;

    // Lecture des enregistrements
    global $bdd;

    $reponse = $bdd->query('SELECT * FROM collector ORDER BY date DESC, id DESC LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
    while($donnees = $reponse->fetch())
    {
      $myCollector = Collector::withData($donnees);

      foreach ($listUsers as $user)
      {
        if ($myCollector->getAuthor() == $user->getIdentifiant())
        {
          $myCollector->setName_a($user->getPseudo());
        }

        if ($myCollector->getSpeaker() == $user->getIdentifiant())
        {
          $myCollector->setName_s($user->getPseudo());
          $myCollector->setAvatar_s($user->getAvatar());
        }
      }

      if (empty($myCollector->getName_a()))
      {
        $myCollector->setName_a("un ancien utilisateur");
      }

      if (empty($myCollector->getName_s()))
      {
        $myCollector->setName_s("un ancien utilisateur");
      }

      array_push($listCollectors, $myCollector);
    }
    $reponse->closeCursor();

    return $listCollectors;
  }

  // METIER : Insertion phrases cultes
  // RETOUR : Aucun
  function insertCollector($post)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['speaker']   = $post['speaker'];
    $_SESSION['date']      = $post['date'];
    $_SESSION['collector'] = $post['collector'];

    $date_a_verifier = $post['date'];

    // On décompose la date à contrôler
    list($d, $m, $y) = explode('/', $date_a_verifier);

    // On vérifie le format de la date
    if (checkdate($m, $d, $y))
    {
      global $bdd;

      $collector = array('author'    => $_SESSION['identifiant'],
                         'speaker'   => $post['speaker'],
                         'date'      => formatDateForInsert($date_a_verifier),
                         'collector' => $post['collector']
                        );

			// Stockage de l'enregistrement en table
      $req = $bdd->prepare('INSERT INTO collector(author,
																									speaker,
																									date,
																									collector
                                                 )
																			     VALUES(:author,
																									:speaker,
																								  :date,
																								  :collector
                                                 )');
      $req->execute($collector);
		  $req->closeCursor();

      $_SESSION['collector_added'] = true;
    }
    else
      $_SESSION['wrong_date'] = true;
  }

  // METIER : Suppression phrases cultes
  // RETOUR : Aucun
  function deleteCollector($id_col)
  {
    global $bdd;

    $req = $bdd->exec('DELETE FROM collector WHERE id = ' . $id_col);

    $_SESSION['collector_deleted'] = true;
  }
?>
