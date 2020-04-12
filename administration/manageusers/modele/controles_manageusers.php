<?php
  // CONTROLE : Dépenses non nulles
  // RETOUR : Booléen
  function controleDepensesNonNulles($expense)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($expense < -0.01 OR $expense > 0.01)
    {
      $_SESSION['alerts']['expenses_not_null'] = true;
      $control_ok                              = false;
    }

    // Retour
    return $control_ok;
  }
?>
