<?php
  // METIER : Récupération et filtrage la liste des restaurants ouverts
  // RETOUR : Liste des restaurants filtrés
  function getListeRestaurantsOuverts($listeLieux, $equipe)
  {
    // Initialisations
    $listeRestaurants = array();

    // Récupération de la liste des restaurants ouverts pour chaque lieu
    foreach ($listeLieux as $lieu)
    {
      $listeRestaurants[$lieu] = physiqueRestaurantsOuvertsParLieux($equipe, $lieu);
    }

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

  // METIER : Conversion de la liste d'objets des restaurants en tableau simple pour JSON
  // RETOUR : Tableau des restaurants par lieu
  function convertForJsonListeRestaurantsParLieu($listeRestaurants)
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

  // METIER : Conversion du tableau d'objet des propositions en tableau simple pour JSON
  // RETOUR : Tableau des détails
  function convertForJsonListePropositions($propositions)
  {
    // Initialisations
    $listePopositionsAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($propositions as $proposition)
    {
      // Conversion des détails d'une proposition
      $detailsPropositionAConvertir = array();

      foreach ($proposition->getDetails() as $detailsProposition)
      {
        $ligneDetails = array('identifiant' => $detailsProposition->getIdentifiant(),
                              'pseudo'      => $detailsProposition->getPseudo(),
                              'avatar'      => $detailsProposition->getAvatar(),
                              'transports'  => $detailsProposition->getTransports(),
                              'horaire'     => $detailsProposition->getHoraire(),
                              'menu'        => $detailsProposition->getMenu()
                             );

        array_push($detailsPropositionAConvertir, $ligneDetails);
      }

      // Conversion d'une proposition
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
                                     'phone'           => formatPhoneNumber($proposition->getPhone()),
                                     'website'         => $proposition->getWebsite(),
                                     'plan'            => $proposition->getPlan(),
                                     'lafourchette'    => $proposition->getLafourchette(),
                                     'opened'          => $proposition->getOpened(),
                                     'min_price'       => $proposition->getMin_price(),
                                     'max_price'       => $proposition->getMax_price(),
                                     'description'     => $proposition->getDescription(),
                                     'details'         => $detailsPropositionAConvertir
                                    );

      // Ajout au tableau
      $listePopositionsAConvertir[$proposition->getId_restaurant()] = $propositionAConvertir;
    }

    // Retour
    return $listePopositionsAConvertir;
  }

  // METIER : Détermine la présence des différents boutons d'action
  // RETOUR : Tableau des actions
  function getActions($propositions, $mesChoix, $isSolo, $isReserved, $identifiant)
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
    if (date('N') > 5 OR date('H') >= 13)
    {
      $actions['saisir_choix']     = false;
      $actions['determiner']       = false;
      $actions['solo']             = false;
      $actions['choix']            = false;
      $actions['reserver']         = false;
      $actions['annuler_reserver'] = false;
      $actions['supprimer_choix']  = false;
      $actions['choix_rapide']     = false;
    }

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
      if ($isReserved == $identifiant AND date('N') <= 5 AND date('H') < 13)
        $actions['annuler_reserver'] = true;
    }

    // Retour
    return $actions;
  }

  // METIER : Récupère les utilisateurs faisant bande à part
  // RETOUR : Liste des utilisateurs
  function getSolos($equipe)
  {
    // Initialisations
    $solos = array();

    // Récupération de la liste des utilisateurs
    $identifiantsSolos = physiqueIdentifiantsSolos($equipe);

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
  function getNoPropositions($equipe)
  {
    // Initialisations
    $noPropositions = array();

    // Calcul des dates de la semaine
    $nombreJoursLundi    = 1 - date('N');
    $nombreJoursVendredi = 5 - date('N');
    $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
    $vendredi            = date('Ymd', strtotime('+' . $nombreJoursVendredi . ' days'));

    // Récupération de la liste des utilisateurs inscrits
    $listeUsers = physiqueUsers($equipe, $lundi, $vendredi);

    // Vérification nombre propositions de chaque utilisateur
    foreach ($listeUsers as $user)
    {
      $nombrePropositions = physiqueNombrePropositions($equipe, $user->getIdentifiant());

      if ($nombrePropositions == 0)
        array_push($noPropositions, $user);
    }

    // Retour
    return $noPropositions;
  }

  // METIER : Insère un choix "bande à part"
  // RETOUR : Aucun
  function setSolo($mesChoix, $isSolo, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_input');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('solo_time');

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
                    'team'          => $equipe,
                    'identifiant'   => $identifiant,
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
  function deleteSolo($sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_delete');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('delete_time_solo');

    // Suppression de l'enregistrement en base
    if ($control_ok == true)
      physiqueDeleteSolo($equipe, $identifiant);
  }

  // METIER : Insère ou met à jour une réservation
  // RETOUR : Aucun
  function insertReservation($post, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $appelant     = $sessionUser['identifiant'];
    $equipe       = $sessionUser['equipe'];
    $idRestaurant = $post['id_restaurant'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_reservation');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('reservation_time');

    // Récupération de la détermination
    if ($control_ok == true)
    {
      // Contrôle si détermination déjà existante
      $determinationExistante = physiqueDeterminationExistante($equipe);

      // Si la détermination existe déjà
      if ($determinationExistante == true)
      {
        // Récupération des données de la détermination
        $determination = physiqueDetermination($equipe);

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
                                       'team'          => $equipe,
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
  function deleteReservation($post, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant  = $sessionUser['identifiant'];
    $equipe       = $sessionUser['equipe'];
    $idRestaurant = $post['id_restaurant'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_reservation');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('delete_time_reservation');

    // Annulation réservation
    if ($control_ok == true)
      physiqueAnnulationReservation($idRestaurant, $equipe, $identifiant);
  }

  // METIER : Supprime les choix de tous les utilisateurs d'un restaurant et relance la détermination
  // RETOUR : Aucun
  function completeChoice($post, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant  = $sessionUser['identifiant'];
    $equipe       = $sessionUser['equipe'];
    $idRestaurant = $post['id_restaurant'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_delete');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('delete_time');

    // Suppression de tous les choix utilisateurs pour ce restaurant et relance de la détermination
    if ($control_ok == true)
    {
      // Suppression de tous les choix utilisateurs pour ce restaurant
      physiqueDeleteComplete($idRestaurant, $equipe);

      // Relance de la détermination si besoin
      relanceDetermination($sessionUser);
    }
  }

  // METIER : Récupère les choix de la semaine
  // RETOUR : Liste des choix de la semaine
  function getWeekChoices($equipe)
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

    for ($i = $lundi; $i <= $vendredi; $i = date('Ymd', strtotime($i . ' + 1 days')))
    {
      // Lecture des données du choix
      $choixSemaine = physiqueDonneesResume($equipe, $i);

      if (!empty($choixSemaine))
      {
        // Lecture des données du restaurant
        $restaurant = physiqueDonneesRestaurant($choixSemaine->getId_restaurant());

        // Nombre de participants
        $nombreParticipants = physiqueNombreParticipants($choixSemaine->getId_restaurant(), $i);

        // Récupération pseudo et avatar
        $user = physiqueUser($choixSemaine->getCaller());

        // Concaténation des données
        $choixSemaine->setName($restaurant->getName());
        $choixSemaine->setPicture($restaurant->getPicture());
        $choixSemaine->setLocation($restaurant->getLocation());
        $choixSemaine->setNb_participants($nombreParticipants);

        if (!empty($user))
        {
          $choixSemaine->setPseudo($user->getPseudo());
          $choixSemaine->setAvatar($user->getAvatar());
        }
      }

      // On ajoute la ligne au tableau
      $jour                               = date('N', strtotime($i));
      $listeChoixSemaine[$semaine[$jour]] = $choixSemaine;
    }

    // Retour
    return $listeChoixSemaine;
  }

  // METIER : Insère un ou plusieurs choix utilisateur
  // RETOUR : Aucun
  function insertChoices($post, $isSolo, $sessionUser)
  {
    // Initialisations
    $maxChoices = 5;
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_input');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('input_time');

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
          $choixNonExistant = controleChoixExistant($post['select_restaurant'][$i], $identifiant, $equipe, 'wrong_choice_already');

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
                         'team'          => $equipe,
                         'identifiant'   => $identifiant,
                         'date'          => $date,
                         'time'          => $time,
                         'transports'    => $transports,
                         'menu'          => $menu
                        );

          physiqueInsertionChoix($choix);

          // Relance de la détermination si besoin
          relanceDetermination($sessionUser);
        }
      }
    }
  }

  // METIER : Insère un ou plusieurs choix utilisateur
  // RETOUR : Aucun
  function insertChoicesMobile($post, $isSolo, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant      = $sessionUser['identifiant'];
    $equipe           = $sessionUser['equipe'];
    $listeRestaurants = array_keys($post['restaurants']);

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_input');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('input_time');

    // Contrôle bande à part
    if ($control_ok == true)
      $control_ok = controleSoloSaisie($isSolo);

    // Contrôle choix déjà existant (non bloquant)
    if ($control_ok == true)
    {
      // On parcourt tous les choix
      if (!empty($listeRestaurants))
      {
        foreach ($listeRestaurants as $keyId => $idRestaurant)
        {
          // Contrôle choix existant
          $choixNonExistant = controleChoixExistant($idRestaurant, $identifiant, $equipe, 'wrong_choice_already');

          // On supprime la ligne du tableau si déjà saisi
          if ($choixNonExistant == false)
            unset($listeRestaurants[$keyId]);
        }
      }
    }

    // Contrôle restaurant ouvert (non bloquant)
    if ($control_ok == true)
    {
      if (!empty($listeRestaurants))
      {
        foreach ($listeRestaurants as $keyId => $idRestaurant)
        {
          // Lecture des données du restaurant
          $restaurant = physiqueDonneesRestaurant($idRestaurant);

          // Contrôle restaurant ouvert
          $restaurantOuvert = controleRestaurantOuvert($restaurant->getOpened());

          // On supprime la ligne du tableau si le restaurant n'est pas ouvert
          if ($restaurantOuvert == false)
            unset($listeRestaurants[$keyId]);
        }
      }
    }

    // Récupération des données et insertion des enregistrements en base
    if ($control_ok == true)
    {
      if (!empty($listeRestaurants))
      {
        foreach ($listeRestaurants as $idRestaurant)
        {
          // Date de saisie
          $date = date('Ymd');

          // Heure choisie
          $time = '';

          // Transports choisis
          $transports = '';

          // Menu
          $menu = ';;;';

          // Insertion de l'enregistrement en base
          $choix = array('id_restaurant' => $idRestaurant,
                         'team'          => $equipe,
                         'identifiant'   => $identifiant,
                         'date'          => $date,
                         'time'          => $time,
                         'transports'    => $transports,
                         'menu'          => $menu
                        );

          physiqueInsertionChoix($choix);

          // Relance de la détermination si besoin
          relanceDetermination($sessionUser);
        }
      }
    }
  }

  // METIER : Met à jour un choix
  // RETOUR : Aucun
  function updateChoice($post, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idChoix = $post['id_choix'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_input');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('input_time');

    // Récupération des données et modification de l'enregistrements en base
    if ($control_ok == true)
    {
      // Heure choisie
      if (isset($post['select_heures_' . $idChoix])  AND !empty($post['select_heures_' . $idChoix])
      AND isset($post['select_minutes_' . $idChoix]) AND !empty($post['select_minutes_' . $idChoix]))
        $time = $post['select_heures_' . $idChoix] . $post['select_minutes_' . $idChoix];
      else
        $time = '';

      // Transports choisis
      $transports = '';

      if (isset($post['checkbox_feet_' . $idChoix]) AND $post['checkbox_feet_' . $idChoix] == 'F')
        $transports .= $post['checkbox_feet_' . $idChoix] . ';';

      if (isset($post['checkbox_bike_' . $idChoix]) AND $post['checkbox_bike_' . $idChoix] == 'B')
        $transports .= $post['checkbox_bike_' . $idChoix] . ';';

      if (isset($post['checkbox_tram_' . $idChoix]) AND $post['checkbox_tram_' . $idChoix] == 'T')
        $transports .= $post['checkbox_tram_' . $idChoix] . ';';

      if (isset($post['checkbox_car_' . $idChoix])  AND $post['checkbox_car_' . $idChoix]  == 'C')
        $transports .= $post['checkbox_car_' . $idChoix] . ';';

      // Menu saisi
      $menu = '';

      if (!isset($post['update_entree_' . $idChoix]) AND !isset($post['update_plat_' . $idChoix]) AND !isset($post['update_dessert_' . $idChoix]))
        $menu .= ';;;';
      else
      {
        $menu .= str_replace(';', ' ', $post['update_entree_' . $idChoix]) . ';';
        $menu .= str_replace(';', ' ', $post['update_plat_' . $idChoix]) . ';';
        $menu .= str_replace(';', ' ', $post['update_dessert_' . $idChoix]) . ';';
      }

      // Modification de l'enregistrement en base
      $choix = array('time'       => $time,
                     'transports' => $transports,
                     'menu'       => $menu
                    );

      physiqueUpdateChoix($idChoix, $choix, $identifiant);
    }
  }

  // METIER : Supprime un choix utilisateur
  // RETOUR : Aucun
  function deleteChoice($post, $sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $equipe  = $sessionUser['equipe'];
    $idChoix = $post['id_choix'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_delete');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('delete_time');

    // Vérification appelant à la suppression du choix
    if ($control_ok == true)
    {
      // Récupération des données du choix
      $choix = physiqueChoix($idChoix);

      // Récupération des données de la détermination si correspondantes
      $determinationExistante = physiqueDeterminationExistanteUser($choix->getIdentifiant(), $equipe);

      if ($determinationExistante == true)
      {
        // Génération succès (pour l'appelant si supprimé)
        insertOrUpdateSuccesValue('star-chief', $choix->getIdentifiant(), -1);

        // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
        physiqueDeleteDeterminationRestaurantUser($choix->getId_restaurant(), $equipe, $choix->getIdentifiant());
      }

      // Suppression de l'enregistrement en base
      physiqueDeleteChoix($idChoix);

      // Relance de la détermination si besoin
      relanceDetermination($sessionUser);
    }
  }

  // METIER : Supprime tous les choix utilisateur
  // RETOUR : Aucun
  function deleteAllChoices($sessionUser)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant = $sessionUser['identifiant'];
    $equipe      = $sessionUser['equipe'];

    // Contrôle date de saisie
    $control_ok = controleDateSaisie('week_end_delete');

    // Contrôle heure de saisie
    if ($control_ok == true)
      $control_ok = controleHeureSaisie('delete_time');

    // Vérification appelant à la suppression du choix
    if ($control_ok == true)
    {
      // Récupération des données de la détermination si correspondantes
      $determinationExistante = physiqueDeterminationExistanteUser($identifiant, $equipe);

      if ($determinationExistante == true)
      {
        // Récupération des données de la détermination
        $determination = physiqueDetermination($equipe);

        // Génération succès (pour l'appelant si supprimé)
        insertOrUpdateSuccesValue('star-chief', $determination->getCaller(), -1);

        // Suppression détermination si existante (restaurant = choix, date = jour, caller = utilisateur)
        physiqueDeleteDeterminationUser($determination->getId_restaurant(), $equipe, $determination->getCaller());
      }

      // Suppression des enregistrements en base
      physiqueDeleteTousChoix($equipe, $identifiant);

      // Relance de la détermination si besoin
      relanceDetermination($sessionUser);
    }
  }

  // METIER : Insère un choix dans le résumé
  // RETOUR : Aucun
  function insertResume($post, $equipe)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $jourSaisie   = $post['num_jour'];
    $idRestaurant = $post['select_restaurant_resume_' . $jourSaisie];

    // Calcul date à insérer
    $jourCourant = date('N');
    $ecartJours  = $jourCourant - $jourSaisie;
    $dateSaisie  = date('Ymd', strtotime('now - ' . $ecartJours . ' Days'));

    // Contrôle choix déjà existant
    $control_ok = controleChoixExistantDate($dateSaisie, $equipe);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $resume = array('id_restaurant' => $idRestaurant,
                      'team'          => $equipe,
                      'date'          => $dateSaisie,
                      'caller'        => '',
                      'reserved'      => 'N'
                     );

      physiqueInsertionDetermination($resume);
    }
  }

  // METIER : Supprime un choix dans le résumé
  // RETOUR : Aucun
  function deleteResume($post, $equipe)
  {
    // Récupération des données
    $idResume   = $post['id_resume'];
    $dateResume = $post['date_resume'];

    // Suppression de l'enregistrement en base
    physiqueDeleteResume($idResume, $equipe, $dateResume);
  }
?>
