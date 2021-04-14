<?php
  /*******************************
  *** Gestion des utilisateurs ***
  ********************************
  Fonctionnalités :
  - Réinitialisation mot de passe
  - Inscriptions
  - Désinscriptions
  - Consultation des statistiques
  *******************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_manageusers.php');
  include_once('modele/controles_manageusers.php');
  include_once('modele/physique_manageusers.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Récupération des utilisateurs inscrits et désinscrits
			$listeUsers    = getUsers();
      $listeUsersDes = getUsersDes($listeUsers);

      // Récupération alerte gestion des utilisateurs
			$alerteUsers = getAlerteUsers();

      // Récupération des statistiques par catégories et des statistiques de demandes et publications
      $tableauStatistiquesIns = getStatistiquesInscrits($listeUsers);
			$tableauStatistiquesDes = getStatistiquesDesinscrits($listeUsersDes);

      // Récupération des totaux
      $totalStatistiques = getTotalStatistiques($tableauStatistiquesIns, $tableauStatistiquesDes);
      break;

    case 'doAnnulerMdp':
      // Annulation de la réinitialisation du mot de passe
      resetOldPassword($_POST);
      break;

    case 'doChangerMdp':
      // Réinitialisation du mot de passe
      setNewPassword($_POST);
      break;

    case 'doAccepterInscription':
      // Validation de l'inscription
      acceptInscription($_POST);
      break;

    case 'doRefuserInscription':
      // Refus de l'inscription
      declineInscription($_POST);
      break;

    case 'doAccepterDesinscription':
      // Validation de la désinscription
      acceptDesinscription($_POST);
      break;

    case 'doRefuserDesinscription':
      // Refus de la désinscription
      resetDesinscription($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: manageusers.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':

			foreach ($listeUsers as $user)
			{
        Profile::secureData($user);
			}

      foreach ($listeUsersDes as &$userDes)
      {
        $userDes = htmlspecialchars($userDes);
      }

      unset($userDes);

			foreach ($tableauStatistiquesIns as $statistiquesIns)
			{
        StatistiquesAdmin::secureData($statistiquesIns);
			}

      foreach ($tableauStatistiquesDes as $statistiquesDes)
			{
        StatistiquesAdmin::secureData($statistiquesDes);
			}

      TotalStatistiquesAdmin::secureData($totalStatistiques);
      break;

    case 'doAnnulerMdp':
    case 'doChangerMdp':
    case 'doAccepterInscription':
    case 'doRefuserInscription':
    case 'doAccepterDesinscription':
    case 'doRefuserDesinscription':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAnnulerMdp':
    case 'doChangerMdp':
    case 'doAccepterInscription':
    case 'doRefuserInscription':
    case 'doAccepterDesinscription':
    case 'doRefuserDesinscription':
      header('location: manageusers.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manageusers.php');
      break;
  }
?>
