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
	<title>Inside - Profil</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
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

				<!-- Bloc utilisateur -->
				<div class="zone_profil_utilisateur">
					<!-- Titre -->
					<div class="zone_profil_utilisateur_titre">
						<?php
							// Affichage pseudo
							echo $_SESSION['full_name'];
						?>
					</div>

					<!-- Tableau modification pseudo & avatar -->
					<table class="zone_profil_utilisateur_table">
						<tr>
							<!-- Saisie pseudo -->
							<td class="zone_profil_utilisateur_pseudo">
								<form method="post" action="change_pseudo.php" class="zone_profil_utilisateur_pseudo_form">
									<input type="text" name="new_pseudo" placeholder="Nouveau pseudo" maxlength="255" class="monoligne_profil" required />
									<input type="submit" name="saisie_pseudo" value="Valider" class="saisie_valider_profil" />
								</form>
							</td>

							<!-- Saisie avatar -->
							<td class="zone_profil_utilisateur_avatar">
								<div class="zone_avatar">
									<form method="post" action="avatar.php" enctype="multipart/form-data" runat="server">
										<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />

										<span class="zone_parcourir_avatar">+<input type="file" accept="image/*" name="avatar" class="bouton_parcourir_avatar" onchange="loadFile(event)" /></span>

										<div class="mask_avatar">
											<img id="output" class="avatar"/>
										</div>

										<input type="submit" name="post_avatar" value="Modifier l'avatar" class="saisie_valider_profil" />
									</form>
								</div>
							</td>

							<!-- Suppression avatar -->
							<td class="zone_profil_utilisateur_suppr">
								<?php
									// Affichage avatar
									include('../includes/appel_bdd.php');

									$reponse = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant="' . $_SESSION['identifiant'] . '"');
									$donnees = $reponse->fetch();

									if (isset($donnees['avatar']) AND !empty($donnees['avatar']))
										echo '<img src="avatars/' . $donnees['avatar'] . '" alt="avatar" title="' . $donnees['full_name'] . '" class="zone_profil_utilisateur_suppr_avatar" />';

									$reponse->closeCursor();
								?>

								<form method="post" action="avatar.php" enctype="multipart/form-data" runat="server">
									<input type="submit" name="delete_avatar" value="Supprimer l'avatar" class="saisie_valider_profil" />
								</form>
							</td>
						</tr>
					</table>
				</div>

				<!-- Bloc contributions -->
				<div class="zone_profil_generique">
					<!-- Titre -->
					<div class="zone_profil_utilisateur_titre">
						Mes contributions
					</div>

					<!-- Tableau contributions -->
					<table class="zone_profil_preferences_table">
						<!-- 3 catégories par ligne ! -->
						<tr>
							<!-- Contributions Movie House -->
							<td class="zone_profil_contribution">
								<div class="titre_preference">
									MOVIE HOUSE
								</div>

								<div class="sous_titre_preference">
									Nombre de commentaires
								</div>

								<div class="contenu_contribution" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
									<?php
										// Nombre de commentaires Movie House
										$reponse = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $_SESSION['identifiant'] . '"');
										$donnees = $reponse->fetch();
										echo $donnees['nb_comments'];
										$reponse->closeCursor();
									?>
								</div>
							</td>

							<!-- Contributions Expense Center -->
							<td class="zone_profil_contribution">
								<div class="titre_preference">
									EXPENSE CENTER
								</div>

								<div class="sous_titre_preference">
									Solde
								</div>

								<div class="contenu_contribution" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
									<?php
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

									echo $bilan_format . ' €';
									?>
								</div>
							</td>

							<!-- Contributions #TheBox -->
							<td class="zone_profil_contribution">
								<div class="titre_preference">
									#THEBOX
								</div>

								<div class="sous_titre_preference">
									Nombre d'idées soumises
								</div>

								<div class="contenu_contribution" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
									<?php
									// Nombre d'idées
									$reponse = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE author = "' . $_SESSION['identifiant'] . '"');
									$donnees = $reponse->fetch();
									echo $donnees['nb_idees'];
									$reponse->closeCursor();
									?>
								</div>
							</td>
						</tr>
					</table>
				</div>

				<!-- Bloc préférences -->
				<div class="zone_profil_generique">
					<!-- Titre -->
					<div class="zone_profil_utilisateur_titre">
						Préférences
					</div>

					<!-- Tableau modification préférences -->
					<table class="zone_profil_preferences_table">
						<tr>
							<!-- Préférences Movie House -->
							<td class="zone_profil_preferences_mh">

								<form method="post" action="preferences.php">

									<div class="titre_preference">
										MOVIE HOUSE
									</div>

									<div class="sous_titre_preference">
										Choix de la vue par défaut
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

									<div class="sous_titre_preference">
										Affichage de la date du jour dans la liste des films
									</div>

									<div class="contenu_preference" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
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

									<input type="submit" name="saisie_preferences" value="Mettre à jour" class="saisie_valider_profil" style="margin-top: 20px;" />

								</form>

							</td>
						</tr>
					</table>
				</div>

				<!-- Bloc utilisateur -->
				<div class="zone_profil_generique">
					<!-- Titre -->
					<div class="zone_profil_utilisateur_titre">
						Utilisateur
					</div>

					<!-- Tableau modification mot de passe & désinscription -->
					<table class="zone_profil_utilisateur_table">
						<tr>
							<!-- Saisie mot de passe -->
							<td class="zone_profil_utilisateur_mdp">
								<form method="post" action="change_mdp.php" class="zone_profil_utilisateur_pseudo_form">
									<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_profil" required />
									<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_profil" required />
									<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_profil" required />
									<input type="submit" name="saisie_mdp" value="Valider" class="saisie_valider_profil" />
								</form>
							</td>

							<!-- Demande désinscription -->
							<td class="zone_profil_utilisateur_desinscription">
								<div class="message_profil">Si vous souhaitez vous désinscrire, vous pouvez en faire la demande à l'administrateur à l'aide de ce bouton. Il validera votre choix après vérification.</div>

								<form method="post" action="ask_inscription.php" class="form_preference">
									<input type="submit" name="ask_desinscription" value="Demander la désinscription" class="saisie_valider_profil" />
								</form>

								<?php
									// On vérifie s'il y a déjà une demande
									$reponse = $bdd->query('SELECT id, identifiant, reset FROM users WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
									$donnees = $reponse->fetch();

									if ($donnees['reset'] == "D")
										echo '<div class="message_profil" style="font-weight: bold;">Une demande est déjà en cours.</div>';
									else
										echo '<div class="message_profil" style="font-weight: bold;">Aucune demande en cours.</div>';

									$reponse->closeCursor();
								?>
							</td>
						</tr>
					</table>
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
