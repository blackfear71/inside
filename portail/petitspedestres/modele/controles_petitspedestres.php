<?php
    // CONTROLE : Donnée numérique et positive
    // RETOUR : Booléen
    function controleDonneeNumerique($donnee, $alerte)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!is_numeric($donnee) OR $donnee <= 0)
        {
            $_SESSION['alerts'][$alerte] = true;
            $control_ok                  = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Donnée entière et positive
    // RETOUR : Booléen
    function controleDonneeEntiere($donnee, $alerte)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!is_numeric($donnee) OR $donnee != floor($donnee) OR $donnee <= 0)
        {
            $_SESSION['alerts'][$alerte] = true;
            $control_ok                  = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Contenu renseigné
    // RETOUR : Booléen
    function controleContenuParcours($post, $document)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (empty($post['nom_parcours']) OR empty($post['distance_parcours']) OR empty($post['lieu_parcours']) OR empty($document))
        {
            $_SESSION['alerts']['empty_parcours'] = true;
            $control_ok                           = false;
        }

        // Retour
        return $control_ok;
    }

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

    // CONTROLE : Temps valide
    // RETOUR : Booléen
    function controleTempsValide($heures, $minutes, $secondes)
    {
        // Initialisations
        $control_ok = true;
        
        // Contrôle
        if ((!empty($heures)   AND (!is_numeric($heures)   OR $heures   != floor($heures)   OR $heures   < 0))
        OR  (!empty($minutes)  AND (!is_numeric($minutes)  OR $minutes  != floor($minutes)  OR $minutes  < 0 OR $minutes  > 59))
        OR  (!empty($secondes) AND (!is_numeric($secondes) OR $secondes != floor($secondes) OR $secondes < 0 OR $secondes > 59)))
        {
            $_SESSION['alerts']['wrong_time'] = true;
            $control_ok                       = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Participation existante
    // RETOUR : Booléen
    function controleParticipationExistante($identifiant, $equipe, $idParcours, $date)
    {
        // Initialisations
        $control_ok = true;
    
        // Contrôle
        if (physiqueParticipationExistante($identifiant, $equipe, $idParcours, $date) == true)
        {
            $_SESSION['alerts']['participation_already_exist'] = true;
            $control_ok                                        = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Date participation <= date du jour
    // RETOUR : Booléen
    function controleDateSaisie($date)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($date > date('Ymd'))
        {
            $_SESSION['alerts']['participation_date'] = true;
            $control_ok                               = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Parcours disponible
    // RETOUR : Booléen
    function controleParcoursDisponible($idParcours, $equipe)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueParcoursDisponible($idParcours, $equipe) != true)
        {
            $_SESSION['alerts']['parcours_doesnt_exist'] = true;
            $control_ok                                  = false;
        }

        // Retour
        return $control_ok;
    }
?>