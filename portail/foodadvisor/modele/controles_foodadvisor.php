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
      $control_ok                       = false;
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
      $_SESSION['alerts']['choix_solo'] = true;
      $control_ok                       = false;
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

  // CONTROLE : Vote en double
  // RETOUR : Booléen
  function controleVoteDoublon($lieu1, $lieu2, $restaurant1, $restaurant2)
  {
    // Initialisations
    $doublon = false;

    // Contrôle
    if ($lieu1 == $lieu2 AND $restaurant1 == $restaurant2)
    {
      $_SESSION['alerts']['wrong_choice'] = true;
      $doublon                            = true;
    }

    // Retour
    return $doublon;
  }
?>
