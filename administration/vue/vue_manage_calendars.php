<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Calendriers";
      $style_head  = "styleAdmin.css";
      $script_head = "";

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion calendriers";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_no_nav">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
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

          // Tableau des demandes de suppression de calendriers
					include('table_calendars.php');

        	// Tableau des demandes de suppression de annexes
          include('table_annexes.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
