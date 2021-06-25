<?php
  include_once('../../includes/classes/bugs.php');
  include_once('../../includes/classes/calendars.php');
  include_once('../../includes/classes/collectors.php');
  include_once('../../includes/classes/expenses.php');
  include_once('../../includes/classes/gateaux.php');
  include_once('../../includes/classes/movies.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/restaurants.php');
  include_once('../../includes/classes/teams.php');

  // METIER : Lecture de la liste des équipes
  // RETOUR : Liste des équipes
  function getListeEquipes()
  {
    // Lecture de la liste des équipes
    $listeEquipes = physiqueListeEquipes();

    // Recherche du nombre d'utilisateurs par équipe
    foreach ($listeEquipes as $equipe)
    {
      $equipe->setNombre_users(physiqueNombreUsersEquipe($equipe->getReference()));
    }

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

  // METIER : Suppression d'une équipe
  // RETOUR : Aucun
  function deleteEquipe($post)
  {
    // Récupération des données
    $equipe = $post['team'];

    // Lecture des bugs / évolutions de l'équipe
    $listeBugsEvolutions = physiqueTableEquipe('bugs', 'id, team, picture', 'BugEvolution', $equipe);

    // Suppression des bugs / évolutions
    if (!empty($listeBugsEvolutions))
    {
      foreach ($listeBugsEvolutions as $bugEvolution)
      {
        // Suppression des images
        if (!empty($bugEvolution->getPicture()))
          unlink('../../includes/images/reports/' . $bugEvolution->getPicture());

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('bugs', $bugEvolution->getId(), $equipe);
      }
    }

    // Lecture des calendriers de l'équipe
    $listeCalendriers = physiqueTableEquipe('calendars', 'id, team, year, calendar', 'Calendrier', $equipe);

    // Suppression des calendriers
    if (!empty($listeCalendriers))
    {
      foreach ($listeCalendriers as $calendrier)
      {
        // Suppression des images
        if (!empty($calendrier->getCalendar()))
        {
          unlink('../../includes/images/calendars/' . $calendrier->getYear() . '/' . $calendrier->getCalendar());
          unlink('../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar());
        }

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('calendars', $calendrier->getId(), $equipe);
      }
    }

    // Lecture des annexes de l'équipe
    $listeAnnexes = physiqueTableEquipe('calendars_annexes', 'id, team, annexe', 'Annexe', $equipe);

    // Suppression des annexes
    if (!empty($listeAnnexes))
    {
      foreach ($listeAnnexes as $annexe)
      {
        // Suppression des images
        if (!empty($annexe->getAnnexe()))
          unlink('../../includes/images/calendars/annexes/' . $annexe->getAnnexe());

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('calendars_annexes', $annexe->getId(), $equipe);
      }
    }

    // Lecture des phrases / images cultes de l'équipe
    $listeCollectors = physiqueTableEquipe('collector', 'id, type_collector, team, collector', 'Collector', $equipe);

    // Suppression des phrases / images cultes
    if (!empty($listeCollectors))
    {
      foreach ($listeCollectors as $collector)
      {
        // Suppression des images
        if ($collector->getType_collector() == 'I' AND !empty($collector->getCollector()))
          unlink('../../includes/images/collector/' . $collector->getCollector());

        // Suppression des votes
        physiqueDeleteVotesCollector($collector->getId(), $equipe);

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('collector', $collector->getId(), $equipe);
      }
    }

    // Lecture des recettes de l'équipe
    $listeRecettes = physiqueTableEquipe('cooking_box', 'id, team, year, picture', 'WeekCake', $equipe);

    // Suppression des recettes
    if (!empty($listeRecettes))
    {
      foreach ($listeRecettes as $recette)
      {
        // Suppression des images
        if (!empty($recette->getPicture()))
          unlink('../../includes/images/cookingbox/' . $recette->getYear() . '/' . $recette->getPicture());

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('cooking_box', $recette->getId(), $equipe);
      }
    }

    // Lecture des dépenses de l'équipe
    $listeDepenses = physiqueTableEquipe('expense_center', 'id, team', 'Expenses', $equipe);

    // Suppression des dépenses
    if (!empty($listeDepenses))
    {
      foreach ($listeDepenses as $depense)
      {
        // Suppression des parts
        physiqueDeletePartsDepenses($depense->getId(), $equipe);

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('expense_center', $depense->getId(), $equipe);
      }
    }

    // Suppression des choix de restaurants
    physiqueDeleteTableEquipeLight('food_advisor_choices', $equipe);

    // Suppression des votes de restaurants
    physiqueDeleteTableEquipeLight('food_advisor_users', $equipe);

    // Lecture des restaurants de l'équipe
    $listeRestaurants = physiqueTableEquipe('food_advisor_restaurants', 'id, team, picture', 'Restaurant', $equipe);

    // Suppression des restaurants
    if (!empty($listeRestaurants))
    {
      foreach ($listeRestaurants as $restaurant)
      {
        // Suppression des images
        if (!empty($restaurant->getPicture()))
          unlink('../../includes/images/foodadvisor/' . $restaurant->getPicture());

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('food_advisor_restaurants', $restaurant->getId(), $equipe);
      }
    }

    // Suppression des idées
    physiqueDeleteTableEquipeLight('ideas', $equipe);

    // Suppression des missions utilisateurs
    physiqueDeleteTableEquipeLight('missions_users', $equipe);

    // Lecture des films de l'équipe
    $listeFilms = physiqueTableEquipe('movie_house', 'id, team', 'Movie', $equipe);

    // Suppression des films
    if (!empty($listeFilms))
    {
      foreach ($listeFilms as $film)
      {
        // Suppression des commentaires
        physiqueDeleteCommentairesFilm($film->getId(), $equipe);

        // Suppression des votes
        physiqueDeleteVotesFilm($film->getId(), $equipe);

        // Suppression de l'enregistrement en base
        physiqueDeleteTableEquipe('movie_house', $film->getId(), $equipe);
      }
    }

    // Suppression des notifications
    physiqueDeleteTableEquipeLight('notifications', $equipe);

    // Suppression des parcours
    physiqueDeleteTableEquipeLight('petits_pedestres_parcours', $equipe);

    // Suppression du fichier de chat
    if (file_exists('../../includes/common/chat/conversations/content_chat_' . $equipe . '.xml'))
      unlink('../../includes/common/chat/conversations/content_chat_' . $equipe . '.xml');

    // Suppression de l'équipe
    physiqueDeleteEquipe($equipe);
  }
?>
