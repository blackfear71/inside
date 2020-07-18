<?php
  include_once('../../includes/classes/expenses.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if (!isset($_SESSION['save']['user_ask_id']) OR !isset($_SESSION['save']['user_ask_name']) OR !isset($_SESSION['save']['new_password']))
  	{
      unset($_SESSION['save']);

  		$_SESSION['save']['user_ask_id']   = '';
  		$_SESSION['save']['user_ask_name'] = '';
  		$_SESSION['save']['new_password']  = '';
  	}
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Récupération liste des utilisateurs
    $listeUsers = physiqueUsers();

    // Retour
    return $listeUsers;
  }

  // METIER : Recherche les utilisateurs désinscrits
  // RETOUR : Liste des utilisateurs désinscrits
  function getUsersDes($listeUsersIns)
  {
    // Initialisations
    $listeUsersDes = array();

    // Récupération des identifiants dans les films
    $listeUsersFilms = physiqueIdentifiantsFilms();

    // Récupération des identifiants dans les commentaires des films
    $listeUsersComments = physiqueIdentifiantsCommentairesFilms();

    // Récupération des identifiants dans les phrases cultes
    $listeUsersCollector = physiqueIdentifiantsCollector();

    // Récupération des identifiants dans les dépenses
    $listeUsersExpenses = physiqueIdentifiantsDepenses();

    // Récupération des identifiants dans les parts des dépenses
    $listeUsersParts = physiqueIdentifiantsPartsDepenses();

    // Récupération des identifiants dans les bugs/évolutions
    $listeUsersBugs = physiqueIdentifiantsBugs();

    // Récupération des identifiants dans les idées #TheBox
    $listeUsersTheBox = physiqueIdentifiantsTheBox();

    // Fusion des données dans le tableau complet
    $listeUsersDes = array_merge($listeUsersFilms,
                                 $listeUsersComments,
                                 $listeUsersCollector,
                                 $listeUsersExpenses,
                                 $listeUsersParts,
                                 $listeUsersBugs,
                                 $listeUsersTheBox
                                );

    // Suppression des doublons
    $listeUsersDes = array_unique($listeUsersDes);

    // Tri par ordre alphabétique
    sort($listeUsersDes);

    // Filtrage avec les utilisateurs inscrits
    foreach ($listeUsersDes as $keyUserDes => $userDes)
    {
      foreach ($listeUsersIns as $userIns)
      {
        if ($userDes == $userIns->getIdentifiant())
        {
          unset($listeUsersDes[$keyUserDes]);
          break;
        }
      }
    }

    return $listeUsersDes;
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
  // RETOUR : Tableau des statistiques
  function getStatistiquesInscrits($listeUsers)
  {
    // Initialisations
    $tableauStatistiques = array();

    // Récupération des statistiques par catégories
    foreach ($listeUsers as $user)
    {
      // Films ajoutés
      $nombreFilms = physiqueFilmsAjoutesUser($user->getIdentifiant());

      // Commentaires films
      $nombreComments = physiqueCommentairesFilmsUser($user->getIdentifiant());

      // Phrases cultes ajoutées
      $nombreCollector = physiqueCollectorAjoutesUser($user->getIdentifiant());

      // Bilan des dépenses
      $bilanUser = physiqueBilanDepensesUser($user->getIdentifiant());

      // Nombre de demandes (bugs/évolutions)
      $nombreBugsSoumis = physiqueBugsSoumisUser($user->getIdentifiant());

      // Nombre de demandes résolues (bugs/évolutions)
      $nombreBugsResolus = physiqueBugsResolusUser($user->getIdentifiant());

      // Nombre d'idées publiées
      $nombreTheBox = physiqueTheBoxUser($user->getIdentifiant());

      // Nombre d'idées en charge
      $nombreTheBoxEnCharge = physiqueTheBoxEnChargeUser($user->getIdentifiant());

      // Nombre d'idées terminées ou rejetées
      $nombreTheBoxTerminees = physiqueTheBoxTermineesUser($user->getIdentifiant());

      // Génération d'un objet StatistiquesAdmin
      $statistiquesUser = new StatistiquesAdmin();

      $statistiquesUser->setIdentifiant($user->getIdentifiant());
      $statistiquesUser->setPseudo($user->getPseudo());
      $statistiquesUser->setNb_films_ajoutes($nombreFilms);
      $statistiquesUser->setNb_films_comments($nombreComments);
      $statistiquesUser->setNb_collectors($nombreCollector);
      $statistiquesUser->setExpenses($bilanUser);
      $statistiquesUser->setNb_bugs_soumis($nombreBugsSoumis);
      $statistiquesUser->setNb_bugs_resolus($nombreBugsResolus);
      $statistiquesUser->setNb_idees_soumises($nombreTheBox);
      $statistiquesUser->setNb_idees_en_charge($nombreTheBoxEnCharge);
      $statistiquesUser->setNb_idees_terminees($nombreTheBoxTerminees);

      // Ajout au tableau
      array_push($tableauStatistiques, $statistiquesUser);
    }

    // Retour
    return $tableauStatistiques;
  }

  // METIER : Lecture statistiques catégories des utilisateurs désinscrits
  // RETOUR : Tableau de nombres de commentaires & bilans des dépenses
  function getStatistiquesDesinscrits($listeUsersDes)
  {
    // Initialisations
    $tableauStatistiquesDes = array();

    // Récupération des statistiques par catégories
    foreach ($listeUsersDes as $userDes)
    {
      // Films ajoutés
      $nombreFilms = physiqueFilmsAjoutesUser($userDes);

      // Commentaires films
      $nombreComments = physiqueCommentairesFilmsUser($userDes);

      // Phrases cultes ajoutées
      $nombreCollector = physiqueCollectorAjoutesUser($userDes);

      // Calcul du bilan des dépenses (non stocké)
      $bilanUser = 0;

      $listeExpenses = physiqueDepenses();

      foreach ($listeExpenses as $expense)
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

      // Génération d'un objet StatistiquesAdmin
      $statistiquesUser = new StatistiquesAdmin();

      $statistiquesUser->setIdentifiant($userDes);
      $statistiquesUser->setPseudo('');
      $statistiquesUser->setNb_films_ajoutes($nombreFilms);
      $statistiquesUser->setNb_films_comments($nombreComments);
      $statistiquesUser->setNb_collectors($nombreCollector);
      $statistiquesUser->setExpenses($bilanUser);
      $statistiquesUser->setNb_bugs_soumis($nombreBugsSoumis);
      $statistiquesUser->setNb_bugs_resolus($nombreBugsResolus);
      $statistiquesUser->setNb_idees_soumises($nombreTheBox);
      $statistiquesUser->setNb_idees_en_charge($nombreTheBoxEnCharge);
      $statistiquesUser->setNb_idees_terminees($nombreTheBoxTerminees);

      // Ajout au tableau
      array_push($tableauStatistiquesDes, $statistiquesUser);
    }

    // Retour
    return $tableauStatistiquesDes;
  }

  // METIER : Lecture total catégories des utilisateurs
  // RETOUR : Tableau des totaux des catégories
  function getTotalStatistiques($tableauIns, $tableauDes)
  {
    // Initialisations
    $sommeBilans = 0;

    // Nombre de films ajoutés
    $nombreFilms = physiqueFilmsAjoutesTotal();

    // Nombre de commentaires
    $nombreComments = physiqueCommentairesFilmsTotal();

    // Nombre de phrase cultes
    $nombreCollector = physiqueCollectorTotal();

    // Calcul somme bilans utilisateurs inscrits
    foreach ($tableauIns as $userIns)
    {
      $sommeBilans += $userIns->getExpenses();
    }

    // Calcul somme bilans utilisateurs désinscrits
    foreach ($tableauDes as $userDes)
    {
      $sommeBilans += $userDes->getExpenses();
    }

    // Récupération des dépenses sans parts
		$expensesNoParts = 0;

    $listeExpenses = physiqueDepenses();

    foreach ($listeExpenses as $expense)
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

    // Génération d'un objet TotalStatistiquesAdmin
    $totalStatistiques = new TotalStatistiquesAdmin();

    $totalStatistiques->setNb_films_ajoutes_total($nombreFilms);
    $totalStatistiques->setNb_films_comments_total($nombreComments);
    $totalStatistiques->setNb_collectors_total($nombreCollector);
    $totalStatistiques->setExpenses_total($sommeBilans);
    $totalStatistiques->setAlerte_expenses($alerteBilan);
    $totalStatistiques->setNb_bugs_soumis_total($nombreBugsSoumis);
    $totalStatistiques->setNb_bugs_resolus_total($nombreBugsResolus);
    $totalStatistiques->setNb_idees_soumises_total($nombreTheBox);
    $totalStatistiques->setNb_idees_en_charge_total($nombreTheBoxEnCharge);
    $totalStatistiques->setNb_idees_terminees_total($nombreTheBoxTerminees);

    // Retour
    return $totalStatistiques;
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
