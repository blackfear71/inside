<?php
	session_start();
	
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
		header('location: ../portail/portail.php');
	
	if ($_SESSION['connected'] == false)
		header('location: ../index.php');
	
	if (!isset($_GET['view']) or ($_GET['view'] != "all" AND $_GET['view'] != "resolved" AND $_GET['view'] != "unresolved"))
		header('location: reports.php?view=all');
?>

<!DOCTYPE html>
<html>

    <head>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="../favicon.png" />
		<link rel="stylesheet" href="../style.css" />
        <title>Inside CGI - Bugs</title>
		<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
    </head>
	
	<body>	
	
		<header> 
			<div class="main_title">
				Rapports de bugs
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
					$back_admin = true;
					
					include('../includes/aside.php');
				?>
			</aside>
		
			<article class="article_portail">
				<div class="switch_bug_view">
					<?php
						$switch1 = '<a href="reports.php?view=all" class="link_bug_switch_inactive">Tous</a>';
						$switch2 = '<a href="reports.php?view=unresolved" class="link_bug_switch_inactive">En cours</a>';
						$switch3 = '<a href="reports.php?view=resolved" class="link_bug_switch_inactive">Résolus</a>';
						
						if ($_GET['view'] == "all")
						{
							$switch1 = '<a href="reports.php?view=all" class="link_bug_switch_active">Tous</a>';
						}
						elseif ($_GET['view'] == "unresolved")
						{
							$switch2 = '<a href="reports.php?view=unresolved" class="link_bug_switch_active">En cours</a>';
						}
						elseif ($_GET['view'] == "resolved")
						{
							$switch3 = '<a href="reports.php?view=resolved" class="link_bug_switch_active">Résolus</a>';
						}
						
						echo $switch1, $switch2, $switch3;						
					?>
				</div>
				
				<div class="liste_bugs">
					<?php
						include('../includes/appel_bdd.php');
						
						if ($_GET['view'] == "resolved")							
							$reponse = $bdd->query('SELECT * FROM bugs WHERE resolved="Y" ORDER BY id DESC');
						elseif ($_GET['view'] == "unresolved")							
							$reponse = $bdd->query('SELECT * FROM bugs WHERE resolved="N" ORDER BY id DESC');
						else
							$reponse = $bdd->query('SELECT * FROM bugs ORDER BY id DESC');
								
						$count = 0;
						
						while ($donnees = $reponse->fetch())
						{
							// Recherche du nom complet
							$reponse2 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['author'] . '"');
							$donnees2 = $reponse2->fetch();
							
							$auteur_bug = $donnees2['full_name'];
							
							$reponse2->closeCursor();	
							
							// Libellé état
							if ($donnees['resolved'] == "Y")
								$etat_bug = '<span style="color: green;">Résolu</span>';
							else
								$etat_bug = '<span style="color: red;">En cours</span>';
							
							// Formatage date
							$date_bug = substr($donnees['date'], 2, 2) . "/" . substr($donnees['date'], 0, 2) . "/" . substr($donnees['date'], 4, 4);
							
							// Affichage de la liste des bugs
							echo '<div class="categorie_profil">';
								echo '<div class="zone_titre_bug">';					
									if ($donnees['type'] == "B")
									{
										echo '<div class="zone_type_bug">';
											echo '<div class="type_bug">Bug</div>';
											echo '<div class="triangle_resolution_blue"></div>';
										echo '</div>';
									}
									elseif ($donnees['type'] == "E")	
									{
										echo '<div class="zone_type_bug">';
											echo '<div class="type_bug">Evolution</div>';
											echo '<div class="triangle_resolution_blue"></div>';
										echo '</div>';
									}

									echo '<span class="titre_bug">Demande : <b>' . $auteur_bug . '</b> // Date : <b>' . $date_bug . '</b> // Sujet : <b>' . $donnees['subject'] . '</b> // Etat : <b>' . $etat_bug . '</b></span>';

									echo '<form method="post" action="bug_status.php?view=' . $_GET['view'] . '&id=' . $donnees['id'] . '" class="resolve_bug">';
										if ($donnees['resolved'] == "N")
										{
											echo '<div class="triangle_resolution_green"></div>';
											echo '<input type="submit" name="resolve_bug" value="Résoudre" class="resolution" style="background-color: rgb(90, 200, 70);" />';
										}
										else
										{
											echo '<div class="triangle_resolution_red"></div>';
											echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="resolution" style="background-color: rgb(255, 25, 55);" />';
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
							if ($_GET['view'] == "resolved")							
								echo '<p class="submitted" style="text-align: center; color: black;">Aucun(e) bug/évolution résolu(e)</p>';
							elseif ($_GET['view'] == "unresolved")							
								echo '<p class="submitted" style="text-align: center; color: black;">Aucun(e) bug/évolution non résolu(e)</p>';
							else
								echo '<p class="submitted" style="text-align: center; color: black;">Aucun(e) bug/évolution</p>';
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