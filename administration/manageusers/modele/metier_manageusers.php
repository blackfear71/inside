<?php
  include_once('../../includes/classes/expenses.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/teams.php');

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
    // Initialisations
    $listeUsersParEquipe              = array();
    $listeUsersParEquipe['new_users'] = array();

    // Récupération liste des utilisateurs
    $listeUsers = physiqueUsers();

    // Ajout à la liste par équipes
    foreach ($listeUsers as $user)
    {
      // Ajout de l'utilisateur à son équipe
      if (!empty($user->getTeam()))
        $team = $user->getTeam();
      else
        $team = 'new_users';

      if (!isset($listeUsersParEquipe[$team]))
        $listeUsersParEquipe[$team] = array();

      array_push($listeUsersParEquipe[$team], $user);
    }

    // Retour
    return $listeUsersParEquipe;
  }

  // METIER : Recherche les utilisateurs désinscrits
  // RETOUR : Liste des utilisateurs désinscrits
  function getUsersDes($listeUsersIns)
  {
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
      foreach ($listeUsersIns as $equipeUsersIns)
      {
        foreach ($equipeUsersIns as $userIns)
        {
          if ($userDes == $userIns->getIdentifiant())
          {
            unset($listeUsersDes[$keyUserDes]);
            break;
          }
        }
      }
    }

    // Retour
    return $listeUsersDes;
  }

  // METIER : Lecture de la liste des équipes
  // RETOUR : Liste des équipes
  function getListeEquipes()
  {
    // Lecture de la liste des équipes
    $listeEquipes = physiqueListeEquipes();

    // Retour
    return $listeEquipes;
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
  function getStatistiquesInscrits($listeUsersParEquipe)
  {
    // Initialisations
    $tableauStatistiques = array();

    // Récupération des statistiques par catégories
    foreach ($listeUsersParEquipe as $listeUsers)
    {
      foreach ($listeUsers as $user)
      {
        // Films ajoutés
        $nombreFilms = physiqueFilmsAjoutesUser($user->getIdentifiant());

        // Commentaires films
        $nombreComments = physiqueCommentairesFilmsUser($user->getIdentifiant());

        // Phrases et images cultes ajoutées
        $nombreCollector = physiqueCollectorAjoutesUser($user->getIdentifiant());

        // Réservations de restaurants
        $nombreReservations = physiqueReservationsUser($user->getIdentifiant());

        // Gâteaux de la semaine
        $nombreGateauxSemaine = physiqueGateauxSemaineUser($user->getIdentifiant());

        // Recettes partagées
        $nombreRecettes = physiqueRecettesUser($user->getIdentifiant());

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
        $statistiquesUser->setNb_reservations($nombreReservations);
        $statistiquesUser->setNb_gateaux_semaine($nombreGateauxSemaine);
        $statistiquesUser->setNb_recettes($nombreRecettes);
        $statistiquesUser->setExpenses($bilanUser);
        $statistiquesUser->setNb_bugs_soumis($nombreBugsSoumis);
        $statistiquesUser->setNb_bugs_resolus($nombreBugsResolus);
        $statistiquesUser->setNb_idees_soumises($nombreTheBox);
        $statistiquesUser->setNb_idees_en_charge($nombreTheBoxEnCharge);
        $statistiquesUser->setNb_idees_terminees($nombreTheBoxTerminees);

        // Ajout au tableau
        array_push($tableauStatistiques, $statistiquesUser);
      }
    }

    // Tri par identifiant
    if (!empty($tableauStatistiques))
    {
      foreach ($tableauStatistiques as $statistiquesIns)
      {
        $triStatistiquesIns[] = $statistiquesIns->getIdentifiant();
      }

      // Tri
      array_multisort($triStatistiquesIns, SORT_ASC, $tableauStatistiques);
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

      // Réservations de restaurants
      $nombreReservations = physiqueReservationsUser($userDes);

      // Gâteaux de la semaine
      $nombreGateauxSemaine = physiqueGateauxSemaineUser($userDes);

      // Recettes partagées
      $nombreRecettes = physiqueRecettesUser($userDes);

      // Calcul du bilan des dépenses (non stocké)
      $bilanUser = 0;

      $listeExpenses = physiqueDepenses();

      foreach ($listeExpenses as $expense)
      {
        // Nombre de parts total et de l'utilisateur
        $nombreParts = physiquePartsDepensesUser($expense->getId(), $userDes);

        if ($expense->getType() == 'M')
        {
          // Montant de la part
          $montantUserDes = $nombreParts['utilisateur'];

          // Calcul de la répartition des frais
          if (!empty($expense->getPrice()) AND $montantUserDes != 0)
            $fraisUserDes = $expense->getPrice() / $nombreParts['nombreUtilisateurs'];
          else
            $fraisUserDes = 0;

          // Calcul du bilan de l'utilisateur (s'il participe ou qu'il est l'acheteur)
          if ($expense->getBuyer() == $userDes OR $montantUserDes != 0)
          {
            if ($expense->getBuyer() == $userDes)
              $bilanUser += $expense->getPrice() + $nombreParts['total'] - ($montantUserDes + $fraisUserDes);
            else
              $bilanUser -= $montantUserDes + $fraisUserDes;
          }
        }
        else
        {
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
      $statistiquesUser->setNb_reservations($nombreReservations);
      $statistiquesUser->setNb_gateaux_semaine($nombreGateauxSemaine);
      $statistiquesUser->setNb_recettes($nombreRecettes);
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

    // Nombre de réservations de restaurants
    $nombreReservations = physiqueReservationsTotal();

    // Nombre de gâteaux de la semaine
    $nombreGateauxSemaine = physiqueGateauxSemaineTotal();

    // Nombre de recettes partagées
    $nombreRecettes = physiqueRecettesTotal();

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
    $totalStatistiques->setNb_reservations_total($nombreReservations);
    $totalStatistiques->setNb_gateaux_semaine_total($nombreGateauxSemaine);
    $totalStatistiques->setNb_recettes_total($nombreRecettes);
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

  // METIER : Réinitialisation mot de passe
  // RETOUR : Aucun
  function setNewPassword($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $status      = 'U';

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

  // METIER : Génère une chaîne aléatoire
  // RETOUR : Chaîne aléatoire
  function generateRandomString($nombreCarateres)
  {
    // Génération d'une chaîne de caractères aléatoires
    $chaine     = '';
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    srand((double)microtime() * 1000000);

    for ($i = 0; $i < $nombreCarateres; $i++)
    {
      $chaine .= $caracteres[rand() % strlen($caracteres)];
    }

    // Retour
    return $chaine;
  }

  // METIER : Refus réinitialisation mot de passe
  // RETOUR : Aucun
  function resetOldPassword($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $status      = 'U';

    // Remise à "U" de l'indicateur de demande
    physiqueUpdateStatusUser($identifiant, $status);
  }

  // METIER : Validation changement d'équipe (mise à jour de l'équipe et du status utilisateur)
  // RETOUR : Aucun
  function acceptEquipe($post, $isUpdateEquipe)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $oldTeam     = $post['team_user'];
    $newTeam     = '';
    $status      = 'U';

    if ($post['team'] == 'other')
    {
      $teamReference      = $post['team_reference'];
      $nameReference      = $post['team_name'];
      $shortNameReference = $post['team_short_name'];
    }
    else
      $teamReference = $post['team'];

    if (isset($post['team_temp_reference']) AND !empty($post['team_temp_reference']))
      $tempTeam = $post['team_temp_reference'];

    // Création ou mise à jour d'une équipe si besoin
    if ($post['team'] == 'other')
    {
      $team = array('reference'  => $teamReference,
                    'team'       => $nameReference,
                    'short'      => $shortNameReference,
                    'activation' => 'Y'
                   );

      if (isset($tempTeam) AND !empty($tempTeam))
      {
        // Mise à jour de l'équipe temporaire créée par l'utilisateur et activation
        physiqueUpdateEquipe($team, $tempTeam);
      }
      else
      {
        // Création d'une nouvelle équipe si l'utilisateur a choisi une équipe et que l'admin créé une nouvelle équipe
        physiqueInsertionEquipe($team);
      }

      // Création d'un fichier XML pour le chat lors de la création d'une nouvelle équipe
      $folder = '../../includes/common/chat/conversations';

      if (!is_dir($folder))
        mkdir($folder, 0777, true);

      // Création du fichier s'il n'existe pas
      if (!file_exists($folder . '/content_chat_' . $teamReference . '.xml'))
      {
        $file    = fopen($folder . '/content_chat_' . $teamReference . '.xml', 'a+');
        $balises =
'<?xml version="1.0" encoding="UTF-8"?>
<INSIDERoom>
</INSIDERoom>';

        fputs($file, $balises);
        fclose($file);
      }
    }
    else
    {
      // Si il ne faut finalement pas créer d'équipe, on supprime l'équipe temporaire
      if (isset($tempTeam) AND !empty($tempTeam))
        physiqueDeleteEquipe($tempTeam);
    }

    // Réinitialisations (seulement lors d'un changement d'équipe)
    if ($isUpdateEquipe == true)
    {
      // Suppression des semaines de gâteaux si non réalisés
      physiqueDeleteRecette($identifiant, $oldTeam);

      // Remise en cours des idées non terminées ou rejetées
      physiqueUpdateStatusTheBox($identifiant);

      // Récupération des missions en cours
      $idMissionsEnCours = physiqueMissionsEnCours();

      // Mise à jour des missions en cours
      if (!empty($idMissionsEnCours))
      {
        foreach ($idMissionsEnCours as $idMission)
        {
          physiqueUpdateMissionsEnCours($idMission, $identifiant, $teamReference);
        }
      }

      // Suppression des étoiles des films
      physiqueDeleteStarsFilmsUser($identifiant);

      // Suppression du succès "viewer" associé
      physiqueDeleteSuccess($identifiant, 'viewer');
    }

    // Mise à jour de la référence de l'équipe et du statut à "U"
    $user = array('team'     => $teamReference,
                  'new_team' => $newTeam,
                  'status'   => $status
                 );

    physiqueUpdateProfilUser($user, $identifiant);
  }

  // METIER : Refus changement d'équipe
  // RETOUR : Aucun
  function declineEquipe($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $oldTeam     = $post['team_user'];
    $newTeam     = $post['new_team_user'];
    $resetTeam   = '';
    $status      = 'U';

    // Suppression de l'équipe éventuellement créée
    physiqueDeleteEquipe($newTeam);

    // Mise à jour de la référence de l'équipe et du statut à "U"
    $user = array('team'     => $oldTeam,
                  'new_team' => $resetTeam,
                  'status'   => $status
                 );

    physiqueUpdateProfilUser($user, $identifiant);
  }

  // METIER : Validation inscription (mise à jour de l'équipe et du status utilisateur)
  // RETOUR : Aucun
  function acceptInscription($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $equipe      = $post['team_user'];

    // Validation de l'équipe (création, modification ou suppression)
    acceptEquipe($post, false);

    // Insertion notification
    insertNotification('inscrit', $equipe, $identifiant, 'admin');
  }

  // METIER : Refus inscription
  // RETOUR : Aucun
  function declineInscription($post)
  {
    // Récupération des données
    $identifiant = $post['id_user'];
    $equipe      = $post['team_user'];

    // Suppression des préférences
    physiqueDeletePreferences($identifiant);

    // Suppression de l'équipe éventuellement créée
    physiqueDeleteEquipe($equipe);

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
    $equipe      = $post['team_user'];

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
      deleteNotification('inscrit', $equipe, $user->getIdentifiant());

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
    $status      = 'U';

    // Mise à jour à "U" du statut
    physiqueUpdateStatusUser($identifiant, $status);
  }
?>
