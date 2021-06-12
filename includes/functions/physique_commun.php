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

  // PHYSIQUE : Lecture de l'équipe d'un utilisateur
  // RETOUR : Equipe
  function physiqueEquipeUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, team
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $equipe = $data['team'];

    $req->closeCursor();

    // Retour
    return $equipe;
  }

  // PHYSIQUE : Lecture de l'équipe
  // RETOUR : Objet Team
  function physiqueDonneesEquipe($reference)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM teams
                        WHERE reference = "' . $reference . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Team à partir des données remontées de la bdd
    $equipe = Team::withData($data);

    $req->closeCursor();

    // Retour
    return $equipe;
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

  // PHYSIQUE : Lecture nombre de notifications
  // RETOUR : Booléen
  function physiqueNotificationExistante($categorie, $contenu)
  {
    // Initialisations
    $notificationExistante = false;

    // Requête
    global $bdd;

    if ($categorie == 'comments')
    {
      $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                          FROM notifications
                          WHERE category = "' . $categorie . '" AND content = "' . $contenu . '" AND date = ' . date('Ymd'));
    }
    else
    {
      $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                          FROM notifications
                          WHERE category = "' . $categorie . '" AND content = "' . $contenu . '"');
    }

    $data = $req->fetch();

    if ($data['nombreNotifications'] > 0)
      $notificationExistante = true;

    $req->closeCursor();

    // Retour
    return $notificationExistante;
  }

  // PHYSIQUE : Lecture de la limite d'un succès
  // RETOUR : Limite du succès
  function physiqueLimiteSucces($reference)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM success
                        WHERE reference = "' . $reference . '"');

    $data = $req->fetch();

    $limite = $data['limit_success'];

    $req->closeCursor();

    // Retour
    return $limite;
  }

  // PHYSIQUE : Lecture ancienne valeur d'un succès utilisateur
  // RETOUR : Ancienne valeur du succès
  function physiqueAncienneValeurSucces($reference, $identifiant)
  {
    // Initialisations
    $ancienneValeur = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM success_users
                        WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $ancienneValeur = $data['value'];

    $req->closeCursor();

    // Retour
    return $ancienneValeur;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle notification
  // RETOUR : Aucun
  function physiqueInsertionNotification($notification)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO notifications(author,
                                                    date,
                                                    time,
                                                    category,
                                                    content,
                                                    to_delete)
                                            VALUES(:author,
                                                   :date,
                                                   :time,
                                                   :category,
                                                   :content,
                                                   :to_delete)');

    $req->execute($notification);

    $req->closeCursor();
  }

  // PHYSIQUE : Insertion valeur succès utilisateur
  // RETOUR : Aucun
  function physiqueInsertionSuccesUser($succesUser)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO success_users(reference,
                                                    identifiant,
                                                    value)
                                            VALUES(:reference,
                                                   :identifiant,
                                                   :value)');

    $req->execute($succesUser);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour valeur succès utilisateur
  // RETOUR : Aucun
  function physiqueUpdateSuccesUser($reference, $identifiant, $value)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE success_users
                          SET value = :value
                          WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'value' => $value
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour expérience utilisateur
  // RETOUR : Aucun
  function physiqueUpdateExperienceUser($identifiant, $experience)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET experience = :experience
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'experience' => $experience
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour statut notification
  // RETOUR : Aucun
  function physiqueUpdateNotification($categorie, $contenu, $toDelete)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE notifications
                          SET to_delete = :to_delete
                          WHERE category = "' . $categorie . '" AND content = "' . $contenu . '"');

    $req->execute(array(
      'to_delete' => $toDelete
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression d'une notification
  // RETOUR : Aucun
  function physiqueDeleteNotification($categorie, $contenu)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM notifications
                       WHERE category = "' . $categorie . '" AND content = "' . $contenu . '"');
  }

  // PHYSIQUE : Suppression d'une valeur succès utilisateur
  // RETOUR : Aucun
  function physiqueDeleteSuccesUser($reference, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM success_users
                       WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
  }
?>
