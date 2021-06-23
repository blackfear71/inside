<?php
  include_once('../../includes/classes/calendars.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/teams.php');

  // METIER : Lecture de la liste des équipes
  // RETOUR : Liste des équipes
  function getListeEquipes()
  {
    // Lecture de la liste des équipes
    $listeEquipes = physiqueListeEquipes();

    // Retour
    return $listeEquipes;
  }

  // METIER : Récupération autorisation tous utilisateurs
  // RETOUR : Liste des préférences
  function getAutorisationsCalendars()
  {
    // Initialisations
    $listeAutorisationsParEquipe = array();

    // Récupération des autorisations de gestion
    $listeAutorisations = physiqueAutorisationsCalendars();

    // Récupération des données complémentaires
    foreach ($listeAutorisations as $autorisation)
    {
      // Récupération du pseudo et de l'équipe de l'utilisateur
      $user = physiqueDonneesUser($autorisation->getIdentifiant());

      $autorisation->setPseudo($user['pseudo']);
      $autorisation->setEquipe($user['team']);

      // Ajout de l'utilisateur à son équipe
      if (!isset($listeAutorisationsParEquipe[$autorisation->getEquipe()]))
        $listeAutorisationsParEquipe[$autorisation->getEquipe()] = array();

      array_push($listeAutorisationsParEquipe[$autorisation->getEquipe()], $autorisation);
    }

    // Retour
    return $listeAutorisationsParEquipe;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Récupération liste des utilisateurs
    $listeUsers = physiqueUsers();

    // Retour
    return $listeUsers;
  }

  // METIER : Mise à jour des autorisations sur les calendriers
  // RETOUR : Aucun
  function updateAutorisations($post, $listeUsers)
  {
    // Boucle de mise à jour de toutes les autorisations
    foreach ($listeUsers as $user)
    {
      if (!empty($post['autorization']) AND isset($post['autorization'][$user->getIdentifiant()]))
        $manageCalendars = 'Y';
      else
        $manageCalendars = 'N';

      physiqueUpdateAutorisationsCalendars($user->getIdentifiant(), $manageCalendars);
    }

    // Message d'alerte
    $_SESSION['alerts']['autorizations_updated'] = true;
  }

  // METIER : Lecture des fichiers de vacances scolaires
  // RETOUR : Liste des fichiers
  function getPeriodesVacances()
  {
    // Initialisations
    $periodesVacances = array();

    // Lecture des fichiers temporaires
    $dossierDonnees = '../../includes/datas/calendars';

    if (is_dir($dossierDonnees))
    {
      // Récupération liste des fichiers journaliers
      $fichiersDonnees = scandir($dossierDonnees);

      // Suppression des racines de dossier
      unset($fichiersDonnees[array_search('..', $fichiersDonnees)]);
      unset($fichiersDonnees[array_search('.', $fichiersDonnees)]);

      foreach ($fichiersDonnees as $fichierDonnees)
      {
        $extension = pathinfo($fichierDonnees, PATHINFO_EXTENSION);

        // On ajoute la ligne au tableau
        array_push($periodesVacances, str_replace('.' . $extension, '', $fichierDonnees));
      }
    }

    // Retour
    return $periodesVacances;
  }

  // METIER : Vérification période saisie pour l'année courante
  // RETOUR : Booléen
  function getPeriodesVacancesPresentes()
  {
    // Initialisations
    $periodesPresentes = false;
    $anneeInitiale     = date('Y');
    $anneeFinale       = $anneeInitiale + 1;
    $nomFichier        = $anneeInitiale . '-' . $anneeFinale . '.csv';

    // Vérification fichier existant
    $dossier = '../../includes/datas/calendars';

    if (file_exists($dossier . '/' . $nomFichier))
      $periodesPresentes = true;

    // Retour
    return $periodesPresentes;
  }

  // METIER : Construction d'un tableau des vacances saisissables
  // RETOUR : Tableau des vacances saisissables
  function getSaisieVacances()
  {
    // Construction
    $saisiesVacances = array(array('nom'       => 'Vacances de la Toussaint',
                                   'reference' => 'toussaint',
                                   'required'  => true),
                             array('nom'       => 'Vacances de Noël',
                                   'reference' => 'noel',
                                   'required'  => true),
                             array('nom'       => 'Vacances d\'hiver',
                                   'reference' => 'hiver',
                                   'required'  => true),
                             array('nom'       => 'Vacances de printemps',
                                   'reference' => 'printemps',
                                   'required'  => true),
                             array('nom'       => 'Pont de l\'Ascension',
                                   'reference' => 'ascension',
                                   'required'  => false),
                             array('nom'       => 'Vacances d\'été',
                                   'reference' => 'ete',
                                   'required'  => true)
                            );

    // Retour
    return $saisiesVacances;
  }

  // METIER : Créé un fichier de périodes de vacances scolaires
  // RETOUR : Aucun
  function insertVacancesCSV($post)
  {
    // Initialisations
    $datesVacances = array();

    // Récupération des données
    $anneeInitiale = $post['annee_vacances'];
    $anneeFinale   = $anneeInitiale + 1;
    $dateInitiale  = $anneeInitiale . '-10-01';
    $dateFinale    = $anneeFinale . '-09-30';
    $nomFichier    = $anneeInitiale . '-' . $anneeFinale . '.csv';

    // Création d'un tableau des dates de vacances
    foreach ($post['vacances'] as $libelle => $vacances)
    {
      // Récupération des jours de début par zone
      if ($vacances['debut']['zone_a']['mois'] >= 10)
        $jourDebutZoneA = $anneeInitiale . '-' . $vacances['debut']['zone_a']['mois'] . '-' . $vacances['debut']['zone_a']['jour'];
      else
        $jourDebutZoneA = $anneeFinale . '-' . $vacances['debut']['zone_a']['mois'] . '-' . $vacances['debut']['zone_a']['jour'];

      if ($vacances['debut']['zone_b']['mois'] >= 10)
        $jourDebutZoneB = $anneeInitiale . '-' . $vacances['debut']['zone_b']['mois'] . '-' . $vacances['debut']['zone_b']['jour'];
      else
        $jourDebutZoneB = $anneeFinale . '-' . $vacances['debut']['zone_b']['mois'] . '-' . $vacances['debut']['zone_b']['jour'];

      if ($vacances['debut']['zone_c']['mois'] >= 10)
        $jourDebutZoneC = $anneeInitiale . '-' . $vacances['debut']['zone_c']['mois'] . '-' . $vacances['debut']['zone_c']['jour'];
      else
        $jourDebutZoneC = $anneeFinale . '-' . $vacances['debut']['zone_c']['mois'] . '-' . $vacances['debut']['zone_c']['jour'];

      // Récupération des jours de fin par zone
      if ($vacances['fin']['zone_a']['mois'] >= 10)
        $jourFinZoneA = $anneeInitiale . '-' . $vacances['fin']['zone_a']['mois'] . '-' . $vacances['fin']['zone_a']['jour'];
      else
        $jourFinZoneA = $anneeFinale . '-' . $vacances['fin']['zone_a']['mois'] . '-' . $vacances['fin']['zone_a']['jour'];

      if ($vacances['fin']['zone_b']['mois'] >= 10)
        $jourFinZoneB = $anneeInitiale . '-' . $vacances['fin']['zone_b']['mois'] . '-' . $vacances['fin']['zone_b']['jour'];
      else
        $jourFinZoneB = $anneeFinale . '-' . $vacances['fin']['zone_b']['mois'] . '-' . $vacances['fin']['zone_b']['jour'];

      if ($vacances['fin']['zone_c']['mois'] >= 10)
        $jourFinZoneC = $anneeInitiale . '-' . $vacances['fin']['zone_c']['mois'] . '-' . $vacances['fin']['zone_c']['jour'];
      else
        $jourFinZoneC = $anneeFinale . '-' . $vacances['fin']['zone_c']['mois'] . '-' . $vacances['fin']['zone_c']['jour'];

      // Détermination de la plus date la plus tôt des vacances concernées
      $minDateVacances = min($jourDebutZoneA, $jourDebutZoneB, $jourDebutZoneC);

      // Détermination de la plus date la plus tard des vacances concernées
      $maxDateVacances = max($jourFinZoneA, $jourFinZoneB, $jourFinZoneC);

      // On parcourt les dates saisies pour formater les jours de vacances
      for ($i = $minDateVacances; $i <= $maxDateVacances; $i = date('Y-m-d', strtotime($i . ' + 1 days')))
      {
        // On détermine si c'est une date de vacances pour chaque zone
        if ($i >= $jourDebutZoneA AND $i <= $jourFinZoneA)
          $vacancesZoneA = 'true';
        else
          $vacancesZoneA = 'false';

        if ($i >= $jourDebutZoneB AND $i <= $jourFinZoneB)
          $vacancesZoneB = 'true';
        else
          $vacancesZoneB = 'false';

        if ($i >= $jourDebutZoneC AND $i <= $jourFinZoneC)
          $vacancesZoneC = 'true';
        else
          $vacancesZoneC = 'false';

        // Récupération du libellé
        switch ($libelle)
        {
          case 'toussaint':
            $nomVacances = 'Vacances de la Toussaint';
            break;

          case 'noel':
            $nomVacances = 'Vacances de Noël';
            break;

          case 'hiver':
            $nomVacances = 'Vacances d\'hiver';
            break;

          case 'printemps':
            $nomVacances = 'Vacances de printemps';
            break;

          case 'ascension':
            $nomVacances = 'Pont de l\'Ascension';
            break;

          case 'ete':
            $nomVacances = 'Vacances d\'été';
            break;

          default:
            break;
        }

        // On ajoute la date au tableau
        $datesVacances[$i] = $i . ',' . $vacancesZoneA . ',' . $vacancesZoneB . ',' . $vacancesZoneC . ',' . $nomVacances;
      }
    }

    // On vérifie la présence du dossier, sinon on le créé de manière récursive
    $dossier = '../../includes/datas/calendars';

    if (!is_dir($dossier))
      mkdir($dossier, 0777, true);

    // Suppression de l'ancien fichier si existant
    if (file_exists($dossier . '/' . $nomFichier))
      unlink($dossier . '/' . $nomFichier);

    // Création et ouverture d'un nouveau fichier
    $periodesVacances = fopen($dossier . '/' . $nomFichier, 'a+');

    // Repositionnement du curseur au début du fichier
    fseek($periodesVacances, 0);

    // Ecriture du fichier des périodes de vacances scolaires
    for ($j = $dateInitiale; $j <= $dateFinale; $j = date('Y-m-d', strtotime($j . ' + 1 days')))
    {
      if (isset($datesVacances[$j]) AND !empty($datesVacances[$j]))
        fputs($periodesVacances, $datesVacances[$j]);
      else
        fputs($periodesVacances, $j . ',false,false,false,');

      fputs($periodesVacances, "\r\n");
    }

    // Fermeture du fichier
    fclose($periodesVacances);

    // Message d'alerte
    $_SESSION['alerts']['holidays_added'] = true;
  }

  // METIER : Supprime un fichier de périodes de vacances scolaires
  // RETOUR : Aucun
  function deleteVacancesCSV($post)
  {
    // Récupération des données
    $nomFichier = $post['nom_fichier'] . '.csv';

    // Suppression du fichier si existant
    $dossier = '../../includes/datas/calendars';

    // Suppression de l'ancien fichier si existant
    if (file_exists($dossier . '/' . $nomFichier))
      unlink($dossier . '/' . $nomFichier);

    // Message d'alerte
    $_SESSION['alerts']['holidays_deleted'] = true;
  }

  // METIER : Création liste des mois
  // RETOUR : Liste des mois
  function getMonths()
  {
    // Création de la liste
    $listeMois = array('01' => 'Janvier',
                       '02' => 'Février',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Août',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Décembre'
                      );

    // Retour
    return $listeMois;
  }

  // METIER : Lecture des calendriers à supprimer
  // RETOUR : Liste des calendriers à supprimer
  function getCalendarsToDelete($listeMois)
  {
    // Récupération de la liste des calendriers à supprimer
    $listeCalendarsToDelete = physiqueCalendarsToDelete($listeMois);

    // Retour
    return $listeCalendarsToDelete;
  }

  // METIER : Contrôle alertes Calendars
  // RETOUR : Booléen
  function getAlerteCalendars()
  {
    // Appel physique
    $alert = physiqueAlerteCalendars();

    // Retour
    return $alert;
  }

  // METIER : Lecture des annexes à supprimer
  // RETOUR : Liste des annexes à supprimer
  function getAnnexesToDelete()
  {
    // Récupération de la liste des annexes à supprimer
    $listeAnnexesToDelete = physiqueAnnexesToDelete();

    // Retour
    return $listeAnnexesToDelete;
  }

  // METIER : Contrôle alertes Annexes
  // RETOUR : Booléen
  function getAlerteAnnexes()
  {
    // Appel physique
    $alert = physiqueAlerteAnnexes();

    // Retour
    return $alert;
  }

  // METIER : Supprime un calendrier de la base
  // RETOUR : Aucun
  function deleteCalendrier($post)
  {
    // Récupération des données
    $idCalendars = $post['id_calendrier'];
    $equipe      = $post['team_calendrier'];

    // Lecture des données en base
    $calendars = physiqueDonneesCalendars($idCalendars, 'calendars');

    // Suppression des images
    unlink('../../includes/images/calendars/' . $calendars->getYear() . '/' . $calendars->getCalendar());
    unlink('../../includes/images/calendars/' . $calendars->getYear() . '/mini/' . $calendars->getCalendar());

    // Suppression de l'enregistrement en base
    physiqueDeleteCalendrier($idCalendars);

    // Suppression des notifications
    deleteNotification('calendrier', $equipe, $idCalendars);

    // Message d'alerte
    $_SESSION['alerts']['calendar_deleted'] = true;
  }

  // METIER : Supprime une annexe de la base
  // RETOUR : Aucun
  function deleteAnnexe($post)
  {
    // Récupération des données
    $idCalendars = $post['id_annexe'];
    $equipe      = $post['team_annexe'];

    // Lecture des données en base
    $calendars = physiqueDonneesCalendars($idCalendars, 'calendars_annexes');

    // Suppression des images
    unlink('../../includes/images/calendars/annexes/' . $calendars->getAnnexe());
    unlink('../../includes/images/calendars/annexes/mini/' . $calendars->getAnnexe());

    // Suppression de l'enregistrement en base
    physiqueDeleteAnnexe($idCalendars);

    // Suppression des notifications
    deleteNotification('annexe', $equipe, $idCalendars);

    // Message d'alerte
    $_SESSION['alerts']['annexe_deleted'] = true;
  }

  // METIER : Réinitialise un calendrier de la base
  // RETOUR : Aucun
  function resetCalendrier($post)
  {
    // Récupération des données
    $idCalendars = $post['id_calendrier'];
    $equipe      = $post['team_calendrier'];
    $toDelete    = 'N';

    // Remise à "N" de l'indicateur de demande de suppression
    physiqueUpdateStatusCalendars('calendars', $idCalendars, $toDelete);

    // Mise à jour du statut de la notification
    updateNotification('calendrier', $equipe, $idCalendars, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['calendar_reseted'] = true;
  }

  // METIER : Réinitialise une annexe de la base
  // RETOUR : Aucun
  function resetAnnexe($post)
  {
    // Récupération des données
    $idAnnexe = $post['id_annexe'];
    $equipe   = $post['team_annexe'];
    $toDelete = 'N';

    // Remise à "N" de l'indicateur de demande de suppression
    physiqueUpdateStatusCalendars('calendars_annexes', $idAnnexe, $toDelete);

    // Mise à jour du statut de la notification
    updateNotification('annexe', $equipe, $idAnnexe, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['annexe_reseted'] = true;
  }
?>
