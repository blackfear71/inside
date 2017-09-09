<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside - MDP</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="includes/images/password_band.png" alt="password_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$back_index = true;

					include('includes/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
      <?php
        include('includes/alerts.php');
      ?>

			<article class="article_portail">
				<div class="avertissement">
					Si vous avez perdu votre mot de passe, vous pouvez effectuer une demande de réinitialisation du mot de passe à l'administrateur via le formulaire ci-dessous.
					L'administrateur est suceptible de vous contacter directement afin de vérifier votre demande. Il vous suffit de renseigner votre identifiant afin que celui-ci
					puisse procéder à la création d'un nouveau mot de passe qu'il vous communiquera par la suite.
				</div>

				<div class="bloc_identification" style="margin-top: 100px;">
          <?php
  					echo '<form method="post" action="index.php?action=doDemanderMdp">';
  						echo '<input type="text" name="login" value="' . $_SESSION['identifiant_saisi_mdp'] . '" placeholder="Identifiant" maxlength="3" class="monoligne" required />';
  						echo '<input type="submit" name="ask_password" value="SOUMETTRE" class="bouton_connexion"/>';
  					echo '</form>';
          ?>
				</div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('includes/footer.php'); ?>
		</footer>
  </body>
</html>
