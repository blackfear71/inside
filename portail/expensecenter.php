<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	//include('../includes/init_session.php');

	// Initialisation sauvegarde saisie
	if (!isset($_SESSION['not_numeric']) OR $_SESSION['not_numeric'] != true)
	{
		$_SESSION['price']   = "";
		$_SESSION['buyer']   = "";
		$_SESSION['comment'] = "";
		unset($_SESSION['tableau_parts']);
	}

	// Contrôle année existante
	$annee_existante = false;

	if (isset($_GET['year']) AND is_numeric($_GET['year']))
	{
		include('../includes/appel_bdd.php');

		$reponse = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4) FROM expense_center ORDER BY SUBSTR(date, 1, 4) ASC');
		while($donnees = $reponse->fetch())
		{
			if ($_GET['year'] == $donnees['SUBSTR(date, 1, 4)'])
				$annee_existante = true;
		}
		$reponse->closeCursor();
	}

	// Contrôle si l'année est renseignée et numérique
	if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
		header('location: expensecenter.php?year=' . date("Y"));
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
	<title>Inside - EC</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
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

          <?php
            // Saisie nouvelle ligne
            include('expensecenter/table_saisie_depense.php');

            // Affichage bilan
            include('expensecenter/table_total_depenses.php');

						// Affichage des onglets (années)
						if (isset($_GET['year']) AND is_numeric($_GET['year']))
						{
							include('../includes/appel_bdd.php');

							$reponse = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4) FROM expense_center ORDER BY SUBSTR(date, 1, 4) ASC');

							echo '<div class="expense_year">';

								while($donnees = $reponse->fetch())
								{
									if ($donnees['SUBSTR(date, 1, 4)'] == $_GET['year'])
										echo '<span class="movie_year_active">' . $donnees['SUBSTR(date, 1, 4)'] . '</span>';
									else
										echo '<a href="expensecenter.php?year=' . $donnees['SUBSTR(date, 1, 4)'] . '" class="movie_year_inactive">' . $donnees['SUBSTR(date, 1, 4)'] . '</a>';
								}

								if ($annee_existante == false)
								{
									echo '<span class="movie_year_active">' . $_GET['year'] . '</span>';
								}
							echo '</div>';

							$reponse->closeCursor();
						}

            // Lignes saisies
            include('expensecenter/table_resume_depenses.php');
          ?>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
