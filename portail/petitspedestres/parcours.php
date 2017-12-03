<?php
  @ini_set('display_errors', 'on');

  // Fonction communes
  include_once('../../includes/fonctions_communes.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_parcours.php');

  // Initialisations booléens
  $lectureListe = false;
  $controlesDonnees = true;

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['erreur_distance']) OR $_SESSION['erreur_distance'] != true)
  {
    $_SESSION['save_add'] = array('nom'      => '',
                                  'distance' => '',
                                  'lieu'     => '',
                                  'image'    => ''
                                 );
    $_SESSION['save_mod'] = array('nom'      => '',
                                  'distance' => '',
                                  'lieu'     => '',
                                  'image'    => ''
                                 );
  }

  // EVALUATE TRUE WHEN COI-FCT = 'L0001' PERFORM...
  switch ($_GET['action']){
    case 'liste':
      // Récupération de tous les parcours. Attention, $parcours est un tableau d'objets Parcours
      $parcours = listParcours();
      $lectureListe = true;
      break;

    case 'goajouter':
      // On ne fait rien
      $name     = '';
      $dist     = '';
      $location = '';
      $picture  = '';
      $controlesDonnees = false;
      break;

    case 'doajouter':
      $parcours = addParcours($_POST);
      // Ceci est un peu chimique mais sinon ça marche pas...
      $_GET['id'] = $parcours->getId();
      break;

    case 'consulter':
    case 'gomodifier':
      // Récupération des données par le modèle
      if (!isset($_GET['id']) OR empty($_GET['id']))
        header('location: parcours.php?action=liste');
      else
        $parcours = getParcours($_GET['id']);
      break;

    case 'domodifier':
      // Mise à jour des données par le modèle
      $parcours = updateParcours($_GET['id'], $_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: parcours.php?action=liste');
      break;
  }

  // Traitements de sécurité avant la vue
  if ($controlesDonnees){
    // Si on a fait une lecture de liste on traite chaque objet dans un foreach
    if ($lectureListe){
      foreach ($parcours as &$prcr){
        $prcr->setNom(htmlspecialchars($prcr->getNom()));
        $prcr->setDistance(htmlspecialchars($prcr->getDistance()));
        $prcr->setLieu(htmlspecialchars($prcr->getLieu()));
        $prcr->setImage(htmlspecialchars($prcr->getImage()));
      }

      unset($prcr);
    }
    else{
      $parcours->setNom(htmlspecialchars($parcours->getNom()));
      $parcours->setDistance(htmlspecialchars($parcours->getDistance()));
      $parcours->setLieu(htmlspecialchars($parcours->getLieu()));
      $parcours->setImage(htmlspecialchars($parcours->getImage()));

      $name     = (isset($_SESSION['erreur_distance']) AND $_SESSION['erreur_distance'] == true) ? $_SESSION['save_mod']['nom'] : $parcours->getNom();
      $dist     = (isset($_SESSION['erreur_distance']) AND $_SESSION['erreur_distance'] == true) ? $_SESSION['save_mod']['distance'] : $parcours->getDistance();
      $location = (isset($_SESSION['erreur_distance']) AND $_SESSION['erreur_distance'] == true) ? $_SESSION['save_mod']['lieu'] : $parcours->getLieu();
      $picture  = (isset($_SESSION['erreur_distance']) AND $_SESSION['erreur_distance'] == true) ? $_SESSION['save_mod']['image'] : $parcours->getImage();
    }
  }


  // Affichage vue
  switch ($_GET['action']){
    case 'liste':
      include_once('vue/liste_parcours.php');
      break;

    case 'goajouter':
      include_once('vue/ajout_parcours.php');
      break;

    case 'doajouter':
      if ($_SESSION['erreur_distance'] == true)
        include_once('vue/ajout_parcours.php');
      else
        include_once('vue/vue_parcours.php');
      break;

    case 'domodifier':
      if ($_SESSION['erreur_distance'] == true)
        include_once('vue/mod_parcours.php');
      else
        include_once('vue/vue_parcours.php');
      break;

    case 'consulter':
      include_once('vue/vue_parcours.php');
      break;

    case 'gomodifier':
      include_once('vue/mod_parcours.php');
      break;

    default:
      include_once('vue/vue_parcours.php');
      break;
  }
?>
