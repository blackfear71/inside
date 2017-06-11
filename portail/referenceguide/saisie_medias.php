<?php
	session_start();
	
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
		header('location: ../../administration/administration.php');
	
	if ($_SESSION['connected'] == false)
		header('location: ../../index.php');
	
	// On empêche la possibilité de revenir sur cette page si on l'a quittée de telle sorte que la session est réinitialisée
	if (empty($_SESSION['title_article']))
		header('location: saisie_article.php');
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
					$back = true;
					$ideas = true;
					$bug = true;
					
					include('../../includes/aside.php');
				?>
			</aside>
		
			<article class="article_portail" onclick="document.getElementById('menu').style.display='none';">
			
				<?php		
					echo '<form method="post" action="get_images.php" enctype="multipart/form-data">';

						echo '<div class="bandeau_titre_article"><div class="previs_article">Ajouter les images de l\'article</div></div>';
					
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
						
						if ($_SESSION['top_images'] == true)
						{
							echo '<div class="article_contenu" style="width: 100%;">';
								
								$tableau_images = array();
								$tableau_images = $_SESSION['tableau_images'];

								echo '<table class="liste">';
									echo '<tr>';
										echo '<td class="init_titre_article">Images à ajouter</td>';
										echo '<td class="init_date_article" style="width: 30%;">Action</td>';
									echo '</tr>';
						
									foreach ($tableau_images as $ligne)
									{
										$search1 = htmlspecialchars('<image>');
										$search2 = htmlspecialchars('</image>');
										$search  = array($search1, $search2);
										$replace = array('', '');
											
										echo '<tr>';
											echo '<td class="titre_article">' . str_replace($search, $replace, $ligne) . '</td>';
											echo '<td class="date_article" style="width: 30%;"><input type="file" name="picture[]" required /></td>';
										echo '</tr>';
									}	
								echo '</table>';
								
							echo '</div>';
						
							echo '<p class="remarque"><u>ATTENTION :</u> en cas de retour, vous devrez fournir à nouveau les images</p>';
						}
						
						if ($_SESSION['top_liens'] == true)
						{
							echo '<div class="article_contenu" style="width: 100%;">';
								
								$tableau_liens = array();
								$tableau_liens = $_SESSION['tableau_liens'];

								echo '<table class="liste">';
									echo '<tr>';
										echo '<td class="init_titre_article">Liens à ajouter</td>';
										echo '<td class="init_date_article" style="width: 30%;">Nom du lien</td>';
									echo '</tr>';
						
									foreach ($tableau_liens as $ligne)
									{
										$search1 = htmlspecialchars('<lien>');
										$search2 = htmlspecialchars('</lien>');
										$search  = array($search1, $search2);
										$replace = array('', '');
											
										echo '<tr>';
											echo '<td class="titre_article">' . str_replace($search, $replace, $ligne) . '</td>';
											echo '<td class="date_article" style="width: 30%;"><input type="text" name="link[]" style="width: 100%;" required /></td>';
										echo '</tr>';
									}	
								echo '</table>';
								
							echo '</div>';
							
							echo '<p class="remarque"><u>ATTENTION :</u> en cas de retour, vous devrez fournir à nouveau les noms des liens</p>';
						}
						
						echo '<div class="trait" style="margin-top: 50px;"></div>';
							
						echo '<input type="submit" name="validate_medias" value="Valider" class="saisie_valider" />';

					echo '</form>';
				?>

			</article>
		</section>
		
		<footer onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/footer.php'); ?>
		</footer>
		
		<script type="text/javascript">
			function afficherMasquer(id)
			{
				if (document.getElementById(id).style.display == "none")
					document.getElementById(id).style.display = "block";
				else
					document.getElementById(id).style.display = "none";
			}
		</script>
    </body>
	
</html>