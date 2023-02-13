<?php
    echo '<div id="zone_saisie_filtre" class="fond_saisie">';
        echo '<div class="div_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                echo 'Appliquer un filtre';
            echo '</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    foreach ($filters as $filter)
                    {
                        if ($filter['value'] == $_GET['filter'])
                            echo '<a href="expensecenter.php?year=' . $_GET['year'] . '&filter=' . $filter['value'] . '&action=goConsulter" class="lien_saisie lien_courant">' . $filter['label'] . '</a>';
                        else
                            echo '<a href="expensecenter.php?year=' . $_GET['year'] . '&filter=' . $filter['value'] . '&action=goConsulter" class="lien_saisie">' . $filter['label'] . '</a>';
                    }
                echo '</div>';
            echo '</div>';

            // Bouton fermeture
            echo '<div class="zone_boutons_saisie">';
                echo '<a id="fermerSaisieFiltre" class="bouton_saisie_fermer">Fermer</a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>