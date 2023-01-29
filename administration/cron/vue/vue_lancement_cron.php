<?php
    echo '<div class="zone_cron_asynchrone">';
        echo '<div class="titre_section"><img src="../../includes/icons/admin/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Lancement asynchrone des tâches CRON</div></div>';

        // CRON journalier
        echo '<div class="zone_cron margin_right_20">';
            echo '<div class="titre_cron">CRON journalier</div>';

            echo '<div class="contenu_cron">';
                echo 'Exécute les tâches suivantes :';

                echo '<ul>';
                    echo '<li>Recherche les sorties cinéma du jour et insère une notification</li>';
                    echo '<li>Notification début et fin de mission</li>';
                    echo '<li>Attribution expérience fin de mission</li>';
                    echo '<li>Purge des fichiers temporaires du générateur de calendriers</li>';
                    echo '<li>Génération log journalier</li>';
                echo '</ul>';

                echo '<u>Fréquence :</u> tous les jours à 7h.';
            echo '</div>';

            echo '<div class="boutons_cron">';
                echo '<form method="post" action="../../cron/daily_cron.php">';
                    echo '<input type="submit" name="daily_cron" value="Lancer" class="bouton_cron" />';
                echo '</form>';
            echo '</div>';
        echo '</div>';

        // CRON hebdomadaire
        echo '<div class="zone_cron">';
            echo '<div class="titre_cron">CRON hebdomadaire</div>';

            echo '<div class="contenu_cron">';
                echo 'Exécute les tâches suivantes :';
                
                echo '<ul>';
                    echo '<li>Remise à plat des bilans des dépenses</li>';
                    echo '<li>Envoi d\'un mail de gestion à l\'administrateur</li>';
                    // echo '<li>Recherche du plus dépensier et du moins dépensier et insère une notification (à venir)</li>';
                    // echo '<li>Sauvegarde automatique de la base de données (à venir)</li>';
                    echo '<li>Génération log hebdomadaire</li>';
                echo '</ul>';

                echo '<u>Fréquence :</u> tous les lundis à 7h.';
            echo '</div>';

            echo '<div class="boutons_cron">';
                echo '<form method="post" action="../../cron/weekly_cron.php">';
                    echo '<input type="submit" name="weekly_cron" value="Lancer" class="bouton_cron" />';
                echo '</form>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>