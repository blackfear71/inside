<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleTheBox.css" />

    <script type="text/javascript" src="/inside/script.js"></script>

  	<title>Inside - &#35;TheBox</title>
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
			<aside>
				<?php
					$disconnect  = true;
					$profil_user = true;
					$back        = true;
					$reports     = true;
          $notifs      = true;

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
						echo '<input type="text" name="subject_idea" placeholder="Titre" maxlength="100" class="saisie_titre_idee" required />';

						echo '<input type="submit" name="new_idea" value="Soumettre" class="submit_idea" />';

            echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_contenu_idee"></textarea>';
					echo '</form>';
				?>

        <!-- Séparation -->
				<div class="trait_ideas"></div>

        <!-- Tableaux des idées -->
				<?php
          include('table_ideas.php');
				?>
			</article>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
