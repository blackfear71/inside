<?php
	session_start();

	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
		header('location: ../portail/portail.php');

	if ($_SESSION['connected'] == false)
		header('location: ../index.php');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - Administration</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Administration
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

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<div class="new_menu_admin">
					<a href="manage_users.php" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">UTILISATEURS
								<?php
									include('../includes/appel_bdd.php');
									$req = $bdd->query('SELECT id, identifiant, full_name, reset FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
									while($data = $req->fetch())
									{
										if ($data['reset'] == "Y" OR $data['reset'] == "I" OR $data['reset'] == "D")
										{
											echo '( ! )';
											break;
										}
									}
									$req->closeCursor();
								?>
								</div>
							</div>
						</div>
					</a>

					<a href="" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">REFERENCE<br />GUIDE</div>
							</div>
						</div>
					</a>

					<a href="manage_films.php" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Gestion
								<div class="saut_ligne">MOVIE<br />HOUSE
								<?php
									include('../includes/appel_bdd.php');
									$req = $bdd->query('SELECT id, to_delete FROM movie_house WHERE to_delete = "Y"');
									while($data = $req->fetch())
									{
										if ($data['to_delete'] == "Y")
										{
											echo '( ! )';
											break;
										}
									}
									$req->closeCursor();
								?>
								</div>
							</div>
						</div>
					</a>

					<a href="show_purge.php" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Purge
								<div class="saut_ligne">FICHIERS<br />TEMPORAIRES</div>
							</div>
						</div>
					</a>

					<a href="reports.php?view=unresolved" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">Rapports
								<div class="saut_ligne">
									BUGS
									<?php
										include('../includes/appel_bdd.php');
										$req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type="B" AND resolved="N"');
										$data = $req->fetch();
										echo ' (' . $data['nb_bugs'] . ')';
										$req->closeCursor();
									?>
									<br />EVOLUTIONS
									<?php
										include('../includes/appel_bdd.php');
										$req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type="E" AND resolved="N"');
										$data = $req->fetch();
										echo ' (' . $data['nb_bugs'] . ')';
										$req->closeCursor();
									?>
								</div>
							</div>
						</div>
					</a>

					<a href="/phpmyadmin/" target="_blank" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<div class="title_admin">phpMyAdmin</div>
						</div>
					</a>

					<form method="post" action="export_bdd.php" class="new_menu_link_admin">
						<div class="menu_admin_box">
							<div class="mask_admin"></div>
							<div class="mask_admin_triangle"></div>
							<input type="submit" name="export" value="Sauvegarde" class="export_bdd" />
							<div class="title_admin">
								<div class="saut_ligne" style="margin-top: 65px;">BDD</div>
							</div>
						</div>
					</form>
				</div>
			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
