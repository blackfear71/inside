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
    function controleOrdonnancementUnique($niveau, $ordre, $reference, $isUpdate)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if ($isUpdate == true)
            $isUnique = physiqueOrdonnancementUniqueModification($niveau, $ordre, $reference);
        else
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
?>