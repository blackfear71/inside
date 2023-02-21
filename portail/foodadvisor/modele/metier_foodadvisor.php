<?php    
    // METIER : Contrôle date valide
    // RETOUR : Booléen
    function controlDateValide($date)
    {
        // Initialisations
        $erreurDate = false;

        // Vérification date du jour
        $control_ok = controleDateValide($date);

        if ($control_ok == false)
            $erreurDate = true;

        // Retour
        return $erreurDate;
    }

    // METIER : Récupération des jours de la semaine en fonction de la date
    // RETOUR : Tableau des jours
    function getJoursSemaine($date)
    {
        // Initialisations
        $joursSemaine = array();

        // Calcul des dates de la semaine
        $nombreJoursLundi    = 1 - date('N', strtotime($date));
        $nombreJoursVendredi = 5 - date('N', strtotime($date));
        $lundi               = date('Ymd', strtotime($date . '+' . $nombreJoursLundi . ' days'));
        $vendredi            = date('Ymd', strtotime($date . '+' . $nombreJoursVendredi . ' days'));

        for ($i = $lundi; $i <= $vendredi; $i = date('Ymd', strtotime($i . ' + 1 days')))
        {
            // Formatage de la date
            $dateWeb    = '';
            $dateMobile = '';

            switch (date('N', strtotime($i)))
            {
                case 1:
                    $dateWeb    = 'Lundi';
                    $dateMobile = 'Lu';
                    break;

                case 2:
                    $dateWeb    = 'Mardi';
                    $dateMobile = 'Ma';
                    break;

                case 3:
                    $dateWeb    = 'Mercredi';
                    $dateMobile = 'Me';
                    break;

                case 4:
                    $dateWeb    = 'Jeudi';
                    $dateMobile = 'Je';
                    break;

                case 5:
                    $dateWeb    = 'Vendredi';
                    $dateMobile = 'Ve';
                    break;

                default:
                    break;
            }

            // Ajout du jour au tableau
            if (!empty($dateWeb) AND !empty($dateMobile))
            {
                $jourCourant = array(
                    'date'   => $i,
                    'web'    => $dateWeb,
                    'mobile' => $dateMobile
                );
    
                array_push($joursSemaine, $jourCourant);
            }
        }

        // Retour
        return $joursSemaine;
    }

    // METIER : Récupération et filtrage la liste des restaurants ouverts
    // RETOUR : Liste des restaurants filtrés
    function getListeRestaurantsOuverts($listeRestaurantsResume, $date)
    {
        // Initialisations
        $listeRestaurants = array();

        // Filtrage de la liste des restaurants ouverts dans la semaine pour chaque lieu
        if ($date >= date('Ymd') AND date('N', strtotime($date)) <= 5)
        {
            foreach ($listeRestaurantsResume as $lieu => $restaurantsParLieux)
            {
                foreach ($restaurantsParLieux as $restaurant)
                {
                    // Vérification restaurant ouvert ce jour
                    $explodedOpened = array_filter(explode(';', $restaurant->getOpened()));

                    if (isset($explodedOpened[date('N', strtotime($date)) - 1]) AND $explodedOpened[date('N', strtotime($date)) - 1] == 'Y')
                    {
                        if (!isset($listeRestaurants[$lieu]))
                            $listeRestaurants[$lieu] = array();
    
                        array_push($listeRestaurants[$lieu], $restaurant);
                    }
                }
            }
        }

        // Retour
        return $listeRestaurants;
    }

    // METIER : Extrait la liste des lieux avec au moins 1 restaurant ouvert
    // RETOUR : Liste des lieux filtrés
    function getLieuxFiltres($listeRestaurants)
    {
        // Récupération des lieux ayant au moins 1 restaurant ouvert
        $listeLieux = array_keys($listeRestaurants);

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
                $restaurantAConvertir = array(
                    'id'   => $restaurant->getId(),
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
                $ligneDetails = array(
                    'identifiant' => $detailsProposition->getIdentifiant(),
                    'pseudo'      => $detailsProposition->getPseudo(),
                    'avatar'      => $detailsProposition->getAvatar(),
                    'transports'  => $detailsProposition->getTransports(),
                    'horaire'     => $detailsProposition->getHoraire(),
                    'menu'        => $detailsProposition->getMenu()
                );

                array_push($detailsPropositionAConvertir, $ligneDetails);
            }

            // Conversion d'une proposition
            $propositionAConvertir = array(
                'id_restaurant'   => $proposition->getId_restaurant(),
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
    function getActions($propositions, $mesChoix, $isSolo, $isReserved, $identifiant, $date)
    {
        // Initialisations
        $actions = array(
            'saisir_choix'     => true,
            'determiner'       => true,
            'solo'             => true,
            'choix'            => true,
            'reserver'         => true,
            'annuler_reserver' => false,
            'supprimer_choix'  => true,
            'choix_rapide'     => true
        );

        // Contrôle date et heure - toutes actions
        if (($date != date('Ymd') AND date('N', strtotime($date)) > 5)
        OR  ($date == date('Ymd') AND date('N') > 5)
        OR  ($date == date('Ymd') AND date('N') <= 5 AND date('H') >= 13))
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

        // Contrôle saisie choix
        if ($actions['saisir_choix'] == true)
        {
            if ($date < date('Ymd') OR $isSolo == true OR !empty($isReserved))
                $actions['saisir_choix'] = false;
        }

        // Contrôle détermination (limitée à la date du jour)
        if ($actions['determiner'] == true)
        {
            if ($date != date('Ymd') OR empty($propositions) OR empty($mesChoix) OR !empty($isReserved))
                $actions['determiner'] = false;
        }

        // Contrôle bande à part
        if ($actions['solo'] == true)
        {
            if ($date < date('Ymd') OR !empty($mesChoix) OR $isSolo == true)
                $actions['solo'] = false;
        }

        // Contrôle actions choix utilisateurs
        if ($actions['choix'] == true)
        {
            if ($date < date('Ymd'))
                $actions['choix'] = false;
        }

        // Contrôle réservation
        if ($actions['reserver'] == true)
        {
            if ($date < date('Ymd') OR !empty($isReserved))
                $actions['reserver'] = false;
        }

        // Contrôle annulation réservation
        if ($actions['reserver'] == false)
        {
            if ((($date == date('Ymd') AND date('N') <= 5 AND date('H') < 13)
            OR   ($date >  date('Ymd') AND date('N', strtotime($date)) <= 5))
            AND $isReserved == $identifiant)
                $actions['annuler_reserver'] = true;
        }

        // Contrôle suppression choix
        if ($actions['supprimer_choix'] == true)
        {
            if ($date < date('Ymd') OR empty($mesChoix) OR $isSolo == true)
                $actions['supprimer_choix'] = false;
        }

        // Contrôle choix rapide
        if ($actions['choix_rapide'] == true)
        {
            if ($date < date('Ymd') OR $isSolo == true)
                $actions['choix_rapide'] = false;
        }

        // Retour
        return $actions;
    }

    // METIER : Récupère les utilisateurs faisant bande à part
    // RETOUR : Liste des utilisateurs
    function getSolos($equipe, $date)
    {
        // Initialisations
        $solos = array();

        // On ne récupère les utilisateurs qui font bande à part que si la date sélectionnée est un jour de la semaine
        if (date('N', strtotime($date)) <= 5)
        {
            // Récupération de la liste des utilisateurs
            $identifiantsSolos = physiqueIdentifiantsSolos($equipe, $date);

            // Récupération des données utilisateurs
            foreach ($identifiantsSolos as $identifiantSolo)
            {
                // On ajoute la ligne au tableau
                array_push($solos, physiqueUser($identifiantSolo));
            }
        }

        // Retour
        return $solos;
    }

    // METIER : Récupère les utilisateurs qui n'ont pas fait de propositions
    // RETOUR : Liste des utilisateurs
    function getNoPropositions($equipe, $date)
    {
        // Initialisations
        $noPropositions = array();

        // Récupération de la liste des utilisateurs inscrits à la date
        $listeUsers = physiqueUsers($equipe, $date);

        // Vérification nombre propositions de chaque utilisateur
        foreach ($listeUsers as $user)
        {
            $nombrePropositions = physiqueNombrePropositions($equipe, $user->getIdentifiant(), $date);

            if ($nombrePropositions == 0)
                array_push($noPropositions, $user);
        }

        // Retour
        return $noPropositions;
    }

    // METIER : Insère un choix "bande à part"
    // RETOUR : Aucun
    function setSolo($mesChoix, $isSolo, $sessionUser, $date)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);

        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_input');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'solo_time');

        // Contrôle déjà solo
        if ($control_ok == true)
            $control_ok = controleAlreadySolo($isSolo);

        // Contrôle autres votes
        if ($control_ok == true)
            $control_ok = controleAlreadyVoted($mesChoix);

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $solo = array(
                'id_restaurant' => 0,
                'team'          => $equipe,
                'identifiant'   => $identifiant,
                'date'          => $date,
                'time'          => '',
                'transports'    => '',
                'menu'          => ';;;'
            );

            physiqueInsertionChoix($solo);
        }
    }

    // METIER : Supprime un choix "bande à part"
    // RETOUR : Aucun
    function deleteSolo($sessionUser, $date)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);

        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_delete');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'delete_time_solo');

        // Suppression de l'enregistrement en base
        if ($control_ok == true)
            physiqueDeleteSolo($equipe, $identifiant, $date);
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
        $date         = $post['date'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);

        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_reservation');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'reservation_time');

        // Récupération de la détermination
        if ($control_ok == true)
        {
            // Contrôle si détermination déjà existante
            $determinationExistante = physiqueDeterminationExistante($equipe, $date);

            // Si la détermination existe déjà
            if ($determinationExistante == true)
            {
                // Récupération des données de la détermination
                $determination = physiqueDetermination($equipe, $date);

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
                $nouvelleDetermination = array(
                    'id_restaurant' => $idRestaurant,
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
                $nouvelleDetermination = array(
                    'id_restaurant' => $idRestaurant,
                    'team'          => $equipe,
                    'date'          => $date,
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
        $date         = $post['date'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);
            
        // Contrôle date de saisie (format)
        if ($control_ok == true)
            $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);

        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_reservation');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'delete_time_reservation');

        // Annulation réservation
        if ($control_ok == true)
            physiqueAnnulationReservation($idRestaurant, $equipe, $identifiant, $date);
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
        $date         = $post['date'];

        // Contrôle date de saisie (week-end)
        $control_ok = controleDateSaisieWeekEnd($date, 'week_end_delete');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'delete_time');

        // Suppression de tous les choix utilisateurs pour ce restaurant et relance de la détermination
        if ($control_ok == true)
        {
            // Suppression de tous les choix utilisateurs pour ce restaurant
            physiqueDeleteComplete($idRestaurant, $equipe, $date);

            // Relance de la détermination si besoin
            relanceDetermination($sessionUser, $date);
        }
    }

    // METIER : Récupère les choix de la semaine
    // RETOUR : Liste des choix de la semaine
    function getWeekChoices($equipe, $joursSemaine)
    {
        // Initialisations
        $listeChoixSemaine = array();

        // Récupération du résumé de la semaine à la date demandée
        foreach ($joursSemaine as $jourSemaine)
        {
            // Lecture des données du choix
            $choixSemaine = array(
                'date'  => $jourSemaine['date'],
                'jour'  => $jourSemaine['web'],
                'choix' => physiqueDonneesResume($equipe, $jourSemaine['date'])
            );

            if (!empty($choixSemaine['choix']))
            {
                // Lecture des données du restaurant
                $restaurant = physiqueDonneesRestaurant($choixSemaine['choix']->getId_restaurant());

                // Nombre de participants
                $nombreParticipants = physiqueNombreParticipants($choixSemaine['choix']->getId_restaurant(), $jourSemaine['date']);

                // Récupération pseudo et avatar
                $user = physiqueUser($choixSemaine['choix']->getCaller());

                // Concaténation des données
                $choixSemaine['choix']->setName($restaurant->getName());
                $choixSemaine['choix']->setPicture($restaurant->getPicture());
                $choixSemaine['choix']->setLocation($restaurant->getLocation());
                $choixSemaine['choix']->setNb_participants($nombreParticipants);

                if (!empty($user))
                {
                    $choixSemaine['choix']->setPseudo($user->getPseudo());
                    $choixSemaine['choix']->setAvatar($user->getAvatar());
                }
            }

            // On ajoute la ligne au tableau
            array_push($listeChoixSemaine, $choixSemaine);
        }

        // Retour
        return $listeChoixSemaine;
    }

    // METIER : Contrôle date du jour sélectionné pour la détermination
    // RETOUR : Aucun
    function controlDateDetermination($date)
    {
        // Initialisations
        $erreurDetermination = false;

        // Vérification date du jour sélectionné
        $control_ok = controleDateDetermination($date);

        if ($control_ok == false)
            $erreurDetermination = true;

        // Retour
        return $erreurDetermination;
    }

    // METIER : Insère un ou plusieurs choix utilisateur
    // RETOUR : Aucun
    function insertChoices($post, $isSolo, $sessionUser)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant      = $sessionUser['identifiant'];
        $equipe           = $sessionUser['equipe'];
        $listeRestaurants = array_keys($post['restaurants']);
        $date             = $post['date'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);

        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_input');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'input_time');

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
                    $choixNonExistant = controleChoixExistant($idRestaurant, $identifiant, $equipe, $date, 'wrong_choice_already');

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
                    $restaurantOuvert = controleRestaurantOuvert($restaurant->getOpened(), $date);

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
                    // Heure choisie
                    if (isset($post['select_heures'][$idRestaurant])  AND !empty($post['select_heures'][$idRestaurant])
                    AND isset($post['select_minutes'][$idRestaurant]) AND !empty($post['select_minutes'][$idRestaurant]))
                        $time = $post['select_heures'][$idRestaurant] . $post['select_minutes'][$idRestaurant];
                    else
                        $time = '';

                    // Transports choisis
                    $transports = '';

                    if (isset($post['checkbox_feet'][$idRestaurant]) AND $post['checkbox_feet'][$idRestaurant] == 'F')
                        $transports .= $post['checkbox_feet'][$idRestaurant] . ';';

                    if (isset($post['checkbox_bike'][$idRestaurant]) AND $post['checkbox_bike'][$idRestaurant] == 'B')
                        $transports .= $post['checkbox_bike'][$idRestaurant] . ';';

                    if (isset($post['checkbox_tram'][$idRestaurant]) AND $post['checkbox_tram'][$idRestaurant] == 'T')
                        $transports .= $post['checkbox_tram'][$idRestaurant] . ';';

                    if (isset($post['checkbox_car'][$idRestaurant])  AND $post['checkbox_car'][$idRestaurant]  == 'C')
                        $transports .= $post['checkbox_car'][$idRestaurant] . ';';

                    // Menu
                    $menu = '';

                    if (!isset($post['saisie_entree'][$idRestaurant]) AND !isset($post['saisie_plat'][$idRestaurant]) AND !isset($post['saisie_dessert'][$idRestaurant]))
                        $menu .= ';;;';
                    else
                    {
                        $menu .= str_replace(';', ' ', $post['saisie_entree'][$idRestaurant]) . ';';
                        $menu .= str_replace(';', ' ', $post['saisie_plat'][$idRestaurant]) . ';';
                        $menu .= str_replace(';', ' ', $post['saisie_dessert'][$idRestaurant]) . ';';
                    }

                    // Insertion de l'enregistrement en base
                    $choix = array(
                        'id_restaurant' => $idRestaurant,
                        'team'          => $equipe,
                        'identifiant'   => $identifiant,
                        'date'          => $date,
                        'time'          => $time,
                        'transports'    => $transports,
                        'menu'          => $menu
                    );

                    physiqueInsertionChoix($choix);

                    // Relance de la détermination si besoin
                    relanceDetermination($sessionUser, $date);
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
        $date    = $post['date'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);
            
        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_input');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'input_time');

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
            $choix = array(
                'time'       => $time,
                'transports' => $transports,
                'menu'       => $menu
            );

            physiqueUpdateChoix($idChoix, $choix, $identifiant, $date);
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
        $date    = $post['date'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);
            
        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_delete');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'delete_time');

        // Vérification appelant à la suppression du choix
        if ($control_ok == true)
        {
            // Récupération des données du choix
            $choix = physiqueChoix($idChoix);

            // Récupération des données de la détermination si correspondantes
            $determinationExistante = physiqueDeterminationExistanteUser($choix->getIdentifiant(), $equipe, $date);

            if ($determinationExistante == true)
            {
                // Génération succès (pour l'appelant si supprimé)
                insertOrUpdateSuccesValue('star-chief', $choix->getIdentifiant(), -1);

                // Suppression détermination si existante (restaurant = choix, équipe = équipe utilisateur, caller = utilisateur, date = jour sélectionné)
                physiqueDeleteDeterminationRestaurantUser($choix->getId_restaurant(), $equipe, $choix->getIdentifiant(), $date);
            }

            // Suppression de l'enregistrement en base
            physiqueDeleteChoix($idChoix);

            // Relance de la détermination si besoin
            relanceDetermination($sessionUser, $date);
        }
    }

    // METIER : Supprime tous les choix utilisateur
    // RETOUR : Aucun
    function deleteAllChoices($sessionUser, $date)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);

        // Contrôle date de saisie (jour)
        if ($control_ok == true)
            $control_ok = controleDateSaisie($date);

        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_delete');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'delete_time');

        // Vérification appelant à la suppression du choix
        if ($control_ok == true)
        {
            // Récupération des données de la détermination si correspondantes
            $determinationExistante = physiqueDeterminationExistanteUser($identifiant, $equipe, $date);

            if ($determinationExistante == true)
            {
                // Récupération des données de la détermination
                $determination = physiqueDetermination($equipe, $date);

                // Génération succès (pour l'appelant si supprimé)
                insertOrUpdateSuccesValue('star-chief', $determination->getCaller(), -1);

                // Suppression détermination si existante (restaurant = choix, équipe = équipe utilisateur, caller = utilisateur, date = jour sélectionné)
                physiqueDeleteDeterminationUser($determination->getId_restaurant(), $equipe, $determination->getCaller(), $date);
            }

            // Suppression des enregistrements en base
            physiqueDeleteTousChoix($equipe, $identifiant, $date);

            // Relance de la détermination si besoin
            relanceDetermination($sessionUser, $date);
        }
    }

    // METIER : Insère un choix dans le résumé
    // RETOUR : Aucun
    function insertResume($post, $equipe)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $date         = $post['date_resume'];
        $idRestaurant = $post['select_restaurant_resume_' . $date];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);
            
        // Contrôle choix déjà existant
        if ($control_ok == true)
            $control_ok = controleChoixExistantDate($date, $equipe);

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $resume = array(
                'id_restaurant' => $idRestaurant,
                'team'          => $equipe,
                'date'          => $date,
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
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $idResume = $post['id_resume'];
        $date     = $post['date_resume'];

        // Contrôle date de saisie (format)
        $control_ok = controleFormatDate($date);
    
        // Suppression de l'enregistrement en base
        if ($control_ok == true)
            physiqueDeleteResume($idResume, $equipe, $date);
    }
?>