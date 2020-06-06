<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des utilisateurs inscrits
  // RETOUR : Liste des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar
                        FROM users
                        WHERE identifiant != "admin" AND status != "D"
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

  // PHYSIQUE : Lecture restaurants ouverts par lieu
  // RETOUR : Liste restaurants
  function physiqueRestaurantsOuvertsParLieux($lieu)
  {
    // Initialisations
    $restaurantsParLieux = array();
    $availableDay        = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM food_advisor_restaurants
                        WHERE location = "' . $lieu . '"
                        ORDER BY name ASC');

    while ($data = $req->fetch())
    {
      // Vérification restaurant ouvert ce jour
      $explodedOpened = explode(";", $data['opened']);

      foreach ($explodedOpened as $keyOpened => $opened)
      {
        if (!empty($opened))
        {
          if (date('N') == $keyOpened + 1 AND $opened == "N")
          {
            $availableDay = false;
            break;
          }
        }
      }

      // Récupération des données si ouvert
      if ($availableDay == true)
      {
        // Instanciation d'un objet Restaurant à partir des données remontées de la bdd
        $myRestaurant = Restaurant::withData($data);

        $myRestaurant->setMin_price(str_replace('.', ',', $myRestaurant->getMin_price()));
        $myRestaurant->setMax_price(str_replace('.', ',', $myRestaurant->getMax_price()));

        // On ajoute la ligne au tableau
        array_push($restaurantsParLieux, $myRestaurant);
      }
    }

    $req->closeCursor();

    // Retour
    return $restaurantsParLieux;
  }

  // PHYSIQUE : Lecture bande à part
  // RETOUR : Liste identifiants
  function physiqueIdentifiantsSolos()
  {
    // Initialisations
    $identifiantsSolos = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM food_advisor_users
                        WHERE id_restaurant = 0 AND date = "' . date('Ymd') . '"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($identifiantsSolos, $data['identifiant']);
    }

    $req->closeCursor();

    // Retour
    return $identifiantsSolos;
  }

  // PHYSIQUE : Lecture nombre de propositions d'un utilisateur
  // RETOUR : Nombre de propositions
  function physiqueNombrePropositions($identifiant)
  {
    // Initialisations
    $nombrePropositions = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM food_advisor_users
                        WHERE date = "' . date('Ymd') . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $nombrePropositions = $data['nombreLignes'];

    $req->closeCursor();

    // Retour
    return $nombrePropositions;
  }

  // PHYSIQUE : Lecture choix semaine existants
  // RETOUR : Booléen
  function physiqueChoixSemainePresents($date1, $date2)
  {
    // Initialisations
    $exist = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM food_advisor_choices
                        WHERE date >= "' . $date1 . '" AND date <= "' . $date2 . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $exist = true;

    $req->closeCursor();

    // Retour
    return $exist;
  }

  // PHYSIQUE : Lecture choix à date
  // RETOUR : Objet Proposition
  function physiqueDonneesResume($date)
  {
    // Initialisations
    $resume = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM food_advisor_choices
                        WHERE date = "' . $date . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'])
    {
      // Instanciation d'un objet Proposition à partir des données remontées de la bdd
      $resume = Proposition::withData($data);
    }

    $req->closeCursor();

    // Retour
    return $resume;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Annulation réservation
  // RETOUR : Aucun
  function physiqueAnnulationReservation($idRestaurant, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE food_advisor_choices
                          SET reserved      = :reserved
                          WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . date('Ymd') . '" AND caller = "' . $identifiant . '"');

    $req->execute(array(
      'reserved' => 'N'
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression choix bande à part
  // RETOUR : Aucun
  function physiqueDeleteSolo($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_users
                       WHERE id_restaurant = 0 AND date = "' . date('Ymd') . '" AND identifiant = "' . $identifiant . '"');
  }

  // PHYSIQUE : Suppression choix d'un restaurant
  // RETOUR : Aucun
  function physiqueDeleteComplete($idRestaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_users
                       WHERE date = "' . date('Ymd') . '" AND id_restaurant = "' . $idRestaurant . '"');
  }
?>
