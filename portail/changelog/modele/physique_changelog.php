<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture nombre de lignes existantes pour une année
  // RETOUR : Booléen
  function physiqueAnneeExistante($annee)
  {
    // Initialisations
    $anneeExistante = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM change_log
                        WHERE year = "' . $annee . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $anneeExistante = true;

    $req->closeCursor();

    // Retour
    return $anneeExistante;
  }

  // PHYSIQUE : Lecture des années existantes
  // RETOUR : Liste des années
  function physiqueOnglets()
  {
    // Initialisations
    $onglets = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT year
                        FROM change_log
                        ORDER BY year DESC');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($onglets, $data['year']);
    }

    $req->closeCursor();

    // Retour
    return $onglets;
  }

  // PHYSIQUE : Lecture des journaux
  // RETOUR : Liste des journaux
  function physiqueChangelog($annee)
  {
    // Initialisations
    $listeLogs = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM change_log
                        WHERE year = "' . $annee . '"
                        ORDER BY week DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet ChangeLog à partir des données remontées de la bdd
      $log = ChangeLog::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeLogs, $log);
    }

    $req->closeCursor();

    // Retour
    return $listeLogs;
  }
?>
