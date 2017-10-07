<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

		<title>Inside - Utilisateurs</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../includes/images/infos_users_band.png" alt="infos_users_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
        <div class="zone_infos">
  				<?php
            foreach ($listeUsers as $user)
            {
              echo '<div class="zone_infos_user">';
                // Avatar
                echo '<div class="circle_avatar">';
                  if (!empty($user->getAvatar()))
                    echo '<img src="../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="infos_avatar" />';
                  else
                    echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="infos_avatar" />';
                echo '</div>';

                // Pseudo
                echo '<div class="infos_pseudo">' . $user->getPseudo() . '</div>';

                // Identifiant
                echo '<div class="infos_identifiant">' . $user->getIdentifiant() . '</div>';

                // Email
                echo '<div class="infos_mail">' . $user->getEmail() . '</div>';

                // Formulaire True Insider
                echo '<form method="post" action="infos_users.php?user=' . $user->getIdentifiant() . '&top=' . $user->getBeginner() . '&action=changeBeginnerStatus">';
                  if ($user->getBeginner() == "1")
                    echo '<input type="submit" value="True Insider" class="beginner" />';
                  else
                    echo '<input type="submit" value="True Insider" class="not_beginner" />';
                echo '</form>';

                // Formulaire Developpeur
                echo '<form method="post" action="infos_users.php?user=' . $user->getIdentifiant() . '&top=' . $user->getDevelopper() . '&action=changeDevelopperStatus">';
                  if ($user->getDevelopper() == "1")
                    echo '<input type="submit" value="Développeur" class="developper" />';
                  else
                    echo '<input type="submit" value="Développeur" class="not_developper" />';
                echo '</form>';
              echo '</div>';
            }
  				?>
        </div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
