<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/expenses.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Récupération liste des utilisateurs
    $listUsers = physiqueUsers();

    // Retour
    return $listUsers;
  }

  // METIER : Recherche les utilisateurs désinscrits
  // RETOUR : Liste des utilisateurs désinscrits
  function getUsersDes($listUsersIns)
  {
    // Initialisations
    $listUsersDes = array();

    // Récupération des identifiants dans les films
    $listUsersFilms = physiqueIdentifiantsFilms();

    // Récupération des identifiants dans les commentaires des films
    $listUsersComments = physiqueIdentifiantsCommentairesFilms();

    // Récupération des identifiants dans les phrases cultes
    $listUsersCollector = physiqueIdentifiantsCollector();

    // Récupération des identifiants dans les dépenses
    $listUsersExpenses = physiqueIdentifiantsDepenses();

    // Récupération des identifiants dans les parts des dépenses
    $listUsersParts = physiqueIdentifiantsPartsDepenses();

    // Récupération des identifiants dans les bugs/évolutions
    $listUsersBugs = physiqueIdentifiantsBugs();

    // Récupération des identifiants dans les idées #TheBox
    $listUsersTheBox = physiqueIdentifiantsTheBox();

    // Fusion des données dans le tableau complet
    $listUsersDes = array_merge($listUsersFilms,
                                $listUsersComments,
                                $listUsersCollector,
                                $listUsersExpenses,
                                $listUsersParts,
                                $listUsersBugs,
                                $listUsersTheBox
                               );

    // Suppression des doublons
    $listUsersDes = array_unique($listUsersDes);

    // Tri par ordre alphabétique
    sort($listUsersDes);

    // Filtrage avec les utilisateurs inscrits
    foreach ($listUsersDes as $keyUserDes => $userDes)
    {
      foreach ($listUsersIns as $userIns)
      {
        if ($userDes == $userIns->getIdentifiant())
        {
          unset($listUsersDes[$keyUserDes]);
          break;
        }
      }
    }

    return $listUsersDes;
  }

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    // Appel physique
    $alert = physiqueAlerteUsers();

    // Retour
    return $alert;
  }

  // METIER : Lecture statistiques catégories des utilisateurs inscrits
  // RETOUR : Tableau de nombres de films ajoutés, de commentaires, de phrases cultes rapportées & bilans des dépenses
  function getTabCategoriesIns($listUsers)
  {
    // Initialisations
    $tabCategories = array();

    // Récupération des statistiques par catégories
    foreach ($listUsers as $user)
    {
      // Films ajoutés
      $nombreFilms = physiqueFilmsAjoutesUser($user->getIdentifiant());

      // Commentaires films
      $nombreComments = physiqueCommentairesFilmsUser($user->getIdentifiant());

      // Phrases cultes ajoutées
      $nombreCollector = physiqueCollectorAjoutesUser($user->getIdentifiant());

      // Bilan des dépenses
      $bilanUser = physiqueBilanDepensesUser($user->getIdentifiant());

      // Génération de la ligne du tableau
      $categoriesUser = array('identifiant'     => $user->getIdentifiant(),
                              'pseudo'          => $user->getPseudo(),
                              'nombreFilms'     => $nombreFilms,
                              'nombreComments'  => $nombreComments,
                              'nombreCollector' => $nombreCollector,
                              'bilanUser'       => $bilanUser
                             );

      // Ajout au tableau
      array_push($tabCategories, $categoriesUser);
    }

    // Retour
    return $tabCategories;
  }

  // METIER : Lecture statistiques catégories des utilisateurs désinscrits
  // RETOUR : Tableau de nombres de commentaires & bilans des dépenses
  function getTabCategoriesDes($listUsersDes)
  {
    // Initialisations
    $tabCategoriesDes = array();

    // Récupération des statistiques par catégories
    foreach ($listUsersDes as $userDes)
    {
      // Films ajoutés
      $nombreFilms = physiqueFilmsAjoutesUser($userDes);

      // Commentaires films
      $nombreComments = physiqueCommentairesFilmsUser($userDes);

      // Phrases cultes ajoutées
      $nombreCollector = physiqueCollectorAjoutesUser($userDes);

      // Calcul du bilan des dépenses (non stocké)
      $bilanUser = 0;

      $listExpenses = physiqueDepenses();

      foreach ($listExpenses as $expense)
      {
        // Nombre de parts total et de l'utilisateur
        $nombreParts = physiquePartsDepensesUser($expense->getId(), $userDes);

        // Prix par parts
        if ($nombreParts['total'] != 0)
          $prixParPart = $expense->getPrice() / $nombreParts['total'];
        else
          $prixParPart = 0;

        // Somme des dépenses moins les parts consommées pour calculer le bilan
        if ($expense->getBuyer() == $userDes)
          $bilanUser += $expense->getPrice() - ($prixParPart * $nombreParts['utilisateur']);
        else
          $bilanUser -= $prixParPart * $nombreParts['utilisateur'];
      }

      // Génération de la ligne du tableau
      $categoriesUserDes = array('identifiant'     => $userDes,
                                 'pseudo'          => '',
                                 'nombreFilms'     => $nombreFilms,
                                 'nombreComments'  => $nombreComments,
                                 'nombreCollector' => $nombreCollector,
                                 'bilanUser'       => $bilanUser
                                );

      // Ajout au tableau
      array_push($tabCategoriesDes, $categoriesUserDes);
    }

    // Retour
    return $tabCategoriesDes;
  }

  // METIER : Lecture total catégories des utilisateurs
  // RETOUR : Tableau des totaux des catégories
  function getTotalCategories($tabIns, $tabDes)
  {
    // Initialisations
    $tabTotalCategories = array();
    $sommeBilans        = 0;

    // Nombre de films ajoutés
    $nombreFilms = physiqueFilmsAjoutesTotal();

    // Nombre de commentaires
    $nombreComments = physiqueCommentairesFilmsTotal();

    // Nombre de phrase cultes
    $nombreCollector = physiqueCollectorTotal();

    // Calcul somme bilans utilisateurs inscrits
    foreach ($tabIns as $userIns)
    {
      $sommeBilans += $userIns['bilanUser'];
    }

    // Calcul somme bilans utilisateurs désinscrits
    foreach ($tabDes as $userDes)
    {
      $sommeBilans += $userDes['bilanUser'];
    }

    // Récupération des dépenses sans parts
		$expensesNoParts = 0;

    $listExpenses = physiqueDepenses();

    foreach ($listExpenses as $expense)
    {
      // Vérification s'il n'y a pas de parts
      $sansParts = physiqueDepenseSansParts($expense->getId());

      if ($sansParts == true)
        $expensesNoParts += $expense->getPrice();
    }

    // Retrait des dépenses sans parts de la somme des bilans
		$sommeBilans = $sommeBilans - $expensesNoParts;

    // Alerte si bilan non nul (proche de 0 à cause de l'arrondi)
    if ($sommeBilans < -0.01 OR $sommeBilans > 0.01)
      $alerteBilan = true;
    else
      $alerteBilan = false;

    // Ajout au tableau
    $tabTotalCategories = array('nombreFilms'     => $nombreFilms,
                                'nombreComments'  => $nombreComments,
                                'nombreCollector' => $nombreCollector,
                                'sommeBilans'     => $sommeBilans,
                                'alerteBilan'     => $alerteBilan
                               );

    return $tabTotalCategories;
  }

  // METIER : Lecture statistiques des utilisateurs
  // RETOUR : Tableau de statistiques
  function getTabStats($listUsersIns, $listUsersDes)
  {
    // Statistiques utilisateurs inscrits
    $statsIns = array();

    foreach ($listUsersIns as $userIns)
    {
      // Nombre de demandes (bugs/évolutions)
      $nombreBugsSoumis = physiqueBugsSoumisUser($userIns->getIdentifiant());

      // Nombre de demandes résolues (bugs/évolutions)
      $nombreBugsResolus = physiqueBugsResolusUser($userIns->getIdentifiant());

      // Nombre d'idées publiées
      $nombreTheBox = physiqueTheBoxUser($userIns->getIdentifiant());

      // Nombre d'idées en charge
      $nombreTheBoxEnCharge = physiqueTheBoxEnChargeUser($userIns->getIdentifiant());

      // Nombre d'idées terminées ou rejetées
      $nombreTheBoxTerminees = physiqueTheBoxTermineesUser($userIns->getIdentifiant());

      // On génère une ligne du tableau
      $myStatsIns = array('identifiant'           => $userIns->getIdentifiant(),
                          'pseudo'                => $userIns->getPseudo(),
                          'nombreBugsSoumis'      => $nombreBugsSoumis,
                          'nombreBugsResolus'     => $nombreBugsResolus,
                          'nombreTheBox'          => $nombreTheBox,
                          'nombreTheBoxEnCharge'  => $nombreTheBoxEnCharge,
                          'nombreTheBoxTerminees' => $nombreTheBoxTerminees
                         );

      array_push($statsIns, $myStatsIns);
    }

    // Statistiques utilisateurs désinscrits
    $statsDes = array();

    foreach ($listUsersDes as $userDes)
    {
      // Nombre de demandes (bugs/évolutions)
      $nombreBugsSoumis = physiqueBugsSoumisUser($userDes);

      // Nombre de demandes résolues (bugs/évolutions)
      $nombreBugsResolus = physiqueBugsResolusUser($userDes);

      // Nombre d'idées publiées
      $nombreTheBox = physiqueTheBoxUser($userDes);

      // Nombre d'idées en charge
      $nombreTheBoxEnCharge = physiqueTheBoxEnChargeUser($userDes);

      // Nombre d'idées terminées ou rejetées
      $nombreTheBoxTerminees = physiqueTheBoxTermineesUser($userDes);

      // On génère une ligne du tableau
      $myStatsDes = array('identifiant'           => $userDes,
                          'pseudo'                => '',
                          'nombreBugsSoumis'      => $nombreBugsSoumis,
                          'nombreBugsResolus'     => $nombreBugsResolus,
                          'nombreTheBox'          => $nombreTheBox,
                          'nombreTheBoxEnCharge'  => $nombreTheBoxEnCharge,
                          'nombreTheBoxTerminees' => $nombreTheBoxTerminees
                         );

      array_push($statsDes, $myStatsDes);
    }

    // Ajout au tableau global
    $tabStats = array('inscrits' => $statsIns, 'desinscrits' => $statsDes);

    return $tabStats;
  }

  // METIER : Lecture total statistiques
  // RETOUR : Tableau des totaux des statistiques
  function getTotalStats()
  {
    // Initialisations
    $tabTotalStats = array();

    // Nombre de demandes (bugs/évolutions)
    $nombreBugsSoumis = physiqueBugsSoumisTotal();

    // Nombre de demandes résolues (bugs/évolutions)
    $nombreBugsResolus = physiqueBugsResolusTotal();

    // Nombre d'idées publiées
    $nombreTheBox = physiqueTheBoxTotal();

    // Nombre d'idées en charge
    $nombreTheBoxEnCharge = physiqueTheBoxEnChargeTotal();

    // Nombre d'idées terminées ou rejetées
    $nombreTheBoxTerminees = physiqueTheBoxTermineesTotal();

    // Ajout au tableau
    $tabTotalStats = array('nombreBugsSoumis'      => $nombreBugsSoumis,
                           'nombreBugsResolus'     => $nombreBugsResolus,
                           'nombreTheBox'          => $nombreTheBox,
                           'nombreTheBoxEnCharge'  => $nombreTheBoxEnCharge,
                           'nombreTheBoxTerminees' => $nombreTheBoxTerminees
                          );

    return $tabTotalStats;
  }

  // METIER : Refus réinitialisation mot de passe
  // RETOUR : Aucun
  function resetOldPassword($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $status      = 'N';

    // Remise à "N" de l'indicateur de demande
    physiqueUpdateStatusUser($identifiant, $status);
  }

  // METIER : Réinitialisation mot de passe
  // RETOUR : Aucun
  function setNewPassword($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $status      = 'N';

    // Génération nouveau mot de passe aléatoire
    $chaine      = generateRandomString(10);
    $salt        = rand();
    $newPassword = htmlspecialchars(hash('sha1', $chaine . $salt));

    // Mise à jour du mot de passe et remise à N de l'indicateur de demande
    physiqueSetNewPassword($identifiant, $salt, $newPassword, $status);

    // Récupération pseudo utilisateur
    $pseudo = physiquePseudoUser($identifiant);

    // Mise en session des données
    $_SESSION['save']['user_ask_id']   = $identifiant;
    $_SESSION['save']['user_ask_name'] = $pseudo;
    $_SESSION['save']['new_password']  = $chaine;
  }

  // METIER : Validation inscription (mise à jour du status utilisateur)
  // RETOUR : Aucun
  function acceptInscription($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $status      = 'N';

    // Mise à jour à "N" du statut
    physiqueUpdateStatusUser($identifiant, $status);

    // Génération notification nouvel inscrit
    insertNotification('admin', 'inscrit', $identifiant);
  }

  // METIER : Refus inscription
  // RETOUR : Aucun
  function declineInscription($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];

    // Suppression des préférences
    physiqueDeletePreferences($identifiant);

    // Suppression de l'utilisateur
    physiqueDeleteUser($identifiant);
  }

  // METIER : Validation désinscription
  // RETOUR : Aucun
  function acceptDesinscription($post)
  {
    // Initialisations
    $control_ok  = true;

    // Récupération des données
    $identifiant = $post['id_user'];

    // Récupération des données utilisateur
    $user = physiqueDonneesDesinscriptionUser($identifiant);

    // Contrôle dépenses nulles
    $control_ok = controleDepensesNonNulles($user->getExpenses());

    // Enregistrement du pseudo dans les phrases cultes (speaker avec passage à "other")
    if ($control_ok == true)
      physiqueUpdateSpeakerCollector($user->getIdentifiant(), $user->getPseudo());

    // Remise en cours des idées non terminées ou rejetées
    if ($control_ok == true)
      physiqueUpdateStatusTheBox($user->getIdentifiant());

    // Suppression des données
    if ($control_ok == true)
    {
      // Suppression des avis movie_house_users
      physiqueDeleteStarsFilmsUser($user->getIdentifiant());

      // Suppression des votes collector
      physiqueDeleteVotesCollectorUser($user->getIdentifiant());

      // Suppression des missions
      physiqueDeleteMissionsUser($user->getIdentifiant());

      // Suppression des succès
      physiqueDeleteSuccessUser($user->getIdentifiant());

      // Suppression propositions restaurants
      physiqueDeleteVotesRestaurantsUser($user->getIdentifiant());

      // Suppression déterminations restaurants
      physiqueDeleteDeterminationsRestaurantsUser($user->getIdentifiant());

      // Suppression semaines gâteau
      physiqueDeleteSemainesGateauxUser($user->getIdentifiant());

      // Suppression des préférences
      physiqueDeletePreferences($user->getIdentifiant());

      // Suppression utilisateur
      physiqueDeleteUser($user->getIdentifiant());

      // Suppression notification inscription
      deleteNotification('inscrit', $user->getIdentifiant());

      // Suppression avatar
      if (!empty($user->getAvatar()))
        unlink('../../includes/images/profil/avatars/' . $user->getAvatar());
    }
  }

  // METIER : Refus désinscription
  // RETOUR : Aucun
  function resetDesinscription($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $status      = 'N';

    // Mise à jour à "N" du statut
    physiqueUpdateStatusUser($identifiant, $status);
  }
?>
