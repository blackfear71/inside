<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "MH";
      $style_head  = "styleMH.css";
      $script_head = "";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php
        $title= "Movie House";

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          $modele_mail = getModeleFilm($detailsFilm, $listeEtoiles);
          echo $modele_mail;

          // Encadré destinataires
          echo '<div class="zone_destinataires_mail">';
            $email_present = false;

            foreach ($listeEtoiles as $participant)
            {
              if (!empty($participant->getEmail()))
              {
                if ($email_present == false)
                {
                  echo 'L\'email sera envoyé aux personnes suivantes :<br />';
                  $email_present = true;
                }
                echo '<p class="destinataires">';
                  if (!empty($participant->getAvatar()))
                    echo '<img src="../../includes/images/profil/avatars/' . $participant->getAvatar() . '" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest" />';
                  else
                    echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest" />';

                  echo $participant->getPseudo();
                echo '</p>';
              }
            }

            if ($email_present == false)
              echo '<p class="avertissement_mail" style="margin-top: 0;">Aucune personne ne sera avertie car aucun email n\'a été renseigné.</p>';
            else
              echo '<p class="avertissement_mail">N\'oubliez pas d\'avertir les éventuelles personnes n\'ayant pas renseigné d\'adresse mail.</p>';
          echo '</div>';

          // Bouton envoi mail
          if ($email_present == true)
          {
            echo '<form method="post" action="mailing.php?id_film=' . $_GET['id_film'] . '&action=sendMail">';
              echo '<input type="submit" name="send_mail_film" value="Envoyer l\'e-mail" class="send_mail_film" />';
            echo '</form>';
          }
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
	</body>
</html>
