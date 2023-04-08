<?php
    include_once('../../includes/classes/expenses.php');
    include_once('../../includes/classes/profile.php');
    include_once('../../includes/classes/teams.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if (!isset($_SESSION['save']['user_ask_id']) OR !isset($_SESSION['save']['user_ask_name']) OR !isset($_SESSION['save']['new_password']))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['user_ask_id']   = '';
            $_SESSION['save']['user_ask_name'] = '';
            $_SESSION['save']['new_password']  = '';
        }
    }

    // METIER : Lecture liste des utilisateurs inscrits ou en cours d'inscription
    // RETOUR : Liste des utilisateurs
    function getUsersInscrits()
    {
        // Initialisations
        $listeUsersParEquipe = array();
        $inscriptionsEnCours = array('new_users' => array());

        // Récupération liste des utilisateurs
        $listeUsers = physiqueUsers();

        // Ajout à la liste par équipes
        foreach ($listeUsers as $user)
        {
            // Ajout de l'utilisateur à son équipe
            if (!empty($user->getTeam()))
            {
                $team = $user->getTeam();

                // Ajout de l'utilisateur à son équipe
                if (!isset($listeUsersParEquipe[$team]))
                    $listeUsersParEquipe[$team] = array();

                array_push($listeUsersParEquipe[$team], $user);
            }
            else
                array_push($inscriptionsEnCours['new_users'], $user);
        }

        // Tri
        ksort($listeUsersParEquipe);

        // S'il y a des utilisateurs en cours d'inscription, on les remet au début du tableau
        if (!empty($inscriptionsEnCours['new_users']))
            $listeUsersParEquipe = $inscriptionsEnCours + $listeUsersParEquipe;
        
        // Retour
        return $listeUsersParEquipe;
    }

    // METIER : Recherche les utilisateurs désinscrits
    // RETOUR : Liste des utilisateurs désinscrits
    function getUsersDesinscrits($listeUsersInscrits)
    {
        // Initialisations
        $listeUsersDesinscrits = array();
        
        // Liste des tables où chercher des identifiants (on ne cherche jamais dans la table "users")
        $listeTables = array(
            'bugs'                      => 'identifiant',
            'collector'                 => 'author',
            'cooking_box'               => 'identifiant',
            'expense_center'            => 'buyer',
            'expense_center_users'      => 'identifiant',
            'food_advisor_restaurants'  => 'identifiant_add',
            'ideas'                     => 'author',
            'movie_house'               => 'identifiant_add',
            'movie_house_comments'      => 'identifiant',
            'notifications'             => 'identifiant',
            'petits_pedestres_parcours' => 'identifiant_add'
        );

        // Récupération des identifiants dans les tables
        foreach ($listeTables as $table => $colonne)
        {
            // Lecture des identifiants dans les tables
            $identifiantsTable = physiqueIdentifiantsTable($table, $colonne);

            // Fusion des données dans le tableau complet
            $listeUsersDesinscrits = array_merge($listeUsersDesinscrits, $identifiantsTable);
        }

        // Suppression des doublons et tri
        $listeUsersDesinscrits = array_unique($listeUsersDesinscrits);
        sort($listeUsersDesinscrits);

        // Suppression de l'admin du tableau
        if (in_array('admin', $listeUsersDesinscrits))
            unset($listeUsersDesinscrits[array_search('admin', $listeUsersDesinscrits)]);

        // Filtrage avec les utilisateurs inscrits
        foreach ($listeUsersDesinscrits as $keyUserDesinscrit => $userDesinscrit)
        {
            foreach ($listeUsersInscrits as $equipeUsersInscrits)
            {
                foreach ($equipeUsersInscrits as $userInscrit)
                {
                    if ($userDesinscrit == $userInscrit->getIdentifiant())
                    {
                        unset($listeUsersDesinscrits[$keyUserDesinscrit]);
                        break;
                    }
                }
            }
        }

        // Retour
        return $listeUsersDesinscrits;
    }

    // METIER : Lecture de la liste des équipes
    // RETOUR : Liste des équipes
    function getListeEquipes()
    {
        // Lecture de la liste des équipes
        $listeEquipes = physiqueListeEquipes();

        // Retour
        return $listeEquipes;
    }

    // METIER : Lecture statistiques catégories des utilisateurs inscrits
    // RETOUR : Tableau des statistiques
    function getStatistiquesInscrits($listeUsersParEquipe)
    {
        // Initialisations
        $tableauStatistiques = array();

        // Récupération des statistiques par catégories
        foreach ($listeUsersParEquipe as $listeUsers)
        {
            foreach ($listeUsers as $user)
            {
                // Films ajoutés
                $nombreFilms = physiqueFilmsAjoutesUser($user->getIdentifiant());

                // Commentaires films
                $nombreComments = physiqueCommentairesFilmsUser($user->getIdentifiant());

                // Restaurants ajoutés
                $nombreRestaurants = physiqueRestaurantsAjoutesUser($user->getIdentifiant());

                // Réservations de restaurants
                $nombreReservations = physiqueReservationsUser($user->getIdentifiant());

                // Gâteaux de la semaine
                $nombreGateauxSemaine = physiqueGateauxSemaineUser($user->getIdentifiant());

                // Recettes partagées
                $nombreRecettes = physiqueRecettesUser($user->getIdentifiant());

                // Bilan des dépenses
                $bilanUser = physiqueBilanDepensesUser($user->getIdentifiant());

                // Phrases et images cultes ajoutées
                $nombreCollector = physiqueCollectorAjoutesUser($user->getIdentifiant());

                // Parcours ajoutés
                $nombreParcours = physiqueParcoursAjoutesUser($user->getIdentifiant());

                // Participations parcours
                $nombreParticipationsParcours = physiqueParticipationsParcoursUser($user->getIdentifiant());

                // Nombre de demandes (bugs / évolutions)
                $nombreBugsSoumis = physiqueBugsSoumisUser($user->getIdentifiant());

                // Nombre de demandes résolues (bugs / évolutions)
                $nombreBugsResolus = physiqueBugsStatutUser($user->getIdentifiant(), 'Y');

                // Nombre de demandes rejetés (bugs / évolutions)
                $nombreBugsRejetes = physiqueBugsStatutUser($user->getIdentifiant(), 'R');

                // Nombre d'idées publiées
                $nombreTheBox = physiqueTheBoxUser($user->getIdentifiant());

                // Nombre d'idées en charge
                $nombreTheBoxEnCharge = physiqueTheBoxEnChargeUser($user->getIdentifiant());

                // Nombre d'idées terminées ou rejetées
                $nombreTheBoxTerminees = physiqueTheBoxTermineesUser($user->getIdentifiant());

                // Génération d'un objet StatistiquesAdmin
                $statistiquesUser = new StatistiquesAdmin();

                $statistiquesUser->setIdentifiant($user->getIdentifiant());
                $statistiquesUser->setPseudo($user->getPseudo());
                $statistiquesUser->setNb_films_ajoutes($nombreFilms);
                $statistiquesUser->setNb_films_comments($nombreComments);
                $statistiquesUser->setNb_restaurants_ajoutes($nombreRestaurants);
                $statistiquesUser->setNb_reservations($nombreReservations);
                $statistiquesUser->setNb_gateaux_semaine($nombreGateauxSemaine);
                $statistiquesUser->setNb_recettes($nombreRecettes);
                $statistiquesUser->setExpenses($bilanUser);
                $statistiquesUser->setNb_collectors($nombreCollector);
                $statistiquesUser->setNb_parcours_ajoutes($nombreParcours);
                $statistiquesUser->setNb_parcours_participations($nombreParticipationsParcours);
                $statistiquesUser->setNb_bugs_soumis($nombreBugsSoumis);
                $statistiquesUser->setNb_bugs_resolus($nombreBugsResolus);
                $statistiquesUser->setNb_bugs_rejetes($nombreBugsRejetes);
                $statistiquesUser->setNb_idees_soumises($nombreTheBox);
                $statistiquesUser->setNb_idees_en_charge($nombreTheBoxEnCharge);
                $statistiquesUser->setNb_idees_terminees($nombreTheBoxTerminees);

                // Ajout au tableau
                array_push($tableauStatistiques, $statistiquesUser);
            }
        }

        // Tri par identifiant
        if (!empty($tableauStatistiques))
        {
            foreach ($tableauStatistiques as $statistiquesIns)
            {
                $triStatistiquesIns[] = $statistiquesIns->getIdentifiant();
            }

            // Tri
            array_multisort($triStatistiquesIns, SORT_ASC, $tableauStatistiques);
        }

        // Retour
        return $tableauStatistiques;
    }

    // METIER : Lecture statistiques catégories des utilisateurs désinscrits
    // RETOUR : Tableau de nombres de commentaires & bilans des dépenses
    function getStatistiquesDesinscrits($listeUsersDesinscrits)
    {
        // Initialisations
        $tableauStatistiquesDes = array();

        // Récupération des statistiques par catégories
        foreach ($listeUsersDesinscrits as $userDesinscrit)
        {
            // Films ajoutés
            $nombreFilms = physiqueFilmsAjoutesUser($userDesinscrit);

            // Commentaires films
            $nombreComments = physiqueCommentairesFilmsUser($userDesinscrit);

            // Restaurants ajoutés
            $nombreRestaurants = physiqueRestaurantsAjoutesUser($userDesinscrit);

            // Réservations de restaurants
            $nombreReservations = physiqueReservationsUser($userDesinscrit);

            // Gâteaux de la semaine
            $nombreGateauxSemaine = physiqueGateauxSemaineUser($userDesinscrit);

            // Recettes partagées
            $nombreRecettes = physiqueRecettesUser($userDesinscrit);

            // Récupération de la liste des dépenses où l'utilisateur désinscrit était acheteur ou participant
            $listeExpenses = physiqueDepensesDesinscrit($userDesinscrit);

            // Calcul du bilan des dépenses (non stocké)
            $bilanUser = 0;

            foreach ($listeExpenses as $expense)
            {
                // Nombre de parts total et de l'utilisateur
                $nombreParts = physiquePartsDepensesUser($expense->getId(), $userDesinscrit);

                if ($expense->getType() == 'M')
                {
                    // Montant de la part
                    $montantUserDesinscrit = $nombreParts['utilisateur'];

                    // Calcul de la répartition des frais
                    if (!empty($expense->getPrice()) AND $montantUserDesinscrit != 0)
                        $fraisUserDesinscrit = $expense->getPrice() / $nombreParts['nombreUtilisateurs'];
                    else
                        $fraisUserDesinscrit = 0;

                    // Calcul du bilan de l'utilisateur (s'il participe ou qu'il est l'acheteur)
                    if ($expense->getBuyer() == $userDesinscrit OR $montantUserDesinscrit != 0)
                    {
                        if ($expense->getBuyer() == $userDesinscrit)
                        {
                            if (!empty($expense->getPrice()))
                                $bilanUser += $expense->getPrice() + $nombreParts['total'] - ($montantUserDesinscrit + $fraisUserDesinscrit);
                            else
                                $bilanUser += $nombreParts['total'] - ($montantUserDesinscrit + $fraisUserDesinscrit);
                        }
                        else
                            $bilanUser -= $montantUserDesinscrit + $fraisUserDesinscrit;
                    }
                }
                else
                {
                    // Prix par parts
                    if ($nombreParts['total'] != 0)
                        $prixParPart = $expense->getPrice() / $nombreParts['total'];
                    else
                        $prixParPart = 0;

                    // Somme des dépenses moins les parts consommées pour calculer le bilan
                    if ($expense->getBuyer() == $userDesinscrit)
                        $bilanUser += $expense->getPrice() - ($prixParPart * $nombreParts['utilisateur']);
                    else
                        $bilanUser -= $prixParPart * $nombreParts['utilisateur'];
                }
            }

            // Phrases cultes ajoutées
            $nombreCollector = physiqueCollectorAjoutesUser($userDesinscrit);

            // Parcours ajoutés
            $nombreParcours = physiqueParcoursAjoutesUser($userDesinscrit);

            // Participations parcours
            $nombreParticipationsParcours = physiqueParticipationsParcoursUser($userDesinscrit);
            
            // Nombre de demandes (bugs / évolutions)
            $nombreBugsSoumis = physiqueBugsSoumisUser($userDesinscrit);

            // Nombre de demandes résolues (bugs / évolutions)
            $nombreBugsResolus = physiqueBugsStatutUser($userDesinscrit, 'Y');

            // Nombre de demandes rejetés (bugs / évolutions)
            $nombreBugsRejetes = physiqueBugsStatutUser($userDesinscrit, 'R');

            // Nombre d'idées publiées
            $nombreTheBox = physiqueTheBoxUser($userDesinscrit);

            // Nombre d'idées en charge
            $nombreTheBoxEnCharge = physiqueTheBoxEnChargeUser($userDesinscrit);

            // Nombre d'idées terminées ou rejetées
            $nombreTheBoxTerminees = physiqueTheBoxTermineesUser($userDesinscrit);

            // Génération d'un objet StatistiquesAdmin
            $statistiquesUser = new StatistiquesAdmin();

            $statistiquesUser->setIdentifiant($userDesinscrit);
            $statistiquesUser->setPseudo('');
            $statistiquesUser->setNb_films_ajoutes($nombreFilms);
            $statistiquesUser->setNb_films_comments($nombreComments);
            $statistiquesUser->setNb_restaurants_ajoutes($nombreRestaurants);
            $statistiquesUser->setNb_reservations($nombreReservations);
            $statistiquesUser->setNb_gateaux_semaine($nombreGateauxSemaine);
            $statistiquesUser->setNb_recettes($nombreRecettes);
            $statistiquesUser->setExpenses($bilanUser);
            $statistiquesUser->setNb_collectors($nombreCollector);
            $statistiquesUser->setNb_parcours_ajoutes($nombreParcours);
            $statistiquesUser->setNb_parcours_participations($nombreParticipationsParcours);
            $statistiquesUser->setNb_bugs_soumis($nombreBugsSoumis);
            $statistiquesUser->setNb_bugs_resolus($nombreBugsResolus);
            $statistiquesUser->setNb_bugs_rejetes($nombreBugsRejetes);
            $statistiquesUser->setNb_idees_soumises($nombreTheBox);
            $statistiquesUser->setNb_idees_en_charge($nombreTheBoxEnCharge);
            $statistiquesUser->setNb_idees_terminees($nombreTheBoxTerminees);

            // Ajout au tableau
            array_push($tableauStatistiquesDes, $statistiquesUser);
        }

        // Retour
        return $tableauStatistiquesDes;
    }

    // METIER : Lecture total catégories des utilisateurs
    // RETOUR : Tableau des totaux des catégories
    function getTotalStatistiques($tableauIns, $tableauDes)
    {
        // Initialisations
        $sommeBilans = 0;

        // Nombre de films ajoutés
        $nombreFilms = physiqueFilmsAjoutesTotal();

        // Nombre de commentaires
        $nombreComments = physiqueCommentairesFilmsTotal();

        // Nombre de restaurants ajoutés
        $nombreRestaurants = physiqueRestaurantsAjoutesTotal();

        // Nombre de réservations de restaurants
        $nombreReservations = physiqueReservationsTotal();

        // Nombre de gâteaux de la semaine
        $nombreGateauxSemaine = physiqueGateauxSemaineTotal();

        // Nombre de recettes partagées
        $nombreRecettes = physiqueRecettesTotal();

        // Calcul somme bilans utilisateurs inscrits
        foreach ($tableauIns as $userIns)
        {
            $sommeBilans += $userIns->getExpenses();
        }

        // Calcul somme bilans utilisateurs désinscrits
        foreach ($tableauDes as $userDes)
        {
            $sommeBilans += $userDes->getExpenses();
        }

        // Récupération des dépenses sans parts
        $expensesNoParts = 0;

        $listeExpenses = physiqueDepensesSansParts();

        foreach ($listeExpenses as $expense)
        {
            $expensesNoParts += $expense->getPrice();
        }

        $regularisations = -1 * $expensesNoParts;

        // Nombre de phrase cultes
        $nombreCollector = physiqueCollectorTotal();
        
        // Parcours ajoutés
        $nombreParcours = physiqueParcoursAjoutesTotal();

        // Participations parcours
        $nombreParticipationsParcours = physiqueParticipationsParcoursTotal();

        // Nombre de demandes (bugs / évolutions)
        $nombreBugsSoumis = physiqueBugsSoumisTotal();

        // Nombre de demandes résolues (bugs / évolutions)
        $nombreBugsResolus = physiqueBugsStatutTotal('Y');

        // Nombre de demandes rejetées (bugs / évolutions)
        $nombreBugsRejetes = physiqueBugsStatutTotal('R');

        // Nombre d'idées publiées
        $nombreTheBox = physiqueTheBoxTotal();

        // Nombre d'idées en charge
        $nombreTheBoxEnCharge = physiqueTheBoxEnChargeTotal();

        // Nombre d'idées terminées ou rejetées
        $nombreTheBoxTerminees = physiqueTheBoxTermineesTotal();

        // Génération d'un objet TotalStatistiquesAdmin
        $totalStatistiques = new TotalStatistiquesAdmin();

        $totalStatistiques->setNb_films_ajoutes_total($nombreFilms);
        $totalStatistiques->setNb_films_comments_total($nombreComments);
        $totalStatistiques->setNb_restaurants_ajoutes_total($nombreRestaurants);
        $totalStatistiques->setNb_reservations_total($nombreReservations);
        $totalStatistiques->setNb_gateaux_semaine_total($nombreGateauxSemaine);
        $totalStatistiques->setNb_recettes_total($nombreRecettes);
        $totalStatistiques->setExpenses_no_parts($regularisations);
        $totalStatistiques->setExpenses_total($sommeBilans);
        $totalStatistiques->setNb_collectors_total($nombreCollector);
        $totalStatistiques->setNb_parcours_ajoutes_total($nombreParcours);
        $totalStatistiques->setNb_parcours_participations_total($nombreParticipationsParcours);
        $totalStatistiques->setNb_bugs_soumis_total($nombreBugsSoumis);
        $totalStatistiques->setNb_bugs_resolus_total($nombreBugsResolus);
        $totalStatistiques->setNb_bugs_rejetes_total($nombreBugsRejetes);
        $totalStatistiques->setNb_idees_soumises_total($nombreTheBox);
        $totalStatistiques->setNb_idees_en_charge_total($nombreTheBoxEnCharge);
        $totalStatistiques->setNb_idees_terminees_total($nombreTheBoxTerminees);

        // Retour
        return $totalStatistiques;
    }

    // METIER : Conversion de la liste d'objets des statistiques en tableau simple pour JSON
    // RETOUR : Tableau des statistiques
    function convertForJsonStatistiques($tableauStatistiques)
    {
        // Initialisations
        $listeStatistiquesAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($tableauStatistiques as $statistiquesAConvertir)
        {
            $statistique = array(
                'identifiant'                => $statistiquesAConvertir->getIdentifiant(),
                'pseudo'                     => $statistiquesAConvertir->getPseudo(),
                'nb_films_ajoutes'           => $statistiquesAConvertir->getNb_films_ajoutes(),
                'nb_films_comments'          => $statistiquesAConvertir->getNb_films_comments(),
                'nb_restaurants_ajoutes'     => $statistiquesAConvertir->getNb_restaurants_ajoutes(),
                'nb_reservations'            => $statistiquesAConvertir->getNb_reservations(),
                'nb_gateaux_semaine'         => $statistiquesAConvertir->getNb_gateaux_semaine(),
                'nb_recettes'                => $statistiquesAConvertir->getNb_recettes(),
                'expenses'                   => $statistiquesAConvertir->getExpenses(),
                'nb_collectors'              => $statistiquesAConvertir->getNb_collectors(),
                'nb_parcours_ajoutes'        => $statistiquesAConvertir->getNb_parcours_ajoutes(),
                'nb_parcours_participations' => $statistiquesAConvertir->getNb_parcours_participations(),
                'nb_bugs_soumis'             => $statistiquesAConvertir->getNb_bugs_soumis(),
                'nb_bugs_resolus'            => $statistiquesAConvertir->getNb_bugs_resolus(),
                'nb_bugs_rejetes'            => $statistiquesAConvertir->getNb_bugs_rejetes(),
                'nb_idees_soumises'          => $statistiquesAConvertir->getNb_idees_soumises(),
                'nb_idees_en_charge'         => $statistiquesAConvertir->getNb_idees_en_charge(),
                'nb_idees_terminees'         => $statistiquesAConvertir->getNb_idees_terminees()
            );

            array_push($listeStatistiquesAConvertir, $statistique);
        }

        // Retour
        return $listeStatistiquesAConvertir;
    }

    // METIER : Réinitialisation mot de passe
    // RETOUR : Aucun
    function setNewPassword($post)
    {
        // Récupération des données
        $identifiant = $post['id_user'];
        $status      = 'U';

        // Génération nouveau mot de passe aléatoire
        $chaine      = generateRandomString(10);
        $salt        = rand();
        $newPassword = htmlspecialchars(hash('sha1', $chaine . $salt));

        // Mise à jour du mot de passe et remise à N de l'indicateur de demande
        physiqueSetNewPassword($identifiant, $salt, $newPassword, $status);

        // Récupération pseudo utilisateur
        $pseudo = physiquePseudoUser($identifiant);

        // Mise en session des données
        $_SESSION['save']['user_ask_id']   = $identifiant;
        $_SESSION['save']['user_ask_name'] = $pseudo;
        $_SESSION['save']['new_password']  = $chaine;
    }

    // METIER : Génère une chaîne aléatoire
    // RETOUR : Chaîne aléatoire
    function generateRandomString($nombreCarateres)
    {
        // Génération d'une chaîne de caractères aléatoires
        $chaine     = '';
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        srand((float)microtime() * 1000000);

        for ($i = 0; $i < $nombreCarateres; $i++)
        {
            $chaine .= $caracteres[rand() % strlen($caracteres)];
        }

        // Retour
        return $chaine;
    }

    // METIER : Refus réinitialisation mot de passe
    // RETOUR : Aucun
    function resetOldPassword($post)
    {
        // Récupération des données
        $identifiant = $post['id_user'];
        $status      = 'U';

        // Remise à "U" de l'indicateur de demande
        physiqueUpdateStatusUser($identifiant, $status);
    }

    // METIER : Validation changement d'équipe (mise à jour de l'équipe et du status utilisateur)
    // RETOUR : Aucun
    function acceptEquipe($post, $isUpdateEquipe)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant = $post['id_user'];
        $oldTeam     = $post['team_user'];
        $newTeam     = '';
        $status      = 'U';

        if ($post['team'] == 'other')
        {
            $teamReference = trim($post['team_reference']);
            $nameReference = trim($post['team_name']);
        }
        else
            $teamReference = $post['team'];

        if (isset($post['team_temp_reference']) AND !empty($post['team_temp_reference']))
            $tempTeam = $post['team_temp_reference'];

        // Contrôle équipe existante (seulement à la création d'une nouvelle équipe)
        if ($post['team'] == 'other')
            $control_ok = controleEquipeUnique($teamReference);

        // Création ou mise à jour d'une équipe si besoin
        if ($control_ok == true)
        {
            if ($post['team'] == 'other')
            {
                $team = array(
                    'reference'  => $teamReference,
                    'team'       => $nameReference,
                    'activation' => 'Y'
                );
    
                if (isset($tempTeam) AND !empty($tempTeam))
                {
                    // Mise à jour de l'équipe temporaire créée par l'utilisateur et activation
                    physiqueUpdateEquipe($team, $tempTeam);
                }
                else
                {
                    // Création d'une nouvelle équipe si l'utilisateur a choisi une équipe et que l'admin créé une nouvelle équipe
                    physiqueInsertionEquipe($team);
                }
    
                // Création d'un fichier XML pour le chat lors de la création d'une nouvelle équipe
                $folder = '../../includes/common/chat/conversations';
    
                if (!is_dir($folder))
                    mkdir($folder, 0777, true);
    
                // Création du fichier s'il n'existe pas
                if (!file_exists($folder . '/content_chat_' . $teamReference . '.xml'))
                {
                    $file    = fopen($folder . '/content_chat_' . $teamReference . '.xml', 'a+');
                    $balises = '<?xml version="1.0" encoding="UTF-8"?>
<INSIDERoom>
</INSIDERoom>';
    
                    fputs($file, $balises);
                    fclose($file);
                    chmod($folder . '/content_chat_' . $teamReference . '.xml', 0757);
                }
            }
            else
            {
                // Si il ne faut finalement pas créer d'équipe, on supprime l'équipe temporaire
                if (isset($tempTeam) AND !empty($tempTeam))
                    physiqueDeleteEquipe($tempTeam);
            }
    
            // Réinitialisations (seulement lors d'un changement d'équipe)
            if ($isUpdateEquipe == true)
            {
                // Suppression des semaines de gâteaux si non réalisés
                physiqueDeleteRecette($identifiant, $oldTeam);
    
                // Remise en cours des idées non terminées ou rejetées
                physiqueUpdateStatusTheBox($identifiant);
    
                // Récupération des missions en cours
                $idMissionsEnCours = physiqueMissionsEnCours();
    
                // Mise à jour des missions en cours
                if (!empty($idMissionsEnCours))
                {
                    foreach ($idMissionsEnCours as $idMission)
                    {
                        physiqueUpdateMissionsEnCours($idMission, $identifiant, $teamReference);
                    }
                }
            }
    
            // Mise à jour de la référence de l'équipe et du statut à "U" de l'utilisateur
            $user = array(
                'team'     => $teamReference,
                'new_team' => $newTeam,
                'status'   => $status
            );
    
            physiqueUpdateProfilUser($user, $identifiant);
        }
    }

    // METIER : Refus changement d'équipe
    // RETOUR : Aucun
    function declineEquipe($post)
    {
        // Récupération des données
        $identifiant = $post['id_user'];
        $oldTeam     = $post['team_user'];
        $newTeam     = $post['new_team_user'];
        $resetTeam   = '';
        $status      = 'U';

        // Suppression de l'équipe éventuellement créée
        physiqueDeleteEquipe($newTeam);

        // Mise à jour de la référence de l'équipe et du statut à "U"
        $user = array(
            'team'     => $oldTeam,
            'new_team' => $resetTeam,
            'status'   => $status
        );

        physiqueUpdateProfilUser($user, $identifiant);
    }

    // METIER : Validation inscription (mise à jour de l'équipe et du status utilisateur)
    // RETOUR : Aucun
    function acceptInscription($post)
    {
        // Récupération des données
        $identifiant = $post['id_user'];

        if ($post['team'] == 'other')
            $equipe = trim($post['team_reference']);
        else
            $equipe = $post['team_user'];

        // Validation de l'équipe (création, modification ou suppression)
        acceptEquipe($post, false);

        // Insertion notification
        insertNotification('inscrit', $equipe, $identifiant, 'admin');
    }

    // METIER : Refus inscription
    // RETOUR : Aucun
    function declineInscription($post)
    {
        // Récupération des données
        $identifiant = $post['id_user'];
        $equipe      = $post['team_user'];

        // Suppression des préférences
        physiqueDeletePreferences($identifiant);

        // Suppression de l'équipe éventuellement créée
        physiqueDeleteEquipe($equipe);

        // Suppression de l'utilisateur
        physiqueDeleteUser($identifiant);
    }

    // METIER : Validation désinscription
    // RETOUR : Aucun
    function acceptDesinscription($post)
    {
        // Initialisations
        $control_ok  = true;

        // Récupération des données
        $identifiant = $post['id_user'];
        $equipe      = $post['team_user'];

        // Récupération des données utilisateur
        $user = physiqueDonneesDesinscriptionUser($identifiant);

        // Contrôle dépenses nulles
        $control_ok = controleDepensesNonNulles($user->getExpenses());

        // Mise à jour des données non supprimées
        if ($control_ok == true)
        {
            // Enregistrement du pseudo dans les phrases cultes (speaker avec passage à "other")
            physiqueUpdateSpeakerCollector($user->getIdentifiant(), $user->getPseudo());

            // Suppression de l'appelant sur les déterminations de restaurants
            physiqueUpdateCallerDeterminationsRestaurantsUser($user->getIdentifiant());

            // Remise en cours des idées non terminées ou rejetées
            physiqueUpdateStatusTheBox($user->getIdentifiant());
        }

        // Suppression des données
        if ($control_ok == true)
        {
            // Suppression des étoiles films
            physiqueDeleteStarsFilmsUser($user->getIdentifiant());

            // Suppression des votes collector
            physiqueDeleteVotesCollectorUser($user->getIdentifiant());

            // Suppression des missions
            physiqueDeleteMissionsUser($user->getIdentifiant());

            // Suppression des succès
            physiqueDeleteSuccessUser($user->getIdentifiant());

            // Suppression propositions restaurants
            physiqueDeleteVotesRestaurantsUser($user->getIdentifiant());

            // Suppression semaines gâteau (futures uniquement)
            physiqueDeleteSemainesGateauxUser($user->getIdentifiant());

            // Suppression participations parcours
            physiqueDeleteParticipationsParcoursUser($user->getIdentifiant());

            // Suppression des préférences
            physiqueDeletePreferences($user->getIdentifiant());

            // Suppression utilisateur
            physiqueDeleteUser($user->getIdentifiant());

            // Suppression notification inscription
            deleteNotification('inscrit', $equipe, $user->getIdentifiant());

            // Suppression avatar
            if (!empty($user->getAvatar()))
                unlink('../../includes/images/profil/avatars/' . $user->getAvatar());
        }
    }

    // METIER : Mise à jour statut utilisateur (refus désinscription ou forçage désinscription) 
    // RETOUR : Aucun
    function updateStatusUser($post, $status)
    {
        // Récupération des données
        $identifiant = $post['id_user'];

        // Mise à jour du statut
        physiqueUpdateStatusUser($identifiant, $status);
    }
?>