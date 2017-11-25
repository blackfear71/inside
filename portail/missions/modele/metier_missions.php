<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/missions.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Contrôle mission existante
  // RETOUR : Booléen
  function controlMission($id)
  {
    global $bdd;

    $missionExistante = false;

    // Contrôle film existant
    $reponse = $bdd->query('SELECT * FROM missions WHERE id = ' . $id);

    if ($reponse->rowCount() == 0)
      $_SESSION['mission_doesnt_exist'] = true;

    $reponse->closeCursor();

    if ($_SESSION['mission_doesnt_exist'] == false)
      $missionExistante = true;

    return $missionExistante;
  }

  // METIER : Récupération des missions
  // RETOUR : Objets mission
  function getMissions()
  {
    $missions = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions ORDER BY date_deb DESC');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);
      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    return $missions;
  }

  // METIER : Récupération mission spécifique
  // RETOUR : Objet mission
  function getMission($id)
  {
    $mission = new Mission();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE id = ' . $id);
    $donnees = $reponse->fetch();

    $mission = Mission::withData($donnees);

    $reponse->closeCursor();

    return $mission;
  }

  // METIER : Récupération des participants d'une mission
  // RETOUR : Objets Profil
  function getParticipants($id)
  {
    $participants = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT identifiant FROM missions_users WHERE id_mission = ' . $id . ' ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
      $donnees2 = $reponse2->fetch();

      $myParticipant = Profile::withData($donnees2);

      $reponse2->closeCursor();

      array_push($participants, $myParticipant);
    }
    $reponse->closeCursor();

    return $participants;
  }

  // METIER : Récupération tableau de l'avancement (quotidien et évènement)
  // RETOUR : Tableau de l'avancement
  function getMissionUser($id, $user)
  {
    $avancement = array('daily' => 0,
                        'event' => 0
                       );

    global $bdd;

    // Nombre atteint aujourd'hui
    $reponse1 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id . ' AND identifiant = "' . $user . '" AND date_mission = "' . date('Ymd') . '"');
    $donnees1 = $reponse1->fetch();

    if ($reponse1->rowCount() > 0)
      $avancement['daily'] = $donnees1['avancement'];

    $reponse1->closeCursor();

    // Nombre total d'objectifs sur la mission
    $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id . ' AND identifiant = "' . $user . '"');
    while($donnees2 = $reponse2->fetch())
    {
      $avancement['event'] += $donnees2['avancement'];
    }
    $reponse2->closeCursor();

    return $avancement;
  }

  // METIER : Validation mission en cours
  // RETOUR : Aucun
  function validateMission($user, $ref, $missions)
  {
    if (!empty($missions))
    {
      $control_maj = false;

      global $bdd;

      // Contrôle mission commencée utilisateur
      $reponse1 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $missions[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date("Ymd"));
      if ($reponse1->rowCount() > 0)
        $control_maj = true;
      $reponse1->closeCursor();

      if ($control_maj == true)
      {
        // Lecture avancement mission
        $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $missions[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date("Ymd"));
        $donnees2 = $reponse2->fetch();
        $avancement = $donnees2['avancement'];
        $reponse2->closeCursor();

        // Mise à jour avancement mission
        $avancement += 1;

        $reponse3 = $bdd->prepare('UPDATE missions_users SET avancement = :avancement WHERE id_mission = ' . $missions[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date("Ymd"));
        $reponse3->execute(array(
          'avancement' => $avancement
        ));
        $reponse3->closeCursor();
      }
      else
      {
        // Création mission du jour pour l'utilisateur et initialisation avancement à 1
        $reponse2 = $bdd->prepare('INSERT INTO missions_users(id_mission, identifiant, avancement, date_mission) VALUES(:id_mission, :identifiant, :avancement, :date_mission)');
        $reponse2->execute(array(
          'id_mission'   => $missions[$ref]['id_mission'],
          'identifiant'  => $user,
          'avancement'   => 1,
          'date_mission' => date("Ymd")
            ));
        $reponse2->closeCursor();
      }

      // On supprime le bouton correspondant pour ne pas cliquer dessus à nouveau
      unset($missions[$ref]);
      $_SESSION['tableau_missions'] = $missions;

      if (empty($missions))
        $_SESSION['mission_achieved'] = true;
    }
  }
?>
