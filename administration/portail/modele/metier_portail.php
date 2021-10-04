<?php
  // METIER : Génération du portail administration
  // RETOUR : Tableau des liens
  function getPortail($alertEquipes, $alertUsers, $alertFilms, $alertVacances, $alertCalendars, $alertAnnexes, $nombreBugs, $nombreEvols)
  {
    // Vérification des alertes
    if ($alertEquipes == true)
      $avertissementEquipes = true;
    else
      $avertissementEquipes = false;

    if ($alertUsers == true)
      $avertissementUsers = true;
    else
      $avertissementUsers = false;

    if ($alertFilms == true)
      $avertissementFilms = true;
    else
      $avertissementFilms = false;

    if ($alertVacances == true OR $alertCalendars == true OR $alertAnnexes == true)
      $avertissementCalendars = true;
    else
      $avertissementCalendars = false;

    if ($nombreBugs != 0 OR $nombreEvols != 0)
      $avertissementBugs = true;
    else
      $avertissementBugs = false;

    // Tableau des catégories
    $listeCategories = array(array('categorie' => 'INFORMATIONS<br />UTILISATEURS',
                                   'lien'      => '../infosusers/infosusers.php?action=goConsulter',
                                   'title'     => 'Infos utilisateurs',
                                   'image'     => '../../includes/icons/common/inside.png',
                                   'alt'       => 'inside',
                                   'alert'     => $avertissementEquipes),
                             array('categorie' => 'GESTION DES<br />UTILISATEURS',
                                   'lien'      => '../manageusers/manageusers.php?action=goConsulter',
                                   'title'     => 'Gestion des utilisateurs',
                                   'image'     => '../../includes/icons/admin/users.png',
                                   'alt'       => 'users',
                                   'alert'     => $avertissementUsers),
                             array('categorie' => 'GESTION DES<br />THÈMES',
                                   'lien'      => '../themes/themes.php?action=goConsulter',
                                   'title'     => 'Gestion des thèmes',
                                   'image'     => '../../includes/icons/admin/themes.png',
                                   'alt'       => 'themes',
                                   'alert'     => false),
                             array('categorie' => 'GESTION DES<br />SUCCÈS',
                                   'lien'      => '../success/success.php?action=goConsulter',
                                   'title'     => 'Gestion des succès',
                                   'image'     => '../../includes/icons/admin/success.png',
                                   'alt'       => 'success',
                                   'alert'     => false),
                             array('categorie' => 'GESTION DES<br />FILMS',
                                   'lien'      => '../movies/movies.php?action=goConsulter',
                                   'title'     => 'Gestion des films',
                                   'image'     => '../../includes/icons/common/movie_house.png',
                                   'alt'       => 'movie_house',
                                   'alert'     => $avertissementFilms),
                             array('categorie' => 'GESTION DES<br />CALENDRIERS',
                                   'lien'      => '../calendars/calendars.php?action=goConsulter',
                                   'title'     => 'Gestion des calendriers',
                                   'image'     => '../../includes/icons/common/calendars.png',
                                   'alt'       => 'calendars',
                                   'alert'     => $avertissementCalendars),
                             array('categorie' => 'GESTION DES<br />MISSIONS',
                                   'lien'      => '../missions/missions.php?action=goConsulter',
                                   'title'     => 'Gestion des missions',
                                   'image'     => '../../includes/icons/common/missions.png',
                                   'alt'       => 'missions',
                                   'alert'     => false),
                             array('categorie' => 'BUGS <div class="nombre_lien_portail">' . $nombreBugs . '</div> ET<br />ÉVOLUTIONS <div class="nombre_lien_portail">' . $nombreEvols . '</div>',
                                   'lien'      => '../reports/reports.php?view=unresolved&action=goConsulter',
                                   'title'     => 'Bugs et évolutions',
                                   'image'     => '../../includes/icons/admin/bugs_evolutions.png',
                                   'alt'       => 'bugs_evolutions',
                                   'alert'     => $avertissementBugs),
                             array('categorie' => 'GESTION DES<br />ALERTES',
                                   'lien'      => '../alerts/alerts.php?action=goConsulter',
                                   'title'     => 'Gestion des alertes',
                                   'image'     => '../../includes/icons/common/alert.png',
                                   'alt'       => 'alert',
                                   'alert'     => false),
                             array('categorie' => 'GESTION<br />CRON',
                                   'lien'      => '../cron/cron.php?action=goConsulter',
                                   'title'     => 'Gestion CRON',
                                   'image'     => '../../includes/icons/admin/cron.png',
                                   'alt'       => 'cron',
                                   'alert'     => false),
                             array('categorie' => 'JOURNAL DES<br />MODIFICATIONS',
                                   'lien'      => '../changelog/changelog.php?action=goConsulter',
                                   'title'     => 'Journal des modifications',
                                   'image'     => '../../includes/icons/admin/datas.png',
                                   'alt'       => 'datas',
                                   'alert'     => false),
                             array('categorie' => 'GÉNÉRATEUR DE<br />CODE',
                                   'lien'      => '../codegenerator/codegenerator.php?action=goConsulter',
                                   'title'     => 'Générateur de code',
                                   'image'     => '../../includes/icons/admin/code.png',
                                   'alt'       => 'code',
                                   'alert'     => false)
                           );

     // Retour
     return $listeCategories;
  }

  // METIER : Contrôle alertes équipes
  // RETOUR : Booléen
  function getAlerteEquipes()
  {
    // Appel physique
    $alert = physiqueAlerteEquipes();

    // Retour
    return $alert;
  }

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    // Appel physique
    $alert = physiqueAlerteUsers();

    // Retour
    return $alert;
  }

  // METIER : Contrôle alertes Movie House
  // RETOUR : Booléen
  function getAlerteFilms()
  {
    // Appel physique
    $alert = physiqueAlerteFilms();

    // Retour
    return $alert;
  }

  // METIER : Contrôle alertes vacances
  // RETOUR : Booléen
  function getAlerteVacances()
  {
    // Initialisations
    $alert         = false;
    $anneeInitiale = date('Y');
    $anneeFinale   = $anneeInitiale + 1;
    $nomFichier    = $anneeInitiale . '-' . $anneeFinale . '.csv';

    // Vérification fichier existant
    $dossier = '../../includes/datas/calendars';

    if (date('m') == 12 AND !file_exists($dossier . '/' . $nomFichier))
      $alert = true;

    // Retour
    return $alert;
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

  // METIER : Contrôle alertes Annexes
  // RETOUR : Booléen
  function getAlerteAnnexes()
  {
    // Appel physique
    $alert = physiqueAlerteAnnexes();

    // Retour
    return $alert;
  }

  // METIER : Nombre de bugs en attente
  // RETOUR : Nombre de bugs
  function getNombreBugs()
  {
    // Appel physique
    $nombreBugs = physiqueNombreBugs();

    // Retour
    return $nombreBugs;
  }

  // METIER : Nombre d'évolutions en attente
  // RETOUR : Nombre d'évolutions
  function getNombreEvols()
  {
    // Appel physique
    $nombreEvolutions = physiqueNombreEvolutions();

    // Retour
    return $nombreEvolutions;
  }

  // METIER : Sauvegarde de la base de données
  // RETOUR : Aucun
  function saveBdd()
  {
    // Appel extraction BDD
    extractBdd();

    // Génération nom du fichier
    $fileName = 'inside_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1,11111111) . '.sql';

    // Génération du fichier
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: Binary');
    header('Content-disposition: attachment; filename="' . $fileName . '"');

    // Retour
    echo $contenu;
  }
?>
