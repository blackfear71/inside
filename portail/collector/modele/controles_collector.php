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
      $control_ok                       = false;
    }

    // Retour
    return $control_ok;
  }
?>
