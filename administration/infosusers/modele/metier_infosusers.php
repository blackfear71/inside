<?php
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/teams.php');

  // METIER : Lecture de la liste des équipes
  // RETOUR : Liste des équipes
  function getListeEquipes()
  {
    // Lecture de la liste des équipes
    $listeEquipes = physiqueListeEquipes();

    // Retour
    return $listeEquipes;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisations
    $listeUsersParEquipe = array();

    // Récupération liste des utilisateurs
    $listeUsers = physiqueUsers();

    // Récupération des données complémentaires et ajout à la liste par équipes
    foreach ($listeUsers as $user)
    {
      // Récupération du niveau
      $level = convertExperience($user->getExperience());
      $user->setLevel($level);

      // Récupération succès Beginner / Developper
      $listeSuccess = array('beginning', 'developper');

      foreach ($listeSuccess as $success)
      {
        // Récupération valeur succès
        $valueSuccess = physiqueSuccessAdmin($success, $user->getIdentifiant());

        // Affectation de la valeur
        switch ($success)
        {
          case 'beginning':
            $user->setBeginner($valueSuccess);
            break;

          case 'developper':
            $user->setDevelopper($valueSuccess);
            break;

          default:
            break;
        }
      }

      // Ajout de l'utilisateur à son équipe
      if (!isset($listeUsersParEquipe[$user->getTeam()]))
        $listeUsersParEquipe[$user->getTeam()] = array();

      array_push($listeUsersParEquipe[$user->getTeam()], $user);
    }

    // Retour
    return $listeUsersParEquipe;
  }

  // METIER : Modification top Beginner
  // RETOUR : Aucun
  function changeBeginner($post)
  {
    // Récupération des données saisies
    $identifiant = $post['user_infos'];
    $topBeginner = $post['top_infos'];

    if ($topBeginner == 1)
      $value = 0;
    else
      $value = 1;

    // Mise à jour succès
    insertOrUpdateSuccesValue('beginning', $identifiant, $value);
  }

  // METIER : Modification top Developper
  // RETOUR : Aucun
  function changeDevelopper($post)
  {
    // Récupération des données saisies
    $identifiant   = $post['user_infos'];
    $topDevelopper = $post['top_infos'];

    if ($topDevelopper == 1)
      $value = 0;
    else
      $value = 1;

    // Mise à jour succès
    insertOrUpdateSuccesValue('developper', $identifiant, $value);
  }
?>
