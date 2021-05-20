<?php
  // CONTROLE : Date détermination
  // RETOUR : Booléen
  function controleDateSaisie($alerte)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (date('N') > 5)
    {
      $_SESSION['alerts'][$alerte] = true;
      $control_ok                  = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Heure saisie
  // RETOUR : Booléen
  function controleHeureSaisie($alerte)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (date('H') >= 13)
    {
      $_SESSION['alerts'][$alerte] = true;
      $control_ok                  = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Bande à part
  // RETOUR : Booléen
  function controleSoloSaisie($isSolo)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($isSolo == true)
    {
      $_SESSION['alerts']['input_solo'] = true;
      $control_ok                       = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Choix existant
  // RETOUR : Booléen
  function controleChoixExistant($idRestaurant, $identifiant, $alerte)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $exist = physiqueChoixExistant($idRestaurant, $identifiant);

    if ($exist == true)
    {
      $_SESSION['alerts'][$alerte] = true;
      $control_ok                  = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Choix déjà existant
  // RETOUR : Booléen
  function controleRestaurantOuvert($opened)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $explodedOpened = explode(';', $opened);

    foreach ($explodedOpened as $keyOpened => $opened)
    {
      if (!empty($opened))
      {
        if (date('N') == $keyOpened + 1 AND $opened == 'N')
        {
          $_SESSION['alerts']['not_open'] = true;
          $control_ok                     = false;

          break;
        }
      }
    }

    // Retour
    return $control_ok;
  }
?>
