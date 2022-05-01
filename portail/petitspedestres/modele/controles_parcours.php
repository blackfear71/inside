<?php
  // CONTROLE : Parcours disponible
  // RETOUR : Booléen
  function controleParcoursDisponible($idParcours, $equipe)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $parcoursDisponible = physiqueParcoursDisponible($idParcours, $equipe);

    if ($parcoursDisponible != true)
    {
      $_SESSION['alerts']['parcours_doesnt_exist'] = true;
      $control_ok                                  = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Distance numérique
  // RETOUR : Booléen
  function controleDistanceNumerique($distance)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!is_numeric($distance) OR $distance <= 0)
    {
      $_SESSION['alerts']['distance_error'] = true;
      $control_ok                           = false;
    }

    // Retour
    return $control_ok;
  }

  // CONTROLE : Type de lien renseigné
  // RETOUR : Booléen
  function controleTypeLien($url, $type)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if (!empty($url) AND empty($type))
    {
      $_SESSION['alerts']['url_type'] = true;
      $control_ok                     = false;
    }

    // Retour
    return $control_ok;
  }
?>
