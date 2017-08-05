<?php
  include_once('../../../includes/appel_bdd.php');
  include_once('../../../includes/classes/parcours.php');

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
?>
