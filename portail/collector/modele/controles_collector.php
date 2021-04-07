<?php
  // CONTROLE : Format de date
  // RETOUR : Booléen
  function controleFormatDate($date, $isMobile)
  {
    // Initialisations
    $control_ok = true;
    $alerteDate = false;

    // Contrôle
    if ($isMobile == true)
    {
      if (validateDateMobile($date) != true)
        $alerteDate = true;
    }
    else
    {
      if (validateDate($date) != true)
        $alerteDate = true;
    }

    if ($alerteDate == true)
    {
      $_SESSION['alerts']['wrong_date'] = true;
      $control_ok                       = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Saisie non vide
  // RETOUR : Booléen
  function controleCollector($collector)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (empty($collector))
    {
      $_SESSION['alerts']['empty_collector'] = true;
      $control_ok                            = false;
    }

    // Retour
    return $control_ok;
  }
?>
