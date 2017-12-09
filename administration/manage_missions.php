<?php
  // Fonction communes
  include_once('../includes/fonctions_communes.php');
  include_once('../includes/fonctions_dates.php');
  include_once('../includes/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $tabMissions = getMissions();
      break;

    case 'goAjouter':
      $detailsMission = initAddMission();
      break;

    case 'goModifier':
      // Lecture liste des données par le modèle
      $detailsMission = getMission($_GET['id_mission']);
      $participants   = getParticipants($_GET['id_mission']);
      $ranking        = getRankingMission($_GET['id_mission'], $participants);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_missions.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($tabMissions as &$ligneMission)
      {
        $ligneMission->setMission(htmlspecialchars($ligneMission->getMission()));
        $ligneMission->setReference(htmlspecialchars($ligneMission->getReference()));
        $ligneMission->setDate_deb(htmlspecialchars($ligneMission->getDate_deb()));
        $ligneMission->setDate_fin(htmlspecialchars($ligneMission->getDate_fin()));
        $ligneMission->setHeure(htmlspecialchars($ligneMission->getHeure()));
        $ligneMission->setObjectif(htmlspecialchars($ligneMission->getObjectif()));
        $ligneMission->setDescription(htmlspecialchars($ligneMission->getDescription()));
        $ligneMission->setExplications(htmlspecialchars($ligneMission->getExplications()));
        $ligneMission->setStatut(htmlspecialchars($ligneMission->getStatut()));
      }

      unset($ligneMission);
      break;

    case 'goModifier':
      $detailsMission->setMission(htmlspecialchars($detailsMission->getMission()));
      $detailsMission->setReference(htmlspecialchars($detailsMission->getReference()));
      $detailsMission->setDate_deb(htmlspecialchars($detailsMission->getDate_deb()));
      $detailsMission->setDate_fin(htmlspecialchars($detailsMission->getDate_fin()));
      $detailsMission->setHeure(htmlspecialchars($detailsMission->getHeure()));
      $detailsMission->setObjectif(htmlspecialchars($detailsMission->getObjectif()));
      $detailsMission->setDescription(htmlspecialchars($detailsMission->getDescription()));
      $detailsMission->setExplications(htmlspecialchars($detailsMission->getExplications()));
      $detailsMission->setStatut(htmlspecialchars($detailsMission->getStatut()));

      foreach ($participants as &$participant)
      {
        $participant->setIdentifiant(htmlspecialchars($participant->getIdentifiant()));
        $participant->setPseudo(htmlspecialchars($participant->getPseudo()));
        $participant->setAvatar(htmlspecialchars($participant->getAvatar()));
      }

      unset($participant);

      foreach ($ranking as &$rankUser)
      {
        $rankUser['identifiant'] = htmlspecialchars($rankUser['identifiant']);
        $rankUser['pseudo']      = htmlspecialchars($rankUser['pseudo']);
        $rankUser['avatar']      = htmlspecialchars($rankUser['avatar']);
        $rankUser['total']       = htmlspecialchars($rankUser['total']);
        $rankUser['rank']        = htmlspecialchars($rankUser['rank']);
      }

      unset($rankUser);
      break;

    case 'goAjouter':
      $detailsMission->setMission(htmlspecialchars($detailsMission->getMission()));
      $detailsMission->setReference(htmlspecialchars($detailsMission->getReference()));
      $detailsMission->setDate_deb(htmlspecialchars($detailsMission->getDate_deb()));
      $detailsMission->setDate_fin(htmlspecialchars($detailsMission->getDate_fin()));
      $detailsMission->setHeure(htmlspecialchars($detailsMission->getHeure()));
      $detailsMission->setObjectif(htmlspecialchars($detailsMission->getObjectif()));
      $detailsMission->setDescription(htmlspecialchars($detailsMission->getDescription()));
      $detailsMission->setExplications(htmlspecialchars($detailsMission->getExplications()));
      $detailsMission->setStatut(htmlspecialchars($detailsMission->getStatut()));
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goAjouter':
    case 'goModifier':
    case 'goConsulter':
    default:
      include_once('vue/vue_manage_missions.php');
      break;
  }
?>
