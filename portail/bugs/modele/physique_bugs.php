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

    switch ($view)
    {
      case 'resolved':
        $req = $bdd->query('SELECT *
                            FROM bugs
                            WHERE type = "' . $type . '" AND (resolved = "Y" OR resolved = "R")
                            ORDER BY date DESC, id DESC');
        break;

      default:
        $req = $bdd->query('SELECT *
                            FROM bugs
                            WHERE type = "' . $type . '" AND resolved = "N"
                            ORDER BY date DESC, id DESC');
    }

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Bugs à partir des données remontées de la bdd
      $rapport = BugEvolution::withData($data);

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

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouveau bug / nouvelle évolution
  // RETOUR : Id bug / évolution culte
  function physiqueInsertionBug($bug)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO bugs(subject,
                                           date,
                                           author,
                                           content,
                                           picture,
                                           type,
                                           resolved
                                          )
                                    VALUES(:subject,
                                           :date,
                                           :author,
                                           :content,
                                           :picture,
                                           :type,
                                           :resolved
                                          )');

    $req->execute($bug);

    $req->closeCursor();

    $newId = $bdd->lastInsertId();

    // Retour
    return $newId;
  }
?>
