<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Utilisateurs";
      $style_head   = "styleAdmin.css";
      $script_head  = "scriptAdmin.js";
      $masonry_head = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Informations utilisateurs";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
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

                // Anniversaire
                if (!empty($user->getAnniversary()))
                  echo '<div class="infos_identifiant"><img src="../includes/icons/admin/anniversary_grey.png" alt="anniversary_grey" class="logo_infos" />' . formatDateForDisplay($user->getAnniversary()) . '</div>';

                // Niveau
                echo '<div class="infos_niveau"><img src="../includes/icons/admin/inside_red.png" alt="inside_red" class="logo_infos" />Niveau ' . $listeNiveaux[$user->getIdentifiant()] . ' (' . $user->getExperience() . ' XP)</div>';

                // Email
                if (!empty($user->getEmail()))
                  echo '<div class="infos_mail"><img src="../includes/icons/admin/mailing_red.png" alt="mailing_red" class="logo_infos" />' . $user->getEmail() . '</div>';

                echo '<div class="zone_form_users">';
                  // Formulaire True Insider
                  echo '<form method="post" action="infos_users.php?action=changeBeginnerStatus" class="form_infos_users">';
                    echo '<input type="hidden" name="user_infos" value="' . $user->getIdentifiant() . '" />';
                    echo '<input type="hidden" name="top_infos" value="' . $user->getBeginner() . '" />';

                    if ($user->getBeginner() == "1")
                      echo '<input type="submit" value="True Insider" class="beginner" />';
                    else
                      echo '<input type="submit" value="True Insider" class="not_beginner" />';
                  echo '</form>';

                  // Formulaire Developpeur
                  echo '<form method="post" action="infos_users.php?action=changeDevelopperStatus" class="form_infos_users">';
                    echo '<input type="hidden" name="user_infos" value="' . $user->getIdentifiant() . '" />';
                    echo '<input type="hidden" name="top_infos" value="' . $user->getDevelopper() . '" />';

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
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
