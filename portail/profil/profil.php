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
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_profil.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      switch ($_GET['view'])
      {
        case 'success':
        case 'ranking':
          $listeUsers   = getUsers();
          $listeSuccess = getSuccess($_SESSION['user']['identifiant'], $listeUsers);
          break;

        case 'settings':
          $profil      = getProfile($_SESSION['user']['identifiant']);
          $preferences = getPreferences($_SESSION['user']['identifiant']);
          break;

        case 'themes':
          $profil         = getProfile($_SESSION['user']['identifiant']);
          $preferences    = getPreferences($_SESSION['user']['identifiant']);
          $themesUsers    = getThemes('U', $profil->getExperience());
          $themesMissions = getThemes('M', NULL);
          $isThemeMission = getThemeMission();
          break;

        case 'profile':
          $profil       = getProfile($_SESSION['user']['identifiant']);
          $statistiques = getStatistiques($_SESSION['user']['identifiant']);
          $progression  = getProgress($profil->getExperience());
          break;

        default:
          header('location: profil.php?view=profile&action=goConsulter');
          break;
      }
      break;

    case 'doModifierAvatar':
      // Mise à jour des données par le modèle & enregistrement fichier
      updateAvatar($_SESSION['user']['identifiant'], $_FILES);
      break;

    case 'doSupprimerAvatar':
      // Suppression des données par le modèle & suppression fichier
      deleteAvatar($_SESSION['user']['identifiant']);
      break;

    case 'doUpdateInfos':
      updateInfos($_SESSION['user']['identifiant'], $_POST);
      break;

    case 'doUpdatePreferences':
      updatePreferences($_SESSION['user']['identifiant'], $_POST);
      break;

    case 'doUpdatePassword':
      updatePassword($_SESSION['user']['identifiant'], $_POST);
      break;

    case 'askDesinscription':
      updateStatus($_SESSION['user']['identifiant'], 'D');
      break;

    case 'cancelDesinscription':
    case 'cancelResetPassword':
      updateStatus($_SESSION['user']['identifiant'], 'N');
      break;

    case 'doSupprimerTheme':
      deleteTheme($_SESSION['user']['identifiant']);
      break;

    case 'doModifierTheme':
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
          $listeSuccessJson = json_encode(convertForJson($listeSuccess));
          break;

        case 'settings':
          Profile::secureData($profil);
          Preferences::secureData($preferences);
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
          StatistiquesProfil::secureData($statistiques);
          Progression::secureData($progression);
          break;
      }
      break;

    case 'doModifierAvatar':
    case 'doSupprimerAvatar':
    case 'doUpdateInfos':
    case 'doUpdatePreferences':
    case 'doUpdatePassword':
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
    case 'doUpdatePreferences':
    case 'doUpdatePassword':
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
