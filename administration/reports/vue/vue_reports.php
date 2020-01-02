<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Bugs";
      $style_head      = "styleBugs.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Rapports de bugs";

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

          /***************/
          /*** Onglets ***/
          /***************/
          echo '<div class="zone_vues">';
            echo '<div class="titre_section"><img src="../../includes/icons/reports/view_grey.png" alt="view_grey" class="logo_titre_section" /><div class="texte_titre_section">Vues</div></div>';

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
          include('vue/vue_liste_rapports.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
