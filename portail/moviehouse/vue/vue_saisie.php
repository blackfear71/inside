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

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>
    <script type="text/javascript" src="scriptMH.js"></script>

  	<title>Inside - MH</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
			<?php
        $title= "Movie House";

        include('../../includes/header.php');
        include('../../includes/onglets.php');
      ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
          $add_film    = true;
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
          if ($filmExistant == true OR ($filmExistant == false AND $initSaisie == true))
          {
    				echo '<div class="zone_saisie_avancee">';
    					echo '<div class="titre_saisie_avancee">';
    							if ($initSaisie == true)
    								echo 'Ajout avancé de film';
    							else
    								echo 'Modification de film';
    					echo '</div>';

					    echo '<div class="contenu_saisie_avancee">';
                if ($initSaisie == true)
  								echo '<form method="post" action="saisie.php?action=doInserer" class="form_saisie_avancee">';
  							else
                  echo '<form method="post" action="saisie.php?modify_id=' . $film->getId() . '&action=doModifier" class="form_saisie_avancee">';

                    echo '<div class="zone_saisie_avancee_infos">';
                      echo '<div class="sous_titre_saisie_avancee">';
                        echo 'Informations sur le film';
                      echo '</div>';

                      // Titre du film
                      echo '<div class="zone_icone_saisie">';
                        echo '<img src="icons/titre.png" alt="titre" title="Titre du film" class="icone_saisie" />';
                        echo '<input type="text" name="nom_film" value="' . $film->getFilm() . '" placeholder="Titre du film" maxlength="255" class="monoligne_film" required />';
                      echo '</div>';

                      // Date de sortie cinéma
                      echo '<div class="zone_icone_saisie">';
                        echo '<img src="icons/date.png" alt="date" title="Date de sortie cinéma" class="icone_saisie" />';

                        if (isBlankDate($film->getDate_theater()))
                          echo '<input type="text" name="date_theater" value="" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
                        else
                          echo '<input type="text" name="date_theater" value="' . formatDateForDisplay($film->getDate_theater()) . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="monoligne_film" />';
                      echo '</div>';

                      // Date de sortie DVD
                      echo '<div class="zone_icone_saisie">';
                        echo '<img src="icons/date.png" alt="date" title="Date de sortie DVD/Bluray" class="icone_saisie" />';
                        echo '<input type="text" name="date_release" value="' . formatDateForDisplay($film->getDate_release()) . '" placeholder="Date de sortie DVD/Bluray (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="monoligne_film" />';
                      echo '</div>';

                      // Lien trailer
                      echo '<div class="zone_icone_saisie">';
                        echo '<img src="icons/trailer.png" alt="trailer" title="Trailer" class="icone_saisie" />';
                        echo '<input type="text" name="trailer" value="' . $film->getTrailer() . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="monoligne_film" />';
                      echo '</div>';

                      // Lien fiche
                      echo '<div class="zone_icone_saisie">';
                        echo '<img src="icons/lien.png" alt="lien" title="Lien" class="icone_saisie" />';
                        echo '<input type="text" name="link" value="' . $film->getLink() . '" placeholder="Lien (Allociné, Wikipédia...)" class="monoligne_film" />';
                      echo '</div>';

                      // Lien poster
                      echo '<div class="zone_icone_saisie" style="margin-bottom: 0;">';
                        echo '<img src="icons/poster.png" alt="poster" title="Poster" class="icone_saisie" />';
                        echo '<input type="text" name="poster" value="' . $film->getPoster() . '" placeholder="URL poster" class="monoligne_film" style="margin-bottom: 0px;" />';
                      echo '</div>';
                    echo '</div>';

                    echo '<div class="zone_saisie_avancee_orga">';
                      echo '<div class="sous_titre_saisie_avancee">';
                        echo 'Organisation sortie';
                      echo '</div>';

                        // Lien Doodle
                        echo '<div class="zone_icone_saisie">';
                          echo '<img src="icons/doodle_white.png" alt="doodle_white" title="Doodle" class="icone_saisie" />';
                          echo '<input type="text" name="doodle" value="' . $film->getDoodle() . '" placeholder="Doodle" class="monoligne_film" />';
                        echo '</div>';

                        // Date sortie
                        echo '<div class="zone_icone_saisie">';
                          echo '<img src="icons/date.png" alt="date" title="Date proposée" class="icone_saisie" />';
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
                        echo '</div>';

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
                        echo '<div class="zone_icone_saisie" style="margin-bottom: 0;">';
                          echo '<img src="icons/restaurant.png" alt="restaurant" title="Restaurant" class="icone_saisie" />';
                          echo '<input type="text" name="place" value="' . $film->getPlace() . '" placeholder="Lieu proposé" class="monoligne_film" style="margin-bottom: 0px;" />';
                        echo '</div>';
                      echo '</div>';

                      echo '<input type="submit" name="saisie_avancee" value="Valider" class="saisie_valider_film" />';
                  echo '</form>';
    					echo '</div>';
    				echo '</div>';
          }
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
