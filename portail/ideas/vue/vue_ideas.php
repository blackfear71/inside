<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "&#35;TheBox";
      $style_head  = "styleTheBox.css";
      $script_head = "scriptTheBox.js";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "#TheBox";

        include('../../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          // Onglets vues
				  echo '<div class="switch_view">';
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
			    echo '</div>';

          // Zone de saisie idée
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
                echo '<td colspan="2" class="td_saisie_idee">';
                  echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_contenu_idee"></textarea>';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
					echo '</form>';

          // Tableaux des idées
  				include('vue/table_ideas.php');
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
