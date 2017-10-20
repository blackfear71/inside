<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/parcours.php');

  // Métier : lecture d'un parcours en fonction de son id
  // Renvoie un objet Parcours
  function getParcours ($id)
  {
    // Pas de paramètre offset/limit, à ajouter le jour où on a 12 millions de parcours
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM petits_pedestres_parcours WHERE id = ' . $id);

    if ($donnees = $reponse->fetch())
    {
      $reponse->closeCursor();
      return Parcours::withData($donnees);
    }
    else
    {
      // Si la requête ne renvoie rien (ce qui n'est pas censé arriver), on renvoie un objet vide
      return new Parcours();
    }
  }

  // Métier : met un parcours à jour en fonction de son id et de données passées par formulaire
  // Renvoie un objet Parcours
  function updateParcours ($id, $post)
  {
    $data = array ('nom'      => $post['name'],
                   'distance' => $post['dist'],
                   'lieu'     => $post['location'],
                   'image'    => $post['picurl']
                  );

    $_SESSION['save_mod'] = $data;

    $parcours = Parcours::withData($data);

    if (is_numeric($parcours->getDistance()))
    {
      global $bdd;
      $req = $bdd->prepare('UPDATE petits_pedestres_parcours SET nom      = :nom,
                                                                 distance = :distance,
                                                                 lieu     = :lieu,
                                                                 image    = :image
                                                             WHERE id     = ' . $id);
      $req->execute($data);
      $req->closeCursor();

      $_SESSION['parcours_modified'] = true;

      return $parcours;
    }
    else
    {
      $_SESSION['erreur_distance'] = true;
      return new Parcours();
    }

  }

  // Métier : liste des parcours, par ordre alphabétique
  // Renvoie une liste d'objets Parcours
  function listParcours()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM petits_pedestres_parcours ORDER BY nom ASC');

    // Nouveau tableau vide de parcours, servira à la vue
    $tableauParcours = array();

    while($donnees = $reponse->fetch()){
			// Ajout d'un objet parcours (instancié à partir des données de la base) au tableau de parcours
      array_push($tableauParcours, Parcours::withData($donnees));
    }

    $reponse->closeCursor();

    return $tableauParcours;
  }

  // Métier : insertion d'un nouveau parcours dans la base de données
  // Ne renvoie rien pour le moment
  function addParcours($post){
    $data = array('nom'      => $post['name'],
                  'distance' => $post['dist'],
                  'lieu'     => $post['location'],
                  'image'    => $post['picurl']
                 );

    $_SESSION['save_add'] = $data;

    if (is_numeric($data['distance']))
    {
      global $bdd;
      $req = $bdd->prepare('INSERT INTO petits_pedestres_parcours(nom, distance, lieu, image)
                                                       VALUES(:nom, :distance, :lieu, :image)');
      $req->execute($data);
      $req->closeCursor();

      $parcours = Parcours::withData($data);
      $parcours->setId($bdd->lastInsertId());

      $_SESSION['parcours_added'] = true;

      return $parcours;
    }
    else
    {
      $_SESSION['erreur_distance'] = true;
      return new Parcours();
    }

  }
?>
