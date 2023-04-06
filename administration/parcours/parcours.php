<?php
    /***************************
    *** Gestion des parcours ***
    ****************************
    Fonctionnalités :
    - Suppression des parcours
    ***************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_parcours.php');
    include_once('modele/physique_parcours.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();

            // Récupération de la liste des parcours à supprimer
            $listeSuppression = getParcoursToDelete();
            break;

        case 'doDeleteParcours':
            // Suppression d'un parcours
            deleteParcours($_POST);
            break;

        case 'doResetParcours':
            // Annulation de la demande de suppression d'un parcours
            resetParcours($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: parcours.php?action=goConsulter');
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

            foreach ($listeSuppression as &$parcours)
            {
                $parcours = Parcours::secureData($parcours);
            }

            unset($parcours);
            break;

        case 'doDeleteParcours':
        case 'doResetParcours':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doDeleteParcours':
        case 'doResetParcours':
            header('location: parcours.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_parcours.php');
            break;
    }
?>