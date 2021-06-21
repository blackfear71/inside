<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Calendars';
      $styleHead       = 'styleCA.css';
      $scriptHead      = 'scriptCA.js';
      $angularHead     = false;
      $chatHead        = true;
      $datepickerHead  = false;
      $masonryHead     = true;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = 'Calendars';

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

          /*********/
          /* Liens */
          /*********/
          if ($preferences->getManage_calendars() == 'Y')
          {
            echo '<div class="zone_liens_saisie">';
              // Création calendrier
              echo '<a href="calendars_generator.php?action=goConsulter" title="Créer un calendrier" class="lien_categorie">';
                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/edit.png" alt="edit" class="image_lien" /></div>';
                echo '<div class="zone_texte_lien">Créer un nouveau calendrier ou une annexe</div>';
              echo '</a>';
            echo '</div>';
          }

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /**********/
          /* Années */
          /**********/
          echo '<div class="zone_calendars_onglets">';
            include('vue/vue_onglets.php');
          echo '</div>';

          /***********/
          /* Annexes */
          /***********/
          echo '<div class="zone_calendars">';
            echo '<div class="titre_section"><img src="../../includes/icons/calendars/annexes_grey.png" alt="annexes_grey" class="logo_titre_section" /><div class="texte_titre_section">Les annexes</div></div>';

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
                      if ($preferences->getManage_calendars() == 'Y')
                      {
                        echo '<form id="delete_annexe_' . $annexe->getId() . '" method="post" action="calendars.php?action=doSupprimerAnnexe" class="download_calendar" >';
                          echo '<input type="hidden" name="id_annexe" value="' . $annexe->getId() . '" />';
                          echo '<input type="hidden" name="team_annexe" value="' . $annexe->getTeam() . '" />';
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
      <?php include('../../includes/common/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
