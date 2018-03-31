<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
		<link rel="stylesheet" href="styleBugs.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - Bug</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Demandes d'évolution";

        include('../../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;

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
                  echo '<td colspan="100%" class="td_saisie_bug">';
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
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
