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

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: expensecenter.php?year=' . date("Y") . '&action=goConsulter');
      else
      {
        // Initialisation de la sauvegarde en session
        initializeSaveSession();

        // Lecture des données par le modèle
        $anneeExistante = controlYear($_GET['year']);
        $listeUsers     = getUsers();
        $onglets        = getOnglets();
        $listeDepenses  = getExpenses($_GET['year']);
      }
      break;

    case 'doInserer':
      // Insertion des données par le modèle
      $id_expense = insertExpense($_POST);
      break;

    case 'doModifier':
      // Mise à jour des données par le modèle
      $id_expense = updateExpense($_POST);
      break;

    case 'doSupprimer':
      // Suppression des données par le modèle
      deleteExpense($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: expensecenter.php?year=' . date("Y") . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      if ($anneeExistante == true)
      {
        foreach ($listeUsers as &$user)
        {
          $user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
          $user->setPseudo(htmlspecialchars($user->getPseudo()));
          $user->setAvatar(htmlspecialchars($user->getAvatar()));
          $user->setExpenses(htmlspecialchars($user->getExpenses()));
        }

        unset($user);

        foreach ($onglets as &$year)
        {
          $year = htmlspecialchars($year);
        }

        unset($year);

        foreach ($listeDepenses as &$depense)
        {
          $depense->setDate(htmlspecialchars($depense->getDate()));
          $depense->setPrice(htmlspecialchars($depense->getPrice()));
          $depense->setBuyer(htmlspecialchars($depense->getBuyer()));
          $depense->setPseudo(htmlspecialchars($depense->getPseudo()));
          $depense->setAvatar(htmlspecialchars($depense->getAvatar()));
          $depense->setComment(htmlspecialchars($depense->getComment()));

          foreach ($depense->getParts() as &$parts)
          {
            $parts->setId_expense(htmlspecialchars($parts->getId_expense()));
            $parts->setId_identifiant(htmlspecialchars($parts->getId_identifiant()));
            $parts->setIdentifiant(htmlspecialchars($parts->getIdentifiant()));
            $parts->setPseudo(htmlspecialchars($parts->getPseudo()));
            $parts->setAvatar(htmlspecialchars($parts->getAvatar()));
            $parts->setParts(htmlspecialchars($parts->getParts()));
          }

          unset($part);
        }

        unset($depense);

        // Conversion JSON
        $listeDepensesJson = json_encode(convertForJson($listeDepenses));
      }
      break;

    case 'doInserer':
    case 'doModifier':
    case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doInserer':
      header('location: expensecenter.php?year=' . date("Y") . '&action=goConsulter&anchor=' . $id_expense);
      break;

    case 'doSupprimer':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'doModifier':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter&anchor=' . $id_expense);
      break;

    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_expensecenter.php');
      break;
  }
?>
