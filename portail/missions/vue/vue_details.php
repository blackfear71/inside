<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMI.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - MI</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Missions : Insider";

        include('../../includes/header.php');
			  include('../../includes/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
        <?php
          if ($missionExistante == true)
          {
            echo '<table cellpadding="0" class="table_mission">';
              // Image & titre
              echo '<tr>';
                echo '<td colspan="2">';
                  echo '<div class="titre_mission_first">' . $detailsMission->getMission() . '</div>';
                  echo '<img src="images/' . $detailsMission->getReference() . '.png" alt="' . $detailsMission->getReference() . '" title="' . $detailsMission->getMission() . '" class="img_mission"  style="margin-bottom: -4px;"/>';
                echo '</td>';
              echo '</tr>';

              // Date
              echo '<tr>';
                echo '<td colspan="2">';
                  echo '<div class="dates_details_mission">Evènement du <strong>' . formatDateForDisplay($detailsMission->getDate_deb()) . '</strong> au <strong>' . formatDateForDisplay($detailsMission->getDate_fin()) . '</strong>.</div>';
                  echo '<div class="separation_details_mission"></div>';
                echo '</td>';
              echo '</tr>';

              // Heure
              echo '<tr>';
                echo '<td colspan="2">';
                  echo '<div class="heure_details_mission">Chaque jour à partir de <strong>' . formatTimeForDisplayLight($detailsMission->getHeure()) . '</strong>.</div>';
                echo '</td>';
              echo '</tr>';

              // Description / contexte
              echo '<tr>';
                echo '<td colspan="2" class="description_details_mission">';
                  echo nl2br($detailsMission->getDescription());
                echo '</td>';
              echo '</tr>';

              // Objectif
              echo '<tr>';
                echo '<td>';
                  echo '<div class="sous_titre_details_mission">Objectif journalier</div>';
                  echo '<div class="separation_details_mission"></div>';
                echo '</td>';

                echo '<td>';
                  echo '<div class="ligne_details_mission">' . formatExplanation($detailsMission->getExplications(), $detailsMission->getObjectif(), '%objectif%') . '</div>';
                  echo '<div class="separation_details_mission_2"></div>';
                echo '</td>';
              echo '</tr>';

              // Objectif du jour
              if (date('Ymd') <= $detailsMission->getDate_fin())
              {
                echo '<tr>';
                  echo '<td>';
                    echo '<div class="sous_titre_details_mission">Aujourd\'hui</div>';
                    echo '<div class="separation_details_mission"></div>';
                  echo '</td>';

                  echo '<td>';
                    echo '<div class="ligne_details_mission" style="text-align: center;"><meter min="0" max="' . $detailsMission->getObjectif() . '" value="' . $missionUser['daily'] . '" class="avancement_details_mission"></meter></div>';
                    echo '<div class="separation_details_mission_2"></div>';
                  echo '</td>';
                echo '</tr>';
              }

              // Objectif général
              echo '<tr>';
                $nb_jours_event = ecartDatesEvent($detailsMission->getDate_deb(), $detailsMission->getDate_fin());
                $objectif_total = $detailsMission->getObjectif() * $nb_jours_event;

                echo '<td>';
                  echo '<div class="sous_titre_details_mission">Avancement mission</div>';
                  echo '<div class="separation_details_mission"></div>';
                echo '</td>';

                echo '<td>';
                  echo '<div class="ligne_details_mission" style="text-align: center;"><meter min="0" max="' . $objectif_total . '" value="' . $missionUser['event'] . '" class="avancement_details_mission"></meter></div>';
                  echo '<div class="separation_details_mission_2"></div>';
                echo '</td>';
              echo '</tr>';

              // Participants
              echo '<tr>';
                echo '<td>';
                  echo '<div class="sous_titre_details_mission_2">Participants</div>';
                echo '</td>';

                echo '<td>';
                  echo '<div class="ligne_details_mission_2">';
                    foreach ($participants as $participant)
                    {
                      echo '<div class="zone_avatar_details_mission">';
                        if (!empty($participant->getAvatar()))
                          echo '<img src="../../profil/avatars/' . $participant->getAvatar() . '" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_details_mission" />';
                        else
                          echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $participant->getPseudo() . '" class="avatar_details_mission" />';

                        echo '<div class="pseudo_details_mission">' . $participant->getPseudo() . '</div>';
                      echo '</div>';
                    }
                  echo '</div>';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
          }
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
