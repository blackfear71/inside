<?php
  /*********************************
  ********* Expense Center *********
  **********************************
  Fonctionnalités :
  - Consultation soldes utilisateurs
  - Ajout de dépenses
  - Consultation des dépense
  - Modification des dépenses
  - Suppression des dépenses
  *********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_expensecenter.php');
  include_once('modele/controles_expensecenter.php');
  include_once('modele/physique_expensecenter.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: expensecenter.php?year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Initialisation de la sauvegarde en session
        initializeSaveSession();

        // Vérification année existante
        $anneeExistante = controlYear($_GET['year']);

        // Récupération de la liste des utilisateurs
        $listeUsers = getUsers();

        // Récupération des onglets (années)
        $onglets = getOnglets();

        // Récupération de la liste des dépenses
        $listeDepenses = getExpenses($_GET['year']);
      }
      break;

    case 'doInserer':
      // Insertion d'une dépense
      $idExpense = insertExpense($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doInsererMontants':
      // Insertion d'une dépense en montants
      $idExpense = insertMontants($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doModifier':
      // Modification d'une dépense
      $idExpense = updateExpense($_POST);
      break;

    case 'doModifierMontants':
      // Modification d'une dépense en montants
      $idExpense = updateMontants($_POST);
      break;

    case 'doSupprimer':
      // Suppression d'une dépense
      deleteExpense($_POST);
      break;

    case 'doSupprimerMontants':
      // Suppression d'une dépense en montants
      deleteMontants($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: expensecenter.php?year=' . date('Y') . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeUsers as $user)
      {
        Profile::secureData($user);
      }

      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

      foreach ($listeDepenses as $depense)
      {
        Expenses::secureData($depense);
      }

      // Conversion JSON
      $listeDepensesJson = json_encode(convertForJsonListeDepenses($listeDepenses));
      break;

    case 'doInserer':
    case 'doInsererMontants':
    case 'doModifier':
    case 'doModifierMontants':
    case 'doSupprimer':
    case 'doSupprimerMontants':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doInserer':
    case 'doInsererMontants':
      header('location: expensecenter.php?year=' . date('Y') . '&action=goConsulter&anchor=' . $idExpense);
      break;

    case 'doModifier':
    case 'doModifierMontants':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter&anchor=' . $idExpense);
      break;

    case 'doSupprimer':
    case 'doSupprimerMontants':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_expensecenter.php');
      break;
  }
?>
