<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture profil
  // RETOUR : Objet Profile
  function physiqueProfil($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    $profil = Profile::withData($data);

    $req->closeCursor();

    // Retour
    return $profil;
  }

  // PHYSIQUE : Lecture du nombre de films ajoutés
  // RETOUR : Nombre de films ajoutés
  function physiqueFilmsAjoutesUser($identifiant)
  {
    // Initialisations
    $nombreAjouts = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreAjouts
                        FROM movie_house
                        WHERE identifiant_add = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreAjouts = $data['nombreAjouts'];

    $req->closeCursor();

    // Retour
    return $nombreAjouts;
  }

  // PHYSIQUE : Lecture du nombre de commentaires de films ajoutés
  // RETOUR : Nombre de commentaires ajoutés
  function physiqueCommentairesFilmsUser($identifiant)
  {
    // Initialisations
    $nombreComments = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreComments
                        FROM movie_house_comments
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreComments = $data['nombreComments'];

    $req->closeCursor();

    // Retour
    return $nombreComments;
  }

  // PHYSIQUE : Lecture du nombre de phrases cultes ajoutées
  // RETOUR : Nombre de phrases cultes ajoutés
  function physiqueCollectorAjoutesUser($identifiant)
  {
    // Initialisations
    $nombreCollector = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                        FROM collector
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreCollector = $data['nombreCollector'];

    $req->closeCursor();

    // Retour
    return $nombreCollector;
  }

  // PHYSIQUE : Lecture du nombre de réservations de restaurants
  // RETOUR : Nombre de réservations
  function physiqueReservationsUser($identifiant)
  {
    // Initialisations
    $nombreReservations = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreReservations
                        FROM food_advisor_choices
                        WHERE caller = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreReservations = $data['nombreReservations'];

    $req->closeCursor();

    // Retour
    return $nombreReservations;
  }

  // PHYSIQUE : Lecture du nombre de gâteaux de la semaine
  // RETOUR : Nombre de gâteaux de la semaine
  function physiqueGateauxSemaineUser($identifiant)
  {
    // Initialisations
    $nombreGateauxSemaine = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreGateauxSemaine
                        FROM cooking_box
                        WHERE identifiant = "' . $identifiant . '" AND cooked = "Y"');

    $data = $req->fetch();

    $nombreGateauxSemaine = $data['nombreGateauxSemaine'];

    $req->closeCursor();

    // Retour
    return $nombreGateauxSemaine;
  }

  // PHYSIQUE : Lecture du nombre de recettes partagées
  // RETOUR : Nombre de recettes partagées
  function physiqueRecettesUser($identifiant)
  {
    // Initialisations
    $nombreRecettes = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreRecettes
                        FROM cooking_box
                        WHERE identifiant = "' . $identifiant . '" AND name != "" AND picture != ""');

    $data = $req->fetch();

    $nombreRecettes = $data['nombreRecettes'];

    $req->closeCursor();

    // Retour
    return $nombreRecettes;
  }

  // PHYSIQUE : Lecture du bilan des dépenses d'un utilisateur
  // RETOUR : Bilan des dépenses de l'utilisateur
  function physiqueBilanDepensesUser($identifiant)
  {
    // Initialisations
    $bilanUser = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, expenses
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $bilanUser = $data['expenses'];

    $req->closeCursor();

    // Retour
    return $bilanUser;
  }

  // PHYSIQUE : Lecture du nombre d'idées #TheBox soumises d'un utilisateur
  // RETOUR : Nombre d'idées soumises de l'utilisateur
  function physiqueTheBoxUser($identifiant)
  {
    // Initialisations
    $nombreTheBox = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreTheBox
                        FROM ideas
                        WHERE author = "' . $identifiant . '"');

    $data = $req->fetch();

    $nombreTheBox = $data['nombreTheBox'];

    $req->closeCursor();

    // Retour
    return $nombreTheBox;
  }

  // PHYSIQUE : Lecture du nombre de bugs / évolutions soumis d'un utilisateur
  // RETOUR : Nombre de bugs / évolutions soumis de l'utilisateur
  function physiqueBugsEvolutionsSoumisUser($identifiant, $type)
  {
    // Initialisations
    $nombreSoumis = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreSoumis
                        FROM bugs
                        WHERE author = "' . $identifiant . '" AND type = "' . $type . '"');

    $data = $req->fetch();

    $nombreSoumis = $data['nombreSoumis'];

    $req->closeCursor();

    // Retour
    return $nombreSoumis;
  }

  // PHYSIQUE : Lecture préférences
  // RETOUR : Objet Preferences
  function physiquePreferences($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM preferences
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Preferences à partir des données remontées de la bdd
    $preferences = Preferences::withData($data);

    $req->closeCursor();

    // Retour
    return $preferences;
  }

  // PHYSIQUE : Lecture avatar utilisateur
  // RETOUR : Avatar
  function physiqueAvatarUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT identifiant, avatar
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $avatar = $data['avatar'];

    $req->closeCursor();

    // Retour
    return $avatar;
  }

  // PHYSIQUE : Lecture données mot de passe utilisateur
  // RETOUR : Données mot de passe
  function physiqueDonneesPasswordUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, salt, password
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $crypt = array('salt' => $data['salt'], 'password' => $data['password']);

    $req->closeCursor();

    // Retour
    return $crypt;
  }

  // PHYSIQUE : Lecture des utilisateurs
  // RETOUR : Tableau des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, experience
                        FROM users
                        WHERE identifiant != "admin" AND status != "I"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des succès
  // RETOUR : Liste des succès
  function physiqueListeSuccess()
  {
    // Initialisation
    $listeSuccess = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM success
                        ORDER BY level ASC, order_success ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Success à partir des données remontées de la bdd
      $success = Success::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeSuccess, $success);
    }

    $req->closeCursor();

    // Retour
    return $listeSuccess;
  }

  // PHYSIQUE : Lecture des succès de l'utilisateur
  // RETOUR : Valeur succès
  function physiqueSuccessUser($reference, $identifiant)
  {
    // Initialisation
    $value = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM success_users
                        WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $value = $data['value'];

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Lecture des succès des utilisateurs
  // RETOUR : Liste des utilisateurs
  function physiqueSuccessUsers($reference, $limite, $missionTermineeOuAutre, $tableauUsers)
  {
    // Initialisation
    $listeRangSuccess = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM success_users
                        WHERE reference = "' . $reference . '"
                        ORDER BY value DESC');

    while ($data = $req->fetch())
    {
      if ($missionTermineeOuAutre == true)
      {
        // Vérification que l'utilisateur a débloqué le succès pour l'ajouter
        if ($data['value'] >= $limite)
        {
          // Génération d'un objet Classement
          $rangSuccess = new Classement();

          $rangSuccess->setIdentifiant($data['identifiant']);
          $rangSuccess->setPseudo($tableauUsers[$data['identifiant']]['pseudo']);
          $rangSuccess->setAvatar($tableauUsers[$data['identifiant']]['avatar']);
          $rangSuccess->setValue($data['value']);

          // On ajoute la ligne au tableau
          array_push($listeRangSuccess, $rangSuccess);
        }
      }
    }

    $req->closeCursor();

    // Retour
    return $listeRangSuccess;
  }

  // PHYSIQUE : Lecture date de fin de mission
  // RETOUR : Date
  function physiqueDateFinMission($reference)
  {
    // Initialisation
    $dateFinMission = '';

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE reference = "' . $reference . '"');

    $data = $req->fetch();

    $dateFinMission = $data['date_fin'];

    $req->closeCursor();

    // Retour
    return $dateFinMission;
  }

  // PHYSIQUE : Lecture liste des thèmes
  // RETOUR : Liste des thèmes
  function physiqueThemes($type, $niveau)
  {
    // Initialisation
    $listeThemes = array();

    // Requête
    global $bdd;

    if ($type == 'U')
    {
      $req = $bdd->query('SELECT *
                          FROM themes
                          WHERE type = "' . $type . '" AND level <= ' . $niveau . '
                          ORDER BY CAST(level AS UNSIGNED) ASC');
    }
    else
    {
      $req = $bdd->query('SELECT *
                          FROM themes
                          WHERE type = "' . $type . '" AND date_deb <= ' . date('Ymd') . '
                          ORDER BY date_deb DESC');
    }

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Theme à partir des données remontées de la bdd
      $theme = Theme::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeThemes, $theme);
    }

    $req->closeCursor();

    // Retour
    return $listeThemes;
  }

  // PHYSIQUE : Détermination thème mission en cours
  // RETOUR : Booléen
  function physiqueThemeMission()
  {
    // Initialisation
    $isThemeMission = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM themes
                        WHERE type = "M" AND date_deb <= ' . date('Ymd') . ' AND date_fin >= ' . date('Ymd') . '
                        ORDER BY id ASC');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $isThemeMission = true;

    $req->closeCursor();

    // Retour
    return $isThemeMission;
  }

  // PHYSIQUE : Lecture référence thème
  // RETOUR : Référence thème
  function physiqueReferenceTheme($idTheme)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM themes
                        WHERE id = ' . $idTheme);

    $data = $req->fetch();

    $referenceTheme = $data['reference'];

    $req->closeCursor();

    // Retour
    return $referenceTheme;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour avatar
  // RETOUR : Aucun
  function physiqueUpdateAvatarUser($identifiant, $avatar)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET avatar = :avatar
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'avatar' => $avatar
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour utilisateur
  // RETOUR : Aucun
  function physiqueUpdateUser($user, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET pseudo      = :pseudo,
                              email       = :email,
                              anniversary = :anniversary
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute($user);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour préférences
  // RETOUR : Aucun
  function physiqueUpdatePreferences($preferences, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE preferences
                          SET init_chat              = :init_chat,
                              celsius                = :celsius,
                              view_movie_house       = :view_movie_house,
                              categories_movie_house = :categories_movie_house,
                              view_the_box           = :view_the_box,
                              view_notifications     = :view_notifications
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute($preferences);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour mot de passe
  // RETOUR : Aucun
  function physiqueUpdatePasswordUser($salt, $password, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET salt     = :salt,
                              password = :password
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'salt'     => $salt,
      'password' => $password
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour statut utilisateur
  // RETOUR : Aucun
  function physiqueUpdateStatusUser($identifiant, $status)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET status = :status
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'status' => $status
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour statut utilisateur
  // RETOUR : Aucun
  function physiqueUpdateTheme($identifiant, $referenceTheme)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE preferences
                          SET ref_theme = :ref_theme
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'ref_theme' => $referenceTheme
    ));

    $req->closeCursor();
  }
?>
