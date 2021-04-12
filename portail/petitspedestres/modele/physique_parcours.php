<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des parcours
  // RETOUR : Liste des parcours
  function physiqueListeParcours()
  {
    // Initialisations
    $listeParcours = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM petits_pedestres_parcours
                        ORDER BY nom ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Parcours à partir des données remontées de la bdd
      $parcours = Parcours::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeParcours, $parcours);
    }

    $req->closeCursor();

    // Retour
    return $listeParcours;
  }

  // PHYSIQUE : Lecture d'un parcours
  // RETOUR : Objet parcours
  function physiqueParcours($idParcours)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM petits_pedestres_parcours
                        WHERE id = ' . $idParcours);

    $data = $req->fetch();

    // Instanciation d'un objet Parcours à partir des données remontées de la bdd
    $parcours = Parcours::withData($data);

    $req->closeCursor();

    // Retour
    return $parcours;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouveau parcours
  // RETOUR : Aucun
  function physiqueInsertionParcours($parcours)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO petits_pedestres_parcours(nom,
                                                                distance,
                                                                lieu,
                                                                image)
                                                        VALUES(:nom,
                                                               :distance,
                                                               :lieu,
                                                               :image)');

    $req->execute($parcours);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour parcours
  // RETOUR : Aucun
  function physiqueUpdateParcours($idParcours, $parcours)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE petits_pedestres_parcours
                          SET nom      = :nom,
                              distance = :distance,
                              lieu     = :lieu,
                              image    = :image
                          WHERE id     = ' . $idParcours);

    $req->execute($parcours);

    $req->closeCursor();
  }
?>
