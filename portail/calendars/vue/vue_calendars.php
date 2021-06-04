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

          /***************/
          /* Calendriers */
          /***************/
          echo '<div class="zone_calendars">';
            echo '<div class="titre_section"><img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Les calendriers</div></div>';

            if (!empty($calendriers))
            {
              echo '<div class="zone_calendriers">';
                foreach ($calendriers as $calendrier)
                {
                  echo '<div class="zone_calendrier">';
                    // Image
                    echo '<img src="../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="' . $calendrier->getTitle() . '" title="' . $calendrier->getTitle() . '" class="calendrier" />';

                    // Nom
                    echo '<div class="titre_calendrier">' . $calendrier->getTitle() . '</div>';

                    // Boutons
                    echo '<div class="zone_boutons">';
                      echo '<a href="../../includes/images/calendars/' . $calendrier->getYear() . '/' . $calendrier->getCalendar() . '" class="download_calendar" download><img src="../../includes/icons/calendars/download_grey.png" alt="download_grey" title="Télécharger" class="download_icon" /></a>';

                      if ($preferences->getManage_calendars() == 'Y')
                      {
                        echo '<form id="delete_calendar_' . $calendrier->getId() . '" method="post" action="calendars.php?year=' . $_GET['year'] . '&action=doSupprimer" class="download_calendar" >';
                          echo '<input type="hidden" name="id_cal" value="' . $calendrier->getId() . '" />';
                          echo '<input type="submit" name="delete_calendar" value="" title="Supprimer le calendrier" class="delete_calendar eventConfirm" />';
                          echo '<input type="hidden" value="Demander la suppression de ce calendrier ?" class="eventMessage" />';
                        echo '</form>';
                      }
                    echo '</div>';
                  echo '</div>';
                }
              echo '</div>';
            }
            else
              echo '<div class="empty">Pas de calendriers pour cette année...</div>';
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
