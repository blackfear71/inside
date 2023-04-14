<?php
    /**********************************
    *********** Cooking Box ***********
    ***********************************
    Fonctionnalités :
    - Consultation des tours de gâteau
    - Modification des tours de gâteau
    - Consultation des recettes
    - Ajout des recettes
    - Modification des recettes
    - Suppression des recettes
    - Détail des recettes
    **********************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_images.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_cookingbox.php');
    include_once('modele/controles_cookingbox.php');
    include_once('modele/physique_cookingbox.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si l'année est renseignée et numérique
            if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
                header('location: cookingbox.php?year=' . date('Y') . '&action=goConsulter');
            else
            {
                // Initialisation de la sauvegarde en session
                initializeSaveSession();

                // Récupération des informations de la semaine en cours
                $currentWeek = getWeek($_SESSION['user']['equipe'], date('WY'));

                // Récupération des informations de la semaine suivante
                $nextWeek = getWeek($_SESSION['user']['equipe'], date('WY', strtotime('+ 1 week')));

                // Récupération de la liste des utilisateurs
                $listeCookers = getUsers($_SESSION['user']['equipe']);

                // Détermination des semaines de saisie possible pour l'utilisateur
                $listeSemaines = getWeeks($_SESSION['user']);

                // Vérification année existante
                $anneeExistante = controlYear($_GET['year'], $_SESSION['user']['equipe']);

                // Récupération des onglets (années)
                $onglets = getOnglets($_SESSION['user']['equipe']);

                // Récupération des recettes
                $recettes = getRecipes($_GET['year'], $_SESSION['user']['equipe'], $listeCookers);
            }
            break;

        case 'doModifierSemaine':
            // Modification d'une semaine (utilisateur choisi)
            updateCake($_POST, $_SESSION['user']['equipe']);
            break;

        case 'doSupprimerSemaine':
            // Suppression d'une semaine (utilisateur choisi)
            deleteCake($_POST, $_SESSION['user']['equipe']);
            break;

        case 'doValiderSemaine':
            // Validation d'une semaine (par l'utilisateur choisi)
            validateCake('Y', $_POST, $_SESSION['user']);
            break;

        case 'doAnnulerSemaine':
            // Annulation de la validation d'une semaine (par l'utilisateur choisi)
            validateCake('N', $_POST, $_SESSION['user']);
            break;

        case 'doAjouterRecette':
            // Récupération de l'année pour redirection
            $year = $_POST['year_recipe'];

            // Insertion d'une recette
            $idRecette = insertRecipe($_POST, $_FILES, $_SESSION['user']);
            break;

        case 'doModifierRecette':
            // Récupération de l'année pour redirection
            $year = $_POST['hidden_year_recipe'];

            // Modification d'une recette
            $idRecette = updateRecipe($_POST, $_FILES, $_SESSION['user']);
            break;

        case 'doSupprimerRecette':
            // Suppression d'une recette
            deleteRecipe($_POST, $_SESSION['user']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: cookingbox.php?year=' . date('Y') . '&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            $currentWeek = WeekCake::secureData($currentWeek);
            $nextWeek    = WeekCake::secureData($nextWeek);

            foreach ($listeCookers as &$user)
            {
                $user['pseudo'] = htmlspecialchars($user['pseudo']);
                $user['avatar'] = htmlspecialchars($user['avatar']);
            }

            unset($user);

            foreach ($listeSemaines as &$year)
            {
                foreach ($year as &$week)
                {
                    $week = htmlspecialchars($week);
                }

                unset($week);
            }

            unset($year);

            foreach ($onglets as &$year)
            {
                $year = htmlspecialchars($year);
            }

            unset($year);

            foreach ($recettes as &$recette)
            {
                $recette = WeekCake::secureData($recette);
            }

            unset($recette);

            // Conversion JSON
            $currentWeekJson   = json_encode(convertForJsonWeek($currentWeek));
            $nextWeekJson      = json_encode(convertForJsonWeek($nextWeek));
            $listeSemainesJson = json_encode($listeSemaines);
            $listeCookersJson  = json_encode(convertForJsonListeCookers($listeCookers, $_SESSION['user']['equipe']));
            $recettesJson      = json_encode(convertForJsonListeRecettes($recettes));
            break;

        case 'doModifierSemaine':
        case 'doSupprimerSemaine':
        case 'doValiderSemaine':
        case 'doAnnulerSemaine':
        case 'doAjouterRecette':
        case 'doModifierRecette':
        case 'doSupprimerRecette':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierSemaine':
        case 'doSupprimerSemaine':
        case 'doValiderSemaine':
        case 'doAnnulerSemaine':
        case 'doSupprimerRecette':
            header('location: cookingbox.php?year=' . $_GET['year'] . '&action=goConsulter');
            break;

        case 'doAjouterRecette':
        case 'doModifierRecette':
            header('location: cookingbox.php?year=' . $year . '&action=goConsulter&anchor=' . $idRecette);
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_cookingbox.php');
            break;
    }
?>