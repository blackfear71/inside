<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  include('../../includes/fonctions_dates.php');

  // Contrôle si la page renseignée et numérique
	if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
		header('location: collector.php?action=goConsulter&page=1');

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['wrong_date']) OR $_SESSION['wrong_date'] != true)
  {
    $_SESSION['speaker']   = "";
    $_SESSION['date']      = "";
    $_SESSION['collector'] = "";
  }

  // Modèle de données : "module métier"
  include_once('modele/metier_collector.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Lecture des données par le modèle
      $listeUsers      = getUsers();
      $nbPages         = getPages();

      if ($nbPages > 0)
      {
        if ($_GET['page'] > $nbPages)
          header('location: collector.php?action=goConsulter&page=' . $nbPages);
        elseif ($_GET['page'] < 1)
          header('location: collector.php?action=goConsulter&page=1');
        else
          $listeCollectors = getCollectors($listeUsers, $nbPages, $_GET['page']);
      }
      break;

    case "doAjouter":
      insertCollector($_POST);
      break;

    case "doSupprimer":
      deleteCollector($_GET['delete_id']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: collector.php?action=goConsulter&page=1');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case "goConsulter":
      if ($nbPages > 0)
      {
        foreach ($listeUsers as $user)
        {
          $user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
          $user->setPseudo(htmlspecialchars($user->getPseudo()));
          $user->setAvatar(htmlspecialchars($user->getAvatar()));
        }
        foreach ($listeCollectors as $collector)
        {
          $collector->setAuthor(htmlspecialchars($collector->getAuthor()));
          $collector->setName_a(htmlspecialchars($collector->getName_a()));
          $collector->setSpeaker(htmlspecialchars($collector->getSpeaker()));
          $collector->setName_s(htmlspecialchars($collector->getName_s()));
          $collector->setDate(htmlspecialchars($collector->getDate()));
          $collector->setCollector(htmlspecialchars($collector->getCollector()));
        }
      }
      break;

    case "doAjouter":
    case "doSupprimer":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
      header('location: collector.php?action=goConsulter&page=1');
      break;

    case "doSupprimer":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page']);
      break;

    case "goConsulter":
    default:
      include_once('vue/vue_collector.php');
      break;
  }
?>
