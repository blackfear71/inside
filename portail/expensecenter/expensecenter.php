<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');
  include_once('../../includes/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Contrôle si l'année est renseignée et numérique
	if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
		header('location: expensecenter.php?year=' . date("Y") . '&action=goConsulter');

  // Initialisation sauvegarde saisie
	if (!isset($_SESSION['alerts']['depense_not_numeric']) OR $_SESSION['alerts']['depense_not_numeric'] != true)
	{
		$_SESSION['save']['price']   = "";
		$_SESSION['save']['buyer']   = "";
		$_SESSION['save']['comment'] = "";
		unset($_SESSION['tableau_parts']);
	}

  // Modèle de données : "module métier"
  include_once('modele/metier_expensecenter.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $anneeExistante  = controlYear($_GET['year']);
      $nbUsers         = countUsers();
      $listeUsers      = getUsers();
      $onglets         = getOnglets();
      $tableauExpenses = getExpenses($_GET['year'], $listeUsers, $nbUsers);
      break;

    case 'doInserer':
      // Insertion des données par le modèle
      $nbUsers    = countUsers();
      $listeUsers = getUsers();
      insertExpense($_POST, $listeUsers, $nbUsers);
      break;

    case 'doModifier':
      // Update des données par le modèle
      $listeUsers = getUsers();
      updateExpense($_GET['id_modify'], $_POST, $listeUsers);
      break;

    case 'doSupprimer':
      // Suppression des données par le modèle
      deleteExpense($_GET['id_delete']);
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
        $nbUsers = htmlspecialchars($nbUsers);

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

        foreach ($tableauExpenses as &$expense)
        {
          $expense['id_expense'] = htmlspecialchars($expense['id_expense']);
          $expense['price']      = htmlspecialchars($expense['price']);
          $expense['buyer']      = htmlspecialchars($expense['buyer']);
          $expense['name_b']     = htmlspecialchars($expense['name_b']);
          $expense['date']       = htmlspecialchars($expense['date']);

          foreach ($expense['tableParts'] as &$part)
          {
            $part['identifiant'] = htmlspecialchars($part['identifiant']);
            $part['part']        = htmlspecialchars($part['part']);
          }

          unset($part);

          $expense['comment']    = htmlspecialchars($expense['comment']);
        }

        unset($expense);
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
    case 'doSupprimer':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'doModifier':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter&anchor=' . $_GET['id_modify']);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_expensecenter.php');
      break;
  }
?>
