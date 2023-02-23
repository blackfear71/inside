<?php
    if ($_SESSION['user']['celsius'] == 'Y' AND !empty($celsius))
    {
        // Récupération du contenu Celsius
        $contenuCelsius = getContenuCelsius($celsius);

        // Affichage
        if (!empty($contenuCelsius))
        {
            // Icône
            echo '<img src="/inside/includes/icons/common/celsius.png" alt="celsius" title="Celsius" class="celsius" />';

            // Contenu
            echo '<div id="contenuCelsius" class="zone_contenu_celsius">';
                // Titre
                echo '<div class="titre_contenu_celsius">';
                    echo 'Celsius';
                echo '</div>';

                // Texte
                echo '<div class="zone_texte_celsius">';
                    echo '<div class="texte_contenu_celsius">' . $contenuCelsius . '</div>';
                echo '</div>';

                // Boutons
                echo '<div class="zone_boutons_celsius">';
                    // Réinitialisation position
                    echo '<a id="resetCelsius" class="bouton_celsius_left">Réinitialiser</a>';

                    // Fermeture
                    echo '<a id="closeCelsius" class="bouton_celsius_right">Fermer</a>';
                echo '</div>';
            echo '</div>';
        }
    }
?>