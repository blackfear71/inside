<?php
  // CONTROLE : Utilisateur existant
  // RETOUR : Booléen
  function controleUserExistConnexion($user)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($user == null)
    {
			$_SESSION['alerts']['wrong_connexion'] = true;
      $control_ok                            = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Utilisateur existant
  // RETOUR : Booléen
  function controleUserExistReset($user)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($user == null)
    {
      $_SESSION['alerts']['wrong_id'] = true;
      $control_ok                     = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Utilisateur en cours d'inscription
  // RETOUR : Booléen
  function controleStatutConnexion($statut)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($statut == 'I')
    {
      $_SESSION['alerts']['not_yet'] = true;
      $control_ok                    = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Statut utilisateur à la réinitialisation du mot de passe
  // RETOUR : Booléen
  function controleStatutReset($statut)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    switch ($statut)
    {
      case 'Y':
        $_SESSION['alerts']['already_asked'] = true;
        $control_ok                          = false;
        break;

      case 'I':
        $_SESSION['alerts']['not_yet'] = true;
        $control_ok                    = false;
        break;

      default:
        break;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Mot de passe saisi
  // RETOUR : Booléen
  function controlePassword($identifiant, $password)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $dataUser = physiquePassword($identifiant);
    $crypted  = htmlspecialchars(hash('sha1', $password . $dataUser['salt']));

    if ($crypted != $dataUser['password'])
    {
      $_SESSION['alerts']['wrong_connexion'] = true;
      $control_ok                            = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Trigramme sur 3 caractères
  // RETOUR : Booléen
  function controleLongueurTrigramme($trigramme)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (strlen($trigramme) != 3)
    {
      $_SESSION['alerts']['too_short'] = true;
      $control_ok                      = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Trigramme existant
  // RETOUR : Booléen
  function controleTrigrammeUnique($trigramme)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $isUnique = physiqueTrigrammeUnique($trigramme);

    if ($isUnique == false)
    {
      $_SESSION['alerts']['already_exist'] = true;
      $control_ok                          = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Confirmation mot de passe
  // RETOUR : Booléen
  function controleConfirmationPassword($password, $confirm)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($password != $confirm)
    {
      $_SESSION['alerts']['wrong_confirm'] = true;
      $control_ok                          = false;
    }

    // Retour
    return $control_ok;
  }
?>