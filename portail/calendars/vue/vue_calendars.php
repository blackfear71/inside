<!DOCTYPE html>
<html>
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
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Onglets années
          include('vue/onglets_calendars.php');

          if ($_GET['action'] == "goConsulterAnnexes")
          {
            echo '<div class="zone_annexes">';
              // Saisie annexe
              if ($preferences->getManage_calendars() == "Y")
              {
                echo '<form method="post" action="calendars.php?action=doAjouterAnnexe" class="zone_annexe" enctype="multipart/form-data" runat="server">';
                  // Image
                  echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

                  echo '<div class="zone_parcourir_image">';
                    echo '<div class="symbole_saisie_image">+</div>';
                    echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="annexe" class="bouton_parcourir_image" onchange="loadFile(event, \'image_calendar_divers\')" required />';
                  echo '</div>';

                  echo '<div class="mask_image">';
                    echo '<img id="image_calendar_divers" class="image" />';
                  echo '</div>';

                  echo '<div class="zone_saisie_nom_divers">';
                    // Nom
                    echo '<input type="text" name="title" value="" placeholder="Nom" maxlength="255" class="input_calendar_text" required />';

                    // Validation
                    echo '<input type="submit" name="send_annexe" value="" class="send_annexe" />';
                  echo '</div>';

                  echo '<div class="align_saisie"></div>';
                echo '</form>';
              }

              // Affichage des annexes
              include('vue/vue_annexes.php');
            echo '</div>';
          }
          else
          {
            // Saisie calendrier
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

              echo '<form method="post" action="calendars.php?action=doAjouter" class="form_saisie_calendar" enctype="multipart/form-data" runat="server">';
                echo '<table class="table_saisie_calendar">';
                  echo '<tr>';
                    // Selection mois
                    echo '<td class="td_saisie_mois">';
                      echo '<select name="months" class="select_month" required>';
                        echo '<option value="" disabled selected hidden>Mois</option>';
                        foreach ($listeMois as $number => $month)
                        {
                          echo '<option value="' . $number . '">' . $month . '</option>';
                        }
                      echo '</select>';
                    echo '</td>';

                    // Selection année
                    echo '<td class="td_saisie_annee">';
                      echo '<select name="years" class="select_year" required>';
                        echo '<option value="" disabled selected hidden>Année</option>';
                        for ($i = $annee_debut; $i <= $annee_fin; $i++)
                        {
                          echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                      echo '</select>';
                    echo '</td>';

                    // Bouton parcourir
                    echo '<td class="td_saisie_calendar">';
                      echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';
                      echo '<div class="zone_parcourir_calendars">';
                        echo '<div class="label_parcourir">Parcourir</div>';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="calendar" class="bouton_parcourir_calendars" required />';
                      echo '</div>';
                    echo '</td>';

                    // Bouton envoi
                    echo '<td class="td_saisie_ajouter">';
                      echo '<input type="submit" name="send" value="" class="send_calendar" />';
                    echo '</td>';
                  echo '</tr>';
                echo '</table>';
              echo '</form>';
            }

            // Affichage des calendriers
            include('vue/table_calendars.php');
          }
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
