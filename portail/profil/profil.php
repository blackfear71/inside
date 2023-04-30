<?php
    /*********************************************
    ************* Profil utilisateur *************
    **********************************************
    Fonctionnalités :
    - Consultation des données personnelles
    - Modification des informations et paramètres
    - Consultation des succès et de l'expérience
    - Modification du thème
    *********************************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');
    include_once('../../includes/functions/fonctions_images.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_profil.php');
    include_once('modele/controles_profil.php');
    include_once('modele/physique_profil.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si la vue est renseignée
            if (!isset($_GET['view']) OR empty($_GET['view']))
                header('location: profil.php?view=profile&action=goConsulter');
            else
            {
                // Récupération des données en fonction de la vue
                switch ($_GET['view'])
                {
                    case 'success':
                    case 'ranking':
                        // Récupération de la liste des utilisateurs
                        $listeUsers = getUsers($_SESSION['user']['equipe']);

                        // Récupération de la liste des succès des utilisateurs
                        $listeSuccess = getSuccess($_SESSION['user']['identifiant'], $listeUsers);
                        break;

                    case 'settings':
                        // Récupération des informations de l'utilisateur
                        $profil = getProfile($_SESSION['user']['identifiant']);

                        // Récupération des préférences de l'utilisateur
                        $preferences = getPreferences($_SESSION['user']['identifiant']);

                        // Récupération de la liste des équipes
                        $listeEquipes = getListeEquipes();
                        break;

                    case 'themes':
                        // Récupération des informations de l'utilisateur
                        $profil = getProfile($_SESSION['user']['identifiant']);

                        // Récupération des préférences de l'utilisateur
                        $preferences = getPreferences($_SESSION['user']['identifiant']);

                        // Récupération des polices de caratères
                        $policesCaracteres = getPolicesCaracteres();

                        // Récupération des thèmes utilisateurs
                        $themesUsers = getThemes('U', $profil->getExperience());

                        // Récupération des thèmes missions
                        $themesMissions = getThemes('M', NULL);

                        // Récupération thème mission en cours
                        $isThemeMission = getThemeMission();
                        break;

                    case 'profile':
                        // Récupération des informations de l'utilisateur
                        $profil = getProfile($_SESSION['user']['identifiant']);

                        // Récupération de l'équipe
                        $equipe = getEquipe($profil->getTeam());

                        // Récupération des statistiques de l'utilisateur
                        $statistiques = getStatistiques($profil);

                        // Récupération de la progression de l'utilisateur
                        $progression = getProgress($profil->getExperience());
                        break;

                    default:
                        // Contrôle action renseignée URL
                        header('location: profil.php?view=profile&action=goConsulter');
                        break;
                }
            }
            break;

        case 'doModifierAvatar':
            // Modification de l'avatar
            updateAvatar($_SESSION['user']['identifiant'], $_FILES);
            break;

        case 'doSupprimerAvatar':
            // Suppression de l'avatar
            deleteAvatar($_SESSION['user']['identifiant']);
            break;

        case 'doModifierInfos':
            // Modification des informations de l'utilisateur
            updateInfos($_SESSION['user']['identifiant'], $_POST, false);
            break;

        case 'doModifierInfosMobile':
            // Modification des informations de l'utilisateur
            updateInfos($_SESSION['user']['identifiant'], $_POST, true);
            break;

        case 'doModifierPreferences':
            // Modification des préférences de l'utilisateur
            updatePreferences($_SESSION['user']['identifiant'], $_POST);
            break;

        case 'doModifierMotDePasse':
            // Modification du mot de passe de l'utilisateur
            updatePassword($_SESSION['user']['identifiant'], $_POST);
            break;

        case 'doModifierEquipe':
            // Modification de l'équipe
            updateEquipe($_SESSION['user'], $_POST);
            break;

        case 'doDemanderDesinscription':
            // Demande de désinscription
            updateStatus($_SESSION['user']['identifiant'], 'D');
            break;

        case 'doAnnulerDesinscription':
        case 'doAnnulerMotDePasse':
            // Annulation changement statut
            updateStatus($_SESSION['user']['identifiant'], 'U');
            break;

        case 'doModifierPolice':
            // Modification de la police de caractères
            updateFont($_SESSION['user']['identifiant'], $_POST);
            break;

        case 'doSupprimerTheme':
            // Suppression du thème
            deleteTheme($_SESSION['user']['identifiant']);
            break;

        case 'doModifierTheme':
            // Modification du thème
            updateTheme($_SESSION['user']['identifiant'], $_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: profil.php?view=profile&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            switch ($_GET['view'])
            {
                case 'success':
                case 'ranking':
                    foreach ($listeUsers as &$user)
                    {
                        $user = Profile::secureData($user);
                    }

                    unset($user);

                    foreach ($listeSuccess as &$success)
                    {
                        $success = Success::secureData($success);
                    }

                    unset($success);

                    // Conversion JSON
                    $listeSuccessJson = json_encode(convertForJsonListeSucces($listeSuccess));
                    break;

                case 'settings':
                    $profil      = Profile::secureData($profil);
                    $preferences = Preferences::secureData($preferences);

                    foreach ($listeEquipes as &$equipe)
                    {
                        $equipe = Team::secureData($equipe);
                    }

                    unset($equipe);
                    break;

                case 'themes':
                    $profil      = Profile::secureData($profil);
                    $preferences = Preferences::secureData($preferences);

                    foreach ($policesCaracteres as &$police)
                    {
                        $police = htmlspecialchars($police);
                    }

                    unset($police);

                    if (!empty($themesUsers))
                    {
                        foreach ($themesUsers as &$themeUsers)
                        {
                            $themeUsers = Theme::secureData($themeUsers);
                        }

                        unset($themeUsers);
                    }

                    if (!empty($themesMissions))
                    {
                        foreach ($themesMissions as &$themeMission)
                        {
                            $themeMission = Theme::secureData($themeMission);
                        }
                        
                        unset($themeMission);
                    }
                    break;

                case 'profile':
                default:
                    $profil       = Profile::secureData($profil);
                    $equipe       = Team::secureData($equipe);
                    $statistiques = StatistiquesProfil::secureData($statistiques);
                    $progression  = Progression::secureData($progression);
                    break;
            }
            break;

        case 'doModifierAvatar':
        case 'doSupprimerAvatar':
        case 'doModifierInfos':
        case 'doModifierInfosMobile':
        case 'doModifierPreferences':
        case 'doModifierMotDePasse':
        case 'doModifierEquipe':
        case 'doDemanderDesinscription':
        case 'doAnnulerDesinscription':
        case 'doAnnulerMotDePasse':
        case 'doModifierPolice':
        case 'doSupprimerTheme':
        case 'doModifierTheme':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierAvatar':
        case 'doSupprimerAvatar':
        case 'doModifierInfos':
        case 'doModifierInfosMobile':
        case 'doModifierPreferences':
        case 'doModifierMotDePasse':
        case 'doModifierEquipe':
        case 'doDemanderDesinscription':
        case 'doAnnulerDesinscription':
        case 'doAnnulerMotDePasse':
            header('location: profil.php?view=settings&action=goConsulter');
            break;

        case 'doModifierPolice':
        case 'doSupprimerTheme':
        case 'doModifierTheme':
            header('location: profil.php?view=themes&action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_profil.php');
            break;
    }
?>