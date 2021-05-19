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

  // CONTROLE : Date 1 < Date 2
  // RETOUR : Booléen
  function controleOrdreDates($date1, $date2)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($date1 > $date2)
    {
      $_SESSION['alerts']['wrong_date_doodle'] = true;
      $control_ok                              = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Moment restaurant renseigné
  // RETOUR : Booléen
  function controleMomentRestaurantSaisi($moment, $lieu)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!empty($lieu) AND $moment != 'A' AND $moment != 'B')
    {
      $_SESSION['alerts']['restaurant_incomplete'] = true;
      $control_ok                                  = false;
    }

    // Retour
    return $control_ok;
  }
?>
