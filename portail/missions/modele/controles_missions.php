<?php
    // CONTROLE : Mission disponible
    // RETOUR : Booléen
    function controlMissionDisponible($idMission)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueMissionDisponible($idMission) != true)
        {
            $_SESSION['alerts']['mission_doesnt_exist'] = true;
            $control_ok                                 = false;
        }

        // Retour
        return $control_ok;
    }
?>