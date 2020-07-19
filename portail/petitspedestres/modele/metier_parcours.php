<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/parcours.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if (!isset($_SESSION['alerts']['erreur_distance']) OR $_SESSION['alerts']['erreur_distance'] != true)
    {
      unset($_SESSION['save']);

      $_SESSION['save'] = array('nom_parcours'      => '',
                                'distance_parcours' => '',
                                'lieu_parcours'     => '',
                                'image_parcours'    => ''
                               );
    }
  }

  // Métier : lecture d'un parcours en fonction de son id
  // Renvoie un objet Parcours
  function getParcours($id)
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

  // Métier : liste des parcours, par ordre alphabétique
  // Renvoie une liste d'objets Parcours
  function listParcours()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM petits_pedestres_parcours ORDER BY nom ASC');

    // Nouveau tableau vide de parcours, servira à la vue
    $tableauParcours = array();

    while ($donnees = $reponse->fetch())
    {
			// Ajout d'un objet parcours (instancié à partir des données de la base) au tableau de parcours
      array_push($tableauParcours, Parcours::withData($donnees));
    }

    $reponse->closeCursor();

    return $tableauParcours;
  }

  // METIER : Conversion du tableau d'objets des parcours en tableau simple pour JSON
  // RETOUR : Tableau des parcours
  function convertForJson($listeParcours)
  {
    var_dump($listeParcours);

    // On transforme les objets en tableau pour y envoyer au Javascript
    $listeParcoursAConvertir = array();

    foreach ($listeParcours as $parcours)
    {
      /* Structure de l'objet parcours
          private $id;
          private $nom;
          private $distance;
          private $lieu;
          (url image mais on n'en a pas besoin là)
      */
      $parcours = array('id'      => $parcours->getId(),
                        'nom'     => $parcours->getNom(),
                        'distance'=> $parcours->getDistance(),
                        'lieu'    => $parcours->getLieu()
                       );

      array_push($listeParcoursAConvertir, $parcours);
    }
    var_dump($listeParcoursAConvertir);


    return $listeParcoursAConvertir;
  }

  // Métier : insertion d'un nouveau parcours dans la base de données
  // Ne renvoie rien pour le moment
  function addParcours($post)
  {
    $data = array('nom'      => $post['name'],
                  'distance' => $post['dist'],
                  'lieu'     => $post['location'],
                  'image'    => $post['picurl']
                 );

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['nom_parcours']      = $post['name'];
    $_SESSION['save']['distance_parcours'] = $post['dist'];
    $_SESSION['save']['lieu_parcours']     = $post['location'];
    $_SESSION['save']['image_parcours']    = $post['picurl'];

    if (is_numeric($data['distance']))
    {
      global $bdd;
      $req = $bdd->prepare('INSERT INTO petits_pedestres_parcours(nom, distance, lieu, image)
                                                       VALUES(:nom, :distance, :lieu, :image)');
      $req->execute($data);
      $req->closeCursor();

      $parcours = Parcours::withData($data);
      $parcours->setId($bdd->lastInsertId());

      $_SESSION['alerts']['parcours_added'] = true;

      return $parcours;
    }
    else
    {
      $_SESSION['alerts']['erreur_distance'] = true;
      return new Parcours();
    }
  }

  // Métier : met un parcours à jour en fonction de son id et de données passées par formulaire
  // Renvoie un objet Parcours
  function updateParcours($id, $post)
  {
    $data = array ('nom'      => $post['name'],
                   'distance' => $post['dist'],
                   'lieu'     => $post['location'],
                   'image'    => $post['picurl']
                  );

    $parcours = Parcours::withData($data);

    if (is_numeric($post['dist']))
    {
      global $bdd;
      $req = $bdd->prepare('UPDATE petits_pedestres_parcours SET nom      = :nom,
                                                                 distance = :distance,
                                                                 lieu     = :lieu,
                                                                 image    = :image
                                                             WHERE id     = ' . $id);
      $req->execute($data);
      $req->closeCursor();

      $_SESSION['alerts']['parcours_updated'] = true;
    }
    else
      $_SESSION['alerts']['erreur_distance'] = true;

    return $parcours;
  }
?>
