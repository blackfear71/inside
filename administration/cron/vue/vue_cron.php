<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'CRON';
      $styleHead      = 'styleAdmin.css';
      $scriptHead     = 'scriptAdmin.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = true;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Tâches CRON';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /************************/
          /* Lancement asynchrone */
          /************************/
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
                  echo '<li>Recherche du plus dépensier et du moins dépensier et insère une notification (à venir)</li>';
                  echo '<li>Sauvegarde automatique de la base de données (à venir)</li>';
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

          /********/
          /* Logs */
          /********/
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

                    // Tableau statut / titre / flèche
                    echo '<table class="zone_log">';
                      echo '<tr>';
                        if (substr($lines[6], 30, 2) == 'OK')
                          echo '<td class="log_ok">OK</td>';
                        else
                          echo '<td class="log_ko">KO</td>';

                        echo '<td class="titre_log">';
                          echo $fileJ;
                        echo '</td>';

                        echo '<td class="voir_log">';
                          echo '<a class="detailsLogs">';
                            echo '<img src="../../includes/icons/common/open.png" alt="open" class="see_log" id="daily_arrow_' . $i . '" />';
                          echo '</a>';
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';

                    // Log
                    echo '<div class="log" id="daily_log_' . $i . '" style="display: none;">';
                      foreach ($lines as $line)
                      {
                        echo nl2br($line);
                      }
                    echo '</div>';

                    $i++;
                  }
                }
                else
                {
                  echo '<div class="zone_no_logs">';
                    echo '<div class="titre_no_logs">';
                      echo 'Pas encore de logs journaliers';
                    echo '</div>';

                    echo '<div class="contenu_no_logs">';
                      echo 'Aucun log journalier n\'a encore été généré par les tâches CRON. Veuillez patienter que des tâches soient exécutées automatiquement ou bien lancez-les manuellement.<br />';
                    echo '</div>';
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

                    // Tableau statut / titre / flèche
                    echo '<table class="zone_log">';
                      echo '<tr>';
                        if (substr($lines[6], 30, 2) == 'OK')
                          echo '<td class="log_ok">OK</td>';
                        else
                          echo '<td class="log_ko">KO</td>';

                        echo '<td class="titre_log">';
                          echo $fileH;
                        echo '</td>';

                        echo '<td class="voir_log">';
                          echo '<a class="detailsLogs">';
                            echo '<img src="../../includes/icons/common/open.png" alt="open" class="see_log" id="weekly_arrow_' . $j . '" />';
                          echo '</a>';
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';

                    // Log
                    echo '<div class="log" id="weekly_log_' . $j . '" style="display: none;">';
                      foreach ($lines as $line)
                      {
                        echo nl2br($line);
                      }
                    echo '</div>';

                    $j++;
                  }
                }
                else
                {
                  echo '<div class="zone_no_logs">';
                    echo '<div class="titre_no_logs">';
                      echo 'Pas encore de logs hebdomadaires';
                    echo '</div>';
                    echo '<div class="contenu_no_logs">';
                      echo 'Aucun log hebdomadaire n\'a encore été généré par les tâches CRON. Veuillez patienter que des tâches soient exécutées automatiquement ou bien lancez-les manuellement.<br />';
                    echo '</div>';
                  echo '</div>';
                }
              echo '</div>';
            echo '</div>';
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
