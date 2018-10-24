<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "&#35;TheBox";
      $style_head  = "styleTheBox.css";
      $script_head = "scriptTheBox.js";

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "#TheBox";

        include('../../includes/header.php');
      ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
        <!-- Onglets vues -->
				<div class="switch_view">
					<?php
            $listeSwitch = array('all'        => 'Toutes',
                                 'inprogress' => 'En cours',
                                 'mine'       => 'En charge',
                                 'done'       => 'Terminées'
                                );

            foreach ($listeSwitch as $view => $lib_view)
            {
              if ($_GET['view'] == $view)
                $actif = 'active';
              else
                $actif = 'inactive';

              echo '<a href="ideas.php?view=' . $view . '&action=goConsulter" class="zone_switch">';
                echo '<div class="titre_switch_' . $actif . '">' . $lib_view . '</div>';
                echo '<div class="border_switch_' . $actif . '"></div>';
              echo '</a>';
            }
					?>
				</div>

        <!-- Zone de saisie idée -->
				<?php
					echo '<form method="post" action="ideas.php?view=' . $_GET['view'] . '&action=doInserer" class="form_saisie_idea">';
            echo '<table class="table_saisie_idee">';
              echo '<tr>';
                echo '<td class="td_saisie_titre">';
                  echo '<input type="text" name="subject_idea" placeholder="Titre" maxlength="100" class="saisie_titre_idee" required />';
                echo '</td>';

                echo '<td class="td_saisie_envoyer">';
                  echo '<input type="submit" name="new_idea" value="Soumettre" class="submit_idea" />';
                echo '</td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td colspan="100%" class="td_saisie_idee">';
                  echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_contenu_idee"></textarea>';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
					echo '</form>';
				?>

        <!-- Tableaux des idées -->
				<?php include('table_ideas.php'); ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
