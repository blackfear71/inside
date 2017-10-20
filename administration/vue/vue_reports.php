<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

    <script type="text/javascript" src="/inside/script.js"></script>
    
		<title>Inside - Bugs</title>
  </head>

	<body>

		<header>
      <?php
        $title = "Rapports de bugs";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="switch_view" style="margin-top: -30px;">
					<?php
						$listeSwitch = array('all'        => 'Tous',
																 'unresolved' => 'En cours',
																 'resolved'   => 'Résolus'
																);

						foreach ($listeSwitch as $view => $lib_view)
						{
							if ($_GET['view'] == $view)
								$switch = '<a href="reports.php?view=' . $view . '&action=goConsulter" class="link_switch_active">' . $lib_view . '</a>';
							else
								$switch = '<a href="reports.php?view=' . $view . '&action=goConsulter" class="link_switch_inactive">' . $lib_view . '</a>';

							echo $switch;
						}
					?>
				</div>

				<div class="liste_bugs">
					<?php
						include('table_bugs.php');
					?>
				</div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
