<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_cookingbox.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      $currentWeek = getWeek(date('W'));
      $nextWeek    = getWeek(date('W') + 1);
      $listeUsers  = getUsers();
      break;

    case "doModifier":
      updateCake($_POST);
      break;

    case "doValider":
      validateCake("Y");
      break;

    case "doAnnuler":
      validateCake("N");
      break;

    default:
      // Contrôle action renseignée URL
      header('location: cookingbox.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case "goConsulter":
      $currentWeek->setIdentifiant(htmlspecialchars($currentWeek->getIdentifiant()));
      $currentWeek->setPseudo(htmlspecialchars($currentWeek->getPseudo()));
      $currentWeek->setAvatar(htmlspecialchars($currentWeek->getAvatar()));
      $currentWeek->setWeek(htmlspecialchars($currentWeek->getWeek()));
      $currentWeek->setCooked(htmlspecialchars($currentWeek->getCooked()));

      $nextWeek->setIdentifiant(htmlspecialchars($nextWeek->getIdentifiant()));
      $nextWeek->setPseudo(htmlspecialchars($nextWeek->getPseudo()));
      $nextWeek->setAvatar(htmlspecialchars($nextWeek->getAvatar()));
      $nextWeek->setWeek(htmlspecialchars($nextWeek->getWeek()));
      $nextWeek->setCooked(htmlspecialchars($nextWeek->getCooked()));

      foreach ($listeUsers as &$user)
      {
        $user = htmlspecialchars($user);
      }

      unset($user);

      // Conversion JSON
      $listeUsersJson = json_encode($listeUsers);
      break;

    case "doModifier":
    case "doValider":
    case "doAnnuler":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doModifier":
    case "doValider":
    case "doAnnuler":
      header('location: cookingbox.php?action=goConsulter');
      break;

    case "goConsulter":
    default:
      include_once('vue/vue_cookingbox.php');
      break;
  }
?>
