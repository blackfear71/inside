<?php
    echo '<div class="zone_bugs_evolutions">';
    /********/
    /* Bugs */
    /********/
    // Titre
    echo '<div id="titre_bugs" class="titre_section">';
        echo '<img src="../../includes/icons/reports/bug.png" alt="bug" class="logo_titre_section" />';
        echo '<div class="texte_titre_section_fleche">Bugs</div>';
        echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Bugs
    echo '<div id="afficher_bugs">';
        if (!empty($listeBugs))
        {
            foreach ($listeBugs as $bug)
            {
                echo '<div class="zone_bug_evolution">';
                    echo '<div id="zone_shadow_' . $bug->getId() . '" class="zone_shadow">';
                        // Titre
                        echo '<div class="zone_bug_evolution_haut" id="' . $bug->getId() . '">';
                            // Libellé
                            echo '<div class="zone_bug_evolution_titre">' . $bug->getSubject() . '</div>';

                            // Numéro
                            echo '<div class="zone_bug_evolution_id">#' . $bug->getId() . '</div>';
                        echo '</div>';

                        // Infos
                        echo '<div class="zone_bug_evolution_milieu">';
                            // Avatar
                            $avatarFormatted = formatAvatar($bug->getAvatar(), $bug->getPseudo(), 2, 'avatar');

                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bug_evolution" />';

                            // Pseudo
                            echo '<div class="pseudo_bug_evolution">' . formatUnknownUser($bug->getPseudo(), true, true) . '</div>';

                            // Date
                            echo '<div class="date_bug_evolution">';
                                echo '<img src="../../includes/icons/reports/date.png" alt="date" class="icone_bug_evolution" />';
                                echo '<div class="texte_date_bug_evolution">' . formatDateForDisplay($bug->getDate()) . '</div>';
                            echo '</div>';

                            // Statut
                            switch ($bug->getResolved())
                            {
                                case 'Y':
                                    echo '<div class="report_status report_ended">Terminé</div>';
                                    break;

                                case 'R':
                                    echo '<div class="report_status report_rejected">Rejeté</div>';
                                    break;

                                case 'N':
                                default:
                                    echo '<div class="report_status report_in_progress">En cours</div>';
                                    break;
                            }
                        echo '</div>';

                        // Contenu
                        echo '<div class="zone_bug_evolution_bas">';
                            if (!empty($bug->getPicture()))
                                echo '<a class="agrandirImage"><img src="../../includes/images/reports/' . $bug->getPicture() . '" alt="' . $bug->getPicture() . '" class="image_bug_evolution" /></a>';

                            echo '<div class="content_bug_evolution">' . nl2br($bug->getContent()) . '</div>';

                            if (!empty($bug->getPicture()))
                                echo '<div class="clear_bug_evolution"></div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Pas de bugs, tout va bien...</div>';
    echo '</div>';

    /**************/
    /* Evolutions */
    /**************/
    // Titre
    echo '<div id="titre_evolutions" class="titre_section">';
        echo '<img src="../../includes/icons/reports/evolution.png" alt="evolution" class="logo_titre_section" />';
        echo '<div class="texte_titre_section_fleche">Evolutions</div>';
        echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Evolutions
    echo '<div id="afficher_evolutions">';
        if (!empty($listeEvolutions))
        {
            foreach ($listeEvolutions as $evolution)
            {
                echo '<div class="zone_bug_evolution">';
                    echo '<div id="zone_shadow_' . $evolution->getId() . '" class="zone_shadow">';
                        // Titre
                        echo '<div class="zone_bug_evolution_haut" id="' . $evolution->getId() . '">';
                            // Libellé
                            echo '<div class="zone_bug_evolution_titre">' . $evolution->getSubject() . '</div>';

                            // Numéro
                            echo '<div class="zone_bug_evolution_id">#' . $evolution->getId() . '</div>';
                        echo '</div>';

                        // Infos
                        echo '<div class="zone_bug_evolution_milieu">';
                            // Avatar
                            $avatarFormatted = formatAvatar($evolution->getAvatar(), $evolution->getPseudo(), 2, 'avatar');

                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bug_evolution" />';

                            // Pseudo
                            echo '<div class="pseudo_bug_evolution">' . formatUnknownUser($evolution->getPseudo(), true, true) . '</div>';

                            // Date
                            echo '<div class="date_bug_evolution">';
                                echo '<img src="../../includes/icons/reports/date.png" alt="date" class="icone_bug_evolution" />';
                                echo '<div class="texte_date_bug_evolution">' . formatDateForDisplay($evolution->getDate()) . '</div>';
                            echo '</div>';

                            // Statut
                            switch ($evolution->getResolved())
                            {
                                case 'Y':
                                    echo '<div class="report_status report_ended">Terminée</div>';
                                    break;

                                case 'R':
                                    echo '<div class="report_status report_rejected">Rejetée</div>';
                                    break;

                                case 'N':
                                default:
                                    echo '<div class="report_status report_in_progress">En cours</div>';
                                    break;
                            }
                        echo '</div>';

                        // Contenu
                        echo '<div class="zone_bug_evolution_bas">';
                            if (!empty($evolution->getPicture()))
                                echo '<a class="agrandirImage"><img src="../../includes/images/reports/' . $evolution->getPicture() . '" alt="' . $evolution->getPicture() . '" class="image_bug_evolution" /></a>';

                            echo '<div class="content_bug_evolution">' . nl2br($evolution->getContent()) . '</div>';

                            if (!empty($evolution->getPicture()))
                                echo '<div class="clear_bug_evolution"></div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Pas d\'évolutions proposées...</div>';
        echo '</div>';
    echo '</div>';
?>