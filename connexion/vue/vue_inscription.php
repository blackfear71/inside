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
			<div class="main_title">
				<img src="includes/images/subscribe_band.png" alt="subscribe_band" class="bandeau_categorie_2" />
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
  						echo '<input type="submit" name="ask_inscription" value="SOUMETTRE" class="bouton_connexion"/>';
  					echo '</form>';
          ?>
				</div>

				<?php
          // Indentifiant déjà existant
					if ($_SESSION['already_exist'] == true)
					{
						echo '<div class="asking_inscription">Cet identifiant existe déjà.</div>';
						$_SESSION['already_exist'] = false;
					}

          // Mauvaise confirmation du mot de passe
          if ($_SESSION['wrong_confirm'] == true)
          {
            echo '<div class="asking_inscription">Mauvaise confirmation du mot de passe.</div>';
            $_SESSION['wrong_confirm'] = false;
          }

          // Demande d'inscription approuvée
          if ($_SESSION['ask_inscription'] == true)
          {
            echo '<div class="asking_inscription">Votre demande d\'inscription a été soumise.</div>';
            $_SESSION['ask_inscription'] = false;
          }
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('includes/footer.php'); ?>
		</footer>
  </body>
</html>
