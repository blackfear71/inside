<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des alertes
  // RETOUR : Liste alertes
  function physiqueListeAlertes()
  {
    // Initialisations
    $alertes = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM alerts
                        ORDER BY category ASC, type DESC, alert ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Alerte à partir des données remontées de la bdd
      $alerte = Alerte::withData($data);

      // On ajoute la ligne au tableau
      array_push($alertes, $alerte);
    }

    $req->closeCursor();

    // Retour
    return $alertes;
  }

  // PHYSIQUE : Lecture du nombre de références existantes
  // RETOUR : Booléen
  function physiqueReferenceUnique($reference)
  {
    // Initialisations
    $isUnique = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreReferences
                        FROM alerts
                        WHERE alert = "' . $reference . '"');

    $data = $req->fetch();

    if ($data['nombreReferences'] > 0)
      $isUnique = false;

    $req->closeCursor();

    // Retour
    return $isUnique;
  }

  // PHYSIQUE : Lecture du nombre de références existantes
  // RETOUR : Booléen
  function physiqueReferenceUniqueUpdate($reference, $idAlert)
  {
    // Initialisations
    $isUnique = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreReferences
                        FROM alerts
                        WHERE alert = "' . $reference . '" AND id != ' . $idAlert);

    $data = $req->fetch();

    if ($data['nombreReferences'] > 0)
      $isUnique = false;

    $req->closeCursor();

    // Retour
    return $isUnique;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle alerte
  // RETOUR : Id alerte
  function physiqueInsertionAlerte($alerte)
  {
    // Initialisations
    $newId = NULL;

    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO alerts(category,
                                             type,
                                             alert,
                                             message)
                                     VALUES(:category,
                                            :type,
                                            :alert,
                                            :message)');

    $req->execute($alerte);

    $req->closeCursor();

    $newId = $bdd->lastInsertId();

    // Retour
    return $newId;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour alerte
  // RETOUR : Aucun
  function physiqueUpdateAlerte($alerte, $idAlert)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE alerts
                          SET category = :category,
                              type     = :type,
                              alert    = :alert,
                              message  = :message
                          WHERE id = ' . $idAlert);

    $req->execute($alerte);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression alerte
  // RETOUR : Aucun
  function physiqueDeleteAlerte($idAlert)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM alerts
                       WHERE id = ' . $idAlert);
  }
?>
