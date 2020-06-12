<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des informations utilisateur
  // RETOUR : Pseudo utilisateur
  function physiquePseudoUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $pseudo = $data['pseudo'];

    $req->closeCursor();

    // Retour
    return $pseudo;
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

  // PHYSIQUE : Lecture anniversaires utilisateurs
  // RETOUR : Pseudos
  function physiqueNewsAnniversaires()
  {
    // Initialisations
    $anniversaires = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, anniversary
                        FROM users
                        WHERE SUBSTR(anniversary, 5, 4) = "' . date("md") . '"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($anniversaires, $data['pseudo']);
    }

    $req->closeCursor();

    // Retour
    return $anniversaires;
  }

  // PHYSIQUE : Lecture réservation restaurant
  // RETOUR : Id restaurant
  function physiqueRestaurantReserved()
  {
    // Initialisations
    $idRestaurant = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM food_advisor_choices
                        WHERE date = "' . date("Ymd") . '" AND reserved = "Y"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $idRestaurant = $data['id_restaurant'];

    $req->closeCursor();

    // Retour
    return $idRestaurant;
  }

  // PHYSIQUE : Lecture nom restaurant
  // RETOUR : Nom restaurant
  function physiqueNomRestaurant($idRestaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM food_advisor_restaurants
                        WHERE id = ' . $idRestaurant);

    $data = $req->fetch();

    $nomRestaurant = $data['name'];

    $req->closeCursor();

    // Retour
    return $nomRestaurant;
  }

  // PHYSIQUE : Lecture vote utilisateur
  // RETOUR : Booléen
  function physiqueVoteUser($identifiant)
  {
    // Initialisations
    $voted = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM food_advisor_users
                        WHERE date = "' . date("Ymd") . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $voted = true;

    $req->closeCursor();

    // Retour
    return $voted;
  }

  // PHYSIQUE : Lecture présence gâteau
  // RETOUR : Booléen
  function physiqueGateauSemainePresent()
  {
    // Initialisations
    $present = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM cooking_box
                        WHERE week = "' . date('W') . '" AND year = "' . date('Y') . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $present = true;

    $req->closeCursor();

    // Retour
    return $present;
  }

  // PHYSIQUE : Lecture gâteau de la semaine
  // RETOUR : Objet WeekCake
  function physiqueGateauSemaine()
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM cooking_box
                        WHERE week = "' . date('W') . '" AND year = "' . date('Y') . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Preferences à partir des données remontées de la bdd
    $gateau = WeekCake::withData($data);

    $req->closeCursor();

    // Retour
    return $gateau;
  }

  // PHYSIQUE : Lecture dernière phrase culte
  // RETOUR : Objet Collector
  function physiqueDernierCollector()
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM collector
                        WHERE type_collector = "T"
                        ORDER BY date_add DESC, id DESC
                        LIMIT 1');

    $data = $req->fetch();

    // Instanciation d'un objet Preferences à partir des données remontées de la bdd
    $collector = Collector::withData($data);

    $req->closeCursor();

    // Retour
    return $collector;
  }

  // PHYSIQUE : Lecture position phrase culte
  // RETOUR : Position phrase culte
  function physiquePositionCollector($idCollector)
  {
    // Initialisations
    $position = 1;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, date_collector
                        FROM collector
                        ORDER BY date_collector DESC, id DESC');

    while ($data = $req->fetch())
    {
      if ($data['id'] == $idCollector)
        break;
      else
        $position++;
    }

    $req->closeCursor();

    // Retour
    return $position;
  }

  // PHYSIQUE : Lecture dernier film ajouté
  // RETOUR : Objet Movie
  function physiqueDernierFilm()
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house
                        WHERE to_delete != "Y"
                        ORDER BY date_add DESC, id DESC
                        LIMIT 1');

    $data = $req->fetch();

    // Instanciation d'un objet Preferences à partir des données remontées de la bdd
    $film = Movie::withData($data);

    $req->closeCursor();

    // Retour
    return $film;
  }

  // PHYSIQUE : Lecture film sortie cinéma
  // RETOUR : Objet Movie
  function physiqueSortieFilm()
  {
    // Initialisations
    $film = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM movie_house
                        WHERE to_delete != "Y" AND date_doodle >= "' . date("Ymd") . '"
                        ORDER BY date_doodle ASC, id ASC
                        LIMIT 1');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
    {
      // Instanciation d'un objet Preferences à partir des données remontées de la bdd
      $film = Movie::withData($data);
    }

    $req->closeCursor();

    // Retour
    return $film;
  }

  // PHYSIQUE : Lecture missions
  // RETOUR : Objet Mission
  function physiqueMissions($date1, $date2)
  {
    // Initialisations
    $listeMissions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE date_deb <= "' . $date1 . '" AND date_fin >= "' . $date2 . '"
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

  // PHYSIQUE : Lecture des participants d'une mission
  // RETOUR : Liste des utilisateurs
  function physiqueUsersMission($idMission)
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT identifiant
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . '
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Récupération des identifiants
      $user = array('identifiant' => $data['identifiant'],
                    'pseudo'      => '',
                    'total'       => 0,
                    'rank'        => 0
                   );

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des informations utilisateur de la mission
  // RETOUR : Total utilisateur
  function physiqueTotalUser($idMission, $identifiant)
  {
    // Initialisations
    $totalMission = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . ' AND identifiant = "' . $identifiant . '"');

    while ($data = $req->fetch())
    {
      $totalMission += $data['avancement'];
    }

    $req->closeCursor();

    // Retour
    return $totalMission;
  }
?>
