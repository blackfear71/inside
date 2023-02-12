<?php
    // METIER : Récupération de l'erreur serveur
    // RETOUR : Erreur serveur
    function getErreurServeur($code)
    {
        // Récupération de l'erreur serveur en fonction du code
        switch ($code)
        {
                /*** Erreurs client ***/
            case 403:
                $erreur = 'Accès interdit';
                break;

            case 404:
                $erreur = 'Page non trouvée';
                break;

            case 408:
                $erreur = 'Délai d\'attente dépassé';
                break;

                /*** Erreurs serveur ***/
            case 500:
                $erreur = 'Erreur interne du serveur';
                break;

            case 503:
                $erreur = 'Service non disponible';
                break;

            default:
                $erreur = 'Une erreur est survenue';
                break;
        }

        // Retour
        return $erreur;
    }
?>