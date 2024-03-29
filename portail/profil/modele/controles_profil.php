<?php
    // CONTROLE : Format de date
    // RETOUR : Booléen
    function controleFormatDate($date, $isMobile)
    {
        // Initialisations
        $control_ok = true;
        $alerteDate = false;

        // Contrôle
        if ($isMobile == true)
        {
            if (validateDateMobile($date) != true)
                $alerteDate = true;
        }
        else
        {
            if (validateDate($date) != true)
                $alerteDate = true;
        }

        if ($alerteDate == true)
        {
            $_SESSION['alerts']['wrong_date'] = true;
            $control_ok                       = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Date dans le futur
    // RETOUR : Booléen
    function controleDateFutur($date)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($date >= date('Ymd'))
        {
            $_SESSION['alerts']['future_date'] = true;
            $control_ok                        = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Correspondance mot de passe
    // RETOUR : Booléen
    function controleCorrespondancePassword($saisie, $base)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($saisie != $base)
        {
            $_SESSION['alerts']['wrong_password'] = true;
            $control_ok                           = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Ancienne équipe différente
    // RETOUR : Booléen
    function controleAncienneEquipe($ancienneEquipe, $nouvelleEquipe)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($ancienneEquipe == $nouvelleEquipe)
        {
            $_SESSION['alerts']['same_team'] = true;
            $control_ok                      = false;
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
            $_SESSION['alerts']['team_expenses_not_null'] = true;
            $control_ok                                   = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Mission terminée ou autre succès que mission
    // RETOUR : Booléen
    function controleMissionTermineeOuAutre($referenceMission)
    {
        // Initialisations
        $missionTermineeOuAutre = false;

        // Contrôle
        if (!empty($referenceMission))
        {
            $dateFinMission = physiqueDateFinMission($referenceMission);

            if (date('Ymd') > $dateFinMission)
                $missionTermineeOuAutre = true;
        }
        else
            $missionTermineeOuAutre = true;

        // Retour
        return $missionTermineeOuAutre;
    }
?>