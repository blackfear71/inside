<?php
    // CONTROLE : Prix renseignés
    // RETOUR : Booléen
    function controlePrixRenseignes($prix1, $prix2)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ((!empty($prix1) AND  empty($prix2))
        OR   (empty($prix1) AND !empty($prix2)))
        {
            $_SESSION['alerts']['miss_price'] = true;
            $control_ok                       = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Prix numérique et positif
    // RETOUR : Booléen
    function controlePrixNumerique($prix, $type)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!empty($prix))
        {
            if (!is_numeric($prix) OR $prix <= 0)
            {
                $_SESSION['alerts']['wrong_price_' . $type] = true;
                $control_ok                                 = false;
            }
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Prix 1 < Prix 2
    // RETOUR : Booléen
    function controleOrdrePrix($prix1, $prix2)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($prix1 > $prix2)
        {
            $_SESSION['alerts']['price_max_min'] = true;
            $control_ok                          = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Numéro téléphone numérique et égal à 10 caractères
    // RETOUR : Booléen
    function controleTelephoneNumerique($telephone)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!empty($telephone))
        {
            if (!is_numeric($telephone) OR strlen($telephone) != 10)
            {
                $_SESSION['alerts']['wrong_phone_number'] = true;
                $control_ok                               = false;
            }
        }

        // Retour
        return $control_ok;
    }
?>