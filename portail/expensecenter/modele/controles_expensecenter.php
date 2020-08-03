<?php
  // CONTROLE : Aucune part pour une régularisation
  // RETOUR : Booléen
  function controleRegularisation($prix, $regularisationSansParts)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (is_numeric($prix) AND $prix < 0 AND $regularisationSansParts == false)
    {
      $_SESSION['alerts']['regul_no_parts'] = true;
      $control_ok                           = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Parts entières
  // RETOUR : Booléen
  function controlePartsEntieres($listeParts, $regularisationSansParts)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($regularisationSansParts == false)
    {
      foreach ($listeParts as $part)
      {
        if (!ctype_digit($part))
        {
          $_SESSION['alerts']['parts_not_integer'] = true;
          $control_ok                              = false;
          break;
        }
      }
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Prix numérique et non nul
  // RETOUR : Booléen
  function controlePrixNumerique($prix)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!is_numeric($prix) OR $prix == 0)
    {
      $_SESSION['alerts']['depense_not_numeric'] = true;
      $control_ok                                = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Frais numériques et positifs
  // RETOUR : Booléen
  function controleFraisPositifs($frais)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!is_numeric($frais) OR $frais <= 0)
    {
      $_SESSION['alerts']['amount_not_positive'] = true;
      $control_ok                                = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Montant saisi
  // RETOUR : Booléen
  function controleMontantsSaisis($listeMontants)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (empty($listeMontants))
    {
      $_SESSION['alerts']['empty_amount'] = true;
      $control_ok                         = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Montants numérique et positifs
  // RETOUR : Booléen
  function controleMontantsPositifs($listeMontants)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    foreach ($listeMontants as $montant)
    {
      $montantFormat = formatAmountForInsert($montant);

      if (!is_numeric($montantFormat) OR $montantFormat <= 0)
      {
        $_SESSION['alerts']['amounts_not_numeric'] = true;
        $control_ok                                = false;
        break;
      }
    }

    // Retour
    return $control_ok;
  }
?>
