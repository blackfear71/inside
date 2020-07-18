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

          if ($_GET['view'] == 'ranking')
            $experienceUsers = getExperienceUsers($listeUsers);
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
      updateStatus($_SESSION['user']['identifiant'], 'N');
      break;

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
          foreach ($listeSuccess as $success)
          {
            Success::secureData($success);
          }

          if ($_GET['view'] == 'ranking')
          {
            foreach ($experienceUsers as &$expUser)
            {
              $expUser['identifiant'] = htmlspecialchars($expUser['identifiant']);
              $expUser['pseudo']      = htmlspecialchars($expUser['pseudo']);
              $expUser['avatar']      = htmlspecialchars($expUser['avatar']);
              $expUser['experience']  = htmlspecialchars($expUser['experience']);
              $expUser['niveau']      = htmlspecialchars($expUser['niveau']);
            }

            unset($expUser);
          }

          $listeSuccessJson = json_encode(convertForJson($listeSuccess));
          break;

        case 'settings':
          $profil->setIdentifiant(htmlspecialchars($profil->getIdentifiant()));
          $profil->setPing(htmlspecialchars($profil->getPing()));
          $profil->setStatus(htmlspecialchars($profil->getStatus()));
          $profil->setPseudo(htmlspecialchars($profil->getPseudo()));
          $profil->setAvatar(htmlspecialchars($profil->getAvatar()));
          $profil->setEmail(htmlspecialchars($profil->getEmail()));
          $profil->setAnniversary(htmlspecialchars($profil->getAnniversary()));
          $profil->setExperience(htmlspecialchars($profil->getExperience()));
          $profil->setLevel(htmlspecialchars($profil->getLevel()));
          $profil->setExpenses(htmlspecialchars($profil->getExpenses()));

          $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
          $preferences->setInit_chat(htmlspecialchars($preferences->getInit_chat()));
          $preferences->setCelsius(htmlspecialchars($preferences->getCelsius()));
          $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
          $preferences->setCategories_movie_house(htmlspecialchars($preferences->getCategories_movie_house()));
          $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
          $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
          $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));
          break;

        case 'themes':
          $profil->setIdentifiant(htmlspecialchars($profil->getIdentifiant()));
          $profil->setPing(htmlspecialchars($profil->getPing()));
          $profil->setStatus(htmlspecialchars($profil->getStatus()));
          $profil->setPseudo(htmlspecialchars($profil->getPseudo()));
          $profil->setAvatar(htmlspecialchars($profil->getAvatar()));
          $profil->setEmail(htmlspecialchars($profil->getEmail()));
          $profil->setAnniversary(htmlspecialchars($profil->getAnniversary()));
          $profil->setExperience(htmlspecialchars($profil->getExperience()));
          $profil->setLevel(htmlspecialchars($profil->getLevel()));
          $profil->setExpenses(htmlspecialchars($profil->getExpenses()));

          $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
          $preferences->setInit_chat(htmlspecialchars($preferences->getInit_chat()));
          $preferences->setCelsius(htmlspecialchars($preferences->getCelsius()));
          $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
          $preferences->setCategories_movie_house(htmlspecialchars($preferences->getCategories_movie_house()));
          $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
          $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
          $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));

          if (!empty($themesUsers))
          {
            foreach ($themesUsers as &$themeUsers)
            {
              $themeUsers->setReference(htmlspecialchars($themeUsers->getReference()));
              $themeUsers->setName(htmlspecialchars($themeUsers->getName()));
              $themeUsers->setType(htmlspecialchars($themeUsers->getType()));
              $themeUsers->setLevel(htmlspecialchars($themeUsers->getLevel()));
              $themeUsers->setLogo(htmlspecialchars($themeUsers->getLogo()));
              $themeUsers->setDate_deb(htmlspecialchars($themeUsers->getDate_deb()));
              $themeUsers->setDate_fin(htmlspecialchars($themeUsers->getDate_fin()));
            }

            unset($themeUsers);
          }

          if (!empty($themesMissions))
          {
            foreach ($themesMissions as &$themeMission)
            {
              $themeMission->setReference(htmlspecialchars($themeMission->getReference()));
              $themeMission->setName(htmlspecialchars($themeMission->getName()));
              $themeMission->setType(htmlspecialchars($themeMission->getType()));
              $themeMission->setLevel(htmlspecialchars($themeMission->getLevel()));
              $themeMission->setLogo(htmlspecialchars($themeMission->getLogo()));
              $themeMission->setDate_deb(htmlspecialchars($themeMission->getDate_deb()));
              $themeMission->setDate_fin(htmlspecialchars($themeMission->getDate_fin()));
            }

            unset($themeMission);
          }
          break;

        case 'profile':
        default:
          $profil->setIdentifiant(htmlspecialchars($profil->getIdentifiant()));
          $profil->setPing(htmlspecialchars($profil->getPing()));
          $profil->setStatus(htmlspecialchars($profil->getStatus()));
          $profil->setPseudo(htmlspecialchars($profil->getPseudo()));
          $profil->setAvatar(htmlspecialchars($profil->getAvatar()));
          $profil->setEmail(htmlspecialchars($profil->getEmail()));
          $profil->setAnniversary(htmlspecialchars($profil->getAnniversary()));
          $profil->setExperience(htmlspecialchars($profil->getExperience()));
          $profil->setLevel(htmlspecialchars($profil->getLevel()));
          $profil->setExpenses(htmlspecialchars($profil->getExpenses()));

          $statistiques->setNb_films_ajoutes(htmlspecialchars($statistiques->getNb_films_ajoutes()));
          $statistiques->setNb_comments(htmlspecialchars($statistiques->getNb_comments()));
          $statistiques->setNb_reservations(htmlspecialchars($statistiques->getNb_reservations()));
          $statistiques->setNb_gateaux(htmlspecialchars($statistiques->getNb_gateaux()));
          $statistiques->setNb_recettes(htmlspecialchars($statistiques->getNb_recettes()));
          $statistiques->setExpenses(htmlspecialchars($statistiques->getExpenses()));
          $statistiques->setNb_collectors(htmlspecialchars($statistiques->getNb_collectors()));
          $statistiques->setNb_ideas(htmlspecialchars($statistiques->getNb_ideas()));
          $statistiques->setNb_bugs(htmlspecialchars($statistiques->getNb_bugs()));
          $statistiques->setNb_evolutions(htmlspecialchars($statistiques->getNb_evolutions()));

          $progression['niveau']   = htmlspecialchars($progression['niveau']);
          $progression['exp_min']  = htmlspecialchars($progression['exp_min']);
          $progression['exp_max']  = htmlspecialchars($progression['exp_max']);
          $progression['exp_lvl']  = htmlspecialchars($progression['exp_lvl']);
          $progression['progress'] = htmlspecialchars($progression['progress']);
          $progression['percent']  = htmlspecialchars($progression['percent']);
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
      include_once('vue/vue_profil.php');
      break;
  }
?>
