<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "CO";
      $style_head      = "styleCO.css";
      $script_head     = "scriptCO.js";
      $chat_head       = true;
      $datepicker_head = true;
      $masonry_head    = true;
      $exif_head       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Collector Room";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$back = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          /******************************/
          /* Liens de saisie et filtres */
          /******************************/
          echo '<div class="zone_liens_saisie">';
            echo '<a onclick="afficherMasquer(\'zone_add_collector\');" title="Ajouter une phrase culte" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/collector/phrases.png" alt="comments" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter une phrase culte</div>';
            echo '</a>';

            echo '<a onclick="afficherMasquer(\'zone_add_image\');" title="Ajouter une image" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/collector/images.png" alt="images" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter une image</div>';
            echo '</a>';

            echo '<div class="zone_filtres">';
              // Tris
              echo '<select onchange="applySortOrFilter(this.value, \'' . $_GET['filter'] . '\');" class="listbox_filtre">';
                if ($_GET['sort'] == "dateDesc")
                  echo '<option value="dateDesc" selected>Du plus récent au plus vieux</option>';
                else
                  echo '<option value="dateDesc">Du plus récent au plus vieux</option>';

                if ($_GET['sort'] == "dateAsc")
                  echo '<option value="dateAsc" selected>Du plus vieux au plus récent</option>';
                else
                  echo '<option value="dateAsc">Du plus vieux au plus récent</option>';
              echo '</select>';

              // Filtres
              echo '<select onchange="applySortOrFilter(\'' . $_GET['sort'] . '\', this.value);" class="listbox_filtre">';
                if ($_GET['filter'] == "none")
                  echo '<option value="none" selected>Aucun filtre</option>';
                else
                  echo '<option value="none">Aucun filtre</option>';

                if ($_GET['filter'] == "noVote")
                  echo '<option value="noVote" selected>Non votés</option>';
                else
                  echo '<option value="noVote">Non votés</option>';

                if ($_GET['filter'] == "meOnly")
                  echo '<option value="meOnly" selected>Mes phrases cultes</option>';
                else
                  echo '<option value="meOnly">Mes phrases cultes</option>';

                if ($_GET['filter'] == "byMe")
                  echo '<option value="byMe" selected>Mes phrases rapportées</option>';
                else
                  echo '<option value="byMe">Mes phrases rapportées</option>';

                if ($_GET['filter'] == "usersOnly")
                  echo '<option value="usersOnly" selected>Les phrases cultes des autres utilisateurs</option>';
                else
                  echo '<option value="usersOnly">Les phrases cultes des autres utilisateurs</option>';

                if ($_GET['filter'] == "othersOnly")
                  echo '<option value="othersOnly" selected>Les phrases cultes hors utilisateurs</option>';
                else
                  echo '<option value="othersOnly">Les phrases cultes hors utilisateurs</option>';

                if ($_GET['filter'] == "textOnly")
                  echo '<option value="textOnly" selected>Seulement les phrases cultes</option>';
                else
                  echo '<option value="textOnly">Seulement les phrases cultes</option>';

                if ($_GET['filter'] == "picturesOnly")
                  echo '<option value="picturesOnly" selected>Seulement les images</option>';
                else
                  echo '<option value="picturesOnly">Seulement les images</option>';

                if ($_GET['filter'] == "topCulte")
                  echo '<option value="topCulte" selected>Les tops cultes</option>';
                else
                  echo '<option value="topCulte">Les tops cultes</option>';
              echo '</select>';
            echo '</div>';
          echo '</div>';

          /**********************************/
          /* Zone de saisie de phrase culte */
          /**********************************/
          include('vue/vue_saisie_collector.php');

          /**************************/
          /* Zone de saisie d'image */
          /**************************/
          include('vue/vue_saisie_image.php');

          /********************************/
          /* Affichage des phrases cultes */
          /********************************/
          include('vue/vue_table_collectors.php');

          /**************/
          /* Pagination */
          /**************/
          if ($nbPages > 1)
          {
            $prev_points = false;
            $next_points = false;
            $limit_inf   = $_GET['page'] - 1;
            $limit_sup   = $_GET['page'] + 1;

            echo '<div class="zone_pagination">';
              for ($i = 1; $i <= $nbPages; $i++)
              {
                if ($i == 1 OR $i == $nbPages)
                {
                  if ($i == $_GET['page'])
                    echo '<div class="numero_page_active">' . $i . '</div>';
                  else
                  {
                    echo '<div class="numero_page_inactive">';
                      echo '<a href="collector.php?action=goConsulter&page=' . $i . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" class="lien_pagination">' . $i . '</a>';
                    echo '</div>';
                  }
                }
                else
                {
                  if ($i < $limit_inf AND $i > 1 AND $prev_points != true)
                  {
                    echo '<div class="points">...</div>';
                    $prev_points = true;
                  }

                  if ($i >= $limit_inf AND $i <= $limit_sup)
                  {
                    if ($i == $_GET['page'])
                      echo '<div class="numero_page_active">' . $i . '</div>';
                    else
                    {
                      echo '<div class="numero_page_inactive">';
                        echo '<a href="collector.php?action=goConsulter&page=' . $i . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '" class="lien_pagination">' . $i . '</a>';
                      echo '</div>';
                    }
                  }

                  if ($i > $limit_sup AND $i < $nbPages AND $next_points != true)
                  {
                    echo '<div class="points">...</div>';
                    $next_points = true;
                  }
                }
              }
            echo '</div>';
          }
        ?>
      </article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
