<?php
    /**************************
    ********** Index **********
    ***************************
    Fonctionnalités :
    - Connexion
    - Inscription
    - Récupération mot de passe
    **************************/

    // Fonctions communes
    include_once('includes/functions/metier_commun.php');
    include_once('includes/functions/physique_commun.php');

    // Contrôles communs
    controlsIndex();

    // Modèle de données
    include_once('portail/index/modele/metier_index.php');
    include_once('portail/index/modele/controles_index.php');
    include_once('portail/index/modele/physique_index.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Si on n'est pas connecté et que les cookies de connexion sont présent, on lance la connexion automatique, sinon on affiche la page de connexion
            if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] != true
            AND isset($_COOKIE['index'])               AND !empty($_COOKIE['index']))
                $connected = connectUser($_COOKIE['index'], true);

            // Initialisation de la sauvegarde en session
            $erreursIndex = initializeSaveSession();

            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();
            break;

        case 'doConnecter':
            // Connexion de l'utilisateur
            $connected = connectUser($_POST, false);
            break;

        case 'doDemanderInscription':
            // Demande d'inscription
            subscribe($_POST);
            break;

        case 'doDemanderMotDePasse':
            // Demande de réinitialisation de mot de passe
            resetPassword($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: /index.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeEquipes as &$equipe)
            {
                $equipe = Team::secureData($equipe);
            }

            unset($equipe);
            break;

        case 'doConnecter':
        case 'doDemanderInscription':
        case 'doDemanderMotDePasse':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doConnecter':
            if ($connected == true)
            {
                if ($_SESSION['user']['identifiant'] == 'admin')
                    header('location: administration/portail/portail.php?action=goConsulter');
                else
                    header('location: portail/portail/portail.php?action=goConsulter');
            }
            else
                header('location: index.php?action=goConsulter');
            break;

        case 'doDemanderInscription':
        case 'doDemanderMotDePasse':
            header('location: index.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            if (isset($connected) AND $connected == true AND $_SESSION['user']['identifiant'] != 'admin')
            {
                if (isset($_COOKIE['index']['page']) AND !empty($_COOKIE['index']['page']))
                    header('location: ' . $_COOKIE['index']['page']);
                else
                    header('location: portail/portail/portail.php?action=goConsulter');
            }                
            else
                include_once('portail/index/vue/' . $_SESSION['index']['plateforme'] . '/vue_index.php');
            break;
    }
?>