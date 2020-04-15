<?php
  include_once('../../includes/classes/calendars.php');

  // METIER : Récupération autorisation tous utilisateurs
  // RETOUR : Liste des préférences
  function getAutorisationsCalendars()
  {
    // Récupération des autorisations de gestion
    $listAutorisations = physiqueAutorisationsCalendars();

    // Récupération du pseudo des utilisateurs
    foreach ($listAutorisations as &$autorisation)
    {
      $autorisation['pseudo'] = physiquePseudoUser($autorisation['identifiant']);
    }

    unset($autorisation);

    // Retour
    return $listAutorisations;
  }

  // METIER : Mise à jour des autorisations sur les calendriers
  // RETOUR : Aucun
  function updateAutorisations($post)
  {
    // Boucle de mise à jour de toutes autorisations activées
    if (!empty($post['autorization']))
    {
      foreach ($post['autorization'] as $identifiant => $autorisation)
      {
        $manageCalendars = 'Y';

        physiqueUpdateAutorisationsCalendars($identifiant, $manageCalendars);
      }
    }

    // Message d'alerte
    $_SESSION['alerts']['autorizations_updated'] = true;
  }

  // METIER : Création liste des mois
  // RETOUR : Liste des mois
  function getMonths()
  {
    // Création de la liste
    $listMonths = array('01' => 'Janvier',
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
    return $listMonths;
  }

  // METIER : Lecture des calendriers à supprimer
  // RETOUR : Liste des calendriers à supprimer
  function getCalendarsToDelete($listMonths)
  {
    // Récupération de la liste des calendriers à supprimer
    $listCalendarsToDelete = physiqueCalendarsToDelete($listMonths);

    // Retour
    return $listCalendarsToDelete;
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
    $listAnnexesToDelete = physiqueAnnexesToDelete();

    // Retour
    return $listAnnexesToDelete;
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

    // Lecture des données en base
    $calendars = physiqueDonneesCalendars($idCalendars, 'calendars');

    // Suppression des images
    unlink("../../includes/images/calendars/" . $calendars->getYear() . "/" . $calendars->getCalendar());
    unlink("../../includes/images/calendars/" . $calendars->getYear() . "/mini/" . $calendars->getCalendar());

    // Suppression de l'enregistrement en base
    physiqueDeleteCalendrier($idCalendars);

    // Suppression des notifications
    deleteNotification('calendrier', $idCalendars);

    // Message d'alerte
    $_SESSION['alerts']['calendar_deleted'] = true;
  }

  // METIER : Supprime une annexe de la base
  // RETOUR : Aucun
  function deleteAnnexe($post)
  {
    // Récupération des données
    $idCalendars = $post['id_annexe'];

    // Lecture des données en base
    $calendars = physiqueDonneesCalendars($idCalendars, 'calendars_annexes');

    // Suppression des images
    unlink("../../includes/images/calendars/annexes/" . $calendars->getAnnexe());
    unlink("../../includes/images/calendars/annexes/mini/" . $calendars->getAnnexe());

    // Suppression de l'enregistrement en base
    physiqueDeleteAnnexe($idCalendars);

    // Suppression des notifications
    deleteNotification('annexe', $idCalendars);

    // Message d'alerte
    $_SESSION['alerts']['annexe_deleted'] = true;
  }

  // METIER : Réinitialise un calendrier de la base
  // RETOUR : Aucun
  function resetCalendrier($post)
  {
    // Récupération des données
    $idCalendars = $post['id_calendrier'];
    $toDelete    = 'N';

    // Remise à "N" de l'indicateur de demande de suppression
    physiqueUpdateStatusCalendars('calendars', $idCalendars, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['calendar_reseted'] = true;
  }

  // METIER : Réinitialise une annexe de la base
  // RETOUR : Aucun
  function resetAnnexe($post)
  {
    // Récupération des données
    $idCalendars = $post['id_annexe'];
    $toDelete    = 'N';

    // Remise à "N" de l'indicateur de demande de suppression
    physiqueUpdateStatusCalendars('calendars_annexes', $idCalendars, $toDelete);

    // Message d'alerte
    $_SESSION['alerts']['annexe_reseted'] = true;
  }
?>
