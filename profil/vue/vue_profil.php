<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Profil";
      $style_head  = "styleProfil.css";
      $script_head = "scriptProfil.js";
      $chat_head   = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Profil";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
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
                $actif = 'active';
              else
                $actif = 'inactive';

              echo '<a href="profil.php?user=' . $_SESSION['user']['identifiant'] . '&view=' . $view . '&action=goConsulter" class="zone_switch">';
                echo '<div class="titre_switch_' . $actif . '">' . $lib_view . '</div>';
                echo '<div class="border_switch_' . $actif . '"></div>';
              echo '</a>';
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

      <?php include('../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
