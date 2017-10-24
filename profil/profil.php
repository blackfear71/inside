<?php
  // Contrôles communs Utilisateurs
  include_once('../includes/controls_users.php');

  // Fonctions communes
	include('../includes/fonctions_regex.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_profil.php');

  // Contrôle user renseignée URL
  if ($_GET['user'] != $_SESSION['identifiant'])
    header('location: profil.php?user=' . $_SESSION['identifiant'] . '&view=settings&action=goConsulter');

  // Contrôle vue renseignée URL
  switch ($_GET['view'])
  {
    case 'settings':
    case 'success':
    case 'ranking':
      break;

    default:
      header('location: profil.php?user=' . $_SESSION['identifiant'] . '&view=settings&action=goConsulter');
      break;
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      switch ($_GET['view'])
      {
        case 'success':
        case 'ranking':
          $listeSuccess    = getSuccess();
          $successUser     = getSuccessUser($listeSuccess, $_SESSION['identifiant']);
          $classementUsers = getRankUsers($listeSuccess);
          break;

        case 'settings':
        default:
          $profil       = getProfile($_GET['user']);
          $preferences  = getPreferences($_GET['user']);
          $statistiques = getStatistiques($_GET['user']);
          break;
      }
      break;

    case 'doChangePseudo':
      // Mise à jour des données par le modèle
      changePseudo($_GET['user'], $_POST);
      break;

    case 'doChangeAvatar':
      // Mise à jour des données par le modèle & enregistrement fichier
      changeAvatar($_GET['user'], $_FILES);
      break;

    case 'doSupprimerAvatar':
      // Suppression des données par le modèle & suppression fichier
      deleteAvatar($_GET['user']);
      break;

    case 'doModifierPreferences':
      updatePreferences($_GET['user'], $_POST);
      break;

    case "doUpdateMail":
      updateMail($_GET['user'], $_POST);
      break;

    case 'doChangeMdp':
      changeMdp($_GET['user'], $_POST);
      break;

    case 'askDesinscription':
      askUnsubscribe($_GET['user']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: profil.php?user=' . $_SESSION['identifiant'] . '&view=settings&action=goConsulter');
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
            $success->setReference(htmlspecialchars($success->getReference()));
            $success->setLevel(htmlspecialchars($success->getLevel()));
            $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
            $success->setTitle(htmlspecialchars($success->getTitle()));
            $success->setDescription(htmlspecialchars($success->getDescription()));
            $success->setLimit_success(htmlspecialchars($success->getLimit_success()));
          }

          foreach ($successUser as $limit)
          {
            $limit = htmlspecialchars($limit);
          }

          foreach ($classementUsers as $classement)
          {
            foreach ($classement['podium'] as $podium)
            {
              $podium['identifiant'] = htmlspecialchars($podium['identifiant']);
              $podium['pseudo']      = htmlspecialchars($podium['pseudo']);
              $podium['value']       = htmlspecialchars($podium['value']);
            }
          }
          break;

        case 'settings':
        default:
          $profil->setIdentifiant(htmlspecialchars($profil->getIdentifiant()));
          $profil->setReset(htmlspecialchars($profil->getReset()));
          $profil->setPseudo(htmlspecialchars($profil->getPseudo()));
          $profil->setAvatar(htmlspecialchars($profil->getAvatar()));
          $profil->setEmail(htmlspecialchars($profil->getEmail()));
          $profil->setBeginner(htmlspecialchars($profil->getBeginner()));
          $profil->setDevelopper(htmlspecialchars($profil->getDevelopper()));

          $statistiques->setNb_films_ajoutes(htmlspecialchars($statistiques->getNb_films_ajoutes()));
          $statistiques->setNb_comments(htmlspecialchars($statistiques->getNb_comments()));
          $statistiques->setExpenses(htmlspecialchars($statistiques->getExpenses()));
          $statistiques->setNb_collectors(htmlspecialchars($statistiques->getNb_collectors()));
          $statistiques->setNb_ideas(htmlspecialchars($statistiques->getNb_ideas()));

          $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
          $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
          $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
          $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
          $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
          $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));
          break;
      }
      break;

    case 'doChangePseudo':
    case 'doChangeAvatar':
    case 'doSupprimerAvatar':
    case 'doModifierPreferences':
    case "doUpdateMail":
    case 'doChangeMdp':
    case 'askDesinscription':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doChangePseudo':
    case 'doChangeAvatar':
    case 'doSupprimerAvatar':
    case 'doModifierPreferences':
    case "doUpdateMail":
    case 'doChangeMdp':
    case 'askDesinscription':
      header('location: profil.php?user=' . $_GET['user'] . '&view=settings&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_profil.php');
      break;
  }
?>
