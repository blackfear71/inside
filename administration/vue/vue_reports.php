<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Bugs";
      $style_head   = "styleBugs.css";
      $script_head  = "scriptAdmin.js";
      $masonry_head = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Rapports de bugs";

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
          /***************/
          /*** Onglets ***/
          /***************/
          echo '<div class="zone_vues">';
            echo '<div class="titre_section"><img src="../includes/icons/reports/view_grey.png" alt="view_grey" class="logo_titre_section" />Vues</div>';

            $listeVues = array('all'        => 'Tous',
                               'unresolved' => 'En cours',
                               'resolved'   => 'Résolu(e)s'
                              );

            foreach ($listeVues as $view => $vue)
            {
              if ($_GET['view'] == $view)
                echo '<span class="view active">' . $vue . '</span>';
              else
                echo '<a href="reports.php?view=' . $view . '&action=goConsulter" class="view inactive">' . $vue . '</a>';
            }
          echo '</div>';

          /****************/
          /*** Rapports ***/
          /****************/
          include('vue_liste_rapports.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
