<?php
    /*********************************
    ********* Expense Center *********
    **********************************
    Fonctionnalités :
    - Consultation soldes utilisateurs
    - Ajout de dépenses
    - Consultation des dépense
    - Modification des dépenses
    - Suppression des dépenses
    *********************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_expensecenter.php');
    include_once('modele/controles_expensecenter.php');
    include_once('modele/physique_expensecenter.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si l'année est renseignée et numérique et si le filtre est présent
            if (!isset($_GET['year'])   OR !is_numeric($_GET['year'])
            OR  !isset($_GET['filter']) OR empty($_GET['filter']))
                header('location: expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter');
            else
            {
                // Initialisation de la sauvegarde en session
                initializeSaveSession();

                // Vérification année existante
                $anneeExistante = controlYear($_GET['year'], $_SESSION['user']['equipe']);

                // Récupération de la liste des utilisateurs
                $listeUsers = getUsers($_GET['year'], $_SESSION['user']['equipe']);

                // Récupération des filtres
                $filters = getFilters();

                // Récupération des onglets (années)
                $onglets = getOnglets($_SESSION['user']['equipe']);

                // Récupération de la liste des dépenses
                $listeDepenses = getExpenses($_GET['year'], $_GET['filter'], $_SESSION['user']);
            }
            break;

        case 'doAjouterDepense':
            // Insertion d'une dépense
            $idExpense = insertExpense($_POST, $_SESSION['user'], false);
            break;

        case 'doAjouterDepenseMobile':
            // Insertion d'une dépense
            $idExpense = insertExpense($_POST, $_SESSION['user'], true);
            break;

        case 'doAjouterMontants':
            // Insertion d'une dépense en montants
            $idExpense = insertMontants($_POST, $_SESSION['user'], false);
            break;

        case 'doAjouterMontantsMobile':
            // Insertion d'une dépense en montants
            $idExpense = insertMontants($_POST, $_SESSION['user'], true);
            break;

        case 'doModifierDepense':
            // Modification d'une dépense
            $idExpense = updateExpense($_POST, $_SESSION['user']['equipe'], false);
            break;

        case 'doModifierDepenseMobile':
            // Modification d'une dépense
            $idExpense = updateExpense($_POST, $_SESSION['user']['equipe'], true);
            break;

        case 'doModifierMontants':
            // Modification d'une dépense en montants
            $idExpense = updateMontants($_POST, $_SESSION['user']['equipe'], false);
            break;

        case 'doModifierMontantsMobile':
            // Modification d'une dépense en montants
            $idExpense = updateMontants($_POST, $_SESSION['user']['equipe'], true);
            break;

        case 'doSupprimerDepense':
            // Suppression d'une dépense
            deleteExpense($_POST);
            break;

        case 'doSupprimerMontants':
            // Suppression d'une dépense en montants
            deleteMontants($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeUsers as &$user)
            {
                $user = Profile::secureData($user);
            }

            unset($user);

            foreach ($filters as &$filter)
            {
                $filter['label'] = htmlspecialchars($filter['label']);
                $filter['value'] = htmlspecialchars($filter['value']);
            }

            unset($filter);

            foreach ($onglets as &$year)
            {
                $year = htmlspecialchars($year);
            }

            unset($year);

            foreach ($listeDepenses as &$depense)
            {
                $depense = Expenses::secureData($depense);
            }

            unset($depense);

            // Conversion JSON
            $equipeJson        = json_encode($_SESSION['user']['equipe']);
            $listeUsersJson    = json_encode(convertForJsonListeUsers($listeUsers));
            $listeDepensesJson = json_encode(convertForJsonListeDepenses($listeDepenses));
            break;

        case 'doAjouterDepense':
        case 'doAjouterDepenseMobile':
        case 'doAjouterMontants':
        case 'doAjouterMontantsMobile':
        case 'doModifierDepense':
        case 'doModifierDepenseMobile':
        case 'doModifierMontants':
        case 'doModifierMontantsMobile':
        case 'doSupprimerDepense':
        case 'doSupprimerMontants':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterDepense':
        case 'doAjouterDepenseMobile':
        case 'doAjouterMontants':
        case 'doAjouterMontantsMobile':
            header('location: expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter&anchor=' . $idExpense);
            break;

        case 'doModifierDepense':
        case 'doModifierDepenseMobile':
        case 'doModifierMontants':
        case 'doModifierMontantsMobile':
            header('location: expensecenter.php?year=' . $_GET['year'] . '&filter=' . $_GET['filter'] . '&action=goConsulter&anchor=' . $idExpense);
            break;

        case 'doSupprimerDepense':
        case 'doSupprimerMontants':
            header('location: expensecenter.php?year=' . $_GET['year'] . '&filter=all&action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_expensecenter.php');
            break;
    }
?>