<?php
	session_start();

	$_SESSION['connected'] = false;

  // Initialisation messages
	if (!isset($_SESSION['already_exist']))
		$_SESSION['already_exist'] = false;

  if (!isset($_SESSION['wrong_confirm']))
    $_SESSION['wrong_confirm'] = false;

  if (!isset($_SESSION['ask_inscription']))
    $_SESSION['ask_inscription'] = false;
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside - Inscription</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Demande d'inscription
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$back_index = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">

				<div class="avertissement">
          Ici vous pouvez vous inscrire au site INSIDE. Il vous suffit de renseigner votre trigramme, votre pseudo ainsi qu'un mot de passe. Celui-ci sera directement crypté afin de garantir la sécurité
          de l'accès. Une demande sera envoyée à l'administrateur qui validera votre inscription dans les plus brefs délais.
				</div>

				<div class="bloc_identification" style="margin-top: 50px;">
					<form method="post" action="ask_inscription.php">
            <input type="text" name="trigramme" placeholder="Identifiant" maxlength="3" class="monoligne" required />
						<input type="text" name="pseudo" placeholder="Pseudo" maxlength="255" class="monoligne" required />
            <input type="password" name="password" placeholder="Mot de passe" maxlength="100" class="monoligne" required />
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" maxlength="100" class="monoligne" required />
						<input type="submit" name="ask_inscription" value="SOUMETTRE" class="bouton_connexion"/>
					</form>
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

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
