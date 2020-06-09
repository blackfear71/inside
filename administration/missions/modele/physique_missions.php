<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des missions
  // RETOUR : Liste missions
  function physiqueListeMissions()
  {
    // Initialisations
    $missions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Mission à partir des données remontées de la bdd
      $myMission = Mission::withData($data);

      // On ajoute la ligne au tableau
      array_push($missions, $myMission);
    }

    $req->closeCursor();

    // Retour
    return $missions;
  }

  // PHYSIQUE : Lecture des détails d'une mission
  // RETOUR : Objet mission
  function physiqueMission($idMission)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE id = ' . $idMission);

    $data = $req->fetch();

    // Instanciation d'un objet Mission à partir des données remontées de la bdd
    $mission = Mission::withData($data);

    $req->closeCursor();

    // Retour
    return $mission;
  }

  // PHYSIQUE : Lecture des participants d'une mission
  // RETOUR : Liste des utilisateurs
  function physiqueUsersMission($idMission)
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT identifiant
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . '
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Récupération des identifiants
      $myUser = array('identifiant' => $data['identifiant'],
                      'pseudo'      => '',
                      'total'       => 0,
                      'rank'        => 0
                     );

      // On ajoute la ligne au tableau
      array_push($listeUsers, $myUser);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des informations utilisateur
  // RETOUR : Pseudo utilisateur
  function physiquePseudoUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $pseudo = $data['pseudo'];

    $req->closeCursor();

    // Retour
    return $pseudo;
  }

  // PHYSIQUE : Lecture des informations utilisateur de la mission
  // RETOUR : Total utilisateur
  function physiqueTotalUser($idMission, $identifiant)
  {
    // Initialisations
    $totalMission = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '"');

    while ($data = $req->fetch())
    {
      $totalMission += $data['avancement'];
    }

    $req->closeCursor();

    // Retour
    return $totalMission;
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
                        FROM missions
                        WHERE reference = "' . $reference . '"');

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
  // PHYSIQUE : Insertion nouvelle mission
  // RETOUR : Aucun
  function physiqueInsertionMission($mission)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO missions(mission,
                                               reference,
                                               date_deb,
                                               date_fin,
                                               heure,
                                               objectif,
                                               description,
                                               explications,
                                               conclusion)
                                       VALUES(:mission,
                                              :reference,
                                              :date_deb,
                                              :date_fin,
                                              :heure,
                                              :objectif,
                                              :description,
                                              :explications,
                                              :conclusion)');

    $req->execute($mission);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour mission existante
  // RETOUR : Aucun
  function physiqueUpdateMission($idMission, $mission)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE missions
                          SET mission      = :mission,
                              date_deb     = :date_deb,
                              date_fin     = :date_fin,
                              heure        = :heure,
                              objectif     = :objectif,
                              description  = :description,
                              explications = :explications,
                              conclusion   = :conclusion
                          WHERE id = ' . $idMission);

    $req->execute($mission);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression mission
  // RETOUR : Aucun
  function physiqueDeleteMission($idMission)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM missions
                       WHERE id = ' . $idMission);
  }

  // PHYSIQUE : Suppression participations mission
  // RETOUR : Aucun
  function physiqueDeleteMissionUsers($idMission)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM missions_users
                       WHERE id_mission = ' . $idMission);
  }
?>
