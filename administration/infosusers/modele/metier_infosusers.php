<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, anniversary, experience FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // Récupération succès Beginner / Developper
      getSuccessAdmin($user);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture succès Beginner et Developper
  // RETOUR : Liste succès
  function getSuccessAdmin($user)
  {
    $listSuccess = array('beginning', 'developper');

    global $bdd;

    foreach ($listSuccess as $success)
    {
      $value = 0;

      // Lecture valeur succès
      $reponse = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success . '" AND identifiant = "' . $user->getIdentifiant() . '"');
      $donnees = $reponse->fetch();

      if ($reponse->rowCount() > 0)
        $value = $donnees['value'];

      $reponse->closeCursor();

      switch ($success)
      {
        case 'beginning':
          $user->setBeginner($value);
          break;

        case 'developper':
          $user->setDevelopper($value);
          break;

        default:
          break;
      }
    }
  }

  // METIER : Récupération des données de progression
  // RETOUR : Tableau des données de progression
  function getProgress($listUsers)
  {
    $listProgression = array();

    foreach ($listUsers as $user)
    {
      $experience = $user->getExperience();
      $niveau     = convertExperience($experience);
      $exp_min    = 10 * $niveau ** 2;
      $exp_max    = 10 * ($niveau + 1) ** 2;
      $exp_lvl    = $exp_max - $exp_min;
      $progress   = $experience - $exp_min;
      $percent    = floor($progress * 100 / $exp_lvl);

      $listProgression[$user->getIdentifiant()] = $niveau;
    }

    return $listProgression;
  }

  // METIER : Modification top Beginner
  // RETOUR : Aucun
  function changeBeginner($post)
  {
    $identifiant = $post['user_infos'];
    $topBeginner = $post['top_infos'];

    if ($topBeginner == 1)
      $value = 0;
    else
      $value = 1;

    insertOrUpdateSuccesValue('beginning', $identifiant, $value);
  }

  // METIER : Modification top Developper
  // RETOUR : Aucun
  function changeDevelopper($post)
  {
    $identifiant   = $post['user_infos'];
    $topDevelopper = $post['top_infos'];

    if ($topDevelopper == 1)
      $value = 0;
    else
      $value = 1;

    insertOrUpdateSuccesValue('developper', $identifiant, $value);
  }
?>
