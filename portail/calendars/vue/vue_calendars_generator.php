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
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = true;

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
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          if ($preferences->getManage_calendars() == 'Y')
          {
            /********************/
            /* Données communes */
            /********************/
            $anneeDebut = date('Y') - 2;
            $anneeFin   = date('Y') + 2;

            /**************/
            /* Générateur */
            /**************/
            echo '<div class="zone_calendars_top">';
              // Titre
              echo '<div class="titre_section"><img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Générer un nouveau calendrier</div></div>';

              // Saisie
              echo '<div class="zone_calendrier_generator_left">';
                echo '<div class="zone_saisie_calendrier">';
                  // Saisie des informations
                  echo '<form method="post" action="calendars_generator.php?action=doGenerer" enctype="multipart/form-data">';
                    // Image
                    echo '<div class="zone_saisie_image">';
                      echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                      echo '<div class="zone_parcourir_image_generator">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="picture_calendar" class="bouton_parcourir_image_generator loadCalendrierGenere" />';
                      echo '</div>';

                      echo '<div class="mask_image_generator">';
                        if (isset($calendarParameters) AND !empty($calendarParameters->getPicture()))
                        {
                          echo '<img src="../../includes/images/calendars/temp/' . $calendarParameters->getPicture() . '" id="image_calendars_generated" alt="" class="image" />';
                          echo '<input type="hidden" name="picture_calendar_generated" value="' . $calendarParameters->getPicture() . '" />';
                        }
                        else
                          echo '<img id="image_calendars_generated" alt="" class="image" />';
                      echo '</div>';
                    echo '</div>';

                    // Listbox mois
                    echo '<select name="month_calendar" class="listbox" required>';
                      echo '<option value="" disabled selected hidden>Mois</option>';

                      foreach ($listeMois as $numeroMois => $mois)
                      {
                        if ($numeroMois == $calendarParameters->getMonth())
                          echo '<option value="' . $numeroMois . '" selected>' . $mois . '</option>';
                        else
                          echo '<option value="' . $numeroMois . '">' . $mois . '</option>';
                      }
                    echo '</select>';

                    // Listbox année
                    echo '<select name="year_calendar" class="listbox" required>';
                      echo '<option value="" disabled selected hidden>Année</option>';
                      for ($i = $anneeDebut; $i <= $anneeFin; $i++)
                      {
                        if ($i == $calendarParameters->getYear())
                          echo '<option value="' . $i . '" selected>' . $i . '</option>';
                        else
                          echo '<option value="' . $i . '">' . $i . '</option>';
                      }
                    echo '</select>';

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                      echo '<input type="submit" name="send" value="Générer le calendrier" id="bouton_saisie_generator" class="saisie_bouton" />';
                    echo '</div>';
                  echo '</form>';
                echo '</div>';
              echo '</div>';

              // Affichage du calendrier généré sous forme d'image
              if (isset($calendarParameters) AND !empty($calendarParameters->getMonth()) AND !empty($calendarParameters->getYear()))
              {
                echo '<div class="zone_calendrier_generator_middle">';
                  // Génération du calendrier au format HTML
                  include('vue_calendar_generated.php');

                  // Affichage du calendrier au format JPEG (une fois généré)
                  echo '<img src="" title="Calendrier généré" id="generated_calendar" class="image_rendu_generator" />';

                  // Formulaire de sauvegarde de l'image générée
                  echo '<form method="post" action="calendars_generator.php?action=doSauvegarder" enctype="multipart/form-data" class="form_sauvegarde_calendrier">';
                    // Image générée
                    echo '<input type="hidden" name="calendar_generator" id="calendar_generator" value="" />';

                    // Nom fichiers temporaires
                    echo '<input type="hidden" name="temp_name_generator" value="' . $calendarParameters->getPicture() . '" />';

                    // Mois
                    echo '<input type="hidden" name="month_generator" value="' . $calendarParameters->getMonth() . '" />';

                    // Année
                    echo '<input type="hidden" name="year_generator" value="' . $calendarParameters->getYear() . '" />';

                    // Bouton sauvegarde
                    echo '<input type="submit" name="save" value="Sauvegarder le calendrier" id="bouton_saisie_generated" class="saisie_bouton" />';
                  echo '</form>';
                echo '</div>';
              }

              // Explications
              echo '<div class="zone_calendrier_generator_right">';
                if (isset($vacances) AND empty($vacances))
                  echo 'Attention, les dates de vacances ne sont pas disponibles pour le mois de ' . $listeMois[$calendarParameters->getMonth()] . ' ' . $calendarParameters->getYear() . '.';

                echo '<br /><br />explications ici';
              echo '</div>';
            echo '</div>';

            /***********************/
            /* Ajout de calendrier */
            /***********************/
            echo '<div class="zone_calendars_left">';
              // Titre
              echo '<div class="titre_section"><img src="../../includes/icons/calendars/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Saisir un calendrier</div></div>';

              // Saisie
              echo '<div class="zone_saisie_calendars_left">';
                echo '<div class="zone_saisie_calendrier">';
                  echo '<form method="post" action="calendars_generator.php?action=doAjouter" enctype="multipart/form-data">';
                    // Image
                    echo '<div class="zone_saisie_image">';
                      echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                      echo '<div class="zone_parcourir_image">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="calendar" class="bouton_parcourir_image loadCalendrier" required />';
                      echo '</div>';

                      echo '<div class="mask_image">';
                        echo '<img id="image_calendars" alt="" class="image" />';
                      echo '</div>';
                    echo '</div>';

                    // Listbox mois
                    echo '<select name="month_calendar" class="listbox" required>';
                      echo '<option value="" disabled selected hidden>Mois</option>';

                      foreach ($listeMois as $numeroMois => $mois)
                      {
                        echo '<option value="' . $numeroMois . '">' . $mois . '</option>';
                      }
                    echo '</select>';

                    // Listbox année
                    echo '<select name="year_calendar" class="listbox" required>';
                      echo '<option value="" disabled selected hidden>Année</option>';
                      for ($i = $anneeDebut; $i <= $anneeFin; $i++)
                      {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                      }
                    echo '</select>';

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                      echo '<input type="submit" name="send" value="Valider" id="bouton_saisie_calendrier" class="saisie_bouton" />';
                    echo '</div>';
                  echo '</form>';
                echo '</div>';
              echo '</div>';

              // Explications
              echo '<div class="zone_saisie_calendars_right">';
                echo 'explications là';
              echo '</div>';
            echo '</div>';

            /******************/
            /* Ajout d'annexe */
            /******************/
            echo '<div class="zone_calendars_right">';
              // Titre
              echo '<div class="titre_section"><img src="../../includes/icons/calendars/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Saisir une annexe</div></div>';

              // Saisie
              echo '<div class="zone_saisie_calendars_left">';
                echo '<div class="zone_saisie_calendrier">';
                  echo '<form method="post" action="calendars_generator.php?action=doAjouterAnnexe" enctype="multipart/form-data">';
                    // Image
                    echo '<div class="zone_saisie_image">';
                      echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                      echo '<div class="zone_parcourir_image">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="annexe" class="bouton_parcourir_image loadAnnexe" required />';
                      echo '</div>';

                      echo '<div class="mask_image">';
                        echo '<img id="image_annexes" alt="" class="image" />';
                      echo '</div>';
                    echo '</div>';

                    // Titre annexe
                    echo '<input type="text" name="title" value="" placeholder="Nom" maxlength="255" class="titre_annexe" required />';

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                      echo '<input type="submit" name="send_annexe" value="Valider" id="bouton_saisie_annexe" class="saisie_bouton" />';
                    echo '</div>';
                  echo '</form>';
                echo '</div>';
              echo '</div>';

              // Explications
              echo '<div class="zone_saisie_calendars_right">';
                echo 'explications et là aussi';
              echo '</div>';
            echo '</div>';
          }
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
