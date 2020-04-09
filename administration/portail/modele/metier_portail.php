<?php
  include_once('../../includes/functions/appel_bdd.php');

  // METIER : Génération du portail administration
  // RETOUR : Tableau des liens
  function getPortail($alertUsers, $alertFilms, $alertCalendars, $alertAnnexes, $nbBugs, $nbEvols)
  {
    // Informations utilisateurs
    $infosusers = array('ligne_1' => 'Infos',
                        'ligne_2' => 'UTILISATEURS',
                        'ligne_3' => '',
                        'lien'    => '../infosusers/infosusers.php?action=goConsulter');

    // Gestion utilisateurs
    if ($alertUsers == true)
      $alert = ' ( ! )';
    else
      $alert = '';

    $manageusers = array('ligne_1' => 'Gestion',
                         'ligne_2' => 'UTILISATEURS' . $alert,
                         'ligne_3' => '',
                         'lien'    => '../manageusers/manageusers.php?action=goConsulter');

    // Gestion thèmes
    $themes = array('ligne_1' => 'Gestion',
                    'ligne_2' => 'THÈMES',
                    'ligne_3' => '',
                    'lien'    => '../themes/themes.php?action=goConsulter');

    // Gestion succès
    $success = array('ligne_1' => 'Gestion',
                     'ligne_2' => 'SUCCÈS',
                     'ligne_3' => '',
                     'lien'    => '../success/success.php?action=goConsulter');

    // Gestion films
    if ($alertFilms == true)
      $alert = ' ( ! )';
    else
      $alert = '';

    $movies = array('ligne_1' => 'Gestion',
                    'ligne_2' => 'MOVIE',
                    'ligne_3' => 'HOUSE' . $alert,
                    'lien'    => '../movies/movies.php?action=goConsulter');

    // Gestion calendriers
    if ($alertCalendars == true OR $alertAnnexes == true)
      $alert = ' ( ! )';
    else
      $alert = '';

    $calendars = array('ligne_1' => 'Gestion',
                       'ligne_2' => 'CALENDARS' . $alert,
                       'ligne_3' => '',
                       'lien'    => '../calendars/calendars.php?action=goConsulter');

    // Gestion missions
    $missions = array('ligne_1' => 'Gestion',
                      'ligne_2' => 'MISSIONS',
                      'ligne_3' => '',
                      'lien'    => '../missions/missions.php?action=goConsulter');

    // Rapports bugs/évolutions
    $bugs = array('ligne_1' => 'Rapports',
                  'ligne_2' => 'BUGS (' . $nbBugs . ')',
                  'ligne_3' => 'ÉVOLUTIONS (' . $nbEvols . ')',
                  'lien'    => '../reports/reports.php?view=unresolved&action=goConsulter');

    // Gestion alertes
    $alerts = array('ligne_1' => 'Gestion',
                    'ligne_2' => 'ALERTES',
                    'ligne_3' => '',
                    'lien'    => '../alerts/alerts.php?action=goConsulter');

    // Gestion CRON
    $cron = array('ligne_1' => 'Gestion',
                  'ligne_2' => 'CRON',
                  'ligne_3' => '',
                  'lien'    => '../cron/cron.php?action=goConsulter');

    // Journal des modifications
    $changelog = array('ligne_1' => 'Journal des',
                       'ligne_2' => 'MODIFICATIONS',
                       'ligne_3' => '',
                       'lien'    => '../changelog/changelog.php?action=goConsulter');

    // Générateur de code
    $generator = array('ligne_1' => 'Générateur',
                       'ligne_2' => 'CODE',
                       'ligne_3' => '',
                       'lien'    => '../codegenerator/codegenerator.php?action=goConsulter');

    // Assemblage portail
    $portail = array($infosusers, $manageusers, $themes, $success, $movies, $calendars, $missions, $bugs, $alerts, $cron, $changelog, $generator);

    return $portail;
  }

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, status FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while ($data = $req->fetch())
    {
      if ($data['status'] == "Y" OR $data['status'] == "I" OR $data['status'] == "D")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Contrôle alertes Movie House
  // RETOUR : Booléen
  function getAlerteFilms()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM movie_house WHERE to_delete = "Y"');
    while ($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Contrôle alertes Calendars
  // RETOUR : Booléen
  function getAlerteCalendars()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM calendars WHERE to_delete = "Y"');
    while ($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Contrôle alertes Annexes
  // RETOUR : Booléen
  function getAlerteAnnexes()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM calendars_annexes WHERE to_delete = "Y"');
    while ($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }
  
  // METIER : Nombre de bugs en attente
  // RETOUR : Nombre de bugs
  function getNbBugs()
  {
    $nb_bugs = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type = "B" AND resolved = "N"');
    $data = $req->fetch();
    $nb_bugs = $data['nb_bugs'];
    $req->closeCursor();

    return $nb_bugs;
  }

  // METIER : Nombre d'évolutions en attente
  // RETOUR : Nombre d'évolutions
  function getNbEvols()
  {
    $nb_evols = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type = "E" AND resolved = "N"');
    $data = $req->fetch();
    $nb_evols = $data['nb_bugs'];
    $req->closeCursor();

    return $nb_evols;
  }
?>
