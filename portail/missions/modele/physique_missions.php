<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des missions
  // RETOUR : Liste des missions
  function physiqueMissions()
  {
    // Initialisations
    $listeMissions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Mission à partir des données remontées de la bdd
      $mission = Mission::withData($data);

      // Assignation du statut en fonction de la date
      if (date('Ymd') < $mission->getDate_deb() OR (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure()))
        $mission->setStatut('V');
      elseif (((date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure()) OR date('Ymd') > $mission->getDate_deb()) AND date('Ymd') <= $mission->getDate_fin())
        $mission->setStatut('C');
      elseif (date('Ymd') > $mission->getDate_fin())
        $mission->setStatut('A');

      // On ajoute la ligne au tableau
      array_push($listeMissions, $mission);
    }

    $req->closeCursor();

    // Retour
    return $listeMissions;
  }

  // PHYSIQUE : Lecture mission commencée par l'utilisateur
  // RETOUR : Booléen
  function physiqueMissionCommencee($idMission, $identifiant)
  {
    // Initialisations
    $missionCommencee = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '" AND date_mission = ' . date('Ymd'));

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $missionCommencee = true;

    $req->closeCursor();

    // Retour
    return $missionCommencee;
  }

  // PHYSIQUE : Lecture mission en fonction de la date
  // RETOUR : Booléen
  function physiqueMissionDisponible($idMission)
  {
    // Initialisations
    $missionDisponible = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM missions
                        WHERE (id = ' . $idMission . ' AND (date_deb < ' . date('Ymd') . ' OR (date_deb = ' . date('Ymd') . ' AND heure <= ' . date('His') . ')))');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $missionDisponible = true;

    $req->closeCursor();

    // Retour
    return $missionDisponible;
  }

  // PHYSIQUE : Lecture d'une mission
  // RETOUR : Objet Mission
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

    // Assignation du statut en fonction de la date
    if (date('Ymd') < $mission->getDate_deb() OR (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure()))
      $mission->setStatut('V');
    elseif (((date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure()) OR date('Ymd') > $mission->getDate_deb()) AND date('Ymd') <= $mission->getDate_fin())
      $mission->setStatut('C');
    elseif (date('Ymd') > $mission->getDate_fin())
      $mission->setStatut('A');

    $req->closeCursor();

    // Retour
    return $mission;
  }

  // PHYSIQUE : Lecture avancement d'une mission
  // RETOUR : Tableau d'avancement
  function physiqueAvancementMission($idMission, $identifiant)
  {
    // Initialisations
    $avancement = array('daily'         => 0,
                        'event'         => 0,
                        'daily_percent' => 0,
                        'event_percent' => 0
                       );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '"');

    while ($data = $req->fetch())
    {
      // Avancement total de la mission
      $avancement['event'] += $data['avancement'];

      // Avancement du jour
      if ($data['date_mission'] == date('Ymd'))
        $avancement['daily'] = $data['avancement'];
    }

    $req->closeCursor();

    // Retour
    return $avancement;
  }

  // PHYSIQUE : Lecture participants d'une mission
  // RETOUR : Liste des participants
  function physiqueParticipantsMission($idMission, $equipe)
  {
    // Initialisations
    $listeParticipants = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, team, pseudo, avatar
                        FROM users
                        WHERE EXISTS (SELECT id, id_mission, team, identifiant
                                      FROM missions_users
                                      WHERE missions_users.identifiant = users.identifiant AND missions_users.id_mission = "' . $idMission . '" AND missions_users.team = "' . $equipe . '")
                       ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // On ajoute la ligne au tableau
      $listeParticipants[$data['identifiant']] = $user;
    }

    $req->closeCursor();

    // Retour
    return $listeParticipants;
  }

  // PHYSIQUE : Lecture participation d'un utilisateur
  // RETOUR : Booléen
  function physiqueParticipationUser($idMission, $equipe, $identifiant)
  {
    // Initialisations
    $participationUser = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . ' AND team != "' . $equipe . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $participationUser = true;

    $req->closeCursor();

    // Retour
    return $participationUser;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion avancement mission utilisateur
  // RETOUR : Aucun
  function physiqueInsertionMissionUser($missionUser)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO missions_users(id_mission,
                                                     team,
                                                     identifiant,
                                                     avancement,
                                                     date_mission)
                                             VALUES(:id_mission,
                                                    :team,
                                                    :identifiant,
                                                    :avancement,
                                                    :date_mission)');

    $req->execute($missionUser);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour avancement mission utilisateur
  // RETOUR : Aucun
  function physiqueUpdateMissionUser($idMission, $identifiant, $avancement)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE missions_users
                          SET avancement = :avancement
                          WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '" AND date_mission = ' . date('Ymd'));

    $req->execute(array(
      'avancement' => $avancement
    ));

    $req->closeCursor();
  }
?>
