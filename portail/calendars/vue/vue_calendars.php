<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "CA";
      $style_head   = "styleCA.css";
      $script_head  = "scriptCA.js";
      $chat_head    = true;
      $masonry_head = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Calendars";

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

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Saisie & Années
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

              echo '<div class="titre_section"><img src="../../includes/icons/common/send_grey.png" alt="send_grey" class="logo_titre_section" />Saisir un calendrier</div>';

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
                    echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

                    echo '<div class="zone_parcourir_image">';
                      echo '<div class="symbole_saisie_image">+</div>';
                      echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="calendar" class="bouton_parcourir_image" onchange="loadFile(event, \'image_calendars\')" required />';
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

          // Calendriers
          echo '<div class="zone_calendars_right">';
            echo '<div class="titre_section"><img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" />Les calendriers</div>';

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
                        echo '<form method="post" action="calendars.php?year=' . $_GET['year'] . '&id_cal=' . $calendrier->getId() . '&action=doSupprimer" class="download_calendar" >';
                          echo '<input type="submit" name="delete_calendar" value="" title="Supprimer le calendrier" onclick="if(!confirm(\'Demander la suppression de ce calendrier ?\')) return false;" class="delete_calendar" />';
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

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
