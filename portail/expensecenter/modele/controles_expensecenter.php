<?php
  // CONTROLE : Aucune part pour une régularisation
  // RETOUR : Booléen
  function controlRegularisation($prix, $regularisationSansParts)
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
?>
