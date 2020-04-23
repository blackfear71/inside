<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture nombre changelog
  // RETOUR : Booléen
  function physiqueChangelogExistant($year, $week)
  {
    // Initialisations
    $exist = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM change_log
                        WHERE year = "' . $year . '" AND week = "' . $week . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $exist = true;

    $req->closeCursor();

    // Retour
    return $exist;
  }

  // PHYSIQUE : Lecture changelog
  // RETOUR : Objet Changelog
  function physiqueChangelog($year, $week)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM change_log
                        WHERE year = "' . $year . '" AND week = "' . $week . '"');

    $data = $req->fetch();

    $changelog = ChangeLog::withData($data);

    $req->closeCursor();

    // Retour
    return $changelog;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle alerte
  // RETOUR : Id alerte
  function physiqueInsertionChangelog($changelog)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO change_log(week,
                                                 year,
                                                 notes,
                                                 logs)
                                         VALUES(:week,
                                                :year,
                                                :notes,
                                                :logs)');

    $req->execute($changelog);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour journal
  // RETOUR : Aucun
  function physiqueUpdateChangelog($changelog, $year, $week)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE change_log
                          SET notes = :notes,
                              logs = :logs
                          WHERE year = ' . $year . ' AND week = ' . $week);

    $req->execute($changelog);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression journal
  // RETOUR : Aucun
  function physiqueDeleteChangelog($year, $week)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM change_log
                       WHERE year = ' . $year . ' AND week = ' . $week);
  }
?>
