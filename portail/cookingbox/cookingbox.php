<?php
  /**********************************
  *********** Cooking Box ***********
  ***********************************
  Fonctionnalités :
  - Consultation des tours de gâteau
  - Modification des tours de gâteau
  - Consultation des recettes
  - Ajout des recettes
  - Modification des recettes
  - Suppression des recettes
  - Détail des recettes
  **********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_cookingbox.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: cookingbox.php?year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Initialisation de la sauvegarde en session
        initializeSaveSession();

        // Gâteaux semaines n et n + 1
        $currentWeek = getWeek(date('W'), date('Y'));
        $nextWeek    = getWeek(date('W', strtotime('+ 1 week')), date('Y'));
        $listeUsers  = getUsers();

        // Saisie
        $listeSemaines = getWeeks($_SESSION['user']['identifiant']);

        // Recettes
        $anneeExistante = controlYear($_GET['year']);
        $onglets        = getOnglets();
        $recettes       = getRecipes($_GET['year']);
      }
      break;

    case 'doModifier':
      updateCake($_POST);
      break;

    case 'doValider':
      validateCake('Y', $_POST['week_cake'], date('Y'), $_SESSION['user']['identifiant']);
      break;

    case 'doAnnuler':
      validateCake('N', $_POST['week_cake'], date('Y'), $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterRecette':
      $year      = $_POST['year_recipe'];
      $idRecette = insertRecipe($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doModifierRecette':
      $year      = $_POST['hidden_year_recipe'];
      $idRecette = updateRecipe($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimerRecette':
      deleteRecipe($_POST, $_GET['year'], $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: cookingbox.php?year=' . date('Y') . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      WeekCake::secureData($currentWeek);
      WeekCake::secureData($nextWeek);

      foreach ($listeUsers as &$user)
      {
        $user = htmlspecialchars($user);
      }

      unset($user);

      foreach ($listeSemaines as &$year)
      {
        foreach ($year as &$week)
        {
          $week = htmlspecialchars($week);
        }

        unset($week);
      }

      unset($year);

      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

      foreach ($recettes as $recette)
      {
        WeekCake::secureData($recette);
      }

      // Conversion JSON
      $listeSemainesJson = json_encode($listeSemaines);
      $listeUsersJson    = json_encode($listeUsers);
      $recettesJson      = json_encode(convertForJson($recettes));
      break;

    case 'doModifier':
    case 'doValider':
    case 'doAnnuler':
    case 'doAjouterRecette':
    case 'doModifierRecette':
    case 'doSupprimerRecette':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doModifier':
    case 'doValider':
    case 'doAnnuler':
    case 'doSupprimerRecette':
      header('location: cookingbox.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'doAjouterRecette':
    case 'doModifierRecette':
      header('location: cookingbox.php?year=' . $year . '&action=goConsulter&anchor=' . $idRecette);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_cookingbox.php');
      break;
  }
?>
