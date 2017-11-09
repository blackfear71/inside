<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="styleCA.css" />

    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - CA</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Calendars";

			  include('../../includes/onglets.php') ;
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect  = true;
					$profil_user = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;
          $notifs      = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
        <?php
          // Onglets années
          include('vue/onglets_calendars.php');

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

            echo '<form method="post" action="calendars.php?year=' . $_GET['year'] . '&action=doAjouter" class="form_saisie_calendar" enctype="multipart/form-data" runat="server">';
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
        ?>
      </article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
