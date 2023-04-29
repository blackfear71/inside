<?php
    // CONTROLE : Référence alerte unique
    // RETOUR : Booléen
    function controleReferenceUnique($reference)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueReferenceUnique($reference) == false)
        {
            $_SESSION['alerts']['already_referenced'] = true;
            $control_ok                               = false;
        }

        // Retour
        return $control_ok;
    }

    // CONTROLE : Référence alerte unique (mise à jour)
    // RETOUR : Booléen
    function controleReferenceUniqueUpdate($reference, $idAlert)
    {
        // Initialisations
        $control_ok = true;

        // Contrôle
        if (physiqueReferenceUniqueUpdate($reference, $idAlert) == false)
        {
            $_SESSION['alerts']['already_referenced'] = true;
            $control_ok                               = false;
        }

        // Retour
        return $control_ok;
    }
?>