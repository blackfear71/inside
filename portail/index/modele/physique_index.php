<?php
  include_once('includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture données utilisateur
  // RETOUR : Objet Profile
  function physiqueUser($identifiant)
  {
    // Initialisations
    $user = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);
    }

    $req->closeCursor();

    // Retour
    return $user;
  }

  // PHYSIQUE : Lecture mot de passe utilisateur
  // RETOUR : Mot de passe crypté
  function physiquePassword($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $dataUser = array('password' => $data['password'],
                      'salt'     => $data['salt']);

    $req->closeCursor();

    // Retour
    return $dataUser;
  }

  // PHYSIQUE : Lecture préférences utilisateur
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
    $preference = Preferences::withData($data);

    $req->closeCursor();

    // Retour
    return $preference;
  }

  // PHYSIQUE : Lecture du nombre d'utilisateurs existants
  // RETOUR : Booléen
  function physiqueTrigrammeUnique($identifiant)
  {
    // Initialisations
    $isUnique = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreUsers
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreUsers'] > 0)
      $isUnique = false;

    $req->closeCursor();

    // Retour
    return $isUnique;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvel utilisateur
  // RETOUR : Aucun
  function physiqueInsertionUser($user)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO users(identifiant,
                                            salt,
                                            password,
                                            ping,
                                            status,
                                            pseudo,
                                            avatar,
                                            email,
                                            anniversary,
                                            experience,
                                            expenses)
                                    VALUES(:identifiant,
                                           :salt,
                                           :password,
                                           :ping,
                                           :status,
                                           :pseudo,
                                           :avatar,
                                           :email,
                                           :anniversary,
                                           :experience,
                                           :expenses)');

    $req->execute($user);

    $req->closeCursor();
  }

  // PHYSIQUE : Insertion préférence nouvel utilisateur
  // RETOUR : Aucun
  function physiqueInsertionPreferences($preferences)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO preferences(identifiant,
                                                  ref_theme,
                                                  init_chat,
                                                  celsius,
                                                  view_movie_house,
                                                  categories_movie_house,
                                                  view_the_box,
                                                  view_notifications,
                                                  manage_calendars)
                                          VALUES(:identifiant,
                                                 :ref_theme,
                                                 :init_chat,
                                                 :celsius,
                                                 :view_movie_house,
                                                 :categories_movie_house,
                                                 :view_the_box,
                                                 :view_notifications,
                                                 :manage_calendars)');

    $req->execute($preferences);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour alerte
  // RETOUR : Aucun
  function physiqueUpdateStatut($statut, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET status = :status
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'status' => $statut
    ));

    $req->closeCursor();
  }
?>
