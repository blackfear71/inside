<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
		<link rel="stylesheet" href="styleBugs.css" />

		<title>Inside - Bug</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../../includes/images/bugs_band.png" alt="bugs_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
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
                $switch = '<a href="bugs.php?view=' . $view . '&action=' . $lib_view['action'] . '" class="link_switch_active">' . $lib_view['lib'] . '</a>';
              else
                $switch = '<a href="bugs.php?view=' . $view . '&action=' . $lib_view['action'] . '" class="link_switch_inactive">' . $lib_view['lib'] . '</a>';

              echo $switch;
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

            echo '<form method="post" action="bugs.php?view=' . $_GET['view'] . '&action=doSignaler">';
              echo '<input type="text" name="subject_bug" placeholder="Objet" maxlength="255" class="saisie_titre_bug" required />';

              echo '<select name="type_bug" class="saisie_type_bug">';
                echo '<option value="B">Bug</option>';
                echo '<option value="E">Evolution</option>';
              echo '</select>';

              echo '<div class="trait_bugs"></div>';

              echo '<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu_bug"></textarea>';

              echo '<div class="trait_bugs"></div>';

              echo '<input type="submit" name="report" value="Soumettre" class="submit_bug" />';
            echo '</form>';
          }
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
