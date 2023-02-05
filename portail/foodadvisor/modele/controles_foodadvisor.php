<?php
  // CONTROLE : Déjà bande à part
  // RETOUR : Booléen
  function controleAlreadySolo($isSolo)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($isSolo == true)
    {
      $_SESSION['alerts']['already_solo'] = true;
      $control_ok                         = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Déjà voté
  // RETOUR : Booléen
  function controleAlreadyVoted($mesChoix)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!empty($mesChoix))
    {
      $_SESSION['alerts']['solo_choice'] = true;
      $control_ok                        = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Déjà réservé
  // RETOUR : Booléen
  function controleAlreadyReserved($reserved)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($reserved == 'Y')
    {
      $_SESSION['alerts']['already_reserved'] = true;
      $control_ok                             = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Choix existant à date
  // RETOUR : Booléen
  function controleChoixExistantDate($date, $equipe)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $exist = physiqueChoixExistantDate($date, $equipe);

    if ($exist == true)
    {
      $_SESSION['alerts']['already_resume'] = true;
      $control_ok                           = false;
    }

    // Retour
    return $control_ok;
  }
?>
