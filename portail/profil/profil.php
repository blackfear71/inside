<?php
  /*********************************************
  ************* Profil utilisateur *************
  **********************************************
  Fonctionnalités :
  - Consultation des données personnelles
  - Modification des informations et paramètres
  - Consultation des succès et de l'expérience
  - Modification du thème
  *********************************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');
  include_once('../../includes/functions/fonctions_images.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_profil.php');
  include_once('modele/controles_profil.php');
  include_once('modele/physique_profil.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si la vue est renseignée
      if (!isset($_GET['view']) OR empty($_GET['view']))
        header('location: profil.php?view=profile&action=goConsulter');
      else
      {
        // Récupération des données en fonction de la vue
        switch ($_GET['view'])
        {
          case 'success':
          case 'ranking':
            // Récupération de la liste des utilisateurs
            $listeUsers = getUsers();

            // Récupération de la liste des succès des utilisateurs
            $listeSuccess = getSuccess($_SESSION['user']['identifiant'], $listeUsers);
            break;

          case 'settings':
            // Récupération des informations de l'utilisateur
            $profil = getProfile($_SESSION['user']['identifiant']);

            // Récupération des préférences de l'utilisateur
            $preferences = getPreferences($_SESSION['user']['identifiant']);

            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();
            break;

          case 'themes':
            // Récupération des informations de l'utilisateur
            $profil = getProfile($_SESSION['user']['identifiant']);

            // Récupération des préférences de l'utilisateur
            $preferences = getPreferences($_SESSION['user']['identifiant']);

            // Récupération des thèmes utilisateurs
            $themesUsers = getThemes('U', $profil->getExperience());

            // Récupération des thèmes missions
            $themesMissions = getThemes('M', NULL);

            // Récupération thème mission en cours
            $isThemeMission = getThemeMission();
            break;

          case 'profile':
            // Récupération des informations de l'utilisateur
            $profil = getProfile($_SESSION['user']['identifiant']);

            // Récupération de l'équipe
            $equipe = getEquipe($profil->getTeam());

            // Récupération des préférences de l'utilisateur
            $statistiques = getStatistiques($_SESSION['user']['identifiant']);

            // Récupération de la progression de l'utilisateur
            $progression = getProgress($profil->getExperience());
            break;

          default:
            // Contrôle action renseignée URL
            header('location: profil.php?view=profile&action=goConsulter');
            break;
        }
      }
      break;

    case 'doModifierAvatar':
      // Modification de l'avatar
      updateAvatar($_SESSION['user']['identifiant'], $_FILES);
      break;

    case 'doSupprimerAvatar':
      // Suppression de l'avatar
      deleteAvatar($_SESSION['user']['identifiant']);
      break;

    case 'doUpdateInfos':
      // Modification des informations de l'utilisateur
      updateInfos($_SESSION['user']['identifiant'], $_POST, false);
      break;

    case 'doUpdateInfosMobile':
      // Modification des informations de l'utilisateur
      updateInfos($_SESSION['user']['identifiant'], $_POST, true);
      break;

    case 'doUpdatePreferences':
      // Modification des préférences de l'utilisateur
      updatePreferences($_SESSION['user']['identifiant'], $_POST);
      break;

    case 'doUpdatePassword':
      // Modification du mot de passe de l'utilisateur
      updatePassword($_SESSION['user']['identifiant'], $_POST);
      break;

    case 'doUpdateEquipe':
      // Modification de l'équipe
      updateEquipe($_SESSION['user'], $_POST);
      break;

    case 'askDesinscription':
      // Demande de désinscription
      updateStatus($_SESSION['user']['identifiant'], 'D');
      break;

    case 'cancelDesinscription':
    case 'cancelResetPassword':
      // Annulation changement statut
      updateStatus($_SESSION['user']['identifiant'], 'U');
      break;

    case 'doSupprimerTheme':
      // Suppression du thème
      deleteTheme($_SESSION['user']['identifiant']);
      break;

    case 'doModifierTheme':
      // Modification du thème
      updateTheme($_SESSION['user']['identifiant'], $_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: profil.php?view=profile&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      switch ($_GET['view'])
      {
        case 'success':
        case 'ranking':
          foreach ($listeUsers as $user)
          {
            Profile::secureData($user);
          }

          foreach ($listeSuccess as $success)
          {
            Success::secureData($success);
          }

          // Conversion JSON
          $listeSuccessJson = json_encode(convertForJsonListeSucces($listeSuccess));
          break;

        case 'settings':
          Profile::secureData($profil);
          Preferences::secureData($preferences);

          foreach ($listeEquipes as $equipe)
          {
            Team::secureData($equipe);
          }
          break;

        case 'themes':
          Profile::secureData($profil);
          Preferences::secureData($preferences);

          if (!empty($themesUsers))
          {
            foreach ($themesUsers as $themeUsers)
            {
              Theme::secureData($themeUsers);
            }
          }

          if (!empty($themesMissions))
          {
            foreach ($themesMissions as $themeMission)
            {
              Theme::secureData($themeMission);
            }
          }
          break;

        case 'profile':
        default:
          Profile::secureData($profil);
          Team::secureData($equipe);
          StatistiquesProfil::secureData($statistiques);
          Progression::secureData($progression);
          break;
      }
      break;

    case 'doModifierAvatar':
    case 'doSupprimerAvatar':
    case 'doUpdateInfos':
    case 'doUpdateInfosMobile':
    case 'doUpdatePreferences':
    case 'doUpdatePassword':
    case 'doUpdateEquipe':
    case 'askDesinscription':
    case 'cancelDesinscription':
    case 'cancelResetPassword':
    case 'doSupprimerTheme':
    case 'doModifierTheme':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doModifierAvatar':
    case 'doSupprimerAvatar':
    case 'doUpdateInfos':
    case 'doUpdateInfosMobile':
    case 'doUpdatePreferences':
    case 'doUpdatePassword':
    case 'doUpdateEquipe':
    case 'askDesinscription':
    case 'cancelDesinscription':
    case 'cancelResetPassword':
      header('location: profil.php?view=settings&action=goConsulter');
      break;

    case 'doSupprimerTheme':
    case 'doModifierTheme':
      header('location: profil.php?view=themes&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_profil.php');
      break;
  }
?>
