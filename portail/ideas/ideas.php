<?php
    /**********************
    ******* #TheBox *******
    ***********************
    Fonctionnalités :
    - Consulation des idées
    - Ajout d'une idée
    - Gestion d'une idée
    **********************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_ideas.php');
    include_once('modele/physique_ideas.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si la page renseignée et numérique
            if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
                header('location: ideas.php?view=all&action=goConsulter&page=1');
            else
            {
                // Lecture des idées en fonction de la vue
                switch ($_GET['view'])
                {
                    case 'all':
                    case 'done':
                    case 'mine':
                    case 'inprogress':
                        // Récupération du nombre de pages
                        $nombrePages = getPages($_GET['view'], $_SESSION['user']);

                        // Récupération des idées
                        if ($nombrePages > 0)
                        {
                            // Récupération de la liste des utilisateurs
                            $listeUsers = getListeUsers($_SESSION['user']['equipe']);

                            // Récupération des idées
                            $listeIdees = getIdees($_GET, $nombrePages, $_SESSION['user'], $listeUsers);
                        }
                        break;

                    default:
                        // Contrôle vue renseignée URL
                        header('location: ideas.php?view=all&action=goConsulter&page=1');
                        break;
                }
            }
            break;

        case 'doAjouterIdee':
            // Insertion d'une idée
            $idIdee = insertIdee($_POST, $_SESSION['user']);
            break;

        case 'doModifierIdee':
            // Récupération de la vue
            $view = $_GET['view'];

            // Modification d'une idée
            $idIdee = updateIdee($_POST);

            // Récupération du numéro de page pour la redirection
            $numeroPage = getNumeroPageIdee($idIdee, $view, $_SESSION['user']);
            break;

        case 'doModifierStatutIdee':
            // Récupération de l'id de l'idée
            $idIdee = $_POST['id_idee'];

            // Modification du statut d'une idée
            $view = updateStatutIdee($_POST, $_GET['view'], $_SESSION['user']['identifiant']);

            // Récupération du numéro de page pour la redirection
            $numeroPage = getNumeroPageIdee($idIdee, $view, $_SESSION['user']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: ideas.php?view=' . $_GET['view'] . '&action=goConsulter&page=1');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            if ($nombrePages > 0)
            {
                foreach ($listeIdees as &$idee)
                {
                    $idee = Idea::secureData($idee);
                }

                unset($idee);

                // Conversion JSON
                $listeIdeesJson = json_encode(convertForJsonListeIdees($listeIdees));
            }
            break;

        case 'doAjouterIdee':
        case 'doModifierIdee':
        case 'doModifierStatutIdee':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierIdee':
        case 'doModifierStatutIdee':
            header('location: ideas.php?view=' . $view . '&action=goConsulter&page=' . $numeroPage . '&anchor=' . $idIdee);
            break;

        case 'doAjouterIdee':
            header('location: ideas.php?view=inprogress&action=goConsulter&page=1&anchor=' . $idIdee);
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_ideas.php');
            break;
    }
?>