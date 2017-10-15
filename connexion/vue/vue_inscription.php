<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside - Inscription</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Inscription";

        include('includes/header.php');
      ?>
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
          Ici vous pouvez vous inscrire au site INSIDE. Il vous suffit de renseigner votre trigramme, votre pseudo ainsi qu'un mot de passe. Celui-ci sera directement crypté afin de garantir la sécurité
          de l'accès. Une demande sera envoyée à l'administrateur qui validera votre inscription dans les plus brefs délais.
				</div>

				<div class="bloc_identification" style="margin-top: 50px;">
					<?php
            echo '<form method="post" action="index.php?action=doDemanderInscription">';
              echo '<input type="text" name="trigramme" value="' . $_SESSION['identifiant_saisi'] . '" placeholder="Identifiant" maxlength="3" class="monoligne" required />';
  						echo '<input type="text" name="pseudo" value="' . $_SESSION['pseudo_saisi'] . '" placeholder="Pseudo" maxlength="255" class="monoligne" required />';
              echo '<input type="password" name="password" value="' . $_SESSION['mot_de_passe_saisi'] . '" placeholder="Mot de passe" maxlength="100" class="monoligne" required />';
              echo '<input type="password" name="confirm_password" value="' . $_SESSION['confirmation_mot_de_passe_saisi'] . '" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne" required />';
  						echo '<input type="submit" name="ask_inscription" value="SOUMETTRE" class="bouton_connexion" />';
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
