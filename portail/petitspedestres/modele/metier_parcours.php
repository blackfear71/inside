<?php
  include_once('../../../includes/appel_bdd.php');
  include_once('../../../includes/classes/parcours.php');

  // Métier : lecture d'un parcours en fonction de son id
  // Renvoie un objet Parcours
  function getParcours ($id){
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
  function updateParcours ($id, $post){
    $data = array (
      'nom' => $post['name'],
      'distance' => $post['dist'],
      'lieu' => $post['location'],
      'image' => $post['picurl']
    );

    $parcours = Parcours::withData($data);    

    if (is_numeric($parcours->getDistance()))
    {
      global $bdd;      
      $req = $bdd->prepare('UPDATE petits_pedestres_parcours SET nom = :nom, 
                                                                 distance = :distance, 
                                                                 lieu = :lieu, 
                                                                 image = :image
                                                              WHERE id = ' . $id);
      $req->execute($data);
      $req->closeCursor();

      return $parcours;
    }
    else
    {
      $_SESSION['erreur_distance'] = true;
      return new Parcours();      
    }

  }
?>
