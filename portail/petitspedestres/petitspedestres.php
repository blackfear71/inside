<?php
    /***************************
    *** Les Petits Pédestres ***
    ****************************
    Fonctionnalités :
    - Tableau de bord
    - Dernières courses
    - Liste des parcours
    - Ajout de parcours
    - Ajout participation
    ****************************/
    
    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_images.php');
    include_once('../../includes/functions/fonctions_regex.php');
    
    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_petitspedestres.php');
    include_once('modele/controles_petitspedestres.php');
    include_once('modele/physique_petitspedestres.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation de la sauvegarde en session
            initializeSaveSession();

            // Récupération du tableau de bord
            $tableauDeBord = getTableauDeBord($_SESSION['user']['identifiant']);

            // Récupération des dernières courses
            $dernieresCourses = getDernieresCourses($_SESSION['user']);

            // Récupération de la liste des parcours
            $listeParcours = getListeParcours($_SESSION['user']['equipe']);
            break;

        case 'doAjouterParcours':
            // Insertion d'un parcours
            $idParcours = insertParcours($_POST, $_FILES, $_SESSION['user']);
            break;

        case 'doAjouterParticipation':
            // Insertion d'une participation
            insertParticipation($_POST, $_SESSION['user'], false);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: petitspedestres.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            $tableauDeBord = TableauDeBord::secureData($tableauDeBord);

            foreach ($dernieresCourses as &$course)
            {
                $course = ParticipationCourse::secureData($course);
            }

            unset($course);

            foreach ($listeParcours as &$parcours)
            {
                $parcours = Parcours::secureData($parcours);
            }

            unset($parcours);

            // Conversion JSON
            $listeParcoursJson = json_encode(convertForJsonListeParcours($listeParcours));
            break;

        case 'doAjouterParcours':
        case 'doAjouterParticipation':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterParcours':
            header('location: details.php?id_parcours=' . $idParcours . '&action=goConsulter');
            break;

        case 'doAjouterParticipation':
            header('location: petitspedestres.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_petitspedestres.php');
            break;
    }
?>