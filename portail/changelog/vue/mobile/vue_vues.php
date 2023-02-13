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
                    // Histoire du site
                    if ($_GET['action'] == 'goConsulterHistoire')
                        echo '<a href="changelog.php?action=goConsulterHistoire" class="lien_saisie lien_courant">Histoire du site</a>';
                    else
                        echo '<a href="changelog.php?action=goConsulterHistoire" class="lien_saisie">Histoire du site</a>';

                    // Années
                    if (!empty($onglets))
                    {
                        foreach ($onglets as $annee)
                        {
                            if (isset($_GET['year']) AND $annee == $_GET['year'])
                                echo '<a href="changelog.php?year=' . $annee . '&action=goConsulter" class="lien_saisie lien_courant">' . $annee . '</a>';
                            else
                                echo '<a href="changelog.php?year=' . $annee . '&action=goConsulter" class="lien_saisie">' . $annee . '</a>';
                        }
                    }
                    else
                    {
                        if ($_GET['action'] != 'goConsulterHistoire')
                            echo '<a href="changelog.php?year=' . $_GET['year'] . '&action=goConsulter" class="lien_saisie lien_courant">' . $_GET['year'] . '</a>';
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