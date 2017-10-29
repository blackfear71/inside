<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="styleEC.css" />

    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptEC.js"></script>

		<title>Inside - EC</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Expense Center";

        include('../../includes/onglets.php') ;
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect  = true;
					$profil_user = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;
          $notifs      = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
        <?php
          // Saisie nouvelle ligne
          include('vue/table_saisie_depense.php');

          // Affichage bilan
          include('vue/table_total_depenses.php');

          // Affichage des onglets (années)
          include('vue/onglets_expensecenter.php');

          // Lignes saisies
          include('vue/table_resume_depenses.php');
        ?>

			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
