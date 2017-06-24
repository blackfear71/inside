<?php
	session_start();

	include('../includes/init_session.php');

	// Redirection si admin
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
		header('location: ../administration/administration.php');

	if ($_SESSION['connected'] == false)
		header('location: ../index.php');

	if (!isset($_SESSION['idea_submited']))
		$_SESSION['idea_submited'] = NULL;

	if (!isset($_GET['view']) or ($_GET['view'] != "all" AND $_GET['view'] != "done" AND $_GET['view'] != "mine" AND $_GET['view'] != "inprogress"))
		header('location: ideas.php?view=all');
?>

<!DOCTYPE html>
<html>

    <head>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="../favicon.png" />
		<link rel="stylesheet" href="../style.css" />
        <title>Inside CGI - &#35;TheBox</title>
		<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
    </head>

	<body>

		<header>
			<div class="main_title">
				#TheBox
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

			<article class="article_portail">
				<div class="switch_bug_view">
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

						if (isset($_SESSION['idea_submited']) AND $_SESSION['idea_submited'] == false)
						{
							echo '<p class="idea_submited">Problème lors de l\'envoi de l\'idée</p>';
							$_SESSION['idea_submited'] = NULL;
						}
						elseif (isset($_SESSION['idea_submited']) AND $_SESSION['idea_submited'] == true)
						{
							echo '<p class="idea_submited">L\'idée a été soumise avec succès</p>';
							$_SESSION['idea_submited'] = NULL;
						}
						else
						{
							$_SESSION['idea_submited'] = NULL;
						}
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
