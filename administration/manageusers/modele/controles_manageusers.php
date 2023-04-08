<?php
    // CONTROLE : Equipe existante
    // RETOUR : Booléen
    function controleEquipeUnique($reference)
    {
        // Initialisations
        $control_ok = true;

        if (physiqueEquipeExistante($reference) == true)
        {
            $_SESSION['alerts']['team_already_exist'] = true;
            $control_ok                               = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Dépenses non nulles
    // RETOUR : Booléen
    function controleDepensesNonNulles($expense)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($expense < -0.01 OR $expense > 0.01)
        {
            $_SESSION['alerts']['expenses_not_null'] = true;
            $control_ok                              = false;
        }

        // Retour
        return $control_ok;
    }
?>