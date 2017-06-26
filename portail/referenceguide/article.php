<?php
	session_start();

	include('../../includes/init_session.php');

	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
		header('location: ../../administration/administration.php');

	if ($_SESSION['connected'] == false)
		header('location: ../../index.php');

	// On vérifie la catégorie de l'article pour éviter un mauvais affichage de l'URL
	include('../../includes/appel_bdd.php');

	$reponse = $bdd->query('SELECT * FROM reference_guide WHERE id=' . $_GET['id']);
	$donnees = $reponse->fetch();

	if ($_GET['univers'] != $donnees['category'])
		header('location: article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category']);

	$reponse->closeCursor();
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../../favicon.png" />
	<link rel="stylesheet" href="../../style.css" />
  <title>Inside CGI - RG</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$menu_rg = true;
					$add_article = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article class="article_portail" onclick="document.getElementById('menu').style.display='none';">
				<?php
					include('../../includes/appel_bdd.php');

					$reponse = $bdd->query('SELECT * FROM reference_guide WHERE id=' . $_GET['id']);
					$donnees = $reponse->fetch();

					echo '<p class="article_titre">' . $donnees['title'] . '</p>';

					$formatted_date = substr($donnees['publish_date'], 2, 2) . "/" . substr($donnees['publish_date'], 0, 2) . "/" . substr($donnees['publish_date'], 4, 4);

					echo '<p class="article_date">' . $formatted_date . '</p>';

					echo '<div class="trait"></div>';

					$categorie = "";

					switch ($donnees['category'])
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

					$req = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['author'] . '"');
					$data = $req->fetch();

					if (isset($donnees2['full_name']) AND !empty($donnees2['full_name']))
						echo '<p class="article_auteur"><i>Par ' . $data['full_name'] . '</i></p>';
					else
						echo '<p class="article_auteur"><i>Par un ancien utilisateur</i></p>';

					$req->closeCursor();

					echo '<p class="article_contenu">' . nl2br ($donnees['content']) . '</p>';

					$reponse->closeCursor();
				?>
			</article>
		</section>

		<footer onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/footer.php'); ?>
		</footer>

  </body>

</html>
