<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/restaurants.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Récupération de la liste des lieux
  // RETOUR : Liste des lieux
  function getLieux()
  {
    $listLocations = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT location FROM food_advisor_restaurants ORDER BY location ASC');
    while ($donnees = $reponse->fetch())
    {
      array_push($listLocations, $donnees['location']);
    }
    $reponse->closeCursor();

    return $listLocations;
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

  // METIER : Récupère les choix du jour
  // RETOUR : Liste des choix du jour (tous)
  function getPropositions($details)
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
      $myProposition->setLafourchette($data2['lafourchette']);
      $myProposition->setOpened($data2['opened']);
      $myProposition->setMin_price(str_replace('.', ',', $data2['min_price']));
      $myProposition->setMax_price(str_replace('.', ',', $data2['max_price']));
      $myProposition->setDescription($data2['description']);

      $req2->closeCursor();

      // Contrôle restaurant disponible ce jour
      $available_day  = true;
      $explodedOpened = explode(";", $myProposition->getOpened());

      foreach ($explodedOpened as $keyOpened => $opened)
      {
        if (!empty($opened))
        {
          if (date('N') == $keyOpened + 1 AND $opened == "N")
          {
            $available_day = false;
            break;
          }
        }
      }

      // Récupération des données si disponible
      if ($available_day == true)
      {
        // Nombre de participants
        $req3 = $bdd->query('SELECT COUNT(id_restaurant) AS nb_participants FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myProposition->getId_restaurant());
        $data3 = $req3->fetch();

        $myProposition->setNb_participants($data3['nb_participants']);

        $req3->closeCursor();

        // Proposition déterminée
        $req4 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '" AND id_restaurant = ' . $myProposition->getId_restaurant());
        $data4 = $req4->fetch();

        if ($req4->rowCount() > 0)
        {
          $myProposition->setDetermined("Y");
          $myProposition->setCaller($data4['caller']);
          $myProposition->setReserved($data4['reserved']);

          // Récupération pseudo et avatar
          $req5 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $myProposition->getCaller() . '"');
          $data5 = $req5->fetch();

          $myProposition->setPseudo($data5['pseudo']);
          $myProposition->setAvatar($data5['avatar']);

          $req5->closeCursor();
        }

        $req4->closeCursor();

        array_push($listPropositions, $myProposition);
      }
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
        if ($details == true)
          $proposition->setDetails(getDetailsProposition($proposition));
      }

      // Tri par détermination puis nombre de participants pour affichage
      $listPropositions = triPropositions($listPropositions);
    }

    return $listPropositions;
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
        $propositions = getPropositions(false);
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

  // METIER : Insère un choix rapide
  // RETOUR : Id restaurant
  function insertFastChoice($post, $isSolo, $user)
  {
    global $bdd;

    $control_ok    = true;
    $id_restaurant = $post['id_restaurant'];

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

    // Contrôle choix déjà existant
    if ($control_ok == true)
    {
      $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant = ' . $id_restaurant . ' AND identifiant = "' . $user . '" AND date = "' . date("Ymd") . '"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $control_ok                                 = false;
        $_SESSION['alerts']['wrong_fast'] = true;
      }

      $req1->closeCursor();
    }

    // Contrôle restaurant ouvert
    if ($control_ok == true)
    {
      $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
      $data2 = $req2->fetch();

      $explodedOpened = explode(";", $data2['opened']);

      foreach ($explodedOpened as $keyOpened => $opened)
      {
        if (!empty($opened))
        {
          if (date('N') == $keyOpened + 1 AND $opened == "N")
          {
            $control_ok                     = false;
            $_SESSION['alerts']['not_open'] = true;
            break;
          }
        }
      }

      $req2->closeCursor();
    }

    // Récupération des données et insertion en base
    if ($control_ok == true)
    {
      $date       = date("Ymd");
      $time       = "";
      $transports = "";
      $menu       = ";;;";

      // Tableau du choix
      $choice = array('id_restaurant' => $id_restaurant,
                      'identifiant'   => $user,
                      'date'          => $date,
                      'time'          => $time,
                      'transports'    => $transports,
                      'menu'          => $menu
                    );

      // On insère dans la table
      $req3 = $bdd->prepare('INSERT INTO food_advisor_users(id_restaurant,
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
      $req3->execute($choice);
      $req3->closeCursor();

      // Relance de la détermination si besoin
      relanceDetermination();
    }

    return $id_restaurant;
  }
?>
