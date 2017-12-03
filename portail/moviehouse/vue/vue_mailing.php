<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMH.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - MH</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php
        $title= "Movie House";

        include('../../includes/header.php');
        include('../../includes/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
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
                    echo '<img src="../../profil/avatars/' . $participant->getAvatar() . '" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest" />';
                  else
                    echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_dest" />';

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
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
