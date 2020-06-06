<?php
  // METIER : Récupération de la liste des restaurants ouverts
  // RETOUR : Liste des restaurants disponibles
  function getListeRestaurantsOuverts($listeLieux)
  {
    // Initialisations
    $listeRestaurants = array();

    // Récupération de la liste des restaurants ouverts pour chaque lieu
    foreach ($listeLieux as $lieu)
    {
      $listeRestaurants[$lieu] = physiqueRestaurantsOuvertsParLieux($lieu);
    }

    // Retour
    return $listeRestaurants;
  }

  // METIER : Filtre la liste des restaurants disponibles si aucun ne l'est
  // RETOUR : Liste des restaurants filtrés
  function getListeRestaurantsFiltres($listeRestaurants)
  {
    // Filtrage des restaurants
    foreach ($listeRestaurants as $keyRestaurant => $restaurantsParLieux)
    {
      if (empty($restaurantsParLieux))
        unset($listeRestaurants[$keyRestaurant]);
    }

    // Retour
    return $listeRestaurants;
  }

  // METIER : Extrait la liste des lieux avec au moins 1 restaurant ouvert
  // RETOUR : Liste des lieux filtrés
  function getLieuxFiltres($listeRestaurants)
  {
    // Initialisations
    $listeLieux = array();

    // Récupération des lieux ayant au moins 1 restaurant ouvert
    foreach ($listeRestaurants as $keyRestaurant => $restaurantsParLieux)
    {
      if (!empty($restaurantsParLieux))
        array_push($listeLieux, $keyRestaurant);
    }

    // Retour
    return $listeLieux;
  }

  // METIER : Conversion du tableau d'objet des restaurants en tableau simple pour JSON
  // RETOUR : Tableau des restaurants par lieu
  function convertForJsonListeRestaurants($listeRestaurants)
  {
    // Initialisations
    $listeRestaurantsAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($listeRestaurants as $keyLieu => $restaurantsParLieux)
    {
      $listeParLieu = array();

      foreach ($restaurantsParLieux as $restaurant)
      {
        $restaurantAConvertir = array('id'   => $restaurant->getId(),
                                      'name' => $restaurant->getName()
                                     );

        // On ajoute la ligne au tableau
        array_push($listeParLieu, $restaurantAConvertir);
      }

      $listeRestaurantsAConvertir[$keyLieu] = $listeParLieu;
    }

    // Retour
    return $listeRestaurantsAConvertir;
  }

  // METIER : Converstion du tableau d'objet des propositions en tableau simple pour JSON
  // RETOUR : Tableau des détails
  function convertForJsonListePropositions($propositions)
  {
    // Initialisations
    $listePopositionsAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($propositions as $proposition)
    {
      // Formatage du numéro de téléphone
      if (!empty($proposition->getPhone()))
        $phone = formatPhoneNumber($proposition->getPhone());
      else
        $phone = '';

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
                                     'description'     => $proposition->getDescription(),
                                     'details'         => $proposition->getDetails()
                                    );

      $listePopositionsAConvertir[$proposition->getId_restaurant()] = $propositionAConvertir;
    }

    // Retour
    return $listePopositionsAConvertir;
  }

  // METIER : Détermine la présence des différents boutons d'action
  // RETOUR : Tableau des actions
  function getActions($propositions, $mesChoix, $isSolo, $isReserved, $user)
  {
    // Initialisations
    $actions = array('saisir_choix'     => true,
                     'determiner'       => true,
                     'solo'             => true,
                     'choix'            => true,
                     'reserver'         => true,
                     'annuler_reserver' => false,
                     'supprimer_choix'  => true,
                     'choix_rapide'     => true
                    );

    // Contrôles date et heure - toutes actions
    /*if (date('N') > 5 OR date('H') >= 13)
    {
      $actions['saisir_choix']     = false;
      $actions['determiner']       = false;
      $actions['solo']             = false;
      $actions['choix']            = false;
      $actions['reserver']         = false;
      $actions['annuler_reserver'] = false;
      $actions['supprimer_choix']  = false;
      $actions['choix_rapide']     = false;
    }*/

    // Contrôle propositions présentes - bouton détermination
    if ($actions['determiner'] == true)
    {
      if (empty($propositions) OR empty($mesChoix))
        $actions['determiner'] = false;
    }

    // Contrôle choix présents - bouton suppression de tous les choix
    if (empty($mesChoix))
      $actions['supprimer_choix'] = false;

    // Contrôle choix présents - bouton bande à part
    if ($actions['solo'] == true)
    {
      if (!empty($mesChoix))
        $actions['solo'] = false;
    }

    // Contrôle vote solo présent
    if ($actions['solo'] == true)
    {
      if ($isSolo == true)
      {
        $actions['saisir_choix']    = false;
        $actions['solo']            = false;
        $actions['supprimer_choix'] = false;
        $actions['choix_rapide']    = false;
      }
    }

    // Contrôle réservation effectuée
    if ($actions['reserver'] == true)
    {
      if (!empty($isReserved))
      {
        $actions['saisir_choix'] = false;
        $actions['reserver']     = false;
        $actions['determiner']   = false;
      }
    }

    // Contrôle réserveur pour annulation - bouton annulation réservation
    if ($actions['reserver'] == false)
    {
      if ($isReserved == $user AND date('N') <= 5 AND date('H') < 13)
        $actions['annuler_reserver'] = true;
    }

    // Retour
    return $actions;
  }

  // METIER : Récupère les utilisateurs faisant bande à part
  // RETOUR : Liste des utilisateurs
  function getSolos()
  {
    // Initialisations
    $solos = array();

    // Récupération de la liste des utilisateurs
    $identifiantsSolos = physiqueIdentifiantsSolos();

    // Récupération des données utilisateurs
    foreach ($identifiantsSolos as $identifiantSolo)
    {
      // On ajoute la ligne au tableau
      array_push($solos, physiqueUser($identifiantSolo));
    }

    // Retour
    return $solos;
  }

  // METIER : Récupère les utilisateurs qui n'ont pas fait de propositions
  // RETOUR : Liste des utilisateurs
  function getNoPropositions()
  {
    // Initialisations
    $noPropositions = array();

    // Récupération de la liste des utilisateurs inscrits
    $listeUsers = physiqueUsers();

    // Vérification nombre propositions de chaque utilisateur
    foreach ($listeUsers as $user)
    {
      $nombrePropositions = physiqueNombrePropositions($user->getIdentifiant());

      if ($nombrePropositions == 0)
        array_push($noPropositions, $user);
    }

    // Retour
    return $noPropositions;
  }

  // METIER : Insère un choix "bande à part"
  // RETOUR : Aucun
  function setSolo($mesChoix, $isSolo, $user)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_saisie');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('heure_solo');

    // Contrôle déjà solo
    if ($control_ok == true)
      $control_ok = controleAlreadySolo($isSolo);

    // Contrôle autres votes
    if ($control_ok == true)
      $control_ok = controleAlreadyVoted($mesChoix);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $solo = array('id_restaurant' => 0,
                    'identifiant'   => $user,
                    'date'          => date('Ymd'),
                    'time'          => '',
                    'transports'    => '',
                    'menu'          => ';;;'
                   );

      physiqueInsertionChoix($solo);
    }
  }

  // METIER : Supprime un choix "bande à part"
  // RETOUR : Aucun
  function deleteSolo($user)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_delete');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('heure_suppression_solo');

    // Suppression de l'enregistrement en base
    if ($control_ok == true)
      physiqueDeleteSolo($user);
  }

  // METIER : Insère ou met à jour une réservation
  // RETOUR : Aucun
  function insertReservation($post, $appelant)
  {
    // Initialisations
    $idRestaurant = $post['id_restaurant'];
    $control_ok   = true;

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_reservation');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('heure_reservation');

    // Récupération de la détermination
    if ($control_ok == true)
    {
      // Contrôle si détermination déjà existante
      $determinationExistante = physiqueDeterminationExistante();

      // Si la détermination existe déjà
      if ($determinationExistante == true)
      {
        // Récupération des données de la détermination
        $determination = physiqueDetermination();

        // Contrôle si déjà réservé
        $control_ok = controleAlreadyReserved($determination->getReserved());
      }
    }

    // Insertion ou modification de l'enregistrement en base
    if ($control_ok == true)
    {
      // Si la détermination existe déjà, mise à jour
      if ($determinationExistante == true)
      {
        // Modification de l'enregistrement en base
        $nouvelleDetermination = array('id_restaurant' => $idRestaurant,
                                       'caller'        => $appelant,
                                       'reserved'      => 'Y'
                                      );

        physiqueUpdateDetermination($nouvelleDetermination, $determination->getId());

        // Génération succès (pour l'appelant si modifié)
        insertOrUpdateSuccesValue('star-chief', $determination->getCaller(), -1);
      }
      // Sinon insertion
      else
      {
        // Insertion de l'enregistrement en base
        $nouvelleDetermination = array('id_restaurant' => $idRestaurant,
                                       'date'          => date('Ymd'),
                                       'caller'        => $appelant,
                                       'reserved'      => 'Y'
                                      );

        physiqueInsertionDetermination($nouvelleDetermination);
      }

      // Génération succès (pour le nouvel appelant)
      insertOrUpdateSuccesValue('star-chief', $appelant, 1);
    }
  }

  // METIER : Supprime une réservation
  // RETOUR : Aucun
  function deleteReservation($post, $user)
  {
    // Initialisations
    $idRestaurant = $post['id_restaurant'];
    $control_ok   = true;

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_reservation');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('heure_suppression_reservation');

    // Annulation réservation
    if ($control_ok == true)
      physiqueAnnulationReservation($idRestaurant, $user);
  }

  // METIER : Supprime les choix de tous les utilisateurs d'un restaurant et relance la détermination
  // RETOUR : Aucun
  function completeChoice($post)
  {
    // Initialisations
    $idRestaurant = $post['id_restaurant'];
    $control_ok   = true;

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_delete');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('heure_suppression');

    // Suppression de tous les choix utilisateurs pour ce restaurant et relance de la détermination
    if ($control_ok == true)
    {
      // Suppression de tous les choix utilisateurs pour ce restaurant
      physiqueDeleteComplete($idRestaurant);

      // Relance de la détermination si besoin
      relanceDetermination();
    }
  }

  // METIER : Récupère les choix de la semaine
  // RETOUR : Liste des choix de la semaine
  function getWeekChoices()
  {
    // Initialisations
    $listeChoixSemaine = array();
    $semaine           = array(1 => 'lundi',
                               2 => 'mardi',
                               3 => 'mercredi',
                               4 => 'jeudi',
                               5 => 'vendredi'
                              );

    // Calcul des dates de la semaine
    $nombreJoursLundi    = 1 - date('N');
    $nombreJoursVendredi = 5 - date('N');
    $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
    $vendredi            = date('Ymd', strtotime('+' . $nombreJoursVendredi . ' days'));

    // Vérification présence de choix
    $choixSemainePresents = physiqueChoixSemainePresents($lundi, $vendredi);

    // On récupère les propositions pour chaque jour de la semaine courante
    if ($choixSemainePresents == true)
    {
      for ($i = $lundi; $i <= $vendredi; $i = date('Ymd', strtotime($i . ' + 1 days')))
      {
        // Lecture des données du choix
        $choixSemaine = physiqueDonneesResume($i);

        if ($choixSemaine != NULL)
        {
          // Lecture des données du restaurant
          $restaurant = physiqueDonneesRestaurant($choixSemaine->getId_restaurant());

          // Nombre de participants
          $nombreParticipants = physiqueNombreParticipants($choixSemaine->getId_restaurant());

          // Récupération pseudo et avatar
          $user = physiqueUser($choixSemaine->getCaller());

          // Concaténation des données
          $choixSemaine->setName($restaurant->getName());
          $choixSemaine->setPicture($restaurant->getPicture());
          $choixSemaine->setLocation($restaurant->getLocation());
          $choixSemaine->setNb_participants($nombreParticipants);

          if ($user != NULL)
          {
            $choixSemaine->setPseudo($user->getPseudo());
            $choixSemaine->setAvatar($user->getAvatar());
          }
        }

        // On ajoute la ligne au tableau
        $jour                               = date('N', strtotime($i));
        $listeChoixSemaine[$semaine[$jour]] = $choixSemaine;
      }
    }

    // Retour
    return $listeChoixSemaine;
  }

  // METIER : Insère un ou plusieurs choix utilisateur
  // RETOUR : Aucun
  function insertChoices($post, $isSolo, $user)
  {
    // Initialisations
    $maxChoices = 5;
    $control_ok = true;

    // Contrôle date de saisie
    //$control_ok = controleDateSaisie('week_end_saisie');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('heure_saisie');

    // Contrôle bande à part
    if ($control_ok == true)
      $control_ok = controleSoloSaisie($isSolo);

    // Contrôle choix saisi en double (non bloquant)
    if ($control_ok == true)
    {
      // On parcourt tous les choix
      for ($i = 1; $i <= $maxChoices; $i++)
      {
        if (isset($post['select_lieu'][$i])       AND !empty($post['select_lieu'][$i])
        AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          // On parcourt à nouveau les choix à partir du courant pour contrôler
          for ($j = $i; $j <= $maxChoices; $j++)
          {
            if (isset($post['select_lieu'][$j])       AND !empty($post['select_lieu'][$j])
            AND isset($post['select_restaurant'][$j]) AND !empty($post['select_restaurant'][$j]))
            {
              if ($j > $i)
              {
                // Contrôle doublon
                $doublon = controleVoteDoublon($post['select_lieu'][$i], $post['select_lieu'][$j], $post['select_restaurant'][$i], $post['select_restaurant'][$j]);

                // On supprime la ligne du tableau si en double
                if ($doublon == true)
                {
                  unset($post['select_lieu'][$j]);
                  unset($post['select_restaurant'][$j]);
                  unset($post['select_heures'][$j]);
                  unset($post['select_minutes'][$j]);
                  unset($post['checkbox_feet'][$j]);
                  unset($post['checkbox_bike'][$j]);
                  unset($post['checkbox_tram'][$j]);
                  unset($post['checkbox_car'][$j]);
                  unset($post['saisie_entree'][$j]);
                  unset($post['saisie_plat'][$j]);
                  unset($post['saisie_dessert'][$j]);
                }
              }
            }
          }
        }
      }
    }

    // Contrôle choix déjà existant (non bloquant)
    if ($control_ok == true)
    {
      // On parcourt tous les choix
      for ($i = 1; $i <= $maxChoices; $i++)
      {
        if (isset($post['select_lieu'][$i])       AND !empty($post['select_lieu'][$i])
        AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          // Contrôle choix existant
          $choixNonExistant = controleChoixExistant($post['select_restaurant'][$i], $user, 'wrong_choice_already');

          // On supprime la ligne du tableau si déjà saisi
          if ($choixNonExistant == false)
          {
            unset($post['select_lieu'][$i]);
            unset($post['select_restaurant'][$i]);
            unset($post['select_heures'][$i]);
            unset($post['select_minutes'][$i]);
            unset($post['checkbox_feet'][$i]);
            unset($post['checkbox_bike'][$i]);
            unset($post['checkbox_tram'][$i]);
            unset($post['checkbox_car'][$i]);
            unset($post['saisie_entree'][$i]);
            unset($post['saisie_plat'][$i]);
            unset($post['saisie_dessert'][$i]);
          }
        }
      }
    }

    // Contrôle restaurant ouvert (non bloquant)
    if ($control_ok == true)
    {
      for ($i = 1; $i <= $maxChoices; $i++)
      {
        if (isset($post['select_lieu'][$i])       AND !empty($post['select_lieu'][$i])
        AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          // Lecture des données du restaurant
          $restaurant = physiqueDonneesRestaurant($post['select_restaurant'][$i]);

          // Contrôle restaurant ouvert
          $restaurantOuvert = controleRestaurantOuvert($restaurant->getOpened());

          // On supprime la ligne du tableau si le restaurant n'est pas ouvert
          if ($restaurantOuvert == false)
          {
            unset($post['select_lieu'][$i]);
            unset($post['select_restaurant'][$i]);
            unset($post['select_heures'][$i]);
            unset($post['select_minutes'][$i]);
            unset($post['checkbox_feet'][$i]);
            unset($post['checkbox_bike'][$i]);
            unset($post['checkbox_tram'][$i]);
            unset($post['checkbox_car'][$i]);
            unset($post['saisie_entree'][$i]);
            unset($post['saisie_plat'][$i]);
            unset($post['saisie_dessert'][$i]);
          }
        }
      }
    }

    // Récupération des données et insertion des enregistrements en base
    if ($control_ok == true)
    {
      for ($i = 1; $i <= $maxChoices; $i++)
      {
        if (isset($post['select_lieu'][$i])       AND !empty($post['select_lieu'][$i])
        AND isset($post['select_restaurant'][$i]) AND !empty($post['select_restaurant'][$i]))
        {
          // Id restaurant
          $idRestaurant = $post['select_restaurant'][$i];

          // Identifiant utilisateur
          $identifiant = $user;

          // Date de saisie
          $date = date('Ymd');

          // Heure choisie
          if (isset($post['select_heures'][$i])  AND !empty($post['select_heures'][$i])
          AND isset($post['select_minutes'][$i]) AND !empty($post['select_minutes'][$i]))
            $time = $post['select_heures'][$i] . $post['select_minutes'][$i];
          else
            $time = '';

          // Transports choisis
          $transports = '';

          if (isset($post['checkbox_feet'][$i]) AND $post['checkbox_feet'][$i] == 'F')
            $transports .= $post['checkbox_feet'][$i] . ';';

          if (isset($post['checkbox_bike'][$i]) AND $post['checkbox_bike'][$i] == 'B')
            $transports .= $post['checkbox_bike'][$i] . ';';

          if (isset($post['checkbox_tram'][$i]) AND $post['checkbox_tram'][$i] == 'T')
            $transports .= $post['checkbox_tram'][$i] . ';';

          if (isset($post['checkbox_car'][$i])  AND $post['checkbox_car'][$i]  == 'C')
            $transports .= $post['checkbox_car'][$i] . ';';

          // Menu saisi
          $menu = '';

          if (!isset($post['saisie_entree'][$i]) AND !isset($post['saisie_plat'][$i]) AND !isset($post['saisie_dessert'][$i]))
            $menu .= ';;;';
          else
          {
            $menu .= str_replace(';', ' ', $post['saisie_entree'][$i]) . ';';
            $menu .= str_replace(';', ' ', $post['saisie_plat'][$i]) . ';';
            $menu .= str_replace(';', ' ', $post['saisie_dessert'][$i]) . ';';
          }

          // Insertion de l'enregistrement en base
          $choix = array('id_restaurant' => $idRestaurant,
                         'identifiant'   => $identifiant,
                         'date'          => $date,
                         'time'          => $time,
                         'transports'    => $transports,
                         'menu'          => $menu
                        );

          physiqueInsertionChoix($choix);

          // Relance de la détermination si besoin
          relanceDetermination();
        }
      }
    }
  }



























  // METIER : Insère un ou plusieurs choix utilisateur
  // RETOUR : Aucun
  function insertChoicesMobile($post, $isSolo, $user)
  {
    global $bdd;

    $control_ok       = true;
    $listeRestaurants = array_keys($post['restaurants']);

    // Contrôle saisie possible en fonction des dates
    if (date('N') > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_saisie'] = true;
    }

    // Contrôle saisie possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date('H') >= 13)
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
      // On parcourt tous les choix
      if (!empty($listeRestaurants))
      {
        foreach ($listeRestaurants as $keyId => $idRestaurant)
        {
          $req1 = $bdd->query('SELECT * FROM food_advisor_users WHERE id_restaurant = ' . $idRestaurant . ' AND identifiant = "' . $user . '" AND date = "' . date('Ymd') . '"');
          $data1 = $req1->fetch();

          if ($req1->rowCount() > 0)
          {
            // On supprime le restaurant si déjà saisi et on le signale
            unset($listeRestaurants[$keyId]);

            $_SESSION['alerts']['wrong_choice_already'] = true;
          }

          $req1->closeCursor();
        }
      }
    }

    // Contrôle restaurant ouvert
    if ($control_ok == true)
    {
      if (!empty($listeRestaurants))
      {
        foreach ($listeRestaurants as $keyId => $idRestaurant)
        {
          $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $idRestaurant);
          $data2 = $req2->fetch();

          $explodedOpened = explode(';', $data2['opened']);

          foreach ($explodedOpened as $keyOpened => $opened)
          {
            if (!empty($opened))
            {
              if (date('N') == $keyOpened + 1 AND $opened == 'N')
              {
                // On supprime le restaurant si déjà saisi et on le signale
                unset($listeRestaurants[$keyId]);

                $_SESSION['alerts']['not_open'] = true;
              }
            }
          }

          $req2->closeCursor();
        }
      }
    }

    // Récupération des données et insertion en base
    if ($control_ok == true)
    {
      if (!empty($listeRestaurants))
      {
        foreach ($listeRestaurants as $idRestaurant)
        {
          // Identifiant utilisateur
          $identifiant = $user;

          // Date de saisie
          $date        = date('Ymd');

          // Heure
          $time        = '';

          // Transports choisis
          $transports  = '';

          // Menu
          $menu        = ';;;';

          // Tableau d'un choix
          $choix = array('id_restaurant' => $idRestaurant,
                         'identifiant'   => $identifiant,
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
                                                                menu)
                                                        VALUES(:id_restaurant,
                                                               :identifiant,
                                                               :date,
                                                               :time,
                                                               :transports,
                                                               :menu)');
          $req3->execute($choix);
          $req3->closeCursor();

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
    if (date('N') > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_saisie'] = true;
    }

    // Contrôle saisie possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date('H') >= 13)
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
        $time        = '';

      // Transports choisis
      $transports    = '';

      if (isset($post['checkbox_feet_' . $id_choix]) AND $post['checkbox_feet_' . $id_choix] == 'F')
        $transports .= $post['checkbox_feet_' . $id_choix] . ';';

      if (isset($post['checkbox_bike_' . $id_choix]) AND $post['checkbox_bike_' . $id_choix] == 'B')
        $transports .= $post['checkbox_bike_' . $id_choix] . ';';

      if (isset($post['checkbox_tram_' . $id_choix]) AND $post['checkbox_tram_' . $id_choix] == 'T')
        $transports .= $post['checkbox_tram_' . $id_choix] . ';';

      if (isset($post['checkbox_car_' . $id_choix]) AND $post['checkbox_car_' . $id_choix] == 'C')
        $transports .= $post['checkbox_car_' . $id_choix] . ';';

      // Menu saisi
      $menu          = '';

      if (!isset($post['update_entree_' . $id_choix]) AND !isset($post['update_plat_' . $id_choix]) AND !isset($post['update_dessert_' . $id_choix]))
        $menu       .= ';;;';
      else
      {
        $menu       .= str_replace(';', ' ', $post['update_entree_' . $id_choix]) . ';';
        $menu       .= str_replace(';', ' ', $post['update_plat_' . $id_choix]) . ';';
        $menu       .= str_replace(';', ' ', $post['update_dessert_' . $id_choix]) . ';';
      }

      // Tableau de mise à jour d'un choix
      $choix = array('time'       => $time,
                     'transports' => $transports,
                     'menu'       => $menu
                    );

      $req = $bdd->prepare('UPDATE food_advisor_users SET time       = :time,
                                                          transports = :transports,
                                                          menu       = :menu
                                                    WHERE id = ' . $id_choix . ' AND identifiant = "' . $user . '" AND date = "' . date('Ymd') . '"');
      $req->execute($choix);
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
    if (date('N') > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_delete'] = true;
    }

    // Contrôle suppression possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date('H') >= 13)
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
      $idRestaurant = $data1['id_restaurant'];
      $identifiant   = $data1['identifiant'];
      $req1->closeCursor();

      // Génération succès (pour l'appelant si supprimé)
      $req2 = $bdd->query('SELECT * FROM food_advisor_choices WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . date('Ymd') . '" AND caller = "' . $identifiant . '"');
      $data2 = $req2->fetch();

      if ($req2->rowCount() > 0)
        insertOrUpdateSuccesValue('star-chief', $identifiant, -1);

      $req2->closeCursor();

      // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
      $req3 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . date('Ymd') . '" AND caller = "' . $identifiant . '"');
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
    if (date('N') > 5)
    {
      $control_ok                            = false;
      $_SESSION['alerts']['week_end_delete'] = true;
    }

    // Contrôle suppression possible en fonction de l'heure
    if ($control_ok == true)
    {
      if (date('H') >= 13)
      {
        $control_ok                              = false;
        $_SESSION['alerts']['heure_suppression'] = true;
      }
    }

    // On vérifie que l'on n'était pas l'appelant quand on supprime le choix
    if ($control_ok == true)
    {
      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date('Ymd') . '" AND caller = "' . $user . '"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $idRestaurant = $data1['id_restaurant'];
        $caller        = $data1['caller'];

        // Génération succès (pour l'appelant si supprimé)
        if ($user == $caller)
          insertOrUpdateSuccesValue('star-chief', $user, -1);

        // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
        $req2 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . date('Ymd') . '" AND caller = "' . $user . '"');
      }

      $req1->closeCursor();
    }

    // Suppression de tous les choix de l'utilisateur'
    if ($control_ok == true)
      $req3 = $bdd->exec('DELETE FROM food_advisor_users WHERE date = "' . date('Ymd') . '" AND identifiant = "' . $user . '"');

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
    $idRestaurant = $post['select_restaurant_resume_' . $jour_saisie];

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
      $choix = array('id_restaurant' => $idRestaurant,
                     'date'          => $date_saisie,
                     'caller'        => '',
                     'reserved'      => 'N'
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
      $req2->execute($choix);
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
