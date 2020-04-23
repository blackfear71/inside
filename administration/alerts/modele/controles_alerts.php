<?php
  // CONTROLE : Référence alerte unique
  // RETOUR : Booléen
  function controleReferenceUnique($reference)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $isUnique = physiqueReferenceUnique($reference);

    if ($isUnique == false)
    {
      $_SESSION['alerts']['already_referenced'] = true;
      $control_ok                               = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Référence alerte unique (mise à jour)
  // RETOUR : Booléen
  function controleReferenceUniqueUpdate($reference, $idAlert)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $isUnique = physiqueReferenceUniqueUpdate($reference, $idAlert);

    if ($isUnique == false)
    {
      $_SESSION['alerts']['already_referenced'] = true;
      $control_ok                               = false;
    }

    // Retour
    return $control_ok;
  }
?>
