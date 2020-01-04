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
              $listeMois = array('01' => 'Janvier',
                                 '02' => 'Février',
                                 '03' => 'Mars',
                                 '04' => 'Avril',
                                 '05' => 'Mai',
                                 '06' => 'Juin',
                                 '07' => 'Juillet',
                                 '08' => 'Août',
                                 '09' => 'Septembre',
                                 '10' => 'Octobre',
                                 '11' => 'Novembre',
                                 '12' => 'Décembre'
                                );

              $annee_debut = date('Y') - 2;
              $annee_fin   = date('Y') + 2;

              echo '<div class="titre_section"><img src="../../includes/icons/common/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Saisir un calendrier</div></div>';

              echo '<div class="zone_saisie_calendrier">';
                echo '<form method="post" action="calendars.php?action=doAjouter" enctype="multipart/form-data">';
                  // Listbox mois
                  echo '<select name="months" class="listbox" required>';
                    echo '<option value="" disabled selected hidden>Mois</option>';
                    foreach ($listeMois as $number => $month)
                    {
                      echo '<option value="' . $number . '">' . $month . '</option>';
                    }
                  echo '</select>';

                  // Listbox année
                  echo '<select name="years" class="listbox" required>';
                    echo '<option value="" disabled selected hidden>Année</option>';
                    for ($i = $annee_debut; $i <= $annee_fin; $i++)
                    {
                      echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                  echo '</select>';

                  // Image
                  echo '<div class="zone_saisie_image">';
                    echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                    echo '<div class="zone_parcourir_image">';
                      echo '<div class="symbole_saisie_image">+</div>';
                      echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="calendar" class="bouton_parcourir_image loadCalendrier" required />';
                    echo '</div>';

                    echo '<div class="mask_image">';
                      echo '<img id="image_calendars" alt="" class="image" />';
                    echo '</div>';
                  echo '</div>';

                  // Bouton validation
                  echo '<input type="submit" name="send" value="Valider" class="bouton_validation" />';
                echo '</form>';
              echo '</div>';
            }

            // Années
            include('vue/vue_onglets.php');
          echo '</div>';

          /***************/
          /* Calendriers */
          /***************/
          echo '<div class="zone_calendars_right">';
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

                      if ($preferences->getManage_calendars() == "Y")
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
      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
