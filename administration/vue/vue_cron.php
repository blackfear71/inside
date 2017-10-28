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

          <div class="zone_cron" style="float: left;">
            <div class="titre_cron">
              CRON journalier
            </div>
            <div class="contenu_cron">
              Exécute les tâches suivantes :<br />
              <ul>
                <li>Recherche les sorties cinéma du jour et insère une notification</li>
                <li>Mise à jour des succès pour tous les utilisateurs (à venir)</li>
                <li>Génération log journalier (à venir)</li>
              </ul>
              <u>Fréquence :</u> tous les jours à 7h.
            </div>
            <div class="boutons_cron">
              <form method="post" action="../cron/daily_cron.php">
                <input type="submit" name="daily_cron" value="Lancer" class="bouton_cron" />
              </form>
            </div>
          </div>

          <div class="zone_cron" style="float: right;">
            <div class="titre_cron">
              CRON hebdomadaire
            </div>
            <div class="contenu_cron">
              Exécute les tâches suivantes :<br />
              <ul>
                <li>Recherche du plus dépensier et du moins dépensier et insère une notification (à venir)</li>
                <li>Sauvegarde automatique de la base de données (à venir)</li>
                <li>Génération log hebdomadaire (à venir)</li>
              </ul>
              <u>Fréquence :</u> tous les lundis à 7h.
            </div>
            <div class="boutons_cron">
              <form method="post" action="../cron/weekly_cron.php">
                <input type="submit" name="weekly_cron" value="Lancer" class="bouton_cron" />
              </form>
            </div>
          </div>
        </div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
