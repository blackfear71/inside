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
                    // Annexes
                    if ($_GET['action'] == 'goConsulterAnnexes')
                        echo '<a href="calendars.php?action=goConsulterAnnexes" class="lien_saisie lien_courant">Annexes</a>';
                    else
                        echo '<a href="calendars.php?action=goConsulterAnnexes" class="lien_saisie">Annexes</a>';

                    // Années
                    if (!empty($onglets))
                    {
                        foreach ($onglets as $annee)
                        {
                            if (isset($_GET['year']) AND $annee == $_GET['year'])
                                echo '<a href="calendars.php?year=' . $annee . '&action=goConsulter" class="lien_saisie lien_courant">' . $annee . '</a>';
                            else
                                echo '<a href="calendars.php?year=' . $annee . '&action=goConsulter" class="lien_saisie">' . $annee . '</a>';
                        }
                    }
                    else
                    {
                        if ($_GET['action'] != 'goConsulterAnnexes')
                            echo '<a href="calendars.php?year=' . $_GET['year'] . '&action=goConsulter" class="lien_saisie lien_courant">' . $_GET['year'] . '</a>';
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