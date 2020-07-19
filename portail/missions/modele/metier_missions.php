<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/missions.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Contrôle mission existante
  // RETOUR : Booléen
  function controlMission($id)
  {
    global $bdd;

    $missionExistante = false;

    // Contrôle mission existante
    $reponse = $bdd->query('SELECT * FROM missions WHERE (id = ' . $id . ' AND (date_deb < ' . date('Ymd') . ' OR (date_deb = ' . date('Ymd') . ' AND heure <= ' . date('His') . ')))');

    if ($reponse->rowCount() > 0)
      $missionExistante = true;

    $reponse->closeCursor();

    if ($missionExistante == false)
      $_SESSION['alerts']['mission_doesnt_exist'] = true;

    return $missionExistante;
  }

  // METIER : Récupération des missions
  // RETOUR : Objets mission
  function getMissions()
  {
    $listeMissions = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions');
    while ($donnees = $reponse->fetch())
    {
      $mission = Mission::withData($donnees);

      if (date('Ymd') < $mission->getDate_deb() OR (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure()))
        $mission->setStatut('V');
      elseif (((date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure()) OR date('Ymd') > $mission->getDate_deb()) AND date('Ymd') <= $mission->getDate_fin())
        $mission->setStatut('C');
      elseif (date('Ymd') > $mission->getDate_fin())
        $mission->setStatut('A');

      array_push($listeMissions, $mission);
    }
    $reponse->closeCursor();

    // Tri sur statut (V : à venir, C : en cours, A : ancienne)
    foreach ($listeMissions as $missionTri)
    {
      $triStatut[]  = $missionTri->getStatut();
      $triDateDeb[] = $missionTri->getDate_deb();
    }

    array_multisort($triStatut, SORT_DESC, $triDateDeb, SORT_DESC, $listeMissions);

    // Retour
    return $listeMissions;
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

    if (date('Ymd') < $mission->getDate_deb() OR (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure()))
      $mission->setStatut('V');
    elseif (((date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure()) OR date('Ymd') > $mission->getDate_deb()) AND date('Ymd') <= $mission->getDate_fin())
      $mission->setStatut('C');
    elseif (date('Ymd') > $mission->getDate_fin())
      $mission->setStatut('A');

    $reponse->closeCursor();

    return $mission;
  }

  // METIER : Récupération des participants d'une mission
  // RETOUR : Objets Profil
  function getParticipants($id)
  {
    $listeParticipants = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT identifiant FROM missions_users WHERE id_mission = ' . $id . ' ORDER BY identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
      $donnees2 = $reponse2->fetch();

      $participant = Profile::withData($donnees2);

      $reponse2->closeCursor();

      array_push($listeParticipants, $participant);
    }
    $reponse->closeCursor();

    return $listeParticipants;
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
    while ($donnees2 = $reponse2->fetch())
    {
      $avancement['event'] += $donnees2['avancement'];
    }
    $reponse2->closeCursor();

    return $avancement;
  }

  // METIER : Validation mission en cours
  // RETOUR : Aucun
  function validateMission($post, $user, $mission)
  {
    $ref = $post['ref_mission'];
    $key = $post['key_mission'];

    if (!empty($mission))
    {
      $missionCommencee = false;

      global $bdd;

      if (isset($_SERVER['HTTP_REFERER']) AND strpos($_SERVER['HTTP_REFERER'], $mission[$ref]['page']) !== false)
      {
        // Contrôle mission commencée utilisateur
        $reponse1 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date('Ymd'));

        if ($reponse1->rowCount() > 0)
          $missionCommencee = true;

        $reponse1->closeCursor();

        if ($missionCommencee == true)
        {
          // Lecture avancement mission
          $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date('Ymd'));
          $donnees2 = $reponse2->fetch();
          $avancement = $donnees2['avancement'];
          $reponse2->closeCursor();

          // Mise à jour avancement mission
          $avancement += 1;

          $reponse3 = $bdd->prepare('UPDATE missions_users SET avancement = :avancement WHERE id_mission = ' . $mission[$ref]['id_mission'] . ' AND identifiant = "' . $user . '" AND date_mission = ' . date('Ymd'));
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
            'date_mission' => date('Ymd')
              ));
          $reponse2->closeCursor();
        }

        // Génération succès mission
        insertOrUpdateSuccesMission($mission[$ref]['reference'], $user);

        // On supprime le bouton correspondant pour ne pas cliquer dessus à nouveau
        unset($mission[$ref]);
        $_SESSION['missions'][$key] = $mission;
      }

      if (empty($mission))
      {
        // Ajout expérience
        insertExperience($user, 'all_missions');

        $_SESSION['alerts']['mission_achieved'] = true;
      }
    }
  }

  // METIER : Classement des utilisateurs sur la mission
  // RETOUR : Tableau classement
  function getRankingMission($id, $listeUsers)
  {
    $rankingUsers = array();

    global $bdd;

    foreach ($listeUsers as $user)
    {
      $totalMission = 0;

      // Nombre total d'objectifs sur la mission
      $reponse = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id . ' AND identifiant = "' . $user->getIdentifiant() . '"');
      while ($donnees = $reponse->fetch())
      {
        $totalMission += $donnees['avancement'];
      }
      $reponse->closeCursor();

      // Récupération des données
      $rankUser = new ParticipantMission();

      $rankUser->setIdentifiant($user->getIdentifiant());
      $rankUser->setPseudo($user->getPseudo());
      $rankUser->setAvatar($user->getAvatar());
      $rankUser->setTotal($totalMission);
      $rankUser->setRank(0);

      // Ajout au tableau
      array_push($rankingUsers, $rankUser);
    }

    if (!empty($rankingUsers))
    {
      // Tri sur avancement puis identifiant
      foreach ($rankingUsers as $rankUser)
      {
        $triRank[]  = $rankUser->getTotal();
        $triAlpha[] = $rankUser->getIdentifiant();
      }

      array_multisort($triRank, SORT_DESC, $triAlpha, SORT_ASC, $rankingUsers);

      // Affectation du rang
      $prevTotal   = $rankingUsers[0]->getTotal();
      $currentRank = 1;

      foreach ($rankingUsers as &$rankUser)
      {
        $currentTotal = $rankUser->getTotal();

        if ($currentTotal != $prevTotal)
        {
          $currentRank += 1;
          $prevTotal = $rankUser->getTotal();
        }

        $rankUser->setRank($currentRank);
      }

      unset($rankUser);
    }

    return $rankingUsers;
  }
?>
