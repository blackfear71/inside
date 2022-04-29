<?php
  // CONTROLE : Contrôle
  // RETOUR : Booléen
  function controleFonction($champ1, $champ2)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($champ1 != $champ2)
    {
      $_SESSION['alerts']['erreur'] = true;
      $control_ok                   = true;
    }

    // Retour
    return $control_ok;
  }
?>