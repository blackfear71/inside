<?php
  include_once('includes/classes/missions.php');
  include_once('includes/classes/profile.php');

  // METIER : Connexion utilisateur
  // RETOUR : Indicateur connexion
  function connectUser($post)
  {
    // Initialisations
    $control_ok                     = true;
    $connected                      = false;
    $_SESSION['index']['connected'] = NULL;

    // Récupération des données
    $password = $post['mdp'];

    if (strtolower($post['login']) == 'admin')
      $identifiant = htmlspecialchars(strtolower($post['login']));
    else
      $identifiant = htmlspecialchars(strtoupper($post['login']));

    // Lectures des données de l'utilisateur
    $user = physiqueUser($identifiant);

    // Contrôle utilisateur existant
    $control_ok = controleUserExistConnexion($user);

    // Contrôle inscription en cours
    if ($control_ok == true)
      $control_ok = controleStatutConnexion($user->getStatus());

    // Contrôle mot de passe
    if ($control_ok == true)
      $control_ok = controlePassword($user->getIdentifiant(), $password);

    // Initialisation des données
    if ($control_ok == true)
    {
      // Initialisation de la session utilisateur
      $_SESSION['index']['connected']  = true;
      $_SESSION['user']['identifiant'] = $user->getIdentifiant();
      $_SESSION['user']['pseudo']      = $user->getPseudo();
      $_SESSION['user']['avatar']      = $user->getAvatar();

      // Initialisation des préférences utilisateur et du chat
      if ($user->getIdentifiant() != 'admin')
      {
        // Récupération des préférences
        $preferences = physiquePreferences($user->getIdentifiant());

        $_SESSION['user']['view_movie_house']   = $preferences->getView_movie_house();
        $_SESSION['user']['view_the_box']       = $preferences->getView_the_box();
        $_SESSION['user']['view_notifications'] = $preferences->getView_notifications();

        if ($preferences->getInit_chat() == 'Y')
          $_SESSION['chat']['show_chat'] = true;
        else
          $_SESSION['chat']['show_chat'] = false;
      }

      // Positionnement indicateur connexion
      $connected = true;
    }

    // Vérification connexion en cas d'erreur
    if ($control_ok == false)
      $_SESSION['index']['connected'] = false;

    // Retour
    return $connected;
  }

  // METIER : Inscription utilisateur
  // RETOUR : Aucun
  function subscribe($post)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $trigramme   = htmlspecialchars(strtoupper(trim($post['trigramme'])));
    $pseudo      = htmlspecialchars(trim($post['pseudo']));
    $salt        = rand();
    $password    = htmlspecialchars(hash('sha1', $post['password'] . $salt));
    $confirm     = htmlspecialchars(hash('sha1', $post['confirm_password'] . $salt));
    $ping        = '';
    $status      = 'I';
    $avatar      = '';
    $email       = '';
    $anniversary = '';
    $experience  = 0;
    $expenses    = 0;

    // Initialisations préférences utilisateur
    $refTheme             = '';
    $initChat             = 'Y';
    $viewMovieHouse       = 'H';
    $categoriesMovieHouse = 'Y;Y;Y;';
    $viewTheBox           = 'P';
    $viewNotifications    = 'T';
    $manageCalendars      = 'N';

    // Sauvegarde en session en cas d'erreur
    $_SESSION['index']['identifiant_saisi']               = $post['trigramme'];
    $_SESSION['index']['pseudo_saisi']                    = $post['pseudo'];
    $_SESSION['index']['mot_de_passe_saisi']              = $post['password'];
    $_SESSION['index']['confirmation_mot_de_passe_saisi'] = $post['confirm_password'];

    // Contrôle trigramme sur 3 caractères
    $control_ok = controleLongueurTrigramme($trigramme);

    // Contrôle trigramme existant
    if ($control_ok == true)
      $control_ok = controleTrigrammeUnique($trigramme);

    // Contrôle saisies mot de passe
    if ($control_ok == true)
      $control_ok = controleConfirmationPassword($password, $confirm);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $user = array('identifiant' => $trigramme,
                    'salt'        => $salt,
                    'password'    => $password,
                    'ping'        => $ping,
                    'status'      => $status,
          					'pseudo'      => $pseudo,
          					'avatar'      => $avatar,
                    'email'       => $email,
                    'anniversary' => $anniversary,
                    'experience'  => $experience,
                    'expenses'    => $expenses
                   );

       physiqueInsertionUser($user);

       $preferences = array('identifiant'            => $trigramme,
                            'ref_theme'              => $refTheme,
                            'init_chat'              => $initChat,
                            'view_movie_house'       => $viewMovieHouse,
                            'categories_movie_house' => $categoriesMovieHouse,
                            'view_the_box'           => $viewTheBox,
                            'view_notifications'     => $viewNotifications,
                            'manage_calendars'       => $manageCalendars
                           );

       physiqueInsertionPreferences($preferences);

       // Message d'alerte
       $_SESSION['alerts']['ask_inscription'] = true;
    }
  }

  // METIER : Demande de récupération de mot de passe
  // RETOUR : Aucun
  function resetPassword($post)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $identifiant = htmlspecialchars(strtoupper(trim($post['login'])));
    $status      = 'Y';

    // Sauvegarde en session en cas d'erreur
    $_SESSION['index']['identifiant_saisi_mdp'] = $post['login'];

    // Lectures des données de l'utilisateur
    $user = physiqueUser($identifiant);

    // Contrôle utilisateur existant
    $control_ok = controleUserExistReset($user);

    // Contrôle statut utilisateur
    if ($control_ok == true)
      $control_ok = controleStatutReset($user->getStatus());

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      physiqueUpdateStatut($status, $user->getIdentifiant());

      // Message d'alerte
      $_SESSION['alerts']['password_asked'] = true;
    }
  }
?>
