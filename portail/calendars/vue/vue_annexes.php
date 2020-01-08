<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "CA";
      $style_head      = "styleCA.css";
      $script_head     = "scriptCA.js";
      $angular_head    = false;
      $chat_head       = true;
      $datepicker_head = false;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = "Calendars";

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

          /*******************/
          /* Saisie & Années */
          /*******************/
          echo '<div class="zone_calendars_left">';
            // Saisie
            if ($preferences->getManage_calendars() == "Y")
            {
              echo '<div class="titre_section"><img src="../../includes/icons/common/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Saisir une annexe</div></div>';

              echo '<div class="zone_saisie_calendrier">';
                echo '<form method="post" action="calendars.php?action=doAjouterAnnexe" enctype="multipart/form-data">';
                  // Image
                  echo '<div class="zone_saisie_image">';
                    echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                    echo '<div class="zone_parcourir_image">';
                      echo '<div class="symbole_saisie_image">+</div>';
                      echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="annexe" class="bouton_parcourir_image loadAnnexe" required />';
                    echo '</div>';

                    echo '<div class="mask_image">';
                      echo '<img id="image_annexes" alt="" class="image" />';
                    echo '</div>';
                  echo '</div>';

                  // Titre annexe
                  echo '<input type="text" name="title" value="" placeholder="Nom" maxlength="255" class="titre_annexe" required />';

                  // Bouton validation
                  echo '<input type="submit" name="send_annexe" value="Valider" class="bouton_validation" />';
                echo '</form>';
              echo '</div>';
            }

            // Années
            include('vue/vue_onglets.php');
          echo '</div>';

          /***********/
          /* Annexes */
          /***********/
          echo '<div class="zone_calendars_right">';
            echo '<div class="titre_section"><img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Les annexes</div></div>';

            if (!empty($annexes))
            {
              echo '<div class="zone_calendriers">';
                foreach ($annexes as $annexe)
                {
                  echo '<div class="zone_calendrier">';
                    // Image
                    echo '<img src="../../includes/images/calendars/annexes/mini/' . $annexe->getAnnexe() . '" alt="' . $annexe->getAnnexe() . '" title="' . $annexe->getTitle() . '" class="calendrier" />';

                    // Nom
                    echo '<div class="titre_calendrier">' . $annexe->getTitle() . '</div>';

                    // Boutons
                    echo '<div class="zone_boutons">';
                      // Télécharger
                      echo '<a href="../../includes/images/calendars/annexes/' . $annexe->getAnnexe() . '" class="download_calendar" download><img src="../../includes/icons/calendars/download_grey.png" alt="download_grey" title="Télécharger" class="download_icon" /></a>';

                      // Supprimer
                      if ($preferences->getManage_calendars() == "Y")
                      {
                        echo '<form id="delete_annexe_' . $annexe->getId() . '" method="post" action="calendars.php?action=doSupprimerAnnexe" class="download_calendar" >';
                          echo '<input type="hidden" name="id_annexe" value="' . $annexe->getId() . '" />';
                          echo '<input type="submit" name="delete_annexe" value="" title="Supprimer l\'annexe" class="delete_calendar eventConfirm" />';
                          echo '<input type="hidden" value="Demander la suppression de cette annexe ?" class="eventMessage" />';
                        echo '</form>';
                      }
                    echo '</div>';
                  echo '</div>';
                }
              echo '</div>';
            }
            else
              echo '<div class="empty">Pas d\'annexes présentes...</div>';
          echo '</div>';
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
