<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/notifications.php');
  include_once('../../includes/classes/missions.php');

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }

  // METIER : Récupération missions actives + 3 jours pour les résultats
  // RETOUR : Objet mission
  function getMessagesMissions()
  {
    $missions  = array();
    $date_jour = date('Ymd');

    global $bdd;

    $date_jour_moins_3 = date("Ymd", strtotime(date("Ymd") . ' - 3 days'));

    $reponse = $bdd->query('SELECT * FROM missions WHERE date_deb <= ' . $date_jour . ' AND date_fin >= ' . $date_jour_moins_3 . ' ORDER BY date_deb ASC');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);
      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    return $missions;
  }

  // METIER : Récupération liste des gagnants
  // RETOUR : Tableau gagnants
  function getWinners($missions)
  {
    $gagnants = array();

    foreach ($missions as $mission)
    {
      if (date('Ymd') > $mission->getDate_fin())
      {
        // Récupération des participants
        $participants = array();

        global $bdd;

        $reponse1 = $bdd->query('SELECT DISTINCT identifiant FROM missions_users WHERE id_mission = ' . $mission->getId() . ' ORDER BY identifiant ASC');
        while($donnees1 = $reponse1->fetch())
        {
          $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['identifiant'] . '"');
          $donnees2 = $reponse2->fetch();

          $myParticipant = Profile::withData($donnees2);

          $reponse2->closeCursor();

          array_push($participants, $myParticipant);
        }
        $reponse1->closeCursor();

        // Récupération du classement
        $ranking = array();

        foreach ($participants as $user)
        {
          $totalMission = 0;
          $initRankUser = 0;

          // Nombre total d'objectifs sur la mission
          $reponse = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission->getId() . ' AND identifiant = "' . $user->getIdentifiant() . '"');
          while($donnees = $reponse->fetch())
          {
            $totalMission += $donnees['avancement'];
          }
          $reponse->closeCursor();

          $myRanking = array('id_mission'  => $mission->getId(),
                             'identifiant' => $user->getIdentifiant(),
                             'pseudo'      => $user->getPseudo(),
                             'total'       => $totalMission,
                             'rank'        => $initRankUser
                           );

          array_push($ranking, $myRanking);
        }

        // Tri classement et extraction gagnants
        if (!empty($ranking))
        {
          unset($tri_rank);
          unset($tri_alpha);

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

        // On ne garde que les gagnants
        foreach ($ranking as &$rankUser)
        {
          if ($rankUser['rank'] != 1)
            unset($rankUser);
        }

        unset($rankUser);

        array_push($gagnants, $ranking);
      }
    }

    return $gagnants;
  }
?>
