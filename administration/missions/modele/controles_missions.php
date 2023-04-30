<?php
    // CONTROLE : Référence succès unique
    // RETOUR : Booléen
    function controleReferenceUnique($reference)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueReferenceUnique($reference) == false)
        {
            $_SESSION['alerts']['already_ref_mission'] = true;
            $control_ok                                = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Format de date
    // RETOUR : Booléen
    function controleFormatDate($date)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (validateDate($date) != true)
        {
            $_SESSION['alerts']['wrong_date'] = true;
            $control_ok                       = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Date 1 < Date 2
    // RETOUR : Booléen
    function controleOrdreDates($date1, $date2)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($date1 > $date2)
        {
            $_SESSION['alerts']['date_less'] = true;
            $control_ok                      = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Objectif numérique et positif
    // RETOUR : Booléen
    function controleObjectifNumerique($objectif)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!is_numeric($objectif) OR $objectif <= 0)
        {
            $_SESSION['alerts']['objective_not_numeric'] = true;
            $control_ok                                  = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Fichier présent
    // RETOUR : Booléen
    function controlePresenceFichier($nomFichier)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (empty($nomFichier) OR $nomFichier == NULL)
        {
            $_SESSION['alerts']['missing_mission_file'] = true;
            $control_ok                                 = false;
        }

        // Retour
        return $control_ok;
    }
?>