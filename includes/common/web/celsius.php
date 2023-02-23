<?php
    if ($_SESSION['user']['celsius'] == 'Y' AND !empty($celsius))
    {
        // Récupération du contenu Celsius
        $contenuCelsius = getContenuCelsius($celsius);

        // Affichage
        if (!empty($contenuCelsius))
        {
            echo '<div class="zone_celsius">';
                // Contenu
                echo '<div class="zone_contenu_celsius">';
                    // Titre
                    echo '<div class="titre_contenu_celsius">';
                        echo 'Celsius';
                    echo '</div>';

                    // Texte
                    echo '<div class="zone_texte_celsius">';
                        echo '<div class="texte_contenu_celsius">' . $contenuCelsius . '</div>';
                    echo '</div>';
                echo '</div>';

                // Icône
                echo '<img src="/inside/includes/icons/common/celsius.png" alt="celsius" title="Celsius" class="celsius" />';
            echo '</div>';
        }
    }
?>