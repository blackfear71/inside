<?php
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

  // METIER : Converstion du tableau d'objet des propositions en tableau simple pour JSON
  // RETOUR : Tableau des détails
  function convertForJson2($propositions)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listePopositionsAConvertir = array();

    foreach ($propositions as $proposition)
    {
      $phone = "";

      if (!empty($proposition->getPhone()))
        $phone = formatPhoneNumber($proposition->getPhone());

      $propositionAConvertir = array('id_restaurant'   => $proposition->getId_restaurant(),
                                     'name'            => $proposition->getName(),
                                     'picture'         => $proposition->getPicture(),
                                     'location'        => $proposition->getLocation(),
                                     'nb_participants' => $proposition->getNb_participants(),
                                     'classement'      => $proposition->getClassement(),
                                     'determined'      => $proposition->getDetermined(),
                                     'caller'          => $proposition->getCaller(),
                                     'pseudo'          => $proposition->getPseudo(),
                                     'avatar'          => $proposition->getAvatar(),
                                     'reserved'        => $proposition->getReserved(),
                                     'types'           => $proposition->getTypes(),
                                     'phone'           => $phone,
                                     'website'         => $proposition->getWebsite(),
                                     'plan'            => $proposition->getPlan(),
                                     'lafourchette'    => $proposition->getLafourchette(),
                                     'opened'          => $proposition->getOpened(),
                                     'min_price'       => $proposition->getMin_price(),
                                     'max_price'       => $proposition->getMax_price(),
                                     'details'         => $proposition->getDetails()
                                   );

      $listePopositionsAConvertir[$proposition->getId_restaurant()] = $propositionAConvertir;
    }

    return $listePopositionsAConvertir;
  }

  // METIER : Détermine la présence des boutons d'action
  // RETOUR : Booléen
  function getActions($propositions, $myChoices, $isSolo, $isReserved, $user)
  {
    $actions = array("saisir_choix"     => true,
                     "determiner"       => true,
                     "solo"             => true,
                     "choix"            => true,
                     "reserver"         => true,
                     "annuler_reserver" => false,
                     "supprimer_choix"  => true,
                     "choix_rapide"     => true
                    );

    // Contrôles date et heure
    if (date("N") > 5 OR date("H") >= 13)
    {
      $actions["saisir_choix"]     = false;
      $actions["determiner"]       = false;
      $actions["solo"]             = false;
      $actions["choix"]            = false;
      $actions["reserver"]         = false;
      $actions["annuler_reserver"] = false;
      $actions["supprimer_choix"]  = false;
      $actions["choix_rapide"]     = false;
    }

    // Contrôle propositions présentes (pour bouton détermination)
    if ($actions["determiner"] == true)
    {
      if (empty($propositions) OR empty($myChoices))
        $actions["determiner"] = false;
    }

    // Contrôle choix présents (pour bouton suppression de tous les choix)
    if (empty($myChoices))
      $actions["supprimer_choix"] = false;

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
      {
        $actions["saisir_choix"]    = false;
        $actions["solo"]            = false;
        $actions["supprimer_choix"] = false;
        $actions["choix_rapide"]    = false;
      }
    }

    // Contrôle réservation effectuée
    if ($actions["reserver"] == true)
    {
      if (!empty($isReserved))
      {
        $actions["saisir_choix"] = false;
        $actions["reserver"]     = false;
        $actions["determiner"]   = false;
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
  function insertReservation($post, $caller)
  {
    $id_restaurant = $post['id_restaurant'];
    $control_ok    = true;

    global $bdd;

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
  function deleteReservation($post, $user)
  {
    $id_restaurant = $post['id_restaurant'];
    $control_ok    = true;

    global $bdd;

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

  // METIER : Supprime les choix de tous les utilisateurs d'un restaurant et relance la détermination
  // RETOUR : Aucun
  function completeChoice($post)
  {
    $id_restaurant = $post['id_restaurant'];
    $control_ok    = true;

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

    // Suppression et détermination
    if ($control_ok == true)
    {
      // Suppression de tous les choix utilisateurs pour ce restaurant
      $req = $bdd->exec('DELETE FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND id_restaurant = "' . $id_restaurant . '"');

      // Relance de la détermination si besoin
      relanceDetermination();
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

  // METIER : Récupère les choix de la semaine
  // RETOUR : Liste des choix de la semaine
  function getWeekChoices()
  {
    $listWeekChoices = array();
    $semaine         = array(1 => "lundi",
                             2 => "mardi",
                             3 => "mercredi",
                             4 => "jeudi",
                             5 => "vendredi"
                            );

    // Calcul des dates de la semaine
    $nb_jours_lundi    = 1 - date("N");
    $nb_jours_vendredi = 5 - date("N");
    $monday            = date("Ymd", strtotime('+' . $nb_jours_lundi . ' days'));
    $friday            = date("Ymd", strtotime('+' . $nb_jours_vendredi . ' days'));

    global $bdd;

    for ($i = $monday; $i <= $friday; $i = date("Ymd", strtotime($i . ' + 1 days')))
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

      $req1->closeCursor();

      // Ajout au tableau
      $jour = date("N", strtotime($i));
      $listWeekChoices[$semaine[$jour]] = $myWeekChoice;
    }

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
    if ($control_ok == true)
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
  function updateChoice($post, $user)
  {
    $id_choix   = $post['id_choix'];
    $control_ok = true;

    global $bdd;

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
      if (isset($post['select_heures_' . $id_choix])  AND !empty($post['select_heures_' . $id_choix])
      AND isset($post['select_minutes_' . $id_choix]) AND !empty($post['select_minutes_' . $id_choix]))
        $time        = $post['select_heures_' . $id_choix] . $post['select_minutes_' . $id_choix];
      else
        $time        = "";

      // Transports choisis
      $transports    = "";

      if (isset($post['checkbox_feet_' . $id_choix]) AND $post['checkbox_feet_' . $id_choix] == "F")
        $transports .= $post['checkbox_feet_' . $id_choix] . ';';

      if (isset($post['checkbox_bike_' . $id_choix]) AND $post['checkbox_bike_' . $id_choix] == "B")
        $transports .= $post['checkbox_bike_' . $id_choix] . ';';

      if (isset($post['checkbox_tram_' . $id_choix]) AND $post['checkbox_tram_' . $id_choix] == "T")
        $transports .= $post['checkbox_tram_' . $id_choix] . ';';

      if (isset($post['checkbox_car_' . $id_choix]) AND $post['checkbox_car_' . $id_choix] == "C")
        $transports .= $post['checkbox_car_' . $id_choix] . ';';

      // Menu saisi
      $menu          = "";

      if (!isset($post['update_entree_' . $id_choix]) AND !isset($post['update_plat_' . $id_choix]) AND !isset($post['update_dessert_' . $id_choix]))
        $menu       .= ";;;";
      else
      {
        $menu       .= str_replace(";", " ", $post['update_entree_' . $id_choix]) . ';';
        $menu       .= str_replace(";", " ", $post['update_plat_' . $id_choix]) . ';';
        $menu       .= str_replace(";", " ", $post['update_dessert_' . $id_choix]) . ';';
      }

      // Tableau de mise à jour d'un choix
      $choice = array('time'          => $time,
                      'transports'    => $transports,
                      'menu'          => $menu
                     );

      $req = $bdd->prepare('UPDATE food_advisor_users SET time       = :time,
                                                          transports = :transports,
                                                          menu       = :menu
                                                    WHERE id = ' . $id_choix . ' AND identifiant = "' . $user . '" AND date = "' . date("Ymd") . '"');
      $req->execute($choice);
      $req->closeCursor();
    }
  }

  // METIER : Supprime un choix utilisateur
  // RETOUR : Aucun
  function deleteChoice($post)
  {
    $id_choix   = $post['id_choix'];
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
      $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id = ' . $id_choix);
      $data1 = $req1->fetch();
      $id_restaurant = $data1['id_restaurant'];
      $identifiant   = $data1['identifiant'];
      $req1->closeCursor();

      // Génération succès (pour l'appelant si supprimé)
      $req2 = $bdd->query('SELECT * FROM food_advisor_choices WHERE id_restaurant = ' . $id_restaurant . ' AND date = "' . date("Ymd") . '" AND caller = "' . $identifiant . '"');
      $data2 = $req2->fetch();

      if ($req2->rowCount() > 0)
        insertOrUpdateSuccesValue('star-chief', $identifiant, -1);

      $req2->closeCursor();

      // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
      $req3 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $id_restaurant . ' AND date = "' . date("Ymd") . '" AND caller = "' . $identifiant . '"');
    }

    // Suppression choix de la base
    if ($control_ok == true)
      $req4 = $bdd->exec('DELETE FROM food_advisor_users WHERE id = ' . $id_choix);

    // Relance de la détermination si besoin
    if ($control_ok == true)
      relanceDetermination();
  }

  // METIER : Supprime tous les choix utilisateur
  // RETOUR : Aucun
  function deleteAllChoices($user)
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
      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '" AND caller = "' . $user . '"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $id_restaurant = $data1['id_restaurant'];
        $caller        = $data1['caller'];

        // Génération succès (pour l'appelant si supprimé)
        if ($user == $caller)
          insertOrUpdateSuccesValue('star-chief', $user, -1);

        // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
        $req2 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $id_restaurant . ' AND date = "' . date("Ymd") . '" AND caller = "' . $user . '"');
      }

      $req1->closeCursor();
    }

    // Suppression de tous les choix de l'utilisateur'
    if ($control_ok == true)
      $req3 = $bdd->exec('DELETE FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND identifiant = "' . $user . '"');

    // Relance de la détermination si besoin
    if ($control_ok == true)
      relanceDetermination();
  }

  // METIER : Insère un choix dans le résumé
  // RETOUR : Aucun
  function insertResume($post)
  {
    $control_ok = true;

    $jour_saisie   = $post['num_jour'];
    $id_restaurant = $post['select_restaurant_resume_' . $jour_saisie];

    // Calcul date à insérer
    $jour_courant  = date('N');
    $ecart_jours   = $jour_courant - $jour_saisie;
    $date_saisie   = date('Ymd', strtotime('now - ' . $ecart_jours . ' Days'));

    global $bdd;

    // Contrôle choix non existant
    $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . $date_saisie . '"');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $control_ok                           = false;
      $_SESSION['alerts']['already_resume'] = true;
    }

    $req1->closeCursor();

    // Insertion en base
    if ($control_ok == true)
    {
      $choice = array('id_restaurant' => $id_restaurant,
                      'date'          => $date_saisie,
                      'caller'        => "",
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
  }

  // METIER : Supprime un choix dans le résumé
  // RETOUR : Aucun
  function deleteResume($post)
  {
    $id_resume   = $post['id_resume'];
    $date_resume = $post['date_resume'];

    global $bdd;

    // Suppression choix résumé (restaurant = choix, date = jour)
    $req = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $id_resume . ' AND date = "' . $date_resume . '"');
  }
?>
