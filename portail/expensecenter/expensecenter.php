<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  include('../../includes/fonctions_dates.php');

  // Initialisation sauvegarde saisie
	if (!isset($_SESSION['not_numeric']) OR $_SESSION['not_numeric'] != true)
	{
		$_SESSION['price']   = "";
		$_SESSION['buyer']   = "";
		$_SESSION['comment'] = "";
		unset($_SESSION['tableau_parts']);
	}

  // Contrôle année existante (pour les onglets)
	$annee_existante = false;

	if (isset($_GET['year']) AND is_numeric($_GET['year']))
	{
		include('../../includes/appel_bdd.php');

		$reponse = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4) FROM expense_center ORDER BY SUBSTR(date, 1, 4) ASC');
		while($donnees = $reponse->fetch())
		{
			if ($_GET['year'] == $donnees['SUBSTR(date, 1, 4)'])
				$annee_existante = true;
		}
		$reponse->closeCursor();
	}

	// Contrôle si l'année est renseignée et numérique
	if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
		header('location: expensecenter.php?year=' . date("Y") . '&action=goConsulter');

  // Modèle de données : "module métier"
  include_once('modele/metier_expensecenter.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $nbUsers         = countUsers();
      $listeUsers      = getUsers();
      $listeBilans     = getBilans($listeUsers);
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
      modifyExpense($_GET['id_modify'], $_POST, $listeUsers);
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
      $nbUsers = htmlspecialchars($nbUsers);

      foreach ($listeUsers as $user)
      {
        $user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
        $user->setFull_name(htmlspecialchars($user->getFull_name()));
        $user->setAvatar(htmlspecialchars($user->getAvatar()));
      }

      foreach ($listeBilans as $bilan)
      {
        $bilan->setIdentifiant(htmlspecialchars($bilan->getIdentifiant()));
        $bilan->setFull_name(htmlspecialchars($bilan->getFull_name()));
        $bilan->setAvatar(htmlspecialchars($bilan->getAvatar()));
        $bilan->setBilan(htmlspecialchars($bilan->getBilan()));
        $bilan->setBilan_format(htmlspecialchars($bilan->getBilan_format()));
      }

      foreach ($onglets as $year)
      {
        $year = htmlspecialchars($year);
      }

      foreach ($tableauExpenses as $expense)
      {
        $expense['id_expense'] = htmlspecialchars($expense['id_expense']);
        $expense['price'] = htmlspecialchars($expense['price']);
        $expense['buyer'] = htmlspecialchars($expense['buyer']);
        $expense['name_b'] = htmlspecialchars($expense['name_b']);
        $expense['date'] = htmlspecialchars($expense['date']);

        foreach ($expense['tableParts'] as $part)
        {
          $part['identifiant'] = htmlspecialchars($part['identifiant']);
          $part['part'] = htmlspecialchars($part['identifiant']);
        }

        $expense['comment'] = htmlspecialchars($expense['comment']);
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
    case 'doModifier':
    case 'doSupprimer':
      header('location: expensecenter.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_expensecenter.php');
      break;
  }
?>
