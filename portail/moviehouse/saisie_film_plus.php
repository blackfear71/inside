<?php
	// Contrôles communs Utilisateurs
	include('../../includes/controls_users.php');
	
	include('../../includes/init_session.php');

	// Initialisation sauvegarde saisie
	if (!isset($_SESSION['wrong_date']) OR $_SESSION['wrong_date'] == false)
	{
		$_SESSION['nom_film_saisi'] = "";
		$_SESSION['date_theater_saisie'] = "";
		$_SESSION['date_release_saisie'] = "";
		$_SESSION['trailer_saisi'] = "";
		$_SESSION['link_saisi'] = "";
		$_SESSION['poster_saisi'] = "";
		$_SESSION['doodle_saisi'] = "";
		$_SESSION['date_doodle_saisie'] = "";
	}

	// Contrôle film non à supprimer
	if (isset($_GET['modify_id']))
	{
		include('../../includes/appel_bdd.php');

		$reponse = $bdd->query('SELECT id, to_delete FROM movie_house WHERE id = ' . $_GET['modify_id']);
		$donnees = $reponse->fetch();

		if ($donnees['to_delete'] == "Y")
			header('location: ../moviehouse.php?view=main&year=' . date("Y"));

		$reponse->closeCursor();
	}
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../../favicon.png" />
	<link rel="stylesheet" href="../../style.css" />
  <title>Inside CGI - MH</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="bandeau_titre_article">
					<?php
						if (!isset($_GET['modify_id']))
							echo '<div class="previs_article">Ajout avancé de film</div>';
						else
							echo '<div class="previs_article">Modification de film</div>';
					?>
				</div>

				<div class="categorie_profil" style="margin-top: 50px;">
					<div class="titre_profil">
						Informations sur le média
					</div>

					<div class="contenu_profil">
						<?php
							if (isset($_GET['modify_id']) AND !empty($_GET['modify_id']))
							{
								include('../../includes/appel_bdd.php');

								$reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $_GET['modify_id']);
								$donnees = $reponse->fetch();

								// On formate les dates
								$date_theater = "";
								$date_release = "";
								$date_doodle = "";

								if (!empty($donnees['date_theater']))
									$date_theater = substr($donnees['date_theater'], 2, 2) . '/' . substr($donnees['date_theater'], 0, 2) . '/' .  substr($donnees['date_theater'], 4, 4);

								if (!empty($donnees['date_release']))
									$date_release = substr($donnees['date_release'], 2, 2) . '/' .  substr($donnees['date_release'], 0, 2) . '/' .  substr($donnees['date_release'], 4, 4);

								if (!empty($donnees['date_doodle']))
									$date_doodle = substr($donnees['date_doodle'], 2, 2) . '/' .  substr($donnees['date_doodle'], 0, 2) . '/' .  substr($donnees['date_doodle'], 4, 4);

								// On affiche le tableau avec les données de la base
								echo '<form method="post" action="saisie_film.php?modify_id=' . $_GET['modify_id'] . '" class="form_pseudo">';
									// On réinsère les données qu'on vient de saisir en cas de mauvaise saisie
									if (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
									{
										echo '<span class="obligatoire">*</span>';
										echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';
										echo '<span class="obligatoire">*</span>';
										echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" class="monoligne_film" required />';
										echo '<input type="text" name="date_release" value="' . $_SESSION['date_release_saisie'] . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" class="monoligne_film" />';
										echo '<input type="text" name="trailer" value="' . $_SESSION['trailer_saisi'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';
										echo '<input type="text" name="link" value="' . $_SESSION['link_saisi'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';
										echo '<input type="text" name="poster" value="' . $_SESSION['poster_saisi'] . '" placeholder="URL poster" class="monoligne_film" />';
										echo '<input type="text" name="doodle" value="' . $_SESSION['doodle_saisi'] . '" placeholder="Doodle" class="monoligne_film" />';
										echo '<input type="text" name="date_doodle" value="' . $_SESSION['date_doodle_saisie'] . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" class="monoligne_film" />';
										echo '<input type="submit" name="modification_avancee" value="Valider" class="saisie_valider_film" />';

										$_SESSION['wrong_date'] = NULL;
									}
									// Sinon on affiche les données de la table
									else
									{
										echo '<span class="obligatoire">*</span>';
										echo '<input type="text" name="nom_film" value="' . $donnees['film'] . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';
										echo '<span class="obligatoire">*</span>';
										echo '<input type="text" name="date_theater" value="' . $date_theater . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" class="monoligne_film" required />';
										echo '<input type="text" name="date_release" value="' . $date_release . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" class="monoligne_film" />';
										echo '<input type="text" name="trailer" value="' . $donnees['trailer'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';
										echo '<input type="text" name="link" value="' . $donnees['link'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';
										echo '<input type="text" name="poster" value="' . $donnees['poster'] . '" placeholder="URL poster" class="monoligne_film" />';
										echo '<input type="text" name="doodle" value="' . $donnees['doodle'] . '" placeholder="Doodle" class="monoligne_film" />';
										echo '<input type="text" name="date_doodle" value="' . $date_doodle . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" class="monoligne_film" />';
										echo '<input type="submit" name="modification_avancee" value="Valider" class="saisie_valider_film" />';
									}
								echo '</form>';

								$reponse->closeCursor();
							}
							else
							{
								echo '<form method="post" action="saisie_film.php" class="form_pseudo">';
									echo '<span class="obligatoire">*</span>';
									echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';
									echo '<span class="obligatoire">*</span>';
									echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" class="monoligne_film" required />';
									echo '<input type="text" name="date_release" value="' . $_SESSION['date_release_saisie'] . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" class="monoligne_film" />';
									echo '<input type="text" name="trailer" value="' . $_SESSION['trailer_saisi'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';
									echo '<input type="text" name="link" value="' . $_SESSION['link_saisi'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';
									echo '<input type="text" name="poster" value="' . $_SESSION['poster_saisi'] . '" placeholder="URL poster" class="monoligne_film" />';
									echo '<input type="text" name="doodle" value="' . $_SESSION['doodle_saisi'] . '" placeholder="Doodle" class="monoligne_film" />';
									echo '<input type="text" name="date_doodle" value="' . $_SESSION['date_doodle_saisie'] . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" class="monoligne_film" />';
									echo '<input type="submit" name="saisie_avancee" value="Valider" class="saisie_valider_film" />';
								echo '</form>';

								if (isset($_SESSION['wrong_date']) AND $_SESSION['wrong_date'] == true)
								{
									$_SESSION['wrong_date'] = NULL;
								}
							}
						?>
					</div>
				</div>
			</article>
		</section>

		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>

  </body>

</html>
