<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - Calendriers</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion calendriers";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
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

			<article>
				<?php
          // Formulaire autorisation saisie calendriers
          echo '<div class="title_gestion">Autorisations de gestion des calendriers</div>';

          echo '<form method="post" action="manage_calendars.php?action=doChangerAutorisations" class="form_autorisations">';
            echo '<div class="zone_autorisations">';
              foreach ($listePreferences as $preference)
              {
                echo '<div class="zone_check_autorisation">';
                  if ($preference['manage_calendars'] == "Y")
                  {
                    echo '<input id="autorisation' . $preference['id'] . '" type="checkbox" name="autorization[' . $preference['id'] . ']" checked>';
                    echo '<label for="autorisation' . $preference['id'] . '" class="label_autorisation">' . $preference['pseudo'] . '</label>';
                  }
                  else
                  {
                    echo '<input id="autorisation' . $preference['id'] . '" type="checkbox" name="autorization[' . $preference['id'] . ']">';
                    echo '<label for="autorisation' . $preference['id'] . '" class="label_autorisation">' . $preference['pseudo'] . '</label>';
                  }
                echo '</div>';
              }
            echo '</div>';

            echo '<input type="submit" name="saisie_autorisations" value="Mettre à jour" class="saisie_autorisations" />';
          echo '</form>';

          echo '<br /><br />';

					// Tableau des demandes
					include('table_calendars.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
