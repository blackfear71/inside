<?php
    // CONTROLE : Référence succès unique
    // RETOUR : Booléen
    function controleReferenceUnique($reference)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        $isUnique = physiqueReferenceUnique($reference);

        if ($isUnique == false)
        {
            $_SESSION['alerts']['already_referenced'] = true;
            $control_ok                               = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Ordonnancement succès unique
    // RETOUR : Booléen
    function controleOrdonnancementUnique($niveau, $ordre)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        $isUnique = physiqueOrdonnancementUnique($niveau, $ordre);

        if ($isUnique == false)
        {
            $_SESSION['alerts']['already_ordered'] = true;
            $control_ok                            = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Element numérique et positif
    // RETOUR : Booléen
    function controleNumerique($element, $error)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (!is_numeric($element) OR $element <= 0)
        {
            $_SESSION['alerts'][$error] = true;
            $control_ok                 = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Contrôle doublons mise à jour succès
    // RETOUR : Booléen
    function controleDoublons($listeSuccess, $successToCheck)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        foreach ($listeSuccess as $success)
        {
            if ($successToCheck['id']            != $success['id']
            AND $successToCheck['order_success'] == $success['order_success']
            AND $successToCheck['level']         == $success['level'])
            {
                $_SESSION['alerts']['already_ordered'] = true;
                $control_ok                            = false;
                break;
            }
        }

        // Retour
        return $control_ok;
    }
?>