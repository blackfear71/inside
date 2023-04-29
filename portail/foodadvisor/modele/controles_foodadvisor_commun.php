<?php
    // CONTROLE : Format de date
    // RETOUR : Booléen
    function controleFormatDate($date)
    {
        // Initialisations
        $control_ok = true;

        if (validateDate($date) != true)
        {
            $_SESSION['alerts']['wrong_date'] = true;
            $control_ok                       = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Date saisie
    // RETOUR : Booléen
    function controleDateSaisie($date)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($date < date('Ymd'))
        {
            $_SESSION['alerts']['input_date'] = true;
            $control_ok                       = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Date saisie (week-end)
    // RETOUR : Booléen
    function controleDateSaisieWeekEnd($date, $alerte)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (date('N', strtotime($date)) > 5)
        {
            $_SESSION['alerts'][$alerte] = true;
            $control_ok                  = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Heure saisie
    // RETOUR : Booléen
    function controleHeureSaisie($date, $alerte)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($date == date('Ymd') AND date('H') >= 13)
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
    function controleChoixExistant($idRestaurant, $identifiant, $equipe, $date, $alerte)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueChoixExistant($idRestaurant, $identifiant, $equipe, $date) == true)
        {
            $_SESSION['alerts'][$alerte] = true;
            $control_ok                  = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Choix déjà existant
    // RETOUR : Booléen
    function controleRestaurantOuvert($opened, $date)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        $explodedOpened = array_filter(explode(';', $opened));

        foreach ($explodedOpened as $keyOpened => $opened)
        {
            if (date('N', strtotime($date)) == $keyOpened + 1 AND $opened == 'N')
            {
                $_SESSION['alerts']['not_open'] = true;
                $control_ok                     = false;
                break;
            }
        }

        // Retour
        return $control_ok;
    }
?>