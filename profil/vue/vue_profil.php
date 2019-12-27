<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Profil";
      $style_head      = "styleProfil.css";
      $script_head     = "scriptProfil.js";
      $chat_head       = true;
      $datepicker_head = true;
      $masonry_head    = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Profil";

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
          /********************/
          /* Boutons missions */
          /********************/
          $zone_inside = "article";
          include('../includes/common/missions.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Onglets */
          /***********/
          echo '<div class="zone_profil_left">';
            include('vue/vue_onglets.php');

            if ($_GET['view'] == 'ranking')
              include('vue/vue_experience.php');
          echo '</div>';

          /********************/
          /* Contenu (droite) */
          /********************/
          echo '<div class="zone_profil_right">';
            // Affichage en fonction des vues
            switch ($_GET['view'])
            {
              case 'settings':
                include('vue/vue_settings.php');
                break;

              case 'success':
                include('vue/vue_success.php');
                break;

              case 'ranking':
                include('vue/vue_ranking.php');
                break;

              case 'themes':
                include('vue/vue_infos_themes.php');
                break;

              case 'profile':
              default:
                include('vue/vue_infos.php');
                break;
            }
          echo '</div>';

          /*****************/
          /* Contenu (bas) */
          /*****************/
          echo '<div class="zone_profil_bottom">';
            switch ($_GET['view'])
            {
              case 'success':
              case 'ranking':
                break;

              case 'settings':
                include('vue/vue_utilisateur.php');
                include('vue/vue_preferences.php');
                break;

              case 'themes':
                include('vue/vue_themes.php');
                break;

              case 'profile':
              default:
                include('vue/vue_contributions.php');
                break;
            }
          echo '</div>';
        ?>
			</article>

      <?php include('../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
