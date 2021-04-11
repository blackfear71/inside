<?php
  // CONTROLE : Film disponible
  // RETOUR : Booléen
  function controleFilmDisponible($idFilm)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    $filmDisponible = physiqueFilmDisponible($idFilm);

    if ($filmDisponible != true)
    {
      $_SESSION['alerts']['film_doesnt_exist'] = true;
      $control_ok                              = false;
    }

    // Retour
    return $control_ok;
  }
?>
