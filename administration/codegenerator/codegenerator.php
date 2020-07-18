<?php
  /*******************************
  *** Gestion des utilisateurs ***
  ********************************
  Fonctionnalités :
  - Génération nouvelle page
  *******************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_codegenerator.php');

  // Appel métier
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
        $controler = getControler($generatorParameters);
        $metier    = getMetier($generatorParameters);
        $controles = getControles($generatorParameters);
        $physique  = getPhysique($generatorParameters);
        $vue       = getVue($generatorParameters);
      }
      break;

    case 'generateCode':
      // Sauvegarde des paramètres saisis en session
      saveParameters($_POST);
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
      GeneratorParameters::secureData($generatorParameters);

      if (isset($controler))
        $controler['content'] = htmlspecialchars($controler['content']);

      if (isset($metier))
        $metier['content'] = htmlspecialchars($metier['content']);

      if (isset($controles))
        $controles['content'] = htmlspecialchars($controles['content']);

      if (isset($physique))
        $physique['content'] = htmlspecialchars($physique['content']);

      if (isset($vue))
        $vue['content'] = htmlspecialchars($vue['content']);

      break;

    case 'generateCode':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'generateCode':
      header('location: codegenerator.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_codegenerator.php');
      break;
  }
?>
