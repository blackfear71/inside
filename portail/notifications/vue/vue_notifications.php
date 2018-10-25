<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Notifications";
      $style_head  = "styleNO.css";
      $script_head = "";
      $chat_head   = true;

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Notifications";

			  include('../../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
				<!-- Switch entre notifications personnelles, notifications du jour, notifications de la semaine et toutes les notifications -->
				<div class="switch_view">
					<?php
						$listeSwitch = array('me'    => 'Moi',
																 'today' => 'Aujourd\'hui',
                                 'week'  => '7 jours',
																 'all'   => 'Toutes'
																);

            foreach ($listeSwitch as $view => $lib_view)
            {
              if ($view == "all" OR $view == "week" OR $view == "me")
                $page = '&page=1';
              else
                $page = '';

              if ($_GET['view'] == $view)
                $actif = 'active';
              else
                $actif = 'inactive';

              echo '<a href="notifications.php?view=' . $view . '&action=goConsulter' . $page . '" class="zone_switch">';
                echo '<div class="titre_switch_' . $actif . '">' . $lib_view . '</div>';
                echo '<div class="border_switch_' . $actif . '"></div>';
              echo '</a>';
            }
					?>
				</div>

        <!-- Affichage de la page -->
        <?php
          if (!empty($notifications))
          {
            $date_notif = "";

            foreach ($notifications as $notification)
            {
              if (!empty($notification->getIcon()) AND !empty($notification->getSentence()))
              {
                // Date en fonction de la vue
                switch ($_GET['view'])
                {
                  case "me":
                  case "week":
                  case "all":
                    if ($notification->getDate() != $date_notif)
                    {
                      echo '<div class="date_notif">' . formatDateForDisplay($notification->getDate()) . '</div>';
                      $date_notif = $notification->getDate();
                    }
                    break;

                  case "today":
                  default:
                    break;
                }

                // Lien si présent
                if (!empty($notification->getLink()))
                  if ($notification->getCategory() == "doodle")
                    echo '<a href="' . $notification->getLink() . '" target="_blank" class="lien_notification">';
                  else
                    echo '<a href="' . $notification->getLink() . '" class="lien_notification">';
                else
                  echo '<div class="lien_notification">';

                    // Contenu (icône, phrase & date)
                    echo '<table class="zone_notification">';
                      echo '<tr>';
                        echo '<td class="zone_notification_icone">';
                          echo '<img src="../../includes/icons/' . $notification->getIcon() . '.png" alt="' . $notification->getIcon() . '" class="icone_notification" />';
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
            echo '<div class="date_notif">Pas encore de notifications !</div>';
          }

          // Pagination
          if (($_GET['view'] == "all" OR $_GET['view'] == "week" OR $_GET['view'] == "me") AND $nbPages > 1)
          {
            echo '<div class="zone_pagination">';
              for ($i = 1; $i <= $nbPages; $i++)
              {
                if ($i == $_GET['page'])
                  echo '<div class="numero_page_active">' . $i . '</div>';
                else
                {
                  echo '<div class="numero_page_inactive">';
                    echo '<a href="notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=' . $i . '" class="lien_pagination">' . $i . '</a>';
                  echo '</div>';
                }
              }
            echo '</div>';
          }
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
