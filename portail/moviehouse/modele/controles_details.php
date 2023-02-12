<?php
    // CONTROLE : Film disponible
    // RETOUR : Booléen
    function controleFilmDisponible($idFilm, $equipe)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        $filmDisponible = physiqueFilmDisponible($idFilm, $equipe);

        if ($filmDisponible != true)
        {
            $_SESSION['alerts']['film_doesnt_exist'] = true;
            $control_ok                              = false;
        }

        // Retour
        return $control_ok;
    }
?>