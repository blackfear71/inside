<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleNO.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - Notifications</title>
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
					$profil_user = true;
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
              if ($view == "all" OR $view == "me")
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
              // Date en fonction de la vue
              switch($_GET['view'])
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
          else
          {
            echo '<div class="date_notif">Pas encore de notifications !</div>';
          }

          // Pagination
          if (($_GET['view'] == "all" OR $_GET['view'] == "me") AND $nbPages > 1)
          {
            echo '<div class="zone_pagination">';
              for($i = 1; $i <= $nbPages; $i++)
              {
                if($i == $_GET['page'])
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
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
