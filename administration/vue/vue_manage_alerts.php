<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Alertes";
      $style_head   = "styleAdmin.css";
      $script_head  = "scriptAdmin.js";

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion alertes";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
        <?php
          echo '<table class="table_alerts">';
            echo '<tr class="title_table_alerts">';
              echo '<td class="title_type_alerts">Type</td>';
              echo '<td class="title_category_alerts">Catégorie</td>';
              echo '<td class="title_reference_alerts">Référence</td>';
              echo '<td class="title_message_alerts">Message</td>';
              echo '<td class="title_actions_alerts">Actions</td>';
            echo '</tr>';

            if (!empty($listeAlertes))
            {
              foreach ($listeAlertes as $alerte)
              {
                // Visualisation
                echo '<tr>';
                  echo '<td class="td_type_alerts">';
                    switch ($alerte->getType())
                    {
                      case 'info':
                        echo '<img src="../includes/icons/common/info.png" alt="info" title="Info" class="img_alert" />';
                        break;

                      case 'erreur':
                        echo '<img src="../includes/icons/common/bug.png" alt="erreur" title="Erreur" class="img_alert" />';
                        break;

                      default:
                        break;
                    }
                  echo '</td>';

                  echo '<td class="td_alerts">' . $alerte->getCategory() . '</td>';
                  echo '<td class="td_alerts">' . $alerte->getAlert() . '</td>';
                  echo '<td class="td_alerts">' . $alerte->getMessage() . '</td>';
                  echo '<td></td>';
                echo '</tr>';

                // Modification
                echo '<tr style="display: none;">';
                  echo '<td></td>';
                  echo '<td></td>';
                  echo '<td></td>';
                  echo '<td></td>';
                  echo '<td></td>';
                echo '</tr>';
              }
            }
            else
              echo '<tr class="">Pas d\'alertes paramétrées</tr>';
          echo '</table>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
