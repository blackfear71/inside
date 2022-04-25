<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des parcours
  // RETOUR : Liste des parcours
  function physiqueListeParcours($equipe)
  {
    // Initialisations
    $listeParcours = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM petits_pedestres_parcours
                        WHERE team = "' . $equipe . '"
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

  // PHYSIQUE : Lecture parcours disponible
  // RETOUR : Booléen
  function physiqueParcoursDisponible($idParcours, $equipe)
  {
    // Initialisations
    $parcoursExistant = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM petits_pedestres_parcours
                        WHERE id = ' . $idParcours . ' AND team = "' . $equipe . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $parcoursExistant = true;

    $req->closeCursor();

    // Retour
    return $parcoursExistant;
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

    $req = $bdd->prepare('INSERT INTO petits_pedestres_parcours(team,
                                                                nom,
                                                                distance,
                                                                lieu,
                                                                url)
                                                        VALUES(:team,
                                                               :nom,
                                                               :distance,
                                                               :lieu,
                                                               :url)');

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
                              url      = :url
                          WHERE id     = ' . $idParcours);

    $req->execute($parcours);

    $req->closeCursor();
  }
?>
