<?php
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Récupération liste des utilisateurs
    $listUsers = physiqueUsers();

    // Récupération des données complémentaires
    foreach ($listUsers as $user)
    {
      // Récupération du niveau
      $level = convertExperience($user->getExperience());
      $user->setLevel($level);

      // Récupération succès Beginner / Developper
      $listSuccess = array('beginning', 'developper');

      foreach ($listSuccess as $success)
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
    }

    // Retour
    return $listUsers;
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
