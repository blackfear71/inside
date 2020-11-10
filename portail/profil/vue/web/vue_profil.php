<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Profil';
      $styleHead      = 'styleProfil.css';
      $scriptHead     = 'scriptProfil.js';
      $angularHead    = false;
      $chatHead       = true;
      $datepickerHead = true;
      $masonryHead    = true;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Profil';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section class="section_no_nav">
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

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
            include('vue/web/vue_onglets.php');

            if ($_GET['view'] == 'ranking')
              include('vue/web/vue_experience.php');
          echo '</div>';

          /********************/
          /* Contenu (droite) */
          /********************/
          echo '<div class="zone_profil_right">';
            // Affichage en fonction des vues
            switch ($_GET['view'])
            {
              case 'settings':
                include('vue/web/vue_settings.php');
                break;

              case 'success':
                include('vue/web/vue_success.php');
                break;

              case 'ranking':
                include('vue/web/vue_ranking.php');
                break;

              case 'themes':
                include('vue/web/vue_infos_themes.php');
                break;

              case 'profile':
              default:
                include('vue/web/vue_infos.php');
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
                include('vue/web/vue_utilisateur.php');
                include('vue/web/vue_preferences.php');
                break;

              case 'themes':
                include('vue/web/vue_themes.php');
                break;

              case 'profile':
              default:
                include('vue/web/vue_contributions.php');
                break;
            }
          echo '</div>';
        ?>
			</article>

      <!-- Chat -->
      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération de la liste des succès débloqués
      var listeSuccess = <?php if (isset($listeSuccessJson) AND !empty($listeSuccessJson)) echo $listeSuccessJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
