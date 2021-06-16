<?php
  /*******************
  ******* Chat *******
  ********************
  Fonctionnalités :
  - Affichage du chat
  *******************/

  // Modèle de données
  include_once('modele/metier_chat.php');
  include_once('modele/physique_chat.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'doSubmit':
      // Insertion du message dans le fichier XML
      submitChat($_POST);
      break;

    default:
      // Récupération des utilisateurs en session
      if (!isset($_SESSION['chat']['users']) OR empty($_SESSION['chat']['users']))
        $_SESSION['chat']['users'] = getUsersChat();

      // Récupération de l'utilisateur courant pour le chat
      $_SESSION['chat']['current'] = $_SESSION['user']['identifiant'];
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'doSubmit':
      break;

    default:
      foreach ($_SESSION['chat']['users'] as &$user)
      {
        $user['identifiant'] = htmlspecialchars($user['identifiant']);
        $user['pseudo']      = htmlspecialchars($user['pseudo']);
        $user['avatar']      = htmlspecialchars($user['avatar']);
      }

      unset($user);

      // Conversion JSON
      $listeUsersChatJson = json_encode($_SESSION['chat']['users']);
      $currentUserJson    = json_encode($_SESSION['chat']['current']);
      $initChat           = json_encode($_SESSION['chat']['show_chat']);

      // Suppression session préférence chat après connexion
      $_SESSION['chat']['show_chat'] = NULL;
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doSubmit':
      break;

    default:
      include_once('vue/vue_chat.php');
      break;
  }
?>
