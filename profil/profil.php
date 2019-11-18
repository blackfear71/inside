<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_dates.php');
  include_once('../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
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
          $listeUsers      = getUsers();
          $listeSuccess    = getSuccess($_SESSION['user']['identifiant']);
          $classementUsers = getRankUsers($listeSuccess, $listeUsers);

          if ($_GET['view'] == 'ranking')
            $experienceUsers = getExperienceUsers($listeUsers);
          break;

        case 'settings':
          $profil       = getProfile($_SESSION['user']['identifiant']);
          $preferences  = getPreferences($_SESSION['user']['identifiant']);
          break;

        case 'themes':
          $profil          = getProfile($_SESSION['user']['identifiant']);
          $preferences     = getPreferences($_SESSION['user']['identifiant']);
          $themes_users    = getThemes("U", $profil->getExperience());
          $themes_missions = getThemes("M", NULL);
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
      updateStatus($_SESSION['user']['identifiant'], "D");
      break;

    case 'cancelDesinscription':
      updateStatus($_SESSION['user']['identifiant'], "N");
      break;

    case 'cancelResetPassword':
      updateStatus($_SESSION['user']['identifiant'], "N");
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
          foreach ($listeSuccess as &$success)
          {
            $success->setReference(htmlspecialchars($success->getReference()));
            $success->setLevel(htmlspecialchars($success->getLevel()));
            $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
            $success->setDefined(htmlspecialchars($success->getDefined()));
            $success->setTitle(htmlspecialchars($success->getTitle()));
            $success->setDescription(htmlspecialchars($success->getDescription()));
            $success->setLimit_success(htmlspecialchars($success->getLimit_success()));
            $success->setExplanation(htmlspecialchars($success->getExplanation()));
            $success->setValue_user(htmlspecialchars($success->getValue_user()));
          }

          unset($success);

          foreach ($classementUsers as &$classement)
          {
            foreach ($classement['podium'] as &$podium)
            {
              $podium['identifiant'] = htmlspecialchars($podium['identifiant']);
              $podium['pseudo']      = htmlspecialchars($podium['pseudo']);
              $podium['avatar']      = htmlspecialchars($podium['avatar']);
              $podium['value']       = htmlspecialchars($podium['value']);
            }

            unset($podium);
          }

          unset($classement);

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
          $profil->setExpenses(htmlspecialchars($profil->getExpenses()));

          $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
          $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
          $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
          $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
          $preferences->setView_old_movies(htmlspecialchars($preferences->getView_old_movies()));
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
          $profil->setExpenses(htmlspecialchars($profil->getExpenses()));

          $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
          $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
          $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
          $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
          $preferences->setView_old_movies(htmlspecialchars($preferences->getView_old_movies()));
          $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
          $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
          $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));

          if (!empty($themes_users))
          {
            foreach ($themes_users as &$theme_users)
            {
              $theme_users->setReference(htmlspecialchars($theme_users->getReference()));
              $theme_users->setName(htmlspecialchars($theme_users->getName()));
              $theme_users->setType(htmlspecialchars($theme_users->getType()));
              $theme_users->setLevel(htmlspecialchars($theme_users->getLevel()));
              $theme_users->setLogo(htmlspecialchars($theme_users->getLogo()));
              $theme_users->setDate_deb(htmlspecialchars($theme_users->getDate_deb()));
              $theme_users->setDate_fin(htmlspecialchars($theme_users->getDate_fin()));
            }

            unset($theme_users);
          }

          if (!empty($themes_missions))
          {
            foreach ($themes_missions as &$theme_mission)
            {
              $theme_mission->setReference(htmlspecialchars($theme_mission->getReference()));
              $theme_mission->setName(htmlspecialchars($theme_mission->getName()));
              $theme_mission->setType(htmlspecialchars($theme_mission->getType()));
              $theme_mission->setLevel(htmlspecialchars($theme_mission->getLevel()));
              $theme_mission->setLogo(htmlspecialchars($theme_mission->getLogo()));
              $theme_mission->setDate_deb(htmlspecialchars($theme_mission->getDate_deb()));
              $theme_mission->setDate_fin(htmlspecialchars($theme_mission->getDate_fin()));
            }

            unset($theme_mission);
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
