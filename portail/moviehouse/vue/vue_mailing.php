<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMH.css" />

		<title>Inside - MH</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="zone_mail">
          <div class="entete_mail">
            <div class="trait_mail_1"></div>
            <img src="../../includes/icons/inside.png" alt="inside" class="logo_inside_mail" />
            <div class="trait_mail_2"></div>
          </div>

          <div class="mask_mail">
            <div class="triangle_mail"></div>
          </div>

          <div class="corps_mail">
            <div class="corps_mail_left">
              <div class="text_mail">
                <?php
                  echo 'Bonjour,';
                  echo '<br /><br /><br /><br />';
                  echo 'Vous avez stipulé être intéréssé(e) par le film : <strong>' . $detailsFilm->getFilm() . '</strong>';
                  echo '<br /><br />';
                  if (!empty($detailsFilm->getDoodle()))
                    echo 'Vous recevez ce mail contenant le lien doodle à renseigner pour donner votre disponibilité : <a href="' . $detailsFilm->getDoodle() . '" target="_blank">Doodle</a>';
                  else
                    echo 'Aucun Doodle n\'a encore été créé pour ce film. Si vous êtes intéressé(e), veuillez le mettre en place.';
                  echo '<br /><br />';
                  echo 'Les autres personnes intéressées sont :<br />';
                  foreach ($listeEtoiles as $participant)
                  {
                    echo '<p class="participants_mail">- ' . $participant->getPseudo() . '</p>';
                  }
                  echo '<br /><br /><br />';
                  echo 'Cordialement,';
                  echo '<br /><br /><br /><br />';
                  echo 'Mail envoyé depuis INSIDE.';
                ?>
              </div>
            </div>

            <div class="corps_mail_right">
              <?php
                if (!empty($detailsFilm->getPoster()))
                  echo '<img src="' . $detailsFilm->getPoster() . '" alt="poster" title="' . $detailsFilm->getFilm() . '" class="poster_mail"/>';
                else
                  echo '<img src="images/cinema.jpg" alt="poster" title="' . $detailsFilm->getFilm() . '" class="poster_mail"/>';
              ?>
            </div>
          </div>

          <div class="footer_mail">
            <div class="trait_mail_3"></div>
            <img src="../../includes/icons/inside_mini.png" alt="inside" class="logo_inside_mini_mail" />
            <div class="trait_mail_4"></div>
          </div>
        </div>

        <?php
          echo '<form method="post" action="mailing.php?id_film=' . $_GET['id_film'] . '&action=sendMail">';
            echo '<input type="submit" name="send_mail_film" value="Envoyer l\'e-mail" class="send_mail_film" />';
          echo '</form>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
