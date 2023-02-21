<?php
    echo '<div class="zone_jours_semaine_propositions">';
        // Semaines suivantes et précédentes
        $lundiSemainePrecedente = date('Ymd', strtotime($joursSemaine[0]['date'] . '- 7 days'));
        $lundiSemaineSuivante   = date('Ymd', strtotime($joursSemaine[0]['date'] . '+ 7 days'));

        // Semaine précédente
        echo '<a href="foodadvisor.php?date=' . $lundiSemainePrecedente . '&action=goConsulter" title="Semaine précédente" class="lien_semaine_precedente"></a>';

        // Affichage des liens de chaque jour de la semaine
        foreach($joursSemaine as $jourSemaine)
        {
            // Formatage de la date
            if (substr($jourSemaine['date'], 0, 4) == date('Y'))
                $jourLien = $jourSemaine['web'] . ' ' . formatDateForDisplayLight($jourSemaine['date']);
            else
                $jourLien = $jourSemaine['web'] . ' ' . formatDateForDisplay($jourSemaine['date']);

            // Lien
            if ($jourSemaine['date'] == date('Ymd'))
                echo '<a href="foodadvisor.php?date=' . $jourSemaine['date'] . '&action=goConsulter" class="jour_semaine_propositions jour_semaine_date">' . $jourLien . '</a>';
            elseif ($jourSemaine['date'] == $_GET['date'])
                echo '<a href="foodadvisor.php?date=' . $jourSemaine['date'] . '&action=goConsulter" class="jour_semaine_propositions jour_semaine_selectionne">' . $jourLien . '</a>';
            else
                echo '<a href="foodadvisor.php?date=' . $jourSemaine['date'] . '&action=goConsulter" class="jour_semaine_propositions jour_semaine_autre">' . $jourLien . '</a>';
        }

        // Semaine suivante
        echo '<a href="foodadvisor.php?date=' . $lundiSemaineSuivante . '&action=goConsulter" title="Semaine suivante" class="lien_semaine_suivante"></a>';
    echo '</div>';
?>