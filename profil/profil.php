<?php
  // Contrôles communs Utilisateurs
  include_once('../includes/controls_users.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_profil.php');

  // Contrôle user renseignée URL
  if ($_GET['user'] != $_SESSION['identifiant'])
    header('location: profil.php?user=' . $_SESSION['identifiant'] . '&action=goConsulter');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $profil       = getProfile($_GET['user']);
      $preferences  = getPreferences($_GET['user']);
      $statistiques = getStatistiques($_GET['user']);
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
      modifyPreferences($_GET['user'], $_POST);
      break;

    case 'doChangeMdp':
      changeMdp($_GET['user'], $_POST);
      break;

    case 'askDesinscription':
      askUnsubscribe($_GET['user']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: profil.php?user=' . $_SESSION['identifiant'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      $profil->setIdentifiant(htmlspecialchars($profil->getIdentifiant()));
      $profil->setReset(htmlspecialchars($profil->getReset()));
      $profil->setFull_name(htmlspecialchars($profil->getFull_name()));
      $profil->setAvatar(htmlspecialchars($profil->getAvatar()));

      $statistiques->setNb_comments(htmlspecialchars($statistiques->getNb_comments()));
      $statistiques->setExpenses(htmlspecialchars($statistiques->getExpenses()));
      $statistiques->setNb_ideas(htmlspecialchars($statistiques->getNb_ideas()));

      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
      $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      break;

    case 'doChangePseudo':
    case 'doChangeAvatar':
    case 'doSupprimerAvatar':
    case 'doModifierPreferences':
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
    case 'doChangeMdp':
    case 'askDesinscription':
      header('location: profil.php?user=' . $_GET['user'] . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_profil.php');
      break;
  }
?>
