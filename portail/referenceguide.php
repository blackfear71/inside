<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	include('../includes/init_session.php');

	$_SESSION['univers'] = "";
	$_SESSION['search'] = "";
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - RG</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<?php include('../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$add_article = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<!-- Barre de recherche -->
				<div class="search_bar">
					<?php
						echo '<form method="post" action="referenceguide/search_tree.php">';
							echo '<input type="text" name="search_content" placeholder="Rechercher..." maxlength="255" class="monoligne_search" />';
							echo '<input type="submit" name="search" value="" title="Rechercher" class="icon_search"/>';
						echo '</form>';
					?>
				</div>

				<!-- Mosaïque Reference Guide -->
				<div class="new_menu_univers">
					<div class="new_menu_univers_top">
						<a href="referenceguide/liste_articles.php?univers=rdz&search=no" class="new_menu_link">
							<div class="new_rdz">
								<div class="mask_rdz"></div>
								<div class="title_rdz">RDZ</div>
							</div>
						</a>

						<a href="referenceguide/liste_articles.php?univers=portaileid&search=no" class="new_menu_link">
							<div class="new_portaileid">
								<div class="mask_portaileid_1"></div>
								<div class="mask_portaileid_2"></div>
								<div class="title_portaileid">POR<br/>TAIL<br /><br />EID</div>
							</div>
						</a>
					</div>

					<div class="new_menu_univers_under">
						<a href="referenceguide/liste_articles.php?univers=tso&search=no" class="new_menu_link">
							<div class="new_tso">
								<div class="mask_tso"></div>
								<div class="title_tso">TSO</div>
							</div>
						</a>

						<a href="referenceguide/liste_articles.php?univers=glossaire&search=no" class="new_menu_link">
							<div class="new_glossaire">
								<div class="mask_glossaire_1"></div>
								<div class="mask_glossaire_2"></div>
								<div class="title_glossaire">GLOSSAIRE</div>
							</div>
						</a>

						<a href="referenceguide/liste_articles.php?univers=ims&search=no" class="new_menu_link">
							<div class="new_ims">
								<div class="mask_ims"></div>
								<div class="title_ims">IMS</div>
							</div>
						</a>

						<a href="referenceguide/liste_articles.php?univers=micrortc&search=no" class="new_menu_link">
							<div class="new_micrortc">
								<div class="mask_micrortc"></div>
								<div class="title_micrortc">MICRO<br />&amp;<br />RTC</div>
							</div>
						</a>
					</div>
				</div>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
