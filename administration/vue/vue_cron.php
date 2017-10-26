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

		<title>Inside - CRON</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Tâches CRON";

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
        <div class="zone_cron_asynchrone">
  				<div class="level_succes">Lancement asynchrone des tâches CRON</div>

          <form method="post" action="../cron/daily_cron.php">
            <input type="submit" name="daily_cron" value="CRON journalier" class="bouton_modification_succes" />
          </form>

          <form method="post" action="../cron/weekly_cron.php">
            <input type="submit" name="weekly_cron" value="CRON hebdomadaire" class="bouton_modification_succes" />
          </form>
        </div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
