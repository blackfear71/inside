<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Bugs";
      $style_head  = "styleBugs.css";
      $script_head = "scriptBugs.js";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Demandes d'évolution";

        include('../../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_no_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <!-- Onglets vues -->
        <div class="switch_view">
          <?php
            $listeSwitch = array('submit'     => array('lib' => 'Saisie',   'action' => 'goSignaler'),
                                 'unresolved' => array('lib' => 'En cours', 'action' => 'goConsulter'),
                                 'resolved'   => array('lib' => 'Résolu(e)s',  'action' => 'goConsulter')
                                );

            foreach ($listeSwitch as $view => $lib_view)
            {
              if ($_GET['view'] == $view)
                $actif = 'active';
              else
                $actif = 'inactive';

              echo '<a href="bugs.php?view=' . $view . '&action=' . $lib_view['action'] . '" class="zone_switch">';
                echo '<div class="titre_switch_' . $actif . '">' . $lib_view['lib'] . '</div>';
                echo '<div class="border_switch_' . $actif . '"></div>';
              echo '</a>';
            }
          ?>
        </div>

        <?php
          // Vue listes
          if (isset($_GET['view']) AND ($_GET['view'] == "resolved" OR $_GET['view'] == "unresolved"))
          {
            include('table_bugs.php');
          }
          // Vue saisie
          else
          {
            echo '<p class="intro_bug">';
              echo 'Le site ne présente aucun bug. Si toutefois vous pensez être tombé sur ce qui prétend en être un, vous pouvez le signaler via le formulaire ci-dessous.
    					Ce que nous appellerons désormais "évolution" sera traitée dans les plus brefs délais par une équipe exceptionnelle, toujours à votre écoute pour vous
    					servir au mieux.';
            echo '</p>';

            echo '<form method="post" action="bugs.php?view=' . $_GET['view'] . '&action=doSignaler" class="form_saisie_bug">';
              echo '<table class="table_saisie_bug">';
                echo '<tr>';
                  echo '<td class="td_saisie_objet">';
                    echo '<input type="text" name="subject_bug" placeholder="Objet" maxlength="255" class="saisie_titre_bug" required />';
                  echo '</td>';

                  echo '<td class="td_saisie_type">';
                    echo '<select name="type_bug" class="saisie_type_bug" required>';
                      echo '<option value="" hidden>Type de demande</option>';
                      echo '<option value="B">Bug</option>';
                      echo '<option value="E">Evolution</option>';
                    echo '</select>';
                  echo '</td>';

                  echo '<td class="td_saisie_envoyer">';
                    echo '<input type="submit" name="report" value="Soumettre" class="submit_bug" />';
                  echo '</td>';
                echo '</tr>';

                echo '<tr>';
                  echo '<td colspan="3" class="td_saisie_bug">';
                    echo '<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu_bug"></textarea>';
                  echo '</td>';
                echo '</tr>';
              echo '</table>';
            echo '</form>';
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
