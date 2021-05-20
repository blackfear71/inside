<?php
  // CONTROLE : Distance numérique
  // RETOUR : Booléen
  function controleDistanceNumerique($distance)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!is_numeric($distance) OR $distance <= 0)
    {
      $_SESSION['alerts']['distance_error'] = true;
      $control_ok                           = false;
    }

    // Retour
    return $control_ok;
  }
?>
