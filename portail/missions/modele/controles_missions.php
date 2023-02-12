<?php
    // CONTROLE : Mission disponible
    // RETOUR : Booléen
    function controlMissionDisponible($idMission)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        $missionDisponible = physiqueMissionDisponible($idMission);

        if ($missionDisponible != true)
        {
            $_SESSION['alerts']['mission_doesnt_exist'] = true;
            $control_ok                                 = false;
        }

        // Retour
        return $control_ok;
    }
?>