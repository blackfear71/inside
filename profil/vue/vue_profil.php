<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Profil";
      $style_head   = "styleProfil.css";
      $script_head  = "scriptProfil.js";
      $chat_head    = true;
      $masonry_head = true;

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
			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');

          // Onglets vues
		      echo '<div class="switch_view">';
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
	        echo '</div>';

          // Affichage en fonction des vues
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
