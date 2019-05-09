<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_regex.php');

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

    case 'doModifierAvatar':
      // Mise à jour des données par le modèle & enregistrement fichier
      updateAvatar('admin', $_FILES);
      break;

    case 'doSupprimerAvatar':
      // Suppression des données par le modèle & suppression fichier
      deleteAvatar('admin');
      break;

    case 'doUpdateInfos':
      updateInfos($_GET['user'], $_POST);
      break;

    case 'doUpdatePassword':
      updatePassword($_GET['user'], $_POST);
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
      $profil->setStatus(htmlspecialchars($profil->getStatus()));
      $profil->setPseudo(htmlspecialchars($profil->getPseudo()));
      $profil->setAvatar(htmlspecialchars($profil->getAvatar()));
      $profil->setEmail(htmlspecialchars($profil->getEmail()));
      $profil->setAnniversary(htmlspecialchars($profil->getAnniversary()));
      $profil->setExperience(htmlspecialchars($profil->getExperience()));
      $profil->setExpenses(htmlspecialchars($profil->getExpenses()));
      break;

    case 'doModifierAvatar':
    case 'doSupprimerAvatar':
    case 'doUpdateInfos':
    case 'doUpdatePassword':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doModifierAvatar':
    case 'doSupprimerAvatar':
    case 'doUpdateInfos':
    case 'doUpdatePassword':
      header('location: profil.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_profil.php');
      break;
  }
?>
