<?php
    // Onglets
    echo '<div class="zone_vues">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/reports/view_grey.png" alt="view_grey" class="logo_titre_section" /><div class="texte_titre_section">Vues</div></div>';

        // Vues
        $listeVues = array('all'        => 'Tous',
                            'unresolved' => 'En cours',
                            'resolved'   => 'Résolu(e)s'
                            );

        foreach ($listeVues as $view => $vue)
        {
            if ($_GET['view'] == $view)
                echo '<span class="view active">' . $vue . '</span>';
            else
                echo '<a href="reports.php?view=' . $view . '&action=goConsulter" class="view inactive">' . $vue . '</a>';
        }
    echo '</div>';
?>