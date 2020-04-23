<?php
  /******************************
  **** Profil administrateur ****
  *******************************
  Fonctionnalités :
  - Modification des informations
  - Modification du mot de passe
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_profil.php');
  include_once('modele/controles_profil.php');
  include_once('modele/physique_profil.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des données du profil
      $profil = getProfile($_SESSION['user']['identifiant']);
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
      // Mise à jour des informations
      updateInfos($_SESSION['user']['identifiant'], $_POST);
      break;

    case 'doUpdatePassword':
      // Mise à jour du mot de passe
      updatePassword($_SESSION['user']['identifiant'], $_POST);
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
      $profil->setLevel(htmlspecialchars($profil->getLevel()));
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
