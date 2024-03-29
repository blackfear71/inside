<?php
    /***************************
    **** Générateur de code ****
    ****************************
    Fonctionnalités :
    - Génération nouvelle page
    ***************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_codegenerator.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation du générateur
            if (!isset($generatorParameters) AND !isset($_SESSION['generator']))
                $generatorParameters = initializeGenerator();
            else
            {
                // Récupération des paramètres saisis
                $generatorParameters = getGenerator($_SESSION['generator']);

                // Récupération des fichiers générés
                $metier    = getMetier($generatorParameters);
                $controles = getControles($generatorParameters);
                $physique  = getPhysique($generatorParameters);
                $listeVues = getVues($generatorParameters);
                $controler = getControler($generatorParameters);

                if (!empty($generatorParameters->getScript_specifique()))
                    $javascript = getJavascript($generatorParameters);
            }
            break;

        case 'doGenererCode':
            // Sauvegarde des paramètres saisis en session
            saveParameters($_POST);
            break;

        case 'doTelechargerCode':
            downloadCode($_POST);
            break;

        case 'doExtraireStructureBase':
            extractStructureBdd();
            break;

        default:
            // Contrôle action renseignée URL
            header('location: codegenerator.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            $generatorParameters = GeneratorParameters::secureData($generatorParameters);

            if (isset($metier))
                $metier['content'] = htmlspecialchars($metier['content']);

            if (isset($controles))
                $controles['content'] = htmlspecialchars($controles['content']);

            if (isset($physique))
                $physique['content'] = htmlspecialchars($physique['content']);

            if (isset($controler))
                $controler['content'] = htmlspecialchars($controler['content']);

            if (isset($listeVues['vue_web']) AND !empty($listeVues['vue_web']))
                $listeVues['vue_web']['content']    = htmlspecialchars($listeVues['vue_web']['content']);

            if (isset($listeVues['vue_mobile']) AND !empty($listeVues['vue_mobile']))
                $listeVues['vue_mobile']['content'] = htmlspecialchars($listeVues['vue_mobile']['content']);

            if (isset($javascript))
                $javascript['content'] = htmlspecialchars($javascript['content']);
            break;

        case 'doGenererCode':
        case 'doTelechargerCode':
        case 'doExtraireStructureBase':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doTelechargerCode':
        case 'doExtraireStructureBase':
            break;

        case 'doGenererCode':
            header('location: codegenerator.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_codegenerator.php');
            break;
    }
?>