<?php
  // CONTROLE : Format de date
  // RETOUR : Booléen
  function controleFormatDate($date)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (validateDate($date) != true)
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
?>
