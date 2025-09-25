<?php
    // Import de la feuille de style
    echo '<link rel="stylesheet" href="/includes/assets/css/' . $plateforme . '/styleErrors.css?version=' . $dateModificationCssErrors . '" />';

    // Affichage de l'erreur
    echo '<div class="titre_erreur">';
        echo '<img src="/includes/icons/common/inside_red.png" alt="inside" title="Inside" class="logo_erreur" />';
        echo $erreur;
    echo '</div>';

    // Lien retour au portail
    echo '<a href="/portail/portail/portail.php?action=goConsulter" class="lien_erreur">Revenir sur Inside</a>';
?>