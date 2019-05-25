<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Notifications";
      $style_head  = "styleNO.css";
      $script_head = "scriptNO.js";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Notifications";

			  include('../../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          /***********/
          /* Onglets */
          /***********/
          include('vue/vue_onglets.php');

          /*********************/
          /*** Notifications ***/
          /*********************/
          echo '<div class="zone_notifications_right">';
            if (!empty($notifications))
            {
              $date_notif = "";

              foreach ($notifications as $notification)
              {
                if (!empty($notification->getIcon()) AND !empty($notification->getSentence()))
                {
                  // Date
                  if ($notification->getDate() != $date_notif)
                  {
                    echo '<div class="titre_section"><img src="../../includes/icons/notifications/date_grey.png" alt="date_grey" class="logo_titre_section" />' . formatDateForDisplay($notification->getDate()) . '</div>';
                    $date_notif = $notification->getDate();
                  }

                  // Lien si présent
                  if (!empty($notification->getLink()))
                    if ($notification->getCategory() == "doodle")
                      echo '<a href="' . $notification->getLink() . '" id="lien_details_' . $notification->getContent() . '" target="_blank" class="lien_notification lienDetails">';
                    else
                      echo '<a href="' . $notification->getLink() . '" class="lien_notification">';
                  else
                    echo '<div class="lien_notification">';

                      // Contenu (icône, phrase & date)
                      echo '<table class="zone_notification">';
                        echo '<tr>';
                          echo '<td class="zone_notification_icone">';
                            echo '<img src="../../includes/icons/common/' . $notification->getIcon() . '.png" alt="' . $notification->getIcon() . '" class="icone_notification" />';
                          echo '</td>';
                          echo '<td class="zone_notification_contenu">';
                            echo $notification->getSentence();
                          echo '</td>';
                          echo '<td class="zone_notification_date">';
                            echo formatTimeForDisplay($notification->getTime());
                          echo '</td>';
                        echo '</tr>';
                      echo '</table>';

                  if (!empty($notification->getLink()))
                    echo '</a>';
                  else
                    echo '</div>';
                }
              }
            }
            else
            {
              echo '<div class="titre_section"><img src="../../includes/icons/notifications/date_grey.png" alt="date_grey" class="logo_titre_section" />' . formatDateForDisplay(date("Ymd")) . '</div>';

              echo '<div class="empty">Pas encore de notifications...</div>';
            }
          echo '</div>';

          /**************/
          /* Pagination */
          /**************/
          include('vue/vue_pagination.php');
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
	</body>
</html>
