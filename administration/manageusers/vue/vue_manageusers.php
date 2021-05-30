<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Gestion utilisateurs';
      $styleHead       = 'styleAdmin.css';
      $scriptHead      = '';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Gestion utilisateurs';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
				<?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********************************/
          /* Mot de passe (réinitialisation) */
          /***********************************/
					if (isset($_SESSION['save']['user_ask_id'])   AND !empty($_SESSION['save']['user_ask_id'])
					AND isset($_SESSION['save']['user_ask_name']) AND !empty($_SESSION['save']['user_ask_name'])
					AND isset($_SESSION['save']['new_password'])  AND !empty($_SESSION['save']['new_password']))
					{
						echo '<div class="reseted">Le mot de passe a été réinitialisé pour l\'utilisateur <b>' . $_SESSION['save']['user_ask_id'] . ' / ' . $_SESSION['save']['user_ask_name'] . '</b> : </div>';
						echo '<p class="reseted_2"><b>' . $_SESSION['save']['new_password'] . '</b></p>';

						$_SESSION['save']['user_ask_id']   = '';
						$_SESSION['save']['user_ask_name'] = '';
						$_SESSION['save']['new_password']  = '';
					}

          /****************************/
          /* Tableau des utilisateurs */
          /****************************/
					include('vue/vue_table_users.php');

          /*******************************************/
          /* Tableau des statistiques des catégories */
          /*******************************************/
					include('vue/vue_table_stats_categories.php');

          /*************************************/
          /* Tableau des statistiques demandes */
          /*************************************/
					include('vue/vue_table_stats_requests.php');
				?>

			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
