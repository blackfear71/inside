<?php
    echo '<div id="zone_saisie_vue" class="fond_saisie">';
        echo '<div class="div_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                echo 'Changer la vue';
            echo '</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    $listeVues = array(
                        'me'    => 'Moi',
                        'today' => 'Aujourd\'hui',
                        'week'  => '7 jours',
                        'all'   => 'Toutes'
                    );

                    foreach ($listeVues as $keyVue => $vue)
                    {
                        if ($keyVue == 'all' OR $keyVue == 'week' OR $keyVue == 'me')
                            $page = '&page=1';
                        else
                            $page = '';

                        if ($_GET['view'] == $keyVue)
                            echo '<a href="notifications.php?view=' . $keyVue . '&action=goConsulter' . $page . '" class="lien_saisie lien_courant">' . $vue . '</a>';
                        else
                            echo '<a href="notifications.php?view=' . $keyVue . '&action=goConsulter' . $page . '" class="lien_saisie">' . $vue . '</a>';
                    }
                echo '</div>';
            echo '</div>';

            // Bouton fermeture
            echo '<div class="zone_boutons_saisie">';
                echo '<a id="fermerSaisieVue" class="bouton_saisie_fermer">Fermer</a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>