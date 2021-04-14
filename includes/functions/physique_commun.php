<?php
  include_once('appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture d'une alerte
  // RETOUR : Objet Alerte
  function physiqueAlerte($referenceAlerte)
  {
    // Initialisations
    $alerte = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM alerts
                        WHERE alert = "' . $referenceAlerte . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    if ($data['nombreLignes'] > 0)
      $alerte = Alerte::withData($data);

    $req->closeCursor();

    // Retour
    return $alerte;
  }

  // PHYSIQUE : Lecture d'un succès
  // RETOUR : Objet Success
  function physiqueSucces($referenceSucces)
  {
    // Requête
    global $bdd;

    $req  = $bdd->query('SELECT *
                         FROM success
                         WHERE reference = "' . $referenceSucces . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Success à partir des données remontées de la bdd
    $succes = Success::withData($data);

    $req->closeCursor();

    // Retour
    return $succes;
  }

  // PHYSIQUE : Lecture de l'expérience d'un utilisateur
  // RETOUR : Expérience
  function physiqueExperienceUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, experience
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $experience = $data['experience'];

    $req->closeCursor();

    // Retour
    return $experience;
  }

  // PHYSIQUE : Lecture des missions actives
  // RETOUR : Liste des missions
  function physiqueMissionsActives()
  {
    // Initialisations
    $listeMissions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE ' . date('Ymd') . ' >= date_deb AND ' . date('Ymd') . ' <= date_fin
                        ORDER BY date_deb ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Mission à partir des données remontées de la bdd
      $mission = Mission::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeMissions, $mission);
    }

    $req->closeCursor();

    // Retour
    return $listeMissions;
  }

  // PHYSIQUE : Lecture avancement du jour d'une mission pour un utilisateur
  // RETOUR : Avancement mission
  function physiqueAvancementMissionUser($idMission, $identifiant)
  {
    // Initialisations
    $listeMissions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '" AND date_mission = "' . date('Ymd') . '"');

    $data = $req->fetch();

    $avancement = $data['avancement'];

    $req->closeCursor();

    // Retour
    return $avancement;
  }

  // PHYSIQUE : Détermination thème mission en cours
  // RETOUR : Objet Theme
  function physiqueThemeMissionActive()
  {
    // Initialisations
    $theme = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM themes
                        WHERE type = "M" AND "' . date('Ymd') . '" >= date_deb AND "' . date('Ymd') . '" <= date_fin');

    $data = $req->fetch();

    // Instanciation d'un objet Theme à partir des données remontées de la bdd
    if ($data['nombreLignes'] > 0)
      $theme = Theme::withData($data);

    $req->closeCursor();

    // Retour
    return $theme;
  }

  // PHYSIQUE : Lecture des préférences utilisateur
  // RETOUR : Préférence thème
  function physiquePreferenceTheme($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM preferences
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $referenceTheme = $data['ref_theme'];

    $req->closeCursor();

    // Retour
    return $referenceTheme;
  }

  // PHYSIQUE : Lecture d'un thème
  // RETOUR : Objet Theme
  function physiqueThemePersonnalise($referenceTheme)
  {
    // Initialisations
    $theme = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM themes
                        WHERE reference = "' . $referenceTheme . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Theme à partir des données remontées de la bdd
    if ($data['nombreLignes'] > 0)
      $theme = Theme::withData($data);

    $req->closeCursor();

    // Retour
    return $theme;
  }
?>
