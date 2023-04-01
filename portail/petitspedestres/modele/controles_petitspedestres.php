<?php
    // CONTROLE : Distance numérique
    // RETOUR : Booléen
    function controleDistanceNumerique($distance)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!is_numeric($distance) OR $distance <= 0)
        {
            $_SESSION['alerts']['wrong_distance'] = true;
            $control_ok                           = false;
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

    // CONTROLE : Parcours disponible
    // RETOUR : Booléen
    function controleParcoursDisponible($idParcours, $equipe)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        $parcoursDisponible = physiqueParcoursDisponible($idParcours, $equipe);

        if ($parcoursDisponible != true)
        {
            $_SESSION['alerts']['parcours_doesnt_exist'] = true;
            $control_ok                                  = false;
        }

        // Retour
        return $control_ok;
    }
?>