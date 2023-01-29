<?php
    echo '<div class="zone_cron_logs">';
        // Logs journaliers
        echo '<div class="zone_jlog">';
            echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Logs journaliers</div></div>';

            echo '<div class="zone_logs">';
                if (!empty($files['daily']))
                {
                    $i = 0;

                    foreach ($files['daily'] as $fileJ)
                    {
                        $lines = file('../../cron/logs/daily/' . $fileJ);

                        echo '<div class="zone_log">';
                            // Entête statut / titre / flèche
                            if (substr($lines[6], 30, 2) == 'OK')
                                echo '<div class="log_ok">OK</div>';
                            else
                                echo '<div class="log_ko">KO</div>';

                            echo '<div class="titre_log">';
                                echo $fileJ;
                            echo '</div>';

                            echo '<div class="voir_log">';
                                echo '<a class="detailsLogs">';
                                    echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="see_log" id="daily_arrow_' . $i . '" />';
                                echo '</a>';
                            echo '</div>';

                            // Log
                            echo '<div class="contenu_log" id="daily_log_' . $i . '" style="display: none;">';
                                foreach ($lines as $line)
                                {
                                    echo nl2br($line);
                                }
                            echo '</div>';
                        echo '</div>';

                        $i++;
                    }
                }
                else
                {
                    echo '<div class="empty">';
                        echo 'Aucun log journalier n\'a encore été généré par les tâches CRON. Veuillez patienter que des tâches soient exécutées automatiquement ou bien lancez-les manuellement.<br />';
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';

        // Logs hebdomadaires
        echo '<div class="zone_hlog">';
            echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Logs hebdomadaires</div></div>';

            echo '<div class="zone_logs">';
                if (!empty($files['weekly']))
                {
                    $j = 0;

                    foreach ($files['weekly'] as $fileH)
                    {
                        $lines = file('../../cron/logs/weekly/' . $fileH);

                        echo '<div class="zone_log">';
                            // Entête statut / titre / flèche
                            if (substr($lines[6], 30, 2) == 'OK')
                                echo '<div class="log_ok">OK</div>';
                            else
                                echo '<div class="log_ko">KO</div>';

                            echo '<div class="titre_log">';
                                echo $fileH;
                            echo '</div>';

                            echo '<div class="voir_log">';
                                echo '<a class="detailsLogs">';
                                    echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="see_log" id="weekly_arrow_' . $j . '" />';
                                echo '</a>';
                            echo '</div>';

                            // Log
                            echo '<div class="contenu_log" id="weekly_log_' . $j . '" style="display: none;">';
                                foreach ($lines as $line)
                                {
                                    echo nl2br($line);
                                }
                            echo '</div>';
                        echo '</div>';

                        $j++;
                    }
                }
                else
                {
                    echo '<div class="empty">';
                        echo 'Aucun log hebdomadaire n\'a encore été généré par les tâches CRON. Veuillez patienter que des tâches soient exécutées automatiquement ou bien lancez-les manuellement.<br />';
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>