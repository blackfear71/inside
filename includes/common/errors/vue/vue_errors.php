<?php
    // Import de la feuille de style
    echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $plateforme . '/styleErrors.css?version=' . $dateModificationCssErrors . '" />';

    // Affichage de l'erreur
    echo '<div class="titre_erreur">';
        echo '<img src="/inside/includes/icons/common/inside_red.png" alt="inside" title="Inside" class="logo_erreur" />';
        echo $erreur;
    echo '</div>';

    // Lien retour au portail
    echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" class="lien_erreur">Revenir sur Inside</a>';
?>