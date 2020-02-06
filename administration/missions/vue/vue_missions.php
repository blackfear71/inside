<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Missions";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = true;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Gestion missions";

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /*********/
          /* Liens */
          /*********/
          if ($_GET['action'] == "goConsulter")
          {
            echo '<div class="zone_liens_saisie">';
              // Saisie mission
              echo '<a href="missions.php?action=goAjouter" title="Ajouter une mission" class="lien_categorie">';
                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/missions.png" alt="missions" class="image_lien" /></div>';
                echo '<div class="zone_texte_lien">Ajouter une mission</div>';
              echo '</a>';
            echo '</div>';
          }

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Contenu */
          /***********/
          switch ($_GET['action'])
          {
            case "goConsulter":
              include('vue/vue_table_missions.php');
              break;

            case "goAjouter":
            case "goModifier":
              include('vue/vue_saisie_mission.php');
              break;

            default:
              break;
          }
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
