<?php
    include_once('../../includes/classes/profile.php');
    include_once('../../includes/classes/restaurants.php');

    // METIER : Récupération de la liste des lieux
    // RETOUR : Liste des lieux
    function getLieux($equipe)
    {
        // Récupération de la liste des lieux
        $listeLieux = physiqueLieux($equipe);

        // Retour
        return $listeLieux;
    }

    // METIER : Récupération de la liste des restaurants par lieu
    // RETOUR : Liste des restaurants
    function getListeRestaurants($listeLieux, $equipe)
    {
        // Initialisations
        $listeRestaurants = array();

        // Récupération de la liste des restaurants pour chaque lieu
        foreach ($listeLieux as $lieu)
        {
            $listeRestaurants[htmlspecialchars($lieu)] = physiqueRestaurantsParLieux($lieu, $equipe);
        }

        // Retour
        return $listeRestaurants;
    }

    // METIER : Détermine si l'utilisateur fait bande à part
    // RETOUR : Booléen
    function getSolo($sessionUser, $date)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Vérification si bande à part
        $solo = physiqueSolo($identifiant, $equipe, $date);

        // Retour
        return $solo;
    }

    // METIER : Récupère les choix du jour sélectionné
    // RETOUR : Liste des choix du jour sélectionné (tous)
    function getPropositions($equipe, $date, $recuperationDetails)
    {
        // Initialisations
        $listePropositions = array();

        // On ne récupère les propositions que si la date sélectionnée est un jour de la semaine
        if (date('N', strtotime($date)) <= 5)
        {
            // Récupération des utilisateurs pour la détermination et les détails
            if ($recuperationDetails == true)
                $listeUtilisateurs = physiqueUsersPropositions($equipe, $date);
            
            // Récupération des différents restaurants proposés
            $listePropositions = physiquePropositions($equipe, $date);

            // Récupération des données de chaque proposition
            foreach ($listePropositions as $proposition)
            {
                // Lecture des données restaurant
                $restaurant = physiqueDonneesRestaurant($proposition->getId_restaurant());

                // Ajout des données restaurant à la proposition
                $proposition->setName($restaurant->getName());
                $proposition->setPicture($restaurant->getPicture());
                $proposition->setLocation($restaurant->getLocation());
                $proposition->setTypes($restaurant->getTypes());
                $proposition->setPhone($restaurant->getPhone());
                $proposition->setWebsite($restaurant->getWebsite());
                $proposition->setPlan($restaurant->getPlan());
                $proposition->setLafourchette($restaurant->getLafourchette());
                $proposition->setOpened($restaurant->getOpened());
                $proposition->setMin_price(str_replace('.', ',', $restaurant->getMin_price()));
                $proposition->setMax_price(str_replace('.', ',', $restaurant->getMax_price()));
                $proposition->setDescription($restaurant->getDescription());

                // Détermination si restaurant disponible ce jour
                $availableDay   = true;
                $explodedOpened = array_filter(explode(';', $proposition->getOpened()));

                foreach ($explodedOpened as $keyOpened => $opened)
                {
                    if (date('N', strtotime($date)) == $keyOpened + 1 AND $opened == 'N')
                    {
                        $availableDay = false;
                        break;
                    }
                }

                // Récupération des données si restaurant ouvert ou jour passé
                if (($date >= date('Ymd') AND $availableDay == true) OR $date < date('Ymd'))
                {
                    // Nombre de participants
                    $proposition->setNb_participants(physiqueNombreParticipants($proposition->getId_restaurant(), $date));

                    // Vérification proposition déterminée
                    $propositionDeterminee = physiquePropositionDeterminee($proposition->getId_restaurant(), $date);

                    // Récupération des données de la proposition déterminée
                    if (!empty($propositionDeterminee))
                    {
                        // Lecture des données proposition déterminée
                        $proposition->setDetermined('Y');
                        $proposition->setCaller($propositionDeterminee->getCaller());
                        $proposition->setReserved($propositionDeterminee->getReserved());
                    }

                    // Récupération détails proposition
                    if ($recuperationDetails == true)
                    {
                        // Recherche pseudo et avatar appelant
                        if (!empty($propositionDeterminee) AND !empty($proposition->getCaller()))
                        {
                            if (isset($listeUtilisateurs[$proposition->getCaller()]))
                            {
                                $proposition->setPseudo($listeUtilisateurs[$proposition->getCaller()]['pseudo']);
                                $proposition->setAvatar($listeUtilisateurs[$proposition->getCaller()]['avatar']);
                            }
                        }

                        // Lecture détail de chaque utilisateur
                        $detailsProposition = physiqueDetailsProposition($proposition->getId_restaurant(), $date);

                        // Recherche pseudo et avatar utilisateurs
                        foreach ($detailsProposition as $detail)
                        {
                            if (isset($listeUtilisateurs[$detail->getIdentifiant()]))
                            {
                                $detail->setPseudo($listeUtilisateurs[$detail->getIdentifiant()]['pseudo']);
                                $detail->setAvatar($listeUtilisateurs[$detail->getIdentifiant()]['avatar']);
                            }
                        }

                        // Récupération des détails
                        $proposition->setDetails($detailsProposition);
                    }
                }
            }

            // Tris
            if (!empty($listePropositions))
            {
                // Tri par nombre de participants pour affecter le classement
                foreach ($listePropositions as $proposition)
                {
                    $triNombreParticipants[] = $proposition->getNb_participants();
                }

                // Tri
                array_multisort($triNombreParticipants, SORT_DESC, $listePropositions);

                unset($triNombreParticipants);

                // Affectation du classement
                $prevNombreParticpants = 0;
                $currentClassement     = 0;

                foreach ($listePropositions as $proposition)
                {
                    $currentNombreParticipants = $proposition->getNb_participants();

                    if ($currentNombreParticipants != $prevNombreParticpants)
                    {
                        $currentClassement    += 1;
                        $prevNombreParticpants = $currentNombreParticipants;
                    }

                    // On enregistre le rang
                    $proposition->setClassement($currentClassement);
                }

                // Tri par détermination puis par nombre de participants pour affichage
                foreach ($listePropositions as $proposition)
                {
                    $triDetermined[]         = $proposition->getDetermined();
                    $triNombreParticipants[] = $proposition->getNb_participants();
                }

                // Tri
                array_multisort($triDetermined, SORT_DESC, $triNombreParticipants, SORT_DESC, $listePropositions);
            }
        }

        // Retour
        return $listePropositions;
    }

    // METIER : Récupère un des restaurants pouvant être déterminé ce jour
    // RETOUR : Id restaurant déterminé
    function getRestaurantDetermined($listePropositions, $date)
    {
        // Initialisations
        $idRestaurant = NULL;
        $control_ok   = true;

        // Contrôle date de détermination
        $control_ok = controleDateSaisieWeekEnd($date, 'week_end_determination');

        // Contrôle heure de détermination
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'determination_time');

        // Détermination Id restaurant aléatoire
        if ($control_ok == true)
        {
            // Extraction des Id en tête
            $idRestaurants = array();

            foreach ($listePropositions as $proposition)
            {
                if ($proposition->getClassement() == 1)
                    array_push($idRestaurants, $proposition->getId_restaurant());
            }

            // Détermination Id aléatoire
            $idRestaurant = $idRestaurants[array_rand($idRestaurants, 1)];
        }

        // Retour
        return $idRestaurant;
    }

    // METIER : Vérification si réservé et retour identifiant
    // RETOUR : Identifiant réservation
    function getReserved($equipe, $date)
    {
        // Récupération de l'identifiant de l'appelant
        $appelant = physiqueIdentifiantCaller($equipe, $date);

        // Retour
        return $appelant;
    }

    // METIER : Détermine un des participants du jour sélectionné n'ayant pas appelé dans la semaine correspondante
    // RETOUR : Participant pouvant appeler
    function getCallers($idRestaurant, $equipe, $date)
    {
        // Calcul des dates de la semaine
        $nombreJoursLundi    = 1 - date('N', strtotime($date));
        $nombreJoursVendredi = 5 - date('N', strtotime($date));
        $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
        $vendredi            = date('Ymd', strtotime('+' . $nombreJoursVendredi . ' days'));

        // Récupération de la liste des participants du jour sélectionné
        $listeParticipants = physiqueParticipants($idRestaurant, $date);

        // Récupération de la liste des appelants de la semaine
        $listeAppelants = physiqueAppelants($lundi, $vendredi, $equipe);

        // Filtrage des participants ayant déjà appelé
        $listeParticipantsFiltres = $listeParticipants;

        foreach ($listeParticipantsFiltres as $keyParticipant => $participant)
        {
            foreach ($listeAppelants as $appelant)
            {
                if ($participant == $appelant)
                {
                    unset($listeParticipantsFiltres[$keyParticipant]);
                    break;
                }
            }
        }

        // Détermination appelant aléatoire parmi ceux restant, ou par défaut une des personnes du jour sélectionné
        if (!empty($listeParticipantsFiltres))
            $caller = $listeParticipantsFiltres[array_rand($listeParticipantsFiltres, 1)];
        else
            $caller = $listeParticipants[array_rand($listeParticipants, 1)];

        // Retour
        return $caller;
    }

    // METIER : Récupère les choix de l'utilisateur
    // RETOUR : Liste des choix du jour sélectionné (utilisateur)
    function getMyChoices($sessionUser, $date)
    {
        // Initialisations
        $listeChoix = array();

        // On ne récupère les choix de l'utilisateur que si la date sélectionnée est un jour de la semaine
        if (date('N', strtotime($date)) <= 5)
        {
            // Récupération des données
            $identifiant = $sessionUser['identifiant'];
            $equipe      = $sessionUser['equipe'];

            // Récupération des choix de l'utilisateur
            $listeChoix = physiqueListeChoix($identifiant, $equipe, $date);

            // Ajout des informations des restaurants
            foreach ($listeChoix as $monChoix)
            {
                $restaurant = physiqueDonneesRestaurant($monChoix->getId_restaurant());

                $monChoix->setName($restaurant->getName());
                $monChoix->setPicture($restaurant->getPicture());
                $monChoix->setLocation($restaurant->getLocation());
                $monChoix->setOpened($restaurant->getOpened());
            }
        }

        // Retour
        return $listeChoix;
    }

    // METIER : Détermine celui qui réserve
    // RETOUR : Aucun
    function setDetermination($propositions, $idRestaurant, $appelant, $equipe, $date)
    {
        // Détermination s'il y a des propositions
        if (!empty($propositions))
        {
            // Contrôle si détermination déjà existante
            $determinationExistante = physiqueDeterminationExistante($equipe, $date);

            // Si la détermination existe déjà, mise à jour
            if ($determinationExistante == true)
            {
                // Récupération des données de la détermination
                $determination = physiqueDetermination($equipe, $date);

                // Modification de l'enregistrement en base
                $nouvelleDetermination = array(
                    'id_restaurant' => $idRestaurant,
                    'caller'        => $appelant,
                    'reserved'      => 'N'
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
                    'reserved'      => 'N'
                );

                physiqueInsertionDetermination($nouvelleDetermination);
            }

            // Génération succès (pour le nouvel appelant)
            insertOrUpdateSuccesValue('star-chief', $appelant, 1);
        }
    }

    // METIER : Relance la détermination
    // RETOUR : Aucun
    function relanceDetermination($sessionUser, $date)
    {
        // Récupération des données
        $equipe = $sessionUser['equipe'];

        // Contrôle si détermination déjà existante
        $determinationExistante = physiqueDeterminationExistante($equipe, $date);

        // Si une détermination du jour a déjà été effectuée, on doit relancer la détermination ou éventuellement la supprimer si c'était le dernier choix
        if ($determinationExistante == true)
        {
            // Recherche du nombre de choix restants
            $nombreChoixRestants = physiqueChoixRestants($equipe, $date);

            // Relance de la détermination si possible, sinon suppression
            if ($nombreChoixRestants > 0)
            {
                // Récupération des propositions (sans détails)
                $propositions = getPropositions($equipe, $date, false);

                // Récupération de l'Id du restaurant déterminé
                $idRestaurant = getRestaurantDetermined($propositions, $date);

                // Détermination si bande à part
                $isSolo = getSolo($sessionUser, $date);

                // Détermination si restaurant réservé
                $isReserved = getReserved($equipe, $date);

                // Lancement de la détermination
                if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
                AND (!isset($_SESSION['alerts']['determination_date'])     OR $_SESSION['alerts']['determination_date']     != true)
                AND (!isset($_SESSION['alerts']['determination_time'])     OR $_SESSION['alerts']['determination_time']     != true)
                AND  $isSolo != true AND empty($isReserved))
                {
                    // Récupération des appelants possibles
                    $appelant = getCallers($idRestaurant, $equipe, $date);

                    // Lancement de la détermination
                    setDetermination($propositions, $idRestaurant, $appelant, $equipe, $date);
                }
            }
            else
            {
                // Suppression de la détermination du jour
                physiqueDeleteDetermination($equipe, $date);
            }
        }
    }

    // METIER : Insère un choix rapide
    // RETOUR : Id restaurant
    function insertFastChoice($post, $isSolo, $sessionUser)
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
            
        // Contrôle date de saisie (week-end)
        if ($control_ok == true)
            $control_ok = controleDateSaisieWeekEnd($date, 'week_end_input');

        // Contrôle heure de saisie (du jour)
        if ($control_ok == true)
            $control_ok = controleHeureSaisie($date, 'input_time');

        // Contrôle bande à part
        if ($control_ok == true)
            $control_ok = controleSoloSaisie($isSolo);

        // Contrôle choix déjà existant
        if ($control_ok == true)
            $control_ok = controleChoixExistant($idRestaurant, $identifiant, $equipe, $date, 'wrong_fast');

        // Lecture des données restaurant
        if ($control_ok == true)
            $restaurant = physiqueDonneesRestaurant($idRestaurant);

        // Contrôle restaurant ouvert
        if ($control_ok == true)
            $control_ok = controleRestaurantOuvert($restaurant->getOpened(), $date);

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $choix = array(
                'id_restaurant' => $idRestaurant,
                'team'          => $equipe,
                'identifiant'   => $identifiant,
                'date'          => $date,
                'time'          => '',
                'transports'    => '',
                'menu'          => ';;;'
            );

            physiqueInsertionChoix($choix);

            // Relance de la détermination si besoin
            relanceDetermination($sessionUser, $date);
        }

        // Retour
        return $idRestaurant;
    }
?>