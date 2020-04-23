<?php
  // CONTROLE : Correspondance mot de passe
  // RETOUR : Booléen
  function controleCorrespondancePassword($saisie, $base)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($saisie != $base)
    {
      $_SESSION['alerts']['wrong_password'] = true;
      $control_ok                           = false;
    }

    // Retour
    return $control_ok;
  }
?>
