<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleProfil.css" />

    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptProfil.js"></script>

		<title>Inside - Profil</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Profil";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">
        <!-- Onglets vues -->
				<div class="switch_view">
					<?php
            $listeSwitch = array('settings' => 'Paramètres',
                                 'success'  => 'Succès',
                                 'ranking'  => 'Classement'
                                );

            foreach ($listeSwitch as $view => $lib_view)
            {
              if ($_GET['view'] == $view)
                $switch = '<a href="profil.php?user=' . $_SESSION['identifiant'] . '&view=' . $view . '&action=goConsulter" class="link_switch_active">' . $lib_view . '</a>';
              else
                $switch = '<a href="profil.php?user=' . $_SESSION['identifiant'] . '&view=' . $view . '&action=goConsulter" class="link_switch_inactive">' . $lib_view . '</a>';

              echo $switch;
            }
					?>
				</div>

        <!-- Affichage en fonction des vues -->
        <?php
          if ($_GET['view'] == "settings")
          {
            include('vue/vue_settings.php');
          }
          elseif ($_GET['view'] == "success")
          {
            include('vue/vue_success.php');
          }
          elseif ($_GET['view'] == "ranking")
          {
            include('vue/vue_ranking.php');
          }
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
