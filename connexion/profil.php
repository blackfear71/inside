<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisations session
	include('../includes/init_session.php');

	if ($_GET['user'] != $_SESSION['identifiant'])
		header('location: ../connexion/profil.php?user=' . $_SESSION['identifiant'] . '');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - Profil</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Profil
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
					$back = true;
					$ideas = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">
				<!-- Gestion pseudo -->
				<div class="categorie_profil">
					<div class="titre_profil">
						Changement de pseudo
					</div>

					<div class="contenu_profil">
						<p class="actual">Pseudo actuel : <span class="pseudo"><?php echo $_SESSION['full_name']; ?></span></p>

						<form method="post" action="change_pseudo.php" class="form_pseudo">
							<input type="text" name="new_pseudo" placeholder="Nouveau pseudo" maxlength="255" class="monoligne_profil" required />
							<input type="submit" name="saisie_pseudo" value="Valider" class="saisie_valider_pseudo" />
						</form>
					</div>
				</div>

				<!-- Gestion avatar -->
				<div class="categorie_profil">
					<div class="titre_profil">
						Avatar
					</div>

					<div class="contenu_profil">
						<div class="zone_avatar">
							<?php
								include('../includes/appel_bdd.php');

								$reponse = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant="' . $_SESSION['identifiant'] . '"');
								$donnees = $reponse->fetch();

								if (isset($donnees['avatar']) AND !empty($donnees['avatar']))
									echo '<img src="avatars/' . $donnees['avatar'] . '" alt="avatar" title="' . $donnees['full_name'] . '" class="avatar_preview" />';

								$reponse->closeCursor();
							?>

							<form method="post" action="avatar.php" enctype="multipart/form-data" runat="server">
								<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />

								<span class="zone_parcourir_avatar">+<input type="file" accept="image/*" name="avatar" class="bouton_parcourir_avatar" onchange="loadFile(event)" /></span>

								<div class="mask_avatar">
									<img id="output" class="avatar"/>
								</div>

								<input type="submit" name="post_avatar" value="Envoyer" class="saisie_envoyer_avatar" />
								<input type="submit" name="delete_avatar" value="Supprimer" class="saisie_envoyer_avatar" />
							</form>
						</div>
					</div>
				</div>

				<!-- Gestion mot de passe -->
				<div class="categorie_profil">
					<div class="titre_profil">
						Changement de mot de passe
					</div>

					<div class="contenu_profil">
						<form method="post" action="change_mdp.php" class="form_pseudo">
							<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_profil_2" required />
							<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_profil_2" required />
							<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_profil_2" required />
							<input type="submit" name="saisie_mdp" value="Valider" class="saisie_valider_mdp" />
						</form>
					</div>
				</div>

				<!-- Gestion préférences -->
				<div class="categorie_profil">
					<div class="titre_profil">
						Mes préférences
					</div>

					<div class="contenu_profil">
						<form method="post" action="preferences.php" class="form_preference">
							<div class="titre_preference">
								Choix de la vue par défaut Movie House
							</div>

							<div class="contenu_preference">
								<?php
									switch ($_SESSION['view_movie_house'])
									{
										case "D":
											echo '<input id="synthese" type= "radio" name="movie_house_view" value="S" required />';
											echo '<label for="synthese">Synthèse</label>';
											echo '<br />';
											echo '<input id="detail" type= "radio" name="movie_house_view" value="D" checked required />';
											echo '<label for="detail">Détails</label>';
											echo '<br />';
											break;

										case "S":
										default:
											echo '<input id="synthese" type= "radio" name="movie_house_view" value="S" checked required />';
											echo '<label for="synthese">Synthèse</label>';
											echo '<br />';
											echo '<input id="detail" type= "radio" name="movie_house_view" value="D" required />';
											echo '<label for="detail">Détails</label>';
											echo '<br />';
											break;
									}
								?>
							</div>

							<div class="titre_preference">
								Affichage de la date du jour dans la liste des films
							</div>

							<div class="contenu_preference">

								<?php
									switch ($_SESSION['today_movie_house'])
									{
										case "Y":
											echo '<input id="afficher" type="checkbox" name="affiche_date" checked />';
											echo '<label for="afficher">Afficher</label>';
											break;

										case "N":
										default:
											echo '<input id="afficher" type="checkbox" name="affiche_date" />';
											echo '<label for="afficher">Afficher</label>';
											break;
									}
								?>
							</div>

							<input type="submit" name="saisie_preferences" value="Mettre à jour" class="saisie_valider_preferences" />
						</form>
					</div>
				</div>

				<!-- Gestion statistiques -->
				<div class="categorie_profil">
					<div class="titre_profil">
						Mes contributions
					</div>

					<?php
						echo '<div class="contenu_profil">';
							include('../includes/appel_bdd.php');

							// Nombre de publications
							$reponse = $bdd->query('SELECT COUNT(id) AS nb_publications FROM reference_guide WHERE author = "' . $_SESSION['identifiant'] . '"');
							$donnees = $reponse->fetch();
							echo '<p class="actual">Nombre de publications <span class="pseudo">ReferenceGuide</span> : <span class="pseudo">' . $donnees['nb_publications'] . '</span></p>';
							$reponse->closeCursor();

							// Nombre de votes utiles
							//echo '<p class="actual">Nombre de votes "utile" : <span class="pseudo">NB ICI</span></p>';

							// Nombre de MAJ/SUP en attente
							//echo '<p class="actual">Demandes de mise à jour / suppression en attente : <span class="pseudo">NB ICI</span></p>';

							// Nombre d'idées
							$reponse = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE author = "' . $_SESSION['identifiant'] . '"');
							$donnees = $reponse->fetch();
							echo '<p class="actual">Nombre d\'idées soumises <span class="pseudo">#TheBox</span> : <span class="pseudo">' . $donnees['nb_idees'] . '</span></p>';
							$reponse->closeCursor();

							// Nombre de commentaires Movie House
							$reponse = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $_SESSION['identifiant'] . '"');
							$donnees = $reponse->fetch();
							echo '<p class="actual">Nombre de commentaires <span class="pseudo">MovieHouse</span> : <span class="pseudo">' . $donnees['nb_comments'] . '</span></p>';
							$reponse->closeCursor();

							// Solde des dépenses
		          $req1 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

		          $bilan = 0;

		          while($data1 = $req1->fetch())
		          {
		            // Prix d'achat
		            $prix_achat = $data1['price'];

		            // Identifiant de l'acheteur
		            $acheteur = $data1['buyer'];

		            // Nombre de parts et prix par parts
		            $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data1['id']);

		            $nb_parts_total = 0;
		            $nb_parts_user = 0;

		            while($data2 = $req2->fetch())
		            {
		              // Nombre de parts total
		              $nb_parts_total = $nb_parts_total + $data2['parts'];

		              // Nombre de parts de l'utilisateur
		              if ($_SESSION['identifiant'] == $data2['identifiant'])
		                $nb_parts_user = $data2['parts'];
		            }

		            if ($nb_parts_total != 0)
		              $prix_par_part = $prix_achat / $nb_parts_total;
		            else
		              $prix_par_part = 0;

		            // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
		            if ($data1['buyer'] == $_SESSION['identifiant'])
		              $bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_user);
		            else
		              $bilan = $bilan - ($prix_par_part * $nb_parts_user);

		            $req2->closeCursor();

		          }

		          $req1->closeCursor();

		          $bilan_format = str_replace('.', ',', round($bilan, 2));

							echo '<p class="actual">Solde <span class="pseudo">ExpenseCenter</span> : <span class="pseudo">' . $bilan_format . ' €</span></p>';
						echo '</div>';
					?>
				</div>

				<!-- Gestion désinscription -->
				<div class="categorie_profil">
					<div class="titre_profil">
						Désinscription
					</div>

					<?php
						echo '<div class="contenu_profil">';
							echo '<p class="actual">Si vous souhaitez vous désinscrire, vous pouvez en faire la demande à l\'administrateur à l\'aide de ce bouton. Il validera votre choix après vérification.</p>';

							echo '<form method="post" action="ask_inscription.php" class="form_preference">';
								echo '<input type="submit" name="ask_desinscription" value="Demander la désinscription" class="saisie_valider_preferences" />';
							echo '</form>';
						echo '</div>';
					?>
				</div>
			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

		<script type="text/javascript">
			var loadFile = function(event)
			{
				var output = document.getElementById('output');
				output.src = URL.createObjectURL(event.target.files[0]);
			};
		</script>

  </body>

</html>
