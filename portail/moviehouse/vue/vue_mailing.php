<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "MH";
      $style_head      = "styleMH.css";
      $script_head     = "";
      $angular_head    = false;
      $chat_head       = true;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
			<?php
        $title= "Movie House";

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***************/
          /* Modèle mail */
          /***************/
          $modele_mail = getModeleFilm($detailsFilm, $listeEtoiles);
          echo $modele_mail;

          /*************************/
          /* Encadré destinataires */
          /*************************/
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
                echo '<div class="destinataires">';
                  // Avatar
                  $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, "avatar");

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_dest" />';

                  echo '<div class="pseudo_dest">' . $participant->getPseudo() . '</div>';
                echo '</div>';
              }
            }

            if ($email_present == false)
              echo '<p class="avertissement_mail" style="margin-top: 0;">Aucune personne ne sera avertie car aucun email n\'a été renseigné.</p>';
            else
              echo '<p class="avertissement_mail">N\'oubliez pas d\'avertir les éventuelles personnes n\'ayant pas renseigné d\'adresse mail.</p>';
          echo '</div>';

          /*********************/
          /* Bouton envoi mail */
          /*********************/
          if ($email_present == true)
          {
            echo '<form method="post" action="mailing.php?action=sendMail">';
              echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
              echo '<input type="submit" name="send_mail_film" value="Envoyer l\'e-mail" class="send_mail_film" />';
            echo '</form>';
          }
        ?>
			</article>

      <!-- Chat -->
      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
	</body>
</html>
