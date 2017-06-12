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
	
	if (!isset($_GET['view']) or ($_GET['view'] != "all" AND $_GET['view'] != "done" AND $_GET['view'] != "inprogress"))
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
						$switch3 = '<a href="ideas.php?view=done" class="link_bug_switch_inactive">Terminées</a>';
						
						if ($_GET['view'] == "all")
						{
							$switch1 = '<a href="ideas.php?view=all" class="link_bug_switch_active">Toutes</a>';
						}
						elseif ($_GET['view'] == "inprogress")
						{
							$switch2 = '<a href="ideas.php?view=inprogress" class="link_bug_switch_active">En cours</a>';
						}
						elseif ($_GET['view'] == "done")
						{
							$switch3 = '<a href="ideas.php?view=done" class="link_bug_switch_active">Terminées</a>';
						}
						
						echo $switch1, $switch2, $switch3;						
					?>
				</div>

				<div class="ajout_idee">
					<?php
						echo '<form method="post" action="ideas/manage_ideas.php?view=' . $_GET['view'] . '" class="">';
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
						include('../includes/appel_bdd.php');
						
						if ($_GET['view'] == "done")							
							$reponse = $bdd->query('SELECT * FROM ideas WHERE status="D" ORDER BY id DESC');
						elseif ($_GET['view'] == "inprogress")							
							$reponse = $bdd->query('SELECT * FROM ideas WHERE status="P" ORDER BY id DESC');
						else
							$reponse = $bdd->query('SELECT * FROM ideas ORDER BY id DESC');
								
						$count = 0;
						
						while ($donnees = $reponse->fetch())
						{
							// Recherche du nom complet de l'auteur
							$reponse2 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['author'] . '"');
							$donnees2 = $reponse2->fetch();
							
							$auteur_idee = $donnees2['full_name'];
							
							$reponse2->closeCursor();
							
							// Recherche du nom complet du developpeur
							$reponse3 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['developper'] . '"');
							$donnees3 = $reponse3->fetch();
							
							$developpeur_idee = $donnees3['full_name'];
							
							$reponse3->closeCursor();
							
							// Libellé état
							if ($donnees['status'] == "D")
								$etat_idee = '<span style="color: green;">Terminée</span>';
							else
								$etat_idee = '<span style="color: red;">En cours</span>';
							
							// Formatage date
							$date_idee = substr($donnees['date'], 2, 2) . "/" . substr($donnees['date'], 0, 2) . "/" . substr($donnees['date'], 4, 4);
							
							// Affichage de la liste des bugs
							echo '<div class="categorie_profil">';
								echo '<div class="zone_titre_bug">';
									echo '<span class="titre_bug">';
										echo 'Idée proposée par : <b>' . $auteur_idee . '</b>';
										echo ' // Date : <b>' . $date_idee . '</b>';
										echo ' // Sujet : <b>' . $donnees['subject'] . '</b>';
										echo ' // Etat : <b>' . $etat_idee . '</b>';
										if (!empty($donnees['developper']))
											echo ' // Pris en charge par : <b>' . $developpeur_idee . '</b>';
									echo '</span>';

									echo '<form method="post" action="ideas/manage_ideas.php?view=' . $_GET['view'] . '&id=' . $donnees['id'] . '" class="resolve_bug">';
										if (empty($donnees['developper']) AND $donnees['status'] != "D")
										{
											echo '<div class="triangle_resolution_blue_2"></div>';
											echo '<input type="submit" name="prendre_en_charge" value="Prendre en charge" class="resolution" style="background-color: #2eb2f4;" />';
										}
										elseif (!empty($donnees['developper']) AND $donnees['status'] != "D" AND $_SESSION['identifiant'] == $donnees['developper'])
										{
											echo '<div class="triangle_resolution_green"></div>';
											echo '<input type="submit" name="cloturer" value="Clôturer" class="resolution" style="background-color: rgb(90, 200, 70);" />';
											echo '<div class="triangle_resolution_red_green"></div>';
											echo '<input type="submit" name="remettre_en_cours" value="Remettre en cours" class="resolution" style="background-color: rgb(255, 25, 55);" />';
										}
										elseif ($donnees['status'] != "P")
										{
											echo '<div class="triangle_resolution_red_2"></div>';
											echo '<input type="submit" name="remettre_en_cours" value="Remettre en cours" class="resolution" style="background-color: rgb(255, 25, 55);" />';									
										}
									echo '</form>';
								echo '</div>';
								
								echo '<div class="contenu_profil">';
									echo $donnees['content'];
								echo '</div>';
							echo '</div>';
							
							$count++;
						}
						
						if ($count == 0)
						{
							if ($_GET['view'] == "done")							
								echo '<p class="submitted" style="text-align: center; color: black;">Aucune idée terminée</p>';
							elseif ($_GET['view'] == "inprogress")							
								echo '<p class="submitted" style="text-align: center; color: black;">Aucune idée en cours</p>';
							else
								echo '<p class="submitted" style="text-align: center; color: black;">Aucune idée proposée</p>';
						}
						
						$reponse->closeCursor();			
					?>
				</div>	

			</article>
		</section>
		
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
		
    </body>
	
</html>