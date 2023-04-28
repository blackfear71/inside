<?php
    /*********************************
    ********* Collector Room *********
    **********************************
    Fonctionnalités :
    - Consultation des phrases cultes
    - Ajout des phrases cultes
    - Modification des phrases cultes
    - Suppression des phrases cultes
    - Consultation des images cultes
    - Ajout des images cultes
    - Modification des images cultes
    - Suppression des images cultes
    - Filtrage
    *********************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_collector.php');
    include_once('modele/controles_collector.php');
    include_once('modele/physique_collector.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si la page renseignée et numérique, si le tri et le filtre sont présents
            if (!isset($_GET['page'])   OR empty($_GET['page'])   OR !is_numeric($_GET['page'])
            OR  !isset($_GET['sort'])   OR empty($_GET['sort'])
            OR  !isset($_GET['filter']) OR empty($_GET['filter']))
                header('location: collector.php?sort=dateDesc&filter=none&action=goConsulter&page=1');
            else
            {
                // Initialisation de la sauvegarde en session
                initializeSaveSession();

                // Récupération de la liste des utilisateurs
                $listeUsers = getUsers($_SESSION['user']['equipe']);

                // Calcul du minimum de smileys pour être culte (75%)
                $minGolden = getMinGolden($listeUsers, $_SESSION['user']['equipe']);

                // Récupération des tris et des filtres
                $ordersAndFilters = getOrdersAndFilters();

                // Récupération de la pagination
                $nombrePages = getPages($_GET['filter'], $_SESSION['user'], $minGolden);

                // Récupération de la liste des phrases cultes ou redirection
                if ($nombrePages > 0)
                {
                    if ($_GET['page'] > $nombrePages)
                        header('location: collector.php?sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&action=goConsulter&page=' . $nombrePages);
                    elseif ($_GET['page'] < 1)
                        header('location: collector.php?sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&action=goConsulter&page=1');
                    else
                        $listeCollectors = getCollectors($listeUsers, $nombrePages, $minGolden, $_GET, $_SESSION['user']);
                }
            }
            break;

        case 'doAjouterCollector':
            // Insertion d'une phrase / image culte
            $idCollector = insertCollector($_POST, $_FILES, $_SESSION['user'], false);
            
            // Récupération du numéro de page
            if (!empty($idCollector))
                $numeroPage = getNumeroPageCollector($idCollector, $_SESSION['user'], 'dateDesc', 'none', 0);
            break;

        case 'doAjouterCollectorMobile':
            // Insertion d'une phrase / image culte
            $idCollector = insertCollector($_POST, $_FILES, $_SESSION['user'], true);
            
            // Récupération du numéro de page
            if (!empty($idCollector))
                $numeroPage = getNumeroPageCollector($idCollector, $_SESSION['user'], 'dateDesc', 'none', 0);
            break;

        case 'doSupprimerCollector':
            // Suppression d'une phrase / image culte
            deleteCollector($_POST);
            break;

        case 'doModifierCollector':
            // Modification d'une phrase / image culte
            $idCollector = updateCollector($_POST, $_FILES, false);

            // Récupération des données complémentaires seulement en cas de besoin
            if ($_GET['filter'] == 'topCulte')
            {
                // Récupération de la liste des utilisateurs
                $listeUsers = getUsers($_SESSION['user']['equipe']);

                // Calcul du minimum de smileys pour être culte (75%)
                $minGolden = getMinGolden($listeUsers, $_SESSION['user']['equipe']);
            }
            else
                $minGolden = 0;
            
            // Récupération du numéro de page
            $numeroPage = getNumeroPageCollector($idCollector, $_SESSION['user'], $_GET['sort'], $_GET['filter'], $minGolden);
            break;

        case 'doModifierCollectorMobile':
            // Modification d'une phrase / image culte
            $idCollector = updateCollector($_POST, $_FILES, true);

            // Récupération des données complémentaires seulement en cas de besoin
            if ($_GET['filter'] == 'topCulte')
            {
                // Récupération de la liste des utilisateurs
                $listeUsers = getUsers($_SESSION['user']['equipe']);

                // Calcul du minimum de smileys pour être culte (75%)
                $minGolden = getMinGolden($listeUsers, $_SESSION['user']['equipe']);
            }
            else
                $minGolden = 0;

            // Récupération du numéro de page
            $numeroPage = getNumeroPageCollector($idCollector, $_SESSION['user'], $_GTT['sort'], $_GET['filter'], $minGolden);
            break;

        case 'doVoterCollector':
            // Vote d'un utilisateur
            $idCollector = voteCollector($_POST, $_SESSION['user']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: collector.php?sort=dateDesc&filter=none&action=goConsulter&page=1');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeUsers as &$user)
            {
                $user['pseudo'] = htmlspecialchars($user['pseudo']);
                $user['avatar'] = htmlspecialchars($user['avatar']);
            }

            unset($user);

            foreach ($ordersAndFilters as &$orderAndFilter)
            {
                foreach ($orderAndFilter as &$orderAndFilterValue)
                {
                    $orderAndFilterValue['label'] = htmlspecialchars($orderAndFilterValue['label']);
                    $orderAndFilterValue['value'] = htmlspecialchars($orderAndFilterValue['value']);
                }

                unset($orderAndFilterValue);
            }

            unset($orderAndFilter);

            if ($nombrePages > 0)
            {
                foreach ($listeCollectors as &$collector)
                {
                    $collector = Collector::secureData($collector);
                }

                unset($collector);

                // Conversion JSON
                $listeCollectorsJson = json_encode(convertForJsonListeCollectors($listeCollectors));
            }

            // Conversion JSON
            $equipeJson     = json_encode($_SESSION['user']['equipe']);
            $listeUsersJson = json_encode($listeUsers);
            break;

        case 'doAjouterCollector':
        case 'doAjouterCollectorMobile':
        case 'doSupprimerCollector':
        case 'doModifierCollector':
        case 'doModifierCollectorMobile':
        case 'doVoterCollector':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterCollector':
        case 'doAjouterCollectorMobile':
            if (!empty($idCollector) AND !empty($numeroPage))
                header('location: collector.php?sort=dateDesc&filter=none&action=goConsulter&page=' . $numeroPage . '&anchor=' . $idCollector);
            else
                header('location: collector.php?sort=dateDesc&filter=none&action=goConsulter&page=' . $_GET['page']);
            break;

        case 'doModifierCollector':
        case 'doModifierCollectorMobile':
            header('location: collector.php?sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&action=goConsulter&page=' . $numeroPage . '&anchor=' . $idCollector);
            break;

        case 'doSupprimerCollector':
            header('location: collector.php?sort=dateDesc&filter=none&action=goConsulter&page=' . $_GET['page']);
            break;

        case 'doVoterCollector':
            header('location: collector.php?sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&action=goConsulter&page=' . $_GET['page'] . '&anchor=' . $idCollector);
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_collector.php');
            break;
    }
?>