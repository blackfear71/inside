<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Calendriers";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Gestion calendriers";

        include('../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
				<?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /**********************************************/
          /* Formulaire autorisation saisie calendriers */
          /**********************************************/
          echo '<div class="title_gestion">Autorisations de gestion des calendriers</div>';

          echo '<form method="post" action="manage_calendars.php?action=doChangerAutorisations" class="form_autorisations">';
            echo '<div class="zone_autorisations">';
              foreach ($listePreferences as $preference)
              {
                if ($preference['manage_calendars'] == "Y")
                {
                  echo '<div id="bouton_' . $preference['id'] . '" class="switch_autorisation switch_checked">';
                    echo '<input id="autorisation' . $preference['id'] . '" type="checkbox" name="autorization[' . $preference['id'] . ']" checked />';
                    echo '<label for="autorisation' . $preference['id'] . '" class="label_switch">' . $preference['pseudo'] . '</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="bouton_' . $preference['id'] . '" class="switch_autorisation">';
                    echo '<input id="autorisation' . $preference['id'] . '" type="checkbox" name="autorization[' . $preference['id'] . ']" />';
                    echo '<label for="autorisation' . $preference['id'] . '" class="label_switch">' . $preference['pseudo'] . '</label>';
                  echo '</div>';
                }
              }
            echo '</div>';

            echo '<input type="submit" name="saisie_autorisations" value="Mettre à jour" class="saisie_autorisations" />';
          echo '</form>';

          /*******************************************************/
          /* Tableau des demandes de suppression des calendriers */
          /*******************************************************/
					include('vue/table_calendars.php');

          /***************************************************/
          /* Tableau des demandes de suppression des annexes */
          /***************************************************/
          include('vue/table_annexes.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
