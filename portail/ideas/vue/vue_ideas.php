<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="../styleTheBox.css" />

  	<title>Inside - &#35;TheBox</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../../../includes/images/the_box_band.png" alt="movie_house_band" class="bandeau_categorie_2" />
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
					$bug = true;

					include('../../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../../includes/alerts.php');
			?>

			<article class="article_portail">
        <!-- Onglets vues -->
				<div class="switch_view" style="margin-top: -30px;">
					<?php
            $listeSwitch = array('all'        => 'Toutes',
                                 'inprogress' => 'En cours',
                                 'mine'       => 'En charge',
                                 'done'       => 'Terminées'
                                );

            foreach ($listeSwitch as $view => $lib_view)
            {
              if ($_GET['view'] == $view)
                $switch = '<a href="../controleur/controleur_ideas.php?view=' . $view . '&action=goConsulter" class="link_switch_active">' . $lib_view . '</a>';
              else
                $switch = '<a href="../controleur/controleur_ideas.php?view=' . $view . '&action=goConsulter" class="link_switch_inactive">' . $lib_view . '</a>';

              echo $switch;
            }
					?>
				</div>

        <!-- Zone de saisie idée -->
				<div class="zone_ajout_idee">
					<?php
						echo '<form method="post" action="../controleur/controleur_ideas.php?view=' . $_GET['view'] . '&action=doInserer">';
							echo '<input type="text" name="subject_idea" placeholder="Titre" maxlength="100" class="saisie_titre_idee" required />';
							echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_contenu_idee"></textarea>';

							echo '<input type="submit" name="new_idea" value="Soumettre" class="submit_idea" />';
						echo '</form>';
					?>
				</div>

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
			<?php include('../../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
