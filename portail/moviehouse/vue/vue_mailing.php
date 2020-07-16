<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Movie House';
      $styleHead      = 'styleMH.css';
      $scriptHead     = '';
      $angularHead    = false;
      $chatHead       = true;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
			<?php
        $title = 'Movie House';

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
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
          $modeleMail = getModeleFilm($detailsFilm, $listeEtoiles);
          echo $modeleMail;

          /*************************/
          /* Encadré destinataires */
          /*************************/
          echo '<div class="zone_destinataires_mail">';
            $emailPresent = false;

            foreach ($listeEtoiles as $participant)
            {
              if (!empty($participant->getEmail()))
              {
                if ($emailPresent == false)
                {
                  echo 'L\'email sera envoyé aux personnes suivantes :<br />';
                  $emailPresent = true;
                }
                echo '<div class="destinataires">';
                  // Avatar
                  $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, 'avatar');

                  echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_dest" />';

                  echo '<div class="pseudo_dest">' . $participant->getPseudo() . '</div>';
                echo '</div>';
              }
            }

            if ($emailPresent == false)
              echo '<p class="avertissement_mail">Aucune personne ne sera avertie car aucun email n\'a été renseigné.</p>';
            else
              echo '<p class="avertissement_mail">N\'oubliez pas d\'avertir les éventuelles personnes n\'ayant pas renseigné d\'adresse mail.</p>';
          echo '</div>';

          /*********************/
          /* Bouton envoi mail */
          /*********************/
          if ($emailPresent == true)
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
