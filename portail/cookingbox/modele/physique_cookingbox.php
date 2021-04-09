<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Vérification présence semaine et lecture des données d'une semaine
  // RETOUR : Objet WeekCake
  function physiqueSemaineGateau($semaine, $annee)
  {
    // Initialisations
    $semaineGateau = new WeekCake();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreGateauxSemaine
                        FROM cooking_box
                        WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

    $data = $req->fetch();

    if ($data['nombreGateauxSemaine'] > 0)
    {
      // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
      $semaineGateau = WeekCake::withData($data);
    }

    $req->closeCursor();

    // Retour
    return $semaineGateau;
  }

  // PHYSIQUE : Lecture des données d'un utilisateur
  // RETOUR : Objet Profile
  function physiqueUser($identifiant)
  {
    // Initialisations
    $user = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar, COUNT(*) AS nombreUser
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    if ($data['nombreUser'] > 0)
      $user = Profile::withData($data);

    $req->closeCursor();

    // Retour
    return $user;
  }

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
                        WHERE identifiant != "admin" AND status != "I" AND status != "D"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // Création tableau de correspondance identifiant / pseudo / avatar
      $listeUsers[$user->getIdentifiant()] = array('pseudo' => $user->getPseudo(),
                                                   'avatar' => $user->getAvatar()
                                                  );
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des recettes saisissables d'un utilisateur
  // RETOUR : Liste des semaines par années
  function physiqueSemainesGateauUser($identifiant)
  {
    // Initialisations
    $listeSemainesParAnnees = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM cooking_box
                        WHERE identifiant = "' . $identifiant . '" AND name = "" AND picture = ""  AND (year < ' . date('Y') . ' OR (year = ' . date('Y') . ' AND week <= ' . date('W') . '))
                        ORDER BY year DESC, week DESC');

    while ($data = $req->fetch())
    {
      // Si l'année n'existe pas on la créé
      if (!isset($listeSemainesParAnnees[$data['year']]))
        $listeSemainesParAnnees[$data['year']] = array();

      // On ajoute la ligne au tableau
      array_push($listeSemainesParAnnees[$data['year']], formatWeekForDisplay($data['week']));
    }

    $req->closeCursor();

    // Retour
    return $listeSemainesParAnnees;
  }

  // PHYSIQUE : Lecture semaine et recette existant
  // RETOUR : Indicateurs semaine et recette existante
  function physiqueSemaineExistante($semaine, $annee)
  {
    // Initialisations
    $semaineExistante = array('exist'       => false,
                              'identifiant' => '',
                              'cooked'      => 'N'
                             );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreRecettes
                        FROM cooking_box
                        WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

    $data = $req->fetch();

    if ($data['nombreRecettes'] > 0)
    {
      $semaineExistante['exist']       = true;
      $semaineExistante['identifiant'] = $data['identifiant'];
      $semaineExistante['cooked']      = $data['cooked'];
    }

    $req->closeCursor();

    // Retour
    return $semaineExistante;
  }

  // PHYSIQUE : Lecture nombre de lignes existantes pour une année
  // RETOUR : Booléen
  function physiqueAnneeExistante($annee)
  {
    // Initialisations
    $anneeExistante = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM cooking_box
                        WHERE year = "' . $annee . '" AND name != "" AND picture != ""');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $anneeExistante = true;

    $req->closeCursor();

    // Retour
    return $anneeExistante;
  }

  // PHYSIQUE : Lecture des années existantes
  // RETOUR : Liste des années
  function physiqueOnglets()
  {
    // Initialisations
    $onglets = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT year
                        FROM cooking_box
                        WHERE name != "" AND picture != ""
                        ORDER BY year DESC');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($onglets, $data['year']);
    }

    $req->closeCursor();

    // Retour
    return $onglets;
  }

  // PHYSIQUE : Lecture des recettes
  // RETOUR : Liste des recettes
  function physiqueRecettes($annee)
  {
    // Initialisations
    $listeRecettes = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM cooking_box
                        WHERE year = "' . $annee . '" AND name != "" AND picture != ""
                        ORDER BY week DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
      $recette = WeekCake::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeRecettes, $recette);
    }

    $req->closeCursor();

    // Retour
    return $listeRecettes;
  }

  // PHYSIQUE : Lecture recette
  // RETOUR : Objet Collector
  function physiqueRecette($semaine, $annee)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM cooking_box
                        WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

    $data = $req->fetch();

    // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
    $recette = WeekCake::withData($data);

    $req->closeCursor();

    // Retour
    return $recette;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle semaine de gâteau pour un utilisateur
  // RETOUR : Aucun
  function physiqueInsertionSemaineGateau($cooking)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO cooking_box(identifiant,
                                                  week,
                                                  year,
                                                  cooked,
                                                  name,
                                                  picture,
                                                  ingredients,
                                                  recipe,
                                                  tips)
                                          VALUES(:identifiant,
                                                 :week,
                                                 :year,
                                                 :cooked,
                                                 :name,
                                                 :picture,
                                                 :ingredients,
                                                 :recipe,
                                                 :tips)');

    $req->execute($cooking);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour d'une semaine de gâteau pour un utilisateur
  // RETOUR : Aucun
  function physiqueUpdateSemaineGateau($semaine, $annee, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE cooking_box
                          SET identifiant = :identifiant
                          WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

    $req->execute(array(
      'identifiant' => $identifiant
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Validation d'une semaine de gâteau pour un utilisateur
  // RETOUR : Aucun
  function physiqueUpdateStatusSemaineGateau($cooked, $semaine, $annee)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE cooking_box
                          SET cooked = :cooked
                          WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

    $req->execute(array(
      'cooked' => $cooked
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour d'une recette
  // RETOUR : Aucun
  function physiqueUpdateRecette($semaine, $annee, $identifiant, $recette)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE cooking_box
                          SET name        = :name,
                              picture     = :picture,
                              ingredients = :ingredients,
                              recipe      = :recipe,
                              tips        = :tips
                          WHERE week = "' . $semaine . '" AND year = "' . $annee . '" AND identifiant = "' . $identifiant . '"');

    $req->execute($recette);

    $req->closeCursor();
  }

  // PHYSIQUE : Réinitialisation d'une recette
  // RETOUR : Aucun
  function physiqueResetRecette($semaine, $annee, $reinitialisationRecette)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE cooking_box
                          SET name        = :name,
                              picture     = :picture,
                              ingredients = :ingredients,
                              recipe      = :recipe,
                              tips        = :tips
                          WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

    $req->execute($reinitialisationRecette);

    $req->closeCursor();
  }
?>
