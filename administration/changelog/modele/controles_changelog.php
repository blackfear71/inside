<?php
    // CONTROLE : Journal existant
    // RETOUR : Booléen
    function controleChangelogExistant($action, $year, $week)
    {
        // Initialisations
        $error = false;

        // Contrôle
        $exist = physiqueChangelogExistant($year, $week);

        if ($action == 'M' OR $action == 'S')
        {
            if ($exist == false)
            {
                $_SESSION['alerts']['log_doesnt_exist'] = true;
                $error                                  = true;
            }
        }
        else
        {
            if ($exist == true)
            {
                $_SESSION['alerts']['log_already_exist'] = true;
                $error                                   = true;
            }
        }

        // Retour
        return $error;
    }

    // CONTROLE : Notes et entrées vides
    // RETOUR : Booléen
    function controleNotesEntreesVides($notes, $saisiesEntrees)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (empty($notes) AND empty($saisiesEntrees))
        {
            $_SESSION['alerts']['log_empty'] = true;
            $control_ok                      = false;
        }

        // Retour
        return $control_ok;
    }
?>