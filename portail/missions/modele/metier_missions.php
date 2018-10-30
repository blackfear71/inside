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

    // Contrôle mission existante
    $reponse = $bdd->query('SELECT *
                            FROM missions
                            WHERE (id = ' . $id . '
                              AND (date_deb < ' . date("Ymd") . '
                              OR  (date_deb = ' . date("Ymd") . ' AND heure <= ' . date("His") . ')))');

    if ($reponse->rowCount() == 0)
      $_SESSION['alerts']['mission_doesnt_exist'] = true;

    $reponse->closeCursor();

    if ($_SESSION['alerts']['mission_doesnt_exist'] == false)
      $missionExistante = true;

    return $missionExistante;
  }

  // METIER : Récupération des missions
  // RETOUR : Objets mission
  function getMissions()
  {
    $missions = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);

      if (date('Ymd') < $myMission->getDate_deb() OR (date('Ymd') == $myMission->getDate_deb() AND date('His') < $myMission->getHeure()))
        $myMission->setStatut('V');
      elseif (date('Ymd') >= $myMission->getDate_deb() AND date('Ymd') <= $myMission->getDate_fin() AND date('His') >= $myMission->getHeure())
        $myMission->setStatut('C');
      elseif (date('Ymd') > $myMission->getDate_fin())
        $myMission->setStatut('A');

      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    // Tri sur statut (V : à venir, C : en cours, A : ancienne)
    foreach ($missions as $mission)
    {
      $tri_statut[]   = $mission->getStatut();
      $tri_date_deb[] = $mission->getDate_deb();
    }

    array_multisort($tri_statut, SORT_DESC, $tri_date_deb, SORT_DESC, $missions);

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
  function validateMission($user, $ref, $key, $mission)
  {
    if (!empty($mission))
    {
      $control_maj = false;

      global $bdd;

      if (isset($_SERVER['HTTP_REFERER']) AND strpos($_SERVER['HTTP_REFERER'], $mission[$ref]['page']) !== false)
      {
        // Contrôle mission commencée utilisateur
        $reponse1 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date("Ymd"));
        if ($reponse1->rowCount() > 0)
          $control_maj = true;
        $reponse1->closeCursor();

        if ($control_maj == true)
        {
          // Lecture avancement mission
          $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date("Ymd"));
          $donnees2 = $reponse2->fetch();
          $avancement = $donnees2['avancement'];
          $reponse2->closeCursor();

          // Mise à jour avancement mission
          $avancement += 1;

          $reponse3 = $bdd->prepare('UPDATE missions_users SET avancement = :avancement WHERE id_mission = ' . $mission[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date("Ymd"));
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
            'id_mission'   => $mission[$ref]['id_mission'],
            'identifiant'  => $user,
            'avancement'   => 1,
            'date_mission' => date("Ymd")
              ));
          $reponse2->closeCursor();
        }

        // On supprime le bouton correspondant pour ne pas cliquer dessus à nouveau
        unset($mission[$ref]);
        $_SESSION['missions'][$key] = $mission;
      }

      if (empty($mission))
        $_SESSION['alerts']['mission_achieved'] = true;
    }
  }

  // METIER : Classement des utilisateurs sur la mission
  // RETOUR : Tableau classement
  function getRankingMission($id, $users)
  {
    $ranking = array();

    global $bdd;

    foreach ($users as $user)
    {
      $totalMission = 0;
      $initRankUser = 0;

      // Nombre total d'objectifs sur la mission
      $reponse = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id . ' AND identifiant = "' . $user->getIdentifiant() . '"');
      while($donnees = $reponse->fetch())
      {
        $totalMission += $donnees['avancement'];
      }
      $reponse->closeCursor();

      $myRanking = array('identifiant' => $user->getIdentifiant(),
                         'pseudo'      => $user->getPseudo(),
                         'avatar'      => $user->getAvatar(),
                         'total'       => $totalMission,
                         'rank'        => $initRankUser
                       );

      array_push($ranking, $myRanking);
    }

    if (!empty($ranking))
    {
      // Tri sur avancement puis identifiant
      foreach ($ranking as $rankUser)
      {
        $tri_rank[]  = $rankUser['total'];
        $tri_alpha[] = $rankUser['identifiant'];
      }

      array_multisort($tri_rank, SORT_DESC, $tri_alpha, SORT_ASC, $ranking);

      // Affectation du rang
      $prevTotal   = $ranking[0]['total'];
      $currentRank = 1;

      foreach ($ranking as &$rankUser)
      {
        $currentTotal = $rankUser['total'];

        if ($currentTotal != $prevTotal)
        {
          $currentRank += 1;
          $prevTotal = $rankUser['total'];
        }

        $rankUser['rank'] = $currentRank;
      }

      unset($rankUser);
    }

    return $ranking;
  }
?>
