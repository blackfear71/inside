<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des rapports
  // RETOUR : Liste rapports
  function physiqueListeRapports($view, $type)
  {
    // Initialisations
    $rapports = array();

    // Requête
    global $bdd;

    if ($view == 'resolved')
      $req = $bdd->query('SELECT *
                          FROM bugs
                          WHERE type = "' . $type . '" AND (resolved = "Y" OR resolved = "R")
                          ORDER BY date DESC, id DESC');
    elseif ($view == 'unresolved')
      $req = $bdd->query('SELECT *
                          FROM bugs
                          WHERE type = "' . $type . '" AND resolved = "N"
                          ORDER BY date DESC, id DESC');
    else
      $req = $bdd->query('SELECT *
                          FROM bugs
                          WHERE type = "' . $type . '"
                          ORDER BY date DESC, id DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Bugs à partir des données remontées de la bdd
      $rapport = Bugs::withData($data);

      // On ajoute la ligne au tableau
      array_push($rapports, $rapport);
    }

    $req->closeCursor();

    // Retour
    return $rapports;
  }

  // PHYSIQUE : Lecture données utilisateur
  // RETOUR : Aucun
  function physiqueDonneesUser($rapport)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT identifiant, pseudo, avatar, COUNT(*) AS nombreLignes
                        FROM users
                        WHERE identifiant = "' . $rapport->getAuthor() . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
    {
      $rapport->setPseudo($data['pseudo']);
      $rapport->setAvatar($data['avatar']);
    }

    $req->closeCursor();
  }

  // PHYSIQUE : Lecture données rapport
  // RETOUR : Objet bugs
  function physiqueRapport($idRapport)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM bugs
                        WHERE id = ' . $idRapport);

    $data = $req->fetch();

    // Instanciation d'un objet Bugs à partir des données remontées de la bdd
    $rapport = Bugs::withData($data);

    $req->closeCursor();

    // Retour
    return $rapport;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour statut rapport
  // RETOUR : Aucun
  function physiqueUpdateRapport($idRapport, $resolved)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE bugs
                          SET resolved = :resolved
                          WHERE id = ' . $idRapport);

    $req->execute(array(
      'resolved' => $resolved
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression rapport
  // RETOUR : Aucun
  function physiqueDeleteRapport($idRapport)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM bugs
                       WHERE id = ' . $idRapport);
  }
?>
