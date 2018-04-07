<?php
  // Fonction communes
  include_once('../includes/fonctions_communes.php');
  include_once('../includes/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $profil = getProfile('admin');
      break;

    case 'doChangePseudo':
      // Mise à jour des données par le modèle
      changePseudo('admin', $_POST);
      break;

    case 'doChangeAvatar':
      // Mise à jour des données par le modèle & enregistrement fichier
      changeAvatar('admin', $_FILES);
      break;

    case 'doSupprimerAvatar':
      // Suppression des données par le modèle & suppression fichier
      deleteAvatar('admin');
      break;

    case 'doChangeMdp':
      changeMdp($_GET['user'], $_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: profil.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      $profil->setIdentifiant(htmlspecialchars($profil->getIdentifiant()));
      $profil->setPing(htmlspecialchars($profil->getPing()));
      $profil->setReset(htmlspecialchars($profil->getReset()));
      $profil->setPseudo(htmlspecialchars($profil->getPseudo()));
      $profil->setAvatar(htmlspecialchars($profil->getAvatar()));
      $profil->setEmail(htmlspecialchars($profil->getEmail()));
      $profil->setBeginner(htmlspecialchars($profil->getBeginner()));
      $profil->setDevelopper(htmlspecialchars($profil->getDevelopper()));
      $profil->setExpenses(htmlspecialchars($profil->getExpenses()));
      break;

    case 'doChangePseudo':
    case 'doChangeAvatar':
    case 'doSupprimerAvatar':
    case 'doChangeMdp':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doChangePseudo':
    case 'doChangeAvatar':
    case 'doSupprimerAvatar':
    case 'doChangeMdp':
      header('location: profil.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_profil.php');
      break;
  }
?>
