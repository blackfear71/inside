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
      // Contrôle user renseignée URL
      if ($_GET['user'] != $_SESSION['user']['identifiant'])
        header('location: profil.php?user=' . $_SESSION['user']['identifiant'] . '&view=profile&action=goConsulter');
      else
      {
        // Lecture des données par le modèle
        switch ($_GET['view'])
        {
          case 'success':
          case 'ranking':
            $listeUsers      = getUsers();
            $listeSuccess    = getSuccess($_SESSION['user']['identifiant']);
            $classementUsers = getRankUsers($listeSuccess, $listeUsers);
            break;

          case 'settings':
            $profil       = getProfile($_GET['user']);
            $preferences  = getPreferences($_GET['user']);
            break;

          case 'profile':
            $profil       = getProfile($_GET['user']);
            $statistiques = getStatistiques($_GET['user']);
            $progression  = getProgress($profil->getExperience());
            break;

          default:
            header('location: profil.php?user=' . $_SESSION['user']['identifiant'] . '&view=profile&action=goConsulter');
            break;
        }
      }
      break;

    case 'doModifierAvatar':
      // Mise à jour des données par le modèle & enregistrement fichier
      updateAvatar($_GET['user'], $_FILES);
      break;

    case 'doSupprimerAvatar':
      // Suppression des données par le modèle & suppression fichier
      deleteAvatar($_GET['user']);
      break;

    case 'doUpdateInfos':
      updateInfos($_GET['user'], $_POST);
      break;

    case 'doUpdatePreferences':
      updatePreferences($_GET['user'], $_POST);
      break;

    case 'doUpdatePassword':
      updatePassword($_GET['user'], $_POST);
      break;

    case 'askDesinscription':
      updateStatus($_GET['user'], "D");
      break;

    case 'cancelDesinscription':
      updateStatus($_GET['user'], "N");
      break;

    case 'cancelResetPassword':
      updateStatus($_GET['user'], "N");
      break;

    default:
      // Contrôle action renseignée URL
      header('location: profil.php?user=' . $_SESSION['user']['identifiant'] . '&view=profile&action=goConsulter');
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
      header('location: profil.php?user=' . $_GET['user'] . '&view=settings&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_profil.php');
      break;
  }
?>
