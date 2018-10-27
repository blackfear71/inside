<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Utilisateurs";
      $style_head  = "styleAdmin.css";
      $script_head = "";

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Informations utilisateurs";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article>
        <div class="zone_infos">
  				<?php
            foreach ($listeUsers as $user)
            {
              echo '<div class="zone_infos_user">';
                // Avatar
                echo '<div class="circle_avatar">';
                  if (!empty($user->getAvatar()))
                    echo '<img src="../includes/images/profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="infos_avatar" />';
                  else
                    echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="infos_avatar" />';
                echo '</div>';

                // Pseudo
                echo '<div class="infos_pseudo">' . $user->getPseudo() . '</div>';

                // Identifiant
                echo '<div class="infos_identifiant">' . $user->getIdentifiant() . '</div>';

                // Email
                echo '<div class="infos_mail">' . $user->getEmail() . '</div>';

                echo '<div class="zone_form_users">';
                  // Formulaire True Insider
                  echo '<form method="post" action="infos_users.php?user=' . $user->getIdentifiant() . '&top=' . $user->getBeginner() . '&action=changeBeginnerStatus" class="form_infos_users">';
                    if ($user->getBeginner() == "1")
                      echo '<input type="submit" value="True Insider" class="beginner" />';
                    else
                      echo '<input type="submit" value="True Insider" class="not_beginner" />';
                  echo '</form>';

                  // Formulaire Developpeur
                  echo '<form method="post" action="infos_users.php?user=' . $user->getIdentifiant() . '&top=' . $user->getDevelopper() . '&action=changeDevelopperStatus" class="form_infos_users">';
                    if ($user->getDevelopper() == "1")
                      echo '<input type="submit" value="Développeur" class="developper" />';
                    else
                      echo '<input type="submit" value="Développeur" class="not_developper" />';
                  echo '</form>';
                echo '</div>';
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
