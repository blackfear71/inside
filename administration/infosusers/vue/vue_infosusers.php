<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Infos utilisateurs';
      $styleHead      = 'styleAdmin.css';
      $scriptHead     = 'scriptAdmin.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = true;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Informations utilisateurs';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Contenu */
          /***********/
          echo '<div class="zone_infos">';
            foreach ($listeUsers as $user)
            {
              echo '<div class="zone_infos_user">';
                // Avatar
                $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                echo '<div class="circle_avatar">';
                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="infos_avatar" />';
                echo '</div>';

                // Pseudo
                echo '<div class="infos_pseudo">' . $user->getPseudo() . '</div>';

                // Identifiant
                echo '<div class="infos_identifiant">' . $user->getIdentifiant() . '</div>';

                // Anniversaire
                if (!empty($user->getAnniversary()))
                  echo '<div class="infos_identifiant"><img src="../../includes/icons/admin/anniversary_grey.png" alt="anniversary_grey" class="logo_infos" />' . formatDateForDisplay($user->getAnniversary()) . '</div>';

                // Niveau
                echo '<div class="infos_niveau"><img src="../../includes/icons/admin/inside_red.png" alt="inside_red" class="logo_infos" />Niveau ' . $user->getLevel() . ' (' . $user->getExperience() . ' XP)</div>';

                // Email
                if (!empty($user->getEmail()))
                  echo '<div class="infos_mail"><img src="../../includes/icons/admin/mailing_red.png" alt="mailing_red" class="logo_infos" />' . $user->getEmail() . '</div>';

                // Date de dernière connexion
                echo '<div class="date_infos_users">';
                  echo 'Date de dernière connexion : ';

                  if (!empty($user->getPing()))
                    echo formatDateForDisplay(substr($user->getPing(), 0, 4) . substr($user->getPing(), 5, 2) . substr($user->getPing(), 8, 2));
                  else
                    echo 'Pas de connexion récente';
                echo '</div>';

                echo '<div class="zone_form_users">';
                  // Formulaire True Insider
                  echo '<form method="post" action="infosusers.php?action=changeBeginnerStatus" class="form_infos_users">';
                    echo '<input type="hidden" name="user_infos" value="' . $user->getIdentifiant() . '" />';
                    echo '<input type="hidden" name="top_infos" value="' . $user->getBeginner() . '" />';

                    if ($user->getBeginner() == '1')
                      echo '<input type="submit" value="True Insider" class="beginner" />';
                    else
                      echo '<input type="submit" value="True Insider" class="not_beginner" />';
                  echo '</form>';

                  // Formulaire Developpeur
                  echo '<form method="post" action="infosusers.php?action=changeDevelopperStatus" class="form_infos_users">';
                    echo '<input type="hidden" name="user_infos" value="' . $user->getIdentifiant() . '" />';
                    echo '<input type="hidden" name="top_infos" value="' . $user->getDevelopper() . '" />';

                    if ($user->getDevelopper() == '1')
                      echo '<input type="submit" value="Développeur" class="developper" />';
                    else
                      echo '<input type="submit" value="Développeur" class="not_developper" />';
                  echo '</form>';
                echo '</div>';
              echo '</div>';
            }
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
