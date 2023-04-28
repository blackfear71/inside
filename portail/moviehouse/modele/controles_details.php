<?php
    // CONTROLE : Film disponible
    // RETOUR : Booléen
    function controleFilmDisponible($idFilm, $equipe)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueFilmDisponible($idFilm, $equipe) != true)
        {
            $_SESSION['alerts']['film_doesnt_exist'] = true;
            $control_ok                              = false;
        }

        // Retour
        return $control_ok;
    }
?>