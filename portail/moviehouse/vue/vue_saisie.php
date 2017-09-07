<!DOCTYPE html>
<html>

  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
  	<meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMH.css" />
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  	<title>Inside - MH</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="zone_saisie_avancee">
					<div class="titre_saisie_avancee">
						<?php
							if ($initSaisie == true)
								echo 'Ajout avancé de film';
							else
								echo 'Modification de film';
						?>
					</div>

					<div class="contenu_saisie_avancee">
						<?php
              if ($initSaisie == true)
								echo '<form method="post" action="saisie.php?action=doInserer" class="form_saisie_avancee">';
							else
                echo '<form method="post" action="saisie.php?modify_id=' . $film->getId() . '&action=doModifier" class="form_saisie_avancee">';

                  echo '<div class="zone_saisie_avancee_infos">';
                    echo '<div class="sous_titre_saisie_avancee">';
                      echo 'Informations sur le film';
                    echo '</div>';

                    // Titre du film
                    echo '<input type="text" name="nom_film" value="' . $film->getFilm() . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';

                    // Date de sortie cinéma
                    if (isBlankDate($film->getDate_theater()))
                      echo '<input type="text" name="date_theater" value="" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
                    else
                      echo '<input type="text" name="date_theater" value="' . formatDateForDisplay($film->getDate_theater()) . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';

                    // Date de sortie DVD
                    echo '<input type="text" name="date_release" value="' . formatDateForDisplay($film->getDate_release()) . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="monoligne_film" />';

                    // Lien trailer
                    echo '<input type="text" name="trailer" value="' . $film->getTrailer() . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';

                    // Lien fiche
                    echo '<input type="text" name="link" value="' . $film->getLink() . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';

                    // Lien poster
                    echo '<input type="text" name="poster" value="' . $film->getPoster() . '" placeholder="URL poster" class="monoligne_film" style="margin-bottom: 0px;" />';
                  echo '</div>';

                  echo '<div class="zone_saisie_avancee_orga">';
                    echo '<div class="sous_titre_saisie_avancee">';
                      echo 'Organisation sortie';
                    echo '</div>';

                    // Lien Doodle
                    echo '<input type="text" name="doodle" value="' . $film->getDoodle() . '" placeholder="Doodle" class="monoligne_film" />';

                    // Date sortie
                    echo '<input type="text" name="date_doodle" value="' . formatDateForDisplay($film->getDate_doodle()) . '" placeholder="Date proposée (jj/mm/yyyy)" maxlength="10" id="datepicker3" class="monoligne_film_short" />';

                    // Selection de l'heure
                    echo '<select name="hours_doodle" class="select_time">';
                      if (empty($film->getTime_doodle()))
                        echo '<option value="" disabled selected hidden>hh</option>';
                      else
                        echo '<option value="" disabled hidden>hh</option>';

                      for ($i = 0; $i <= 23; $i++)
                      {
                        if (!empty($film->getTime_doodle()) AND substr($film->getTime_doodle(), 0, 2) == $i)
                        {
                          if ($i < 10)
                            echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
                          else
                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                        }
                        else
                        {
                          if (substr($film->getTime_doodle(), 0, 2) == "  ")
                            echo '<option value="" disabled selected hidden>hh</option>';

                          if ($i < 10)
                            echo '<option value="0' . $i . '">0' . $i . '</option>';
                          else
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                      }
                    echo '</select>';

                    // Selection des minutes
                    echo '<select name="minutes_doodle" class="select_time">';
                      if (empty($film->getTime_doodle()))
                        echo '<option value="" disabled selected hidden>mm</option>';
                      else
                        echo '<option value="" disabled hidden>mm</option>';

                      for ($i = 0; $i <= 11; $i++)
                      {
                        if (!empty($film->getTime_doodle()) AND (substr($film->getTime_doodle(), 2, 2) / 5) == $i)
                        {
                          if ($i < 2)
                            echo '<option value="0' . 5*$i . '" selected>0' . 5*$i . '</option>';
                          else
                            echo '<option value="' . 5*$i . '" selected>' . 5*$i . '</option>';
                        }
                        else
                        {
                          if (substr($film->getTime_doodle(), 2, 2) == "  ")
                            echo '<option value="" disabled selected hidden>mm</option>';

                          if ($i < 2)
                            echo '<option value="0' . 5*$i . '">0' . 5*$i . '</option>';
                          else
                            echo '<option value="' . 5*$i . '">' . 5*$i . '</option>';
                        }
                      }
                    echo '</select>';

                    // Choix restaurant
                    echo '<div class="restaurant">Restaurant</div>';
                    echo '<div class="zone_select_restaurant">';
                      if ($film->getRestaurant() == "N" OR $film->getRestaurant() == "")
                        echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" checked />';
                      else
                        echo '<input id="none" type="radio" name="restaurant" value="N" class="bouton_restaurant" />';
                      echo '<label for="none" class="label_restaurant">Aucun</label>';
                      echo '<br />';

                      if ($film->getRestaurant() == "B")
                        echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" checked />';
                      else
                        echo '<input id="before" type="radio" name="restaurant" value="B" class="bouton_restaurant" />';
                      echo '<label for="before" class="label_restaurant">Avant</label>';
                      echo '<br />';

                      if ($film->getRestaurant() == "A")
                        echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" checked />';
                      else
                        echo '<input id="after" type="radio" name="restaurant" value="A" class="bouton_restaurant" />';
                      echo '<label for="after" class="label_restaurant">Après</label>';
                      echo '<br />';
                    echo '</div>';

                    // Lieu restaurant
                    echo '<input type="text" name="place" value="' . $film->getPlace() . '" placeholder="Lieu proposé" class="monoligne_film" style="margin-bottom: 0px;" />';
                  echo '</div>';

                  echo '<input type="submit" name="saisie_avancee" value="Valider" class="saisie_valider_film" />';
              echo '</form>';
						?>
					</div>
				</div>
			</article>
		</section>

		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>

  </body>

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script>
		$(function()
		{
			$( "#datepicker" ).datepicker(
			{
				firstDay: 1,
				altField: "#datepicker",
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
				weekHeader: 'Sem.',
				dateFormat: 'dd/mm/yy'
			});
			$( "#datepicker2" ).datepicker(
			{
				firstDay: 1,
				altField: "#datepicker2",
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
				weekHeader: 'Sem.',
				dateFormat: 'dd/mm/yy'
			});
			$( "#datepicker3" ).datepicker(
			{
				firstDay: 1,
				altField: "#datepicker3",
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
				monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
				dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
				dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
				dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
				weekHeader: 'Sem.',
				dateFormat: 'dd/mm/yy'
			});
		});
	</script>

</html>
