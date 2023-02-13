<?php
    // Journaux de modification
    echo '<div class="zone_changelogs">';
        if (!empty($listeLogs))
        {
            foreach ($listeLogs as $keyLog => $log)
            {
                // Titre
                echo '<div id="titre_journal_semaine_' . $log->getWeek() . '" class="titre_section">';
                    echo '<img src="../../includes/icons/changelog/change_log_grey.png" alt="change_log_grey" class="logo_titre_section" />';
                    echo '<div class="texte_titre_section_fleche">Nouveautés de la semaine ' . formatWeekForDisplay($log->getWeek()) . '</div>';

                    if (isset($_GET['anchor']) AND is_numeric($_GET['anchor']))
                    {
                        if ($log->getWeek() == $_GET['anchor'])
                            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                        else
                            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
                    }
                    else
                    {
                        if ($keyLog == 0)
                            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                        else
                            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
                    }
                echo '</div>';

                // Journal
                if (isset($_GET['anchor']) AND is_numeric($_GET['anchor']))
                {
                    if ($log->getWeek() == $_GET['anchor'])
                        echo '<div id="afficher_journal_semaine_' . $log->getWeek() . '">';
                    else
                        echo '<div id="afficher_journal_semaine_' . $log->getWeek() . '" style="display: none;">';
                }
                else
                {
                    if ($keyLog == 0)
                        echo '<div id="afficher_journal_semaine_' . $log->getWeek() . '">';
                    else
                        echo '<div id="afficher_journal_semaine_' . $log->getWeek() . '" style="display: none;">';
                }
                    // Notes
                    echo '<div class="notes_changelog">';
                        echo nl2br($log->getNotes());
                    echo '</div>';

                    // Logs par catégories
                    echo '<div class="zone_logs_semaine">';
                        foreach ($log->getLogs() as $keyLogs => $logsCategorie)
                        {
                            echo '<div class="zone_logs_categorie">';
                                // Titre catégorie
                                echo '<div class="titre_categorie">';
                                    if (isset($categories[$keyLogs]))
                                    {
                                        echo '<img src="../../includes/icons/changelog/' . $keyLogs . '_grey.png" alt="' . $keyLogs . '" class="logo_titre_categorie" />';
                                        echo $categories[$keyLogs];
                                    }
                                    else
                                        echo $keyLogs;
                                echo '</div>';

                                // Entrées
                                echo '<ul class="logs_categorie">';
                                    foreach ($logsCategorie as $logCategorie)
                                    {
                                        echo '<li class="log_categorie">' . $logCategorie . '</li>';
                                    }
                                echo '</ul>';
                            echo '</div>';
                        }
                    echo '</div>';
                echo '</div>';
            }
        }
        else
        {
            // Titre
            echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/changelog/change_log_grey.png" alt="change_log_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Journaux de modification</div>';
            echo '</div>';

            echo '<div class="empty">Il n\'y a pas de journaux pour cette année...</div>';
        }
    echo '</div>';
?>