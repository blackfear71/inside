<?php
  // CONTROLE : Date détermination
  // RETOUR : Booléen
  function controleDateDetermination()
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (date('N') > 5)
    {
      $_SESSION['alerts']['week_end_determination'] = true;
      $control_ok                                   = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Heure détermination
  // RETOUR : Booléen
  function controleHeureDetermination()
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (date('H') >= 13)
    {
      $_SESSION['alerts']['heure_determination'] = true;
      $control_ok                                = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Date saisie
  // RETOUR : Booléen
  function controleDateSaisie()
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (date('N') > 5)
    {
      $_SESSION['alerts']['week_end_saisie'] = true;
      $control_ok                            = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Heure saisie
  // RETOUR : Booléen
  function controleHeureSaisie()
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (date('H') >= 13)
    {
      $_SESSION['alerts']['heure_saisie'] = true;
      $control_ok                         = false;
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
      $_SESSION['alerts']['solo_saisie'] = true;
      $control_ok                        = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Choix déjà existant
  // RETOUR : Booléen
  function controleChoixExistant($idRestaurant, $identifiant)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $exist = physiqueChoixExistant($idRestaurant, $identifiant);

    if ($exist == true)
    {
      $_SESSION['alerts']['wrong_fast'] = true;
      $control_ok                       = false;
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
