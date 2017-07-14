<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	include('../includes/init_session.php');

	// Contrôle si l'année existe
	$annee_existante = false;

	if (isset($_GET['year']) AND is_numeric($_GET['year']))
	{
		include('../includes/appel_bdd.php');

		$reponse = $bdd->query('SELECT DISTINCT SUBSTR(date_theater,5,4) FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_theater,5,4) ASC');
		while($donnees = $reponse->fetch())
		{
			if ($_GET['year'] == $donnees['SUBSTR(date_theater,5,4)'])
				$annee_existante = true;
		}
		$reponse->closeCursor();
	}

	// Contrôle de la vue
	if (!isset($_GET['view']) OR empty($_GET['view']) OR ($_GET['view'] != "main" AND $_GET['view'] != "user"))
		header('location: moviehouse.php?view=main&year=' . $_GET['year']);

	// Contrôle si l'année est renseignée et numérique
	if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
		header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . date("Y"));

	// Initialisation sauvegarde saisie
	if (!isset($_SESSION['wrong_date']) OR $_SESSION['wrong_date'] == false)
	{
		$_SESSION['nom_film_saisi'] = "";
		$_SESSION['date_theater_saisie'] = "";
	}
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - MH</title>
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
					$add_film = true;
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
				<!-- Switch entre vue générale et vue personnelle-->
				<div class="switch_bug_view">
					<?php
						$switch1 = '<a href="moviehouse.php?view=main&year=' . $_GET['year'] . '" class="link_bug_switch_inactive">Synthèse</a>';
						$switch2 = '<a href="moviehouse.php?view=user&year=' . $_GET['year'] . '" class="link_bug_switch_inactive">Détails</a>';

						if ($_GET['view'] == "main")
						{
							$switch1 = '<a href="moviehouse.php?view=main&year=' . $_GET['year'] . '" class="link_bug_switch_active">Synthèse</a>';
						}
						elseif ($_GET['view'] == "user")
						{
							$switch2 = '<a href="moviehouse.php?view=user&year=' . $_GET['year'] . '" class="link_bug_switch_active">Détails</a>';
						}

						echo $switch1, $switch2;
					?>
				</div>

				<!-- Gestion affichage des onglets en fonction de l'année -->
				<?php
					if (isset($_GET['year']) AND is_numeric($_GET['year']))
					{
						include('../includes/appel_bdd.php');

						$reponse = $bdd->query('SELECT DISTINCT SUBSTR(date_theater,5,4) FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_theater,5,4) ASC');

						echo '<div class="movie_year">';

							while($donnees = $reponse->fetch())
							{
								if ($donnees['SUBSTR(date_theater,5,4)'] == $_GET['year'])
									echo '<span class="movie_year_active">' . $donnees['SUBSTR(date_theater,5,4)'] . '</span>';
								else
									echo '<a href="moviehouse.php?view=' . $_GET['view'] . '&year=' . $donnees['SUBSTR(date_theater,5,4)'] . '" class="movie_year_inactive">' . $donnees['SUBSTR(date_theater,5,4)'] . '</a>';
							}

							if ($annee_existante == false)
							{
								echo '<a href="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '" class="movie_year_active">' . $_GET['year'] . '</a>';
							}
						echo '</div>';

						$reponse->closeCursor();
					}
				?>

				<form method="post" action="moviehouse/saisie_film.php?view=<?php echo $_GET['view']; ?>" class="form_saisie_rapide">
					<input type="text" name="nom_film" value="<?php echo $_SESSION['nom_film_saisi']; ?>" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />
					<input type="text" name="date_theater" value="<?php echo $_SESSION['date_theater_saisie']; ?>" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" class="date_saisie_rapide" required />
					<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />
				</form>

				<?php
					// Message s'il n'y a pas de films
					if ($annee_existante == false)
					{
						echo '<p class="wrong_date">Pas encore de films pour cette année...</p>';
					}
					else
					{
						// Tableau des films
						if ($_GET['view'] == "user")
							include('moviehouse/table_films_user.php');
						else
							include('moviehouse/table_films_synthese.php');
					}
				?>

				<form method="post" action="moviehouse/saisie_film.php?view=<?php echo $_GET['view']; ?>" class="form_saisie_rapide">
					<input type="text" name="nom_film" value="<?php echo $_SESSION['nom_film_saisi']; ?>" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />
					<input type="text" name="date_theater" value="<?php echo $_SESSION['date_theater_saisie']; ?>" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" class="date_saisie_rapide" required />
					<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />
				</form>
			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
