<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/calendars/year_grey.png" alt="year_grey" class="logo_titre_section" /><div class="texte_titre_section">Années & annexes</div></div>';

    // Onglets
    echo '<div class="zone_annees_calendrier">';
        if ($_GET['action'] == 'goConsulterAnnexes')
            echo '<span class="annexes active">Annexes</span>';
        else
            echo '<a href="calendars.php?action=goConsulterAnnexes" class="annexes inactive">Annexes</a>';

        if (!empty($onglets))
        {
            $i            = 0;
            $previousYear = $onglets[0];
            $lastYear     = true;

            foreach ($onglets as $annee)
            {
                // Année inexistante (première ou au milieu)
                if ($_GET['action'] != 'goConsulterAnnexes')
                {
                    if ($lastYear != false AND $anneeExistante == false AND (($_GET['year'] < $previousYear AND $_GET['year'] > $annee) OR $_GET['year'] > $onglets[0]))
                    {
                        if ($i % 2 == 0)
                            echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
                        else
                            echo '<span class="year active">' . $_GET['year'] . '</span>';

                        $lastYear = false;
                        $i++;
                    }
                }

                // Année existante
                if ($i % 2 == 0)
                {
                    if (isset($_GET['year']) AND $annee == $_GET['year'])
                        echo '<span class="year active margin_right_20">' . $annee . '</span>';
                    else
                        echo '<a href="calendars.php?year=' . $annee . '&action=goConsulter" class="year inactive margin_right_20">' . $annee . '</a>';
                }
                else
                {
                    if (isset($_GET['year']) AND $annee == $_GET['year'])
                        echo '<span class="year active">' . $annee . '</span>';
                    else
                        echo '<a href="calendars.php?year=' . $annee . '&action=goConsulter" class="year inactive">' . $annee . '</a>';
                }

                $previousYear = $annee;
                $i++;
            }

            // Année inexistante (dernière)
            if ($_GET['action'] != 'goConsulterAnnexes')
            {
                if ($lastYear == true AND $anneeExistante == false)
                    echo '<span class="year active">' . $_GET['year'] . '</span>';
            }
        }
        else
        {
            if ($_GET['action'] != 'goConsulterAnnexes')
                echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
        }
    echo '</div>';
?>