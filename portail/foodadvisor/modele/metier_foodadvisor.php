<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/restaurants.php');

  // METIER : Conversion du tableau d'objet des restaurants en tableau simple pour JSON
  // RETOUR : Tableau des restaurants par lieu
  function convertForJson($listeRestaurants)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listeRestaurantsAConvertir = array();

    foreach ($listeRestaurants as $keyLieu => $restaurantsParLieux)
    {
      $listeParLieu = array();

      foreach ($restaurantsParLieux as $restaurant)
      {
        $restaurantAConvertir = array('id'   => $restaurant->getId(),
                                      'name' => $restaurant->getName()
                                     );
        array_push($listeParLieu, $restaurantAConvertir);
      }

      $listeRestaurantsAConvertir[$keyLieu] = $listeParLieu;
    }

    return $listeRestaurantsAConvertir;
  }

  // METIER : Détermine la présence des boutons d'action
  // RETOUR : Booléen
  function getActions($propositions, $myChoices, $isSolo, $isReserved, $user)
  {
    $actions = array("determiner"       => true,
                     "solo"             => true,
                     "choix"            => true,
                     "reserver"         => true,
                     "annuler_reserver" => false
                    );

    // Contrôle date et heure
    if (date("N") > 5 OR date("H") >= 13)
    {
      $actions["determiner"]       = false;
      $actions["solo"]             = false;
      $actions["choix"]            = false;
      $actions["reserver"]         = false;
      $actions["annuler_reserver"] = false;
    }

    // Contrôle propositions présentes (pour bouton détermination)
    if ($actions["determiner"] == true)
    {
      if (empty($propositions) OR empty($myChoices))
        $actions["determiner"] = false;
    }

    // Contrôle choix présents (pour bouton bande à part)
    if ($actions["solo"] == true)
    {
      if (!empty($myChoices))
        $actions["solo"] = false;
    }

    // Contrôle vote solo présent
    if ($actions["solo"] == true)
    {
      if ($isSolo == true)
        $actions["solo"] = false;
    }

    // Contrôle réservation effectuée
    if ($actions["reserver"] == true)
    {
      if (!empty($isReserved))
      {
        $actions["reserver"]   = false;
        $actions["determiner"] = false;
      }
    }

    // Contrôle réserveur pour annulation
    if ($actions["reserver"] == false)
    {
      if ($isReserved == $user AND date("N") <= 5 AND date("H") < 13)
        $actions["annuler_reserver"] = true;
    }

    return $actions;
  }

  // METIER : Récupère les choix du jour
  // RETOUR : Liste des choix du jour (tous)
  function getPropositions()
  {
    $listPropositions = array();

    global $bdd;

    $req1 = $bdd->query('SELECT DISTINCT id_restaurant FROM food_advisor_users WHERE id_restaurant != 0 AND date = "' . date("Ymd") . '"');
    while ($data1 = $req1->fetch())
    {
      $myProposition = Proposition::withData($data1);

      // Données restaurant
      $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $myProposition->getId_restaurant());
      $data2 = $req2->fetch();

      $myProposition->setName($data2['name']);
      $myProposition->setPicture($data2['picture']);
      $myProposition->setLocation($data2['location']);
      $myProposition->setTypes($data2['types']);
      $myProposition->setPhone($data2['phone']);
      $myProposition->setWebsite($data2['website']);
      $myProposition->setPlan($data2['plan']);
      $myProposition->setOpened($data2['opened']);
      $myProposition->setMin_price(str_replace('.', ',', $data2['min_price']));
      $myProposition->setMax_price(str_replace('.', ',', $data2['max_price']));

      $req2->closeCursor();

      // Nombre de participants
      $req3 = $bdd->query('SELECT COUNT(id_restaurant) AS nb_participants FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myProposition->getId_restaurant());
      $data3 = $req3->fetch();

      $myProposition->setNb_participants($data3['nb_participants']);

      $req3->closeCursor();

      // Proposition déterminée
      $req3 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myProposition->getId_restaurant());
      $data3 = $req3->fetch();

      if ($req3->rowCount() > 0)
      {
        $myProposition->setDetermined("Y");
        $myProposition->setCaller($data3['caller']);
        $myProposition->setReserved($data3['reserved']);

        // Récupération pseudo et avatar
        $req4 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myProposition->getCaller() . '"');
        $data4 = $req4->fetch();

        $myProposition->setPseudo($data4['pseudo']);
        $myProposition->setAvatar($data4['avatar']);

        $req4->closeCursor();
      }

      $req3->closeCursor();

      array_push($listPropositions, $myProposition);
    }
    $req1->closeCursor();

    // Tris
    if (!empty($listPropositions))
    {
      // Tri par nombre de participants pour affecter le classement
      foreach ($listPropositions as $proposition)
      {
        $tri_nb_participants[] = $proposition->getNb_participants();
      }

      array_multisort($tri_nb_participants, SORT_DESC, $listPropositions);

      // Affectation du classement
      $prevNb_particpants = 0;
      $currentClassement  = 0;

      foreach ($listPropositions as $proposition)
      {
        $currentNb_participants = $proposition->getNb_participants();

        if ($currentNb_participants != $prevNb_particpants)
        {
          $currentClassement += 1;
          $prevNb_particpants = $currentNb_participants;
        }

        // On enregistre le rang
        $proposition->setClassement($currentClassement);

        // Récupération détails proposition
        $proposition->setDetails(getDetailsProposition($proposition));
      }

      // Tri par détermination puis nombre de participants pour affichage
      $listPropositions = triPropositions($listPropositions);
    }

    return $listPropositions;
  }

  // METIER : Récupère les détails utilisateurs de la proposition
  // RETOUR : Tableau des détails
  function getDetailsProposition($proposition)
  {
    $details = array();

    global $bdd;

    $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $proposition->getId_restaurant() . ' ORDER BY identifiant ASC');
    while ($data1 = $req1->fetch())
    {
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $data1['identifiant'] . '"');
      $data2 = $req2->fetch();
      $pseudo      = $data2['pseudo'];
      $avatar      = $data2['avatar'];
      $req2->closeCursor();

      $detailsUser = array("identifiant" => $data1['identifiant'],
                           "pseudo"      => $pseudo,
                           "avatar"      => $avatar,
                           "transports"  => $data1['transports'],
                           "horaire"     => $data1['time'],
                           "menu"        => $data1['menu']
                          );

      array_push($details, $detailsUser);
    }
    $req1->closeCursor();

    return $details;
  }

  // METIER : Récupère les personnes faisant bande à part
  // RETOUR : Tableau des utilisateurs
  function getSolos()
  {
    global $bdd;

    $solos = array();

    $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant = 0 AND date = "' . date("Ymd") . '" ORDER BY identifiant ASC');
    while ($data1 = $req1->fetch())
    {
      $req2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $data1['identifiant'] . '"');
      $data2 = $req2->fetch();
      $mySolo = Profile::withData($data2);
      $req2->closeCursor();

      array_push($solos, $mySolo);
    }
    $req1->closeCursor();

    return $solos;
  }

  // METIER : Récupère les utilisateurs qui n'ont pas fait de propositions
  // RETOUR : Liste des utilisateurs
  function getNoPropositions()
  {
    global $bdd;

    $noPropositions = array();

    $req1 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" AND status != "D" ORDER BY identifiant ASC');
    while ($data1 = $req1->fetch())
    {
      $req2 = $bdd->query('SELECT * FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND identifiant = "' . $data1['identifiant'] . '"');
      $data2 = $req2->fetch();

      if ($req2->rowCount() == 0)
      {
        $myUser = Profile::withData($data1);
        array_push($noPropositions, $myUser);
      }

      $req2->closeCursor();
    }
    $req1->closeCursor();

    return $noPropositions;
  }

  // METIER : Vérification si réservé et retour identifiant
  // RETOUR : Identifiant réservation
  function getReserved($user)
  {
    $caller = "";

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '" AND reserved = "Y"');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
      $caller = $donnees['caller'];

    $reponse->closeCursor();

    return $caller;
  }

  // METIER : Contrôle la date et récupère un des restaurants pouvant être déterminé ce jour
  // RETOUR : Id restaurant déterminé
  function getRestaurantDetermined($propositions)
  {
    $control_ok    = true;
    $id_restaurant = NULL;

    // Contrôle date de détermination
    if (date("N") > 5)
    {
      $control_ok                                   = false;
      $_SESSION['alerts']['week_end_determination'] = true;
    }

    // Contrôle heure de détermination
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                                = false;
        $_SESSION['alerts']['heure_determination'] = true;
      }
    }

    // Détermination Id restaurant aléatoire
    if ($control_ok == true)
    {
      $id_restaurants = array();

      foreach ($propositions as $proposition)
      {
        if ($proposition->getClassement() == 1)
        {
          array_push($id_restaurants, $proposition->getId_restaurant());
        }
      }

      $id_restaurant = $id_restaurants[array_rand($id_restaurants, 1)];
    }

    return $id_restaurant;
  }

  // METIER : Récupère un des participants du jour n'ayant pas appelé dans la semaine
  // RETOUR : Participant pouvant appeler
  function getCallers($id_restaurant)
  {
    global $bdd;

    $caller = "";

    // Calcul des dates de la semaine
    $nb_jours_lundi    = 1 - date("N");
    $nb_jours_vendredi = 5 - date("N");
    $monday            = date("Ymd", strtotime('+' . $nb_jours_lundi . ' days'));
    $friday            = date("Ymd", strtotime('+' . $nb_jours_vendredi . ' days'));

    // Liste des participants du jour
    $listUsers = array();

    $req1 = $bdd->query('SELECT DISTINCT identifiant FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $id_restaurant . ' ORDER BY identifiant ASC');
    while ($data1 = $req1->fetch())
    {
      $req2 = $bdd->query('SELECT id, identifiant FROM users WHERE identifiant = "' . $data1['identifiant'] . '"');
      $data2 = $req2->fetch();
      $identifiant = $data2['identifiant'];
      $req2->closeCursor();

      array_push($listUsers, $identifiant);
    }
    $req1->closeCursor();

    // Liste des appelants de la semaine
    $listCallersWeek = array();

    $req3 = $bdd->query('SELECT DISTINCT caller FROM food_advisor_choices WHERE date != "' . date("Ymd") . '"
                                                                            AND date >= "' . $monday . '"
                                                                            AND date <= "' . $friday . '"
                                                                       ORDER BY caller ASC');
    while ($data3 = $req3->fetch())
    {
      array_push($listCallersWeek, $data3['caller']);
    }
    $req3->closeCursor();

    // On ne concerve que ceux qui n'ont pas appelé dans la liste des utilisateurs
    $listCallers = $listUsers;

    foreach ($listCallers as $key => $user)
    {
      foreach ($listCallersWeek as $callerWeek)
      {
        if ($user == $callerWeek)
        {
          unset($listCallers[$key]);
          break;
        }
      }
    }

    // Détermination appelant aléatoire parmi ceux restant, ou par défaut une des personnes du jour
    if (!empty($listCallers))
      $caller = $listCallers[array_rand($listCallers, 1)];
    else
      $caller = $listUsers[array_rand($listUsers, 1)];

    return $caller;
  }

  // METIER : Détermine celui qui réserve
  // RETOUR : Aucun
  function setDetermination($propositions, $id_restaurant, $caller)
  {
    if (!empty($propositions))
    {
      global $bdd;

      // Contrôle si choix déjà existant
      $existant = false;

      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $existant   = true;
        $old_caller = $data1['caller'];
        $id         = $data1['id'];
      }

      $req1->closeCursor();

      // Mise à jour ou insertion
      if ($existant == true)
      {
        $choice = array('id_restaurant' => $id_restaurant,
                        'caller'        => $caller
                       );

        $req2 = $bdd->prepare('UPDATE food_advisor_choices SET id_restaurant = :id_restaurant,
                                                               caller        = :caller
                                                         WHERE id = ' . $id);
        $req2->execute($choice);
        $req2->closeCursor();

        // Génération succès (pour l'appelant si modifié)
        insertOrUpdateSuccesValue('star-chief', $old_caller, -1);
      }
      else
      {
        $choice = array('id_restaurant' => $id_restaurant,
                        'date'          => date("Ymd"),
                        'caller'        => $caller,
                        'reserved'      => "N"
                       );

        $req2 = $bdd->prepare('INSERT INTO food_advisor_choices(id_restaurant,
                                                                date,
                                                                caller,
                                                                reserved
                                                               )
                                                        VALUES(:id_restaurant,
                                                               :date,
                                                               :caller,
                                                               :reserved
                                                              )');
        $req2->execute($choice);
        $req2->closeCursor();
      }

      // Génération succès (pour le nouvel appelant)
      insertOrUpdateSuccesValue('star-chief', $caller, 1);
    }
  }

  // METIER : Insère un choix "bande à part"
  // RETOUR : Aucun
  function setSolo($myChoices, $isSolo, $user)
  {
    global $bdd;

    $control_ok  = true;

    // Contrôle déjà solo
    if ($isSolo == true)
      $control_ok = false;

    // Contrôle saisie possible en fonction des dates
    if ($control_ok == true)
    {
      if (date("N") > 5)
      {
        $control_ok                            = false;
        $_SESSION['alerts']['week_end_saisie'] = true;
      }
    }

    // Contrôle heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                       = false;
        $_SESSION['alerts']['heure_solo'] = true;
      }
    }

    // Contrôle choix saisis
    if ($control_ok == true)
    {
      if (!empty($myChoices))
      {
        $control_ok                       = false;
        $_SESSION['alerts']['choix_solo'] = true;
      }
    }

    // Insertion en base
    if ($control_ok == true)
    {
      // Tableau d'un choix
      $solo = array('id_restaurant' => 0,
                    'identifiant'   => $user,
                    'date'          => date("Ymd"),
                    'time'          => "",
                    'transports'    => "",
                    'menu'          => ";;;"
                   );

       $req = $bdd->prepare('INSERT INTO food_advisor_users(id_restaurant,
                                                            identifiant,
                                                            date,
                                                            time,
                                                            transports,
                                                            menu
                                                           )
                                                    VALUES(:id_restaurant,
                                                           :identifiant,
                                                           :date,
                                                           :time,
                                                           :transports,
                                                           :menu
                                                          )');
       $req->execute($solo);
       $req->closeCursor();
    }
  }

  // METIER : Supprime un choix "bande à part"
  // RETOUR : Aucun
  function deleteSolo($user)
  {
    global $bdd;

    $control_ok  = true;

    // Contrôle date
    if (date("N") > 5)
    {
      $control_ok                                 = false;
      $_SESSION['alerts']['week_end_delete'] = true;
    }

    // Contrôle heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                              = false;
        $_SESSION['alerts']['heure_suppression_solo'] = true;
      }
    }

    if ($control_ok == true)
      $reponse = $bdd->exec('DELETE FROM food_advisor_users WHERE id_restaurant = 0 AND date = "' . date("Ymd") . '" AND identifiant = "' . $user . '"');
  }

  // METIER : Insère ou met à jour une réservation
  // RETOUR : Aucun
  function insertReservation($id_restaurant, $caller)
  {
    global $bdd;

    $control_ok  = true;

    // Contrôle date
    if (date("N") > 5)
    {
      $control_ok                                 = false;
      $_SESSION['alerts']['week_end_reservation'] = true;
    }

    // Contrôle heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                              = false;
        $_SESSION['alerts']['heure_reservation'] = true;
      }
    }

    // Contrôle si choix déjà existant
    if ($control_ok == true)
    {
      $existant = false;
      $reserved = false;

      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $existant   = true;
        $old_caller = $data1['caller'];
        $id         = $data1['id'];

        if ($data1['reserved'] == "Y")
          $reserved = true;
      }

      $req1->closeCursor();

      // Mise à jour ou insertion si pas déjà réservé
      if ($reserved == false)
      {
        if ($existant == true)
        {
          $choice = array('id_restaurant' => $id_restaurant,
                          'caller'        => $caller,
                          'reserved'      => "Y"
                         );

          $req2 = $bdd->prepare('UPDATE food_advisor_choices SET id_restaurant = :id_restaurant,
                                                                 caller        = :caller,
                                                                 reserved      = :reserved
                                                           WHERE id = ' . $id);
          $req2->execute($choice);
          $req2->closeCursor();

          // Génération succès (pour l'appelant si modifié)
          insertOrUpdateSuccesValue('star-chief', $old_caller, -1);
        }
        else
        {
          $choice = array('id_restaurant' => $id_restaurant,
                          'date'          => date("Ymd"),
                          'caller'        => $caller,
                          'reserved'      => "Y"
                         );

          $req2 = $bdd->prepare('INSERT INTO food_advisor_choices(id_restaurant,
                                                                  date,
                                                                  caller,
                                                                  reserved
                                                                 )
                                                          VALUES(:id_restaurant,
                                                                 :date,
                                                                 :caller,
                                                                 :reserved
                                                                )');
          $req2->execute($choice);
          $req2->closeCursor();
        }

        // Génération succès (pour le nouvel appelant)
        insertOrUpdateSuccesValue('star-chief', $caller, 1);
      }
      else
        $_SESSION['alerts']['already_reserved'] = true;
    }
  }

  // METIER : Supprime une réservation
  // RETOUR : Aucun
  function deleteReservation($id_restaurant, $user)
  {
    global $bdd;

    $control_ok  = true;

    // Contrôle date
    if (date("N") > 5)
    {
      $control_ok                                 = false;
      $_SESSION['alerts']['week_end_reservation'] = true;
    }

    // Contrôle heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                                          = false;
        $_SESSION['alerts']['heure_suppression_reservation'] = true;
      }
    }

    // Annulation réservation
    if ($control_ok == true)
    {
      $choice = array('reserved' => "N");

      $reponse = $bdd->prepare('UPDATE food_advisor_choices SET reserved = :reserved
                                                          WHERE id_restaurant = ' . $id_restaurant . ' AND date = "' . date("Ymd") . '" AND caller = "' . $user . '"');
      $reponse->execute($choice);
      $reponse->closeCursor();
    }
  }

  // METIER : Récupère les choix de l'utilisateur
  // RETOUR : Liste des choix du jour (utilisateur)
  function getMyChoices($user)
  {
    $listChoices = array();

    global $bdd;

    // Récupération des choix
    $reponse1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant != 0 AND identifiant = "' . $user . '" AND date = "' . date("Ymd") . '" ORDER BY id ASC');
    while ($donnees1 = $reponse1->fetch())
    {
      $myChoice = Choix::withData($donnees1);

      // Données restaurant
      $reponse2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $myChoice->getId_restaurant());
      $donnees2 = $reponse2->fetch();

      $myChoice->setName($donnees2['name']);
      $myChoice->setPicture($donnees2['picture']);
      $myChoice->setLocation($donnees2['location']);
      $myChoice->setOpened($donnees2['opened']);

      $reponse2->closeCursor();

      array_push($listChoices, $myChoice);
    }
    $reponse1->closeCursor();

    return $listChoices;
  }

  // METIER : Détermine si l'utilisateur fait bande à part
  // RETOUR : Booléen
  function getSolo($user)
  {
    global $bdd;

    $solo = false;

    $reponse = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant = 0 AND date = "' . date("Ymd") . '" AND identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
      $solo = true;

    $reponse->closeCursor();

    return $solo;
  }

  // METIER : Récupère les choix de la semaine
  // RETOUR : Liste des choix de la semaine
  function getWeekChoices()
  {
    $listWeekChoices = array();
    $semaine         = array("lundi", "mardi", "mercredi", "jeudi", "vendredi");

    // Calcul des dates de la semaine
    $nb_jours_lundi    = 1 - date("N");
    $nb_jours_vendredi = 5 - date("N");
    $monday            = date("Ymd", strtotime('+' . $nb_jours_lundi . ' days'));
    $friday            = date("Ymd", strtotime('+' . $nb_jours_vendredi . ' days'));

    global $bdd;

    for ($i = $monday; $i <= $friday; $i++)
    {
      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . $i . '"');
      $data1 = $req1->fetch();

      $myWeekChoice = NULL;

      if ($req1->rowCount() > 0)
      {
        $myWeekChoice = Proposition::withData($data1);

        // Données restaurant
        $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $myWeekChoice->getId_restaurant());
        $data2 = $req2->fetch();

        $myWeekChoice->setName($data2['name']);
        $myWeekChoice->setPicture($data2['picture']);
        $myWeekChoice->setLocation($data2['location']);

        $req2->closeCursor();

        // Nombre de participants
        $req3 = $bdd->query('SELECT COUNT(id) AS nb_participants FROM food_advisor_users WHERE date = "' . $i . '" AND id_restaurant = ' . $myWeekChoice->getId_restaurant());
        $data3 = $req3->fetch();

        $myWeekChoice->setNb_participants($data3['nb_participants']);

        $req3->closeCursor();

        // Récupération pseudo et avatar
        $req4 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myWeekChoice->getCaller() . '"');
        $data4 = $req4->fetch();

        $myWeekChoice->setPseudo($data4['pseudo']);
        $myWeekChoice->setAvatar($data4['avatar']);

        $req4->closeCursor();
      }

      // Ajout au tableau
      $jour                             = $i - $monday;
      $listWeekChoices[$semaine[$jour]] = $myWeekChoice;
    }
    $req1->closeCursor();

    return $listWeekChoices;
  }

  // METIER : Insère un ou plusieurs choix utilisateur
  // RETOUR : Aucun
  function insertChoices($post, $isSolo, $user)
  {
    global $bdd;

    $max_choices = 5;
    $control_ok  = true;

    // Contrôle saisie possible en fonction des dates
    if (date("N") > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_saisie'] = true;
    }

    // Contrôle saisie possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                         = false;
        $_SESSION['alerts']['heure_saisie'] = true;
      }
    }

    // Contrôle bande à part
    if ($control_ok == true)
    {
      if ($isSolo == true)
      {
        $control_ok                        = false;
        $_SESSION['alerts']['solo_saisie'] = true;
      }
    }

    // Contrôle choix saisi en double
    if ($control_ok == true)
    {
      for ($i = 1; $i <= $max_choices; $i++)
      {
        if (isset($post['select_lieu'][$i]) AND !empty($post['select_lieu'][$i]) AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          for ($j = 1; $j <= $max_choices; $j++)
          {
            if (isset($post['select_lieu'][$j]) AND !empty($post['select_lieu'][$j]) AND isset($post['select_restaurant'][$j]) AND !empty($post['select_restaurant'][$j]))
            {
              if ($i != $j AND $post['select_lieu'][$i] == $post['select_lieu'][$j] AND $post['select_restaurant'][$i] == $post['select_restaurant'][$j])
              {
                $control_ok                         = false;
                $_SESSION['alerts']['wrong_choice'] = true;
                break;
              }
            }
          }
        }
      }
    }

    // Contrôle choix déjà existant
    if($control_ok == true)
    {
      for ($i = 1; $i <= $max_choices; $i++)
      {
        if (isset($post['select_lieu'][$i]) AND !empty($post['select_lieu'][$i]) AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant = "' . $post['select_restaurant'][$i] . '" AND identifiant = "' . $user . '" AND date = "' . date("Ymd") . '"');
          $data1 = $req1->fetch();

          if ($req1->rowCount() > 0)
          {
            $control_ok                                 = false;
            $_SESSION['alerts']['wrong_choice_already'] = true;
            break;
          }

          $req1->closeCursor();
        }
      }
    }

    // Récupération des données et insertion en base
    if ($control_ok == true)
    {
      for ($i = 1; $i <= $max_choices; $i++)
      {
        if (isset($post['select_lieu'][$i]) AND !empty($post['select_lieu'][$i]) AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          // Id restaurant
          $id_restaurant = $post['select_restaurant'][$i];

          // Identifiant utilisateur
          $identifiant   = $user;

          // Date de saisie
          $date          = date("Ymd");

          // Heure choisie
          if (isset($post['select_heures'][$i]) AND !empty($post['select_heures'][$i]) AND isset($post['select_minutes'][$i]) AND !empty($post['select_minutes'][$i]))
            $time        = $post['select_heures'][$i] . $post['select_minutes'][$i];
          else
            $time        = "";

          // Transports choisis
          $transports    = "";

          if (isset($post['checkbox_feet'][$i]) AND $post['checkbox_feet'][$i] == "F")
            $transports .= $post['checkbox_feet'][$i] . ';';

          if (isset($post['checkbox_bike'][$i]) AND $post['checkbox_bike'][$i] == "B")
            $transports .= $post['checkbox_bike'][$i] . ';';

          if (isset($post['checkbox_tram'][$i]) AND $post['checkbox_tram'][$i] == "T")
            $transports .= $post['checkbox_tram'][$i] . ';';

          if (isset($post['checkbox_car'][$i]) AND $post['checkbox_car'][$i] == "C")
            $transports .= $post['checkbox_car'][$i] . ';';

          // Menu saisi
          $menu          = "";

          if (!isset($post['saisie_entree'][$i]) AND !isset($post['saisie_plat'][$i]) AND !isset($post['saisie_dessert'][$i]))
            $menu       .= ";;;";
          else
          {
            $menu       .= str_replace(";", " ", $post['saisie_entree'][$i]) . ';';
            $menu       .= str_replace(";", " ", $post['saisie_plat'][$i]) . ';';
            $menu       .= str_replace(";", " ", $post['saisie_dessert'][$i]) . ';';
          }

          // Tableau d'un choix
          $choice = array('id_restaurant' => $id_restaurant,
                          'identifiant'   => $identifiant,
                          'date'          => $date,
                          'time'          => $time,
                          'transports'    => $transports,
                          'menu'          => $menu
                        );

          // On insère dans la table
          $req2 = $bdd->prepare('INSERT INTO food_advisor_users(id_restaurant,
                                                                identifiant,
                                                                date,
                                                                time,
                                                                transports,
                                                                menu
                                                              )
                                                        VALUES(:id_restaurant,
                                                               :identifiant,
                                                               :date,
                                                               :time,
                                                               :transports,
                                                               :menu
                                                              )');
          $req2->execute($choice);
          $req2->closeCursor();

          // Relance de la détermination si besoin
          relanceDetermination();
        }
      }
    }
  }

  // METIER : Met à jour un choix
  // RETOUR : Aucun
  function updateChoice($post, $id, $user)
  {
    global $bdd;

    $control_ok = true;

    // Contrôle saisie possible en fonction des dates
    if (date("N") > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_saisie'] = true;
    }

    // Contrôle saisie possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                         = false;
        $_SESSION['alerts']['heure_saisie'] = true;
      }
    }

    // Récupération des données et insertion en base
    if ($control_ok == true)
    {
      // Heure choisie
      if (isset($post['select_heures_' . $id])  AND !empty($post['select_heures_' . $id])
      AND isset($post['select_minutes_' . $id]) AND !empty($post['select_minutes_' . $id]))
        $time        = $post['select_heures_' . $id] . $post['select_minutes_' . $id];
      else
        $time        = "";

      // Transports choisis
      $transports    = "";

      if (isset($post['checkbox_feet_' . $id]) AND $post['checkbox_feet_' . $id] == "F")
        $transports .= $post['checkbox_feet_' . $id] . ';';

      if (isset($post['checkbox_bike_' . $id]) AND $post['checkbox_bike_' . $id] == "B")
        $transports .= $post['checkbox_bike_' . $id] . ';';

      if (isset($post['checkbox_tram_' . $id]) AND $post['checkbox_tram_' . $id] == "T")
        $transports .= $post['checkbox_tram_' . $id] . ';';

      if (isset($post['checkbox_car_' . $id]) AND $post['checkbox_car_' . $id] == "C")
        $transports .= $post['checkbox_car_' . $id] . ';';

      // Menu saisi
      $menu          = "";

      if (!isset($post['update_entree_' . $id]) AND !isset($post['update_plat_' . $id]) AND !isset($post['update_dessert_' . $id]))
        $menu       .= ";;;";
      else
      {
        $menu       .= str_replace(";", " ", $post['update_entree_' . $id]) . ';';
        $menu       .= str_replace(";", " ", $post['update_plat_' . $id]) . ';';
        $menu       .= str_replace(";", " ", $post['update_dessert_' . $id]) . ';';
      }

      // Tableau de mise à jour d'un choix
      $choice = array('time'          => $time,
                      'transports'    => $transports,
                      'menu'          => $menu
                     );

      $req = $bdd->prepare('UPDATE food_advisor_users SET time       = :time,
                                                          transports = :transports,
                                                          menu       = :menu
                                                    WHERE id = ' . $id . ' AND identifiant = "' . $user . '" AND date = "' . date("Ymd") . '"');
      $req->execute($choice);
      $req->closeCursor();
    }
  }

  // METIER : Supprime un choix utilisateur
  // RETOUR : Aucun
  function deleteChoice($id)
  {
    $control_ok = true;

    global $bdd;

    // Contrôle suppression possible en fonction des dates
    if (date("N") > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_delete'] = true;
    }

    // Contrôle suppression possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date("H") >= 13)
      {
        $control_ok                              = false;
        $_SESSION['alerts']['heure_suppression'] = true;
      }
    }

    // On vérifie que l'on n'était pas l'appelant quand on supprime le choix
    if ($control_ok == true)
    {
      // Recherche Id restaurant correspondant au choix
      $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id = ' . $id);
      $data1 = $req1->fetch();
      $id_restaurant = $data1['id_restaurant'];
      $identifiant   = $data1['identifiant'];
      $req1->closeCursor();

      // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
      $req2 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $id_restaurant . ' AND date = "' . date("Ymd") . '" AND caller = "' . $identifiant . '"');
    }

    // Suppression choix de la base
    if ($control_ok == true)
      $req3 = $bdd->exec('DELETE FROM food_advisor_users WHERE id = ' . $id);

    // Relance de la détermination si besoin
    if ($control_ok == true)
      relanceDetermination();
  }

  // METIER : Tri des propositions
  // RETOUR : Propositions triées
  function triPropositions($propositions)
  {
    // Tri par détermination puis par nombre de participants
    foreach ($propositions as $proposition)
    {
      $tri_determined[]      = $proposition->getDetermined();
      $tri_nb_participants[] = $proposition->getNb_participants();
    }

    array_multisort($tri_determined, SORT_DESC, $tri_nb_participants, SORT_DESC, $propositions);

    return $propositions;
  }

  // METIER : Relance la détermination
  // RETOUR : Aucun
  function relanceDetermination()
  {
    global $bdd;

    // Si une détermination du jour a déjà été effectuée, on doit relancer la détermination ou éventuellement la supprimer si c'était le dernier choix
    $existant = false;

    $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $existant    = true;
      $id_existant = $data1['id'];
    }

    $req1->closeCursor();

    if ($existant == true)
    {
      // Nombre de choix restants
      $req2 = $bdd->query('SELECT COUNT(id) AS nb_choix_restants FROM food_advisor_users WHERE id_restaurant != 0 AND date = "' . date("Ymd") . '"');
      $data2 = $req2->fetch();
      $nb_choix_restants = $data2['nb_choix_restants'];
      $req2->closeCursor();

      // Relance de la détermination si possible
      if ($nb_choix_restants > 0)
      {
        $propositions = getPropositions();
        $idRestaurant = getRestaurantDetermined($propositions);
        $isSolo       = getSolo($_SESSION['user']['identifiant']);
        $isReserved   = getReserved($_SESSION['user']['identifiant']);

        if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
        AND (!isset($_SESSION['alerts']['heure_determination'])    OR $_SESSION['alerts']['heure_determination']    != true)
        AND  $isSolo != true AND empty($isReserved))
        {
          $appelant = getCallers($idRestaurant);
          setDetermination($propositions, $idRestaurant, $appelant);
        }
      }
      // Suppression de la détermination
      else
        $req3 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id = ' . $id_existant);
    }
  }
?>
