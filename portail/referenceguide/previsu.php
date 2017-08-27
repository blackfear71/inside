<?php
	// Contrôles communs Utilisateurs
	include('../../includes/controls_users.php');

	// On empêche la possibilité de revenir sur cette page si on l'a quittée de telle sorte que la session est réinitialisée
	if (empty($_SESSION['title_article']))
		header('location: saisie_article.php');
?>

<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside - RG</title>
  </head>

	<body>
		<!-- Onglets -->
		<header onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$menu_rg = true;
					$back = true;
					$ideas = true;
					$reports = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article class="article_portail" onclick="document.getElementById('menu').style.display='none';">
				<?php
					echo '<form method="post" action="publish.php" enctype="multipart/form-data">';

						echo '<div class="bandeau_titre_article"><div class="previs_article">Pré-visualiser l\'article</div></div>';

						echo '<p class="article_titre">' . $_SESSION['title_article'] . '</p>';

						echo '<p class="article_date">' . date("d/m/Y") . '</p>';

						echo '<div class="trait"></div>';

						$categorie = "";

						switch ($_SESSION['category_article'])
						{
							case "rdz":
								$categorie = "RDZ";
								break;

							case "tso":
								$categorie = "TSO";
								break;

							case "ims":
								$categorie = "IMS";
								break;

							case "micrortc":
								$categorie = "Micro/RTC";
								break;

							case "portaileid":
								$categorie = "Portail EID";
								break;

							case "glossaire":
								$categorie = "Glossaire";
								break;

							default:
								$categorie = "Sans catégorie";
								break;
						}

						echo '<p class="article_categorie">' . $categorie . '</p>';

						echo '<p class="article_auteur"><i>Par ' . $_SESSION['full_name'] . '</i></p>';

						$nouveau_texte = "";

						if ($_SESSION['top_images'] == true OR $_SESSION['top_liens'] == true)
						{
							// On va générer un nouveau texte pour remplacer les balises <image> par les images ajoutées
							include('scan_images.php');
						}
						else
						{
							// On récupère le texte saisi sans images
							$nouveau_texte = $_SESSION['content_article'];
							$_SESSION['nouveau_texte'] = $nouveau_texte;
						}

						echo '<p class="article_contenu">' . nl2br($nouveau_texte) . '</p>';

						echo '<div class="trait" style="margin-top: 50px;"></div>';

						echo '<input type="submit" name="publish_article" value="Publier l\'article" class="saisie_valider" />';

					echo '</form>';
				?>

			</article>
		</section>

		<!-- Pied de page -->
		<footer onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
