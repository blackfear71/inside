<?php
    /******************************
    **** Profil administrateur ****
    *******************************
    Fonctionnalités :
    - Modification des informations
    - Modification du mot de passe
    ******************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_regex.php');
    include_once('../../includes/functions/fonctions_images.php');

    // Contrôles communs Utilisateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_profil.php');
    include_once('modele/controles_profil.php');
    include_once('modele/physique_profil.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Récupération des données du profil
            $profil = getProfile($_SESSION['user']['identifiant']);
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
            // Mise à jour des informations
            updateInfos($_SESSION['user']['identifiant'], $_POST);
            break;

        case 'doModifierMotDePasse':
            // Mise à jour du mot de passe
            updatePassword($_SESSION['user']['identifiant'], $_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: profil.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            $profil = Profile::secureData($profil);
            break;

        case 'doModifierAvatar':
        case 'doSupprimerAvatar':
        case 'doModifierInfos':
        case 'doModifierMotDePasse':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierAvatar':
        case 'doSupprimerAvatar':
        case 'doModifierInfos':
        case 'doModifierMotDePasse':
            header('location: profil.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_profil.php');
            break;
    }
?>