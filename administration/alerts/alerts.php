<?php
    /**************************
    *** Gestion des alertes ***
    ***************************
    Fonctionnalités :
    - Ajout d'alertes
    - Modification d'alertes
    - Suppression d'alertes
    **************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_alerts.php');
    include_once('modele/controles_alerts.php');
    include_once('modele/physique_alerts.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation de la sauvegarde en session
            initializeSaveSession();

            // Récupération de la liste des alertes
            $listeAlertes = getAlerts();
            break;

        case 'doAjouterAlerte':
            // Insertion d'une nouvelle alerte
            $idAlerte = insertAlert($_POST);
            break;

        case 'doModifierAlerte':
            // Mise à jour d'une alerte
            $idAlerte = updateAlert($_POST);
            break;

        case 'doSupprimerAlerte':
            // Suppression d'une alerte
            deleteAlert($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: alerts.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeAlertes as &$alerte)
            {
                $alerte = Alerte::secureData($alerte);
            }

            unset($alerte);
            break;

        case 'doAjouterAlerte':
        case 'doModifierAlerte':
        case 'doSupprimerAlerte':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterAlerte':
            if (!empty($idAlerte))
                header('location: alerts.php?action=goConsulter&anchorAlerts=' . $idAlerte);
            else
                header('location: alerts.php?action=goConsulter');
            break;

        case 'doModifierAlerte':
            header('location: alerts.php?action=goConsulter&anchorAlerts=' . $idAlerte);
            break;

        case 'doSupprimerAlerte':
            header('location: alerts.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_alerts.php');
            break;
    }
?>