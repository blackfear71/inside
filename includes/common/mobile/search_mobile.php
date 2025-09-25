<?php
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true)
    {
        echo '<div id="searchBar" class="fond_recherche">';
            echo '<div class="form_recherche_bandeau">';
                // Saisie recherche
                echo '<form method="post" action="/portail/search/search.php?action=doRechercher">';
                    echo '<input type="text" name="text_search" placeholder="Rechercher..." id="searchFocus" class="recherche_bandeau" />';
                    echo '<input type="submit" name="search" value="" class="logo_rechercher" />';
                echo '</form>';

                // Bouton fermeture
                echo '<a id="masquerBarreRecherche" class="bouton_fermer_recherche">Fermer la recherche</a>';
            echo '</div>';
        echo '</div>';
    }
?>