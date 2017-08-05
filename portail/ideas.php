<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	//include('../includes/init_session.php');

	if (!isset($_GET['view']) or ($_GET['view'] != "all" AND $_GET['view'] != "done" AND $_GET['view'] != "mine" AND $_GET['view'] != "inprogress"))
		header('location: ideas.php?view=all');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
	<title>Inside - &#35;TheBox</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				<img src="../includes/images/the_box_band.png" alt="movie_house_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="switch_bug_view" style="margin-top: -30px;">
					<?php
						$switch1 = '<a href="ideas.php?view=all" class="link_bug_switch_inactive">Toutes</a>';
						$switch2 = '<a href="ideas.php?view=inprogress" class="link_bug_switch_inactive">En cours</a>';
						$switch3 = '<a href="ideas.php?view=mine" class="link_bug_switch_inactive">En charge</a>';
						$switch4 = '<a href="ideas.php?view=done" class="link_bug_switch_inactive">Terminées</a>';

						if ($_GET['view'] == "all")
						{
							$switch1 = '<a href="ideas.php?view=all" class="link_bug_switch_active">Toutes</a>';
						}
						elseif ($_GET['view'] == "inprogress")
						{
							$switch2 = '<a href="ideas.php?view=inprogress" class="link_bug_switch_active">En cours</a>';
						}
						elseif ($_GET['view'] == "mine")
						{
							$switch3 = '<a href="ideas.php?view=mine" class="link_bug_switch_active">En charge</a>';
						}
						elseif ($_GET['view'] == "done")
						{
							$switch4 = '<a href="ideas.php?view=done" class="link_bug_switch_active">Terminées</a>';
						}

						echo $switch1, $switch2, $switch3, $switch4;
					?>
				</div>

				<div class="ajout_idee">
					<?php
						echo '<form method="post" action="ideas/manage_ideas.php?view=' . $_GET['view'] . '">';
							echo '<input type="text" name="subject_idea" placeholder="Titre" maxlength="100" class="saisie_titre_3" required />';
							echo '<textarea placeholder="Description de l\'idée" name="content_idea" class="saisie_contenu_2"></textarea>';

							echo '<input type="submit" name="new_idea" value="Soumettre" class="submit_idea" />';
						echo '</form>';
					?>
				</div>

				<div class="trait_2"></div>

				<div class="liste_bugs">
					<?php
						include('ideas/table_ideas.php');
					?>
				</div>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
