<?php
	session_start();
	
	include('../includes/init_session.php');
	
	// Redirection si admin
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
		header('location: ../administration/administration.php');
	
	if ($_SESSION['connected'] == false)
		header('location: ../index.php');
	
	if (!isset($_SESSION['submitted']))
		$_SESSION['submitted'] = false;
?>

<!DOCTYPE html>
<html>

    <head>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="../favicon.png" />
		<link rel="stylesheet" href="../style.css" />
        <title>Inside CGI - Bug</title>
		<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
    </head>
	
	<body>	
	
		<header> 
			<div class="main_title">
				Outil de demande d'évolution
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
					$ideas = true;
					
					include('../includes/aside.php');
				?>
			</aside>
		
			<article class="article_portail">
				<p class="intro_bug">
					Le site ne présente aucun bug. Si toutefois vous pensez être tombé sur ce qui prétend en être un, vous pouvez le signaler via le formulaire ci-dessous.
					Ce que nous appellerons désormais "évolution" sera traitée dans les plus brefs délais par une équipe exceptionnelle, toujours à votre écoute pour vous 
					servir au mieux.
				</p>
				
				<?php
					if (isset($_SESSION['submitted']) AND $_SESSION['submitted'] == true)
					{
						echo '<p class="submitted">Votre message a été envoyé à l\'administrateur.</p>';
						$_SESSION['submitted'] = false;
					}
				?>
				
				<form method="post" action="bugs/report_bug.php">
					<input type="text" name="subject" placeholder="Objet" maxlength="255" class="saisie_titre_2" required />
					
					<select name="type_bug" class="saisie_type_bug">
						<option value="B">Bug</option>
						<option value="E">Evolution</option>
					</select>

					<div class="trait"></div>
					
					<textarea placeholder="Description du problème" name="contenu_bug" class="saisie_contenu"></textarea>
					
					<div class="trait"></div>
					
					<input type="submit" name="report" value="Soumettre" class="saisie_valider" />
				</form>
			</article>
		</section>
		
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
		
    </body>
	
</html>