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

  // CONTROLE : Date dépense < date du jour
  // RETOUR : Booléen
  function controleDateSaisie($date, $isMobile)
  {
    // Initialisations
    $control_ok     = true;

    // Contrôle
    if ($isMobile == true)
      $dateAControler = substr($date, 0, 4) . substr($date, 5, 2) . substr($date, 8, 2);
    else
      $dateAControler = substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);

    if ($dateAControler > date('Ymd'))
    {
      $_SESSION['alerts']['expense_date'] = true;
      $control_ok                         = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Aucune part pour une régularisation
  // RETOUR : Booléen
  function controleRegularisation($prix, $regularisationSansParts)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (is_numeric($prix) AND $prix < 0 AND $regularisationSansParts == false)
    {
      $_SESSION['alerts']['no_parts_regularization'] = true;
      $control_ok                                    = false;
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
      $_SESSION['alerts']['expense_not_numeric'] = true;
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

  // CONTROLE : Pourcentage numérique et comprise entre minimum et maximum
  // RETOUR : Booléen
  function controlePourcentageIntervalle($pourcentage, $minimum, $maximum)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!is_numeric($pourcentage) OR $pourcentage < $minimum OR $pourcentage > $maximum)
    {
      $_SESSION['alerts']['reduction_not_correct'] = true;
      $control_ok                                  = false;
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
