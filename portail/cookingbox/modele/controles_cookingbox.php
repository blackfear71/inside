<?php
  // CONTROLE : Semaine déjà validée (lors de la modification de la semaine)
  // RETOUR : Booléen
  function controleSemaineValidee($semaineExistante)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($semaineExistante['exist'] == true AND $semaineExistante['cooked'] == 'Y')
    {
      $_SESSION['alerts']['already_cooked'] = true;
      $control_ok                           = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Semaine déjà validée (lors de la validation de la semaine)
  // RETOUR : Booléen
  function controleSemaineValideeAutre($cooker, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($cooker != $identifiant)
    {
      $_SESSION['alerts']['other_cooker'] = true;
      $control_ok                         = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Element numérique et positif
  // RETOUR : Booléen
  function controleNumerique($element, $error)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if  (!empty($element) AND (!is_numeric($element) OR $element <= 0))
    {
      $_SESSION['alerts'][$error] = true;
      $control_ok                 = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Doublon à l'insertion
  // RETOUR : Booléen
  function controleInsertionDoublon($semaineGateau)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!empty($semaineGateau->getName())
    OR  !empty($semaineGateau->getPicture())
    OR  !empty($semaineGateau->getIngredients())
    OR  !empty($semaineGateau->getRecipe())
    OR  !empty($semaineGateau->getTips()))
    {
      $control_ok = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Saisie non vide
  // RETOUR : Booléen
  function controleImageInseree($nomFichier)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (empty($nomFichier))
    {
      $_SESSION['alerts']['empty_recipe'] = true;
      $control_ok                         = false;
    }

    // Retour
    return $control_ok;
  }
?>
