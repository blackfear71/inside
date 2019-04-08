<?php
  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide">';
    echo '<table class="table_saisie_rapide">';
      echo '<tr>';
        echo '<td class="td_saisie_film">';
          echo '<input type="text" name="nom_film" value="' . $_SESSION['save']['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
        echo '</td>';

        echo '<td class="td_saisie_date">';
          echo '<input type="text" name="date_theater" value="' . $_SESSION['save']['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" autocomplete="off" id="datepicker_sortie_1" class="date_saisie_rapide" />';
        echo '</td>';

        echo '<td class="td_saisie_ajouter">';
          echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
        echo '</td>';
      echo '</tr>';
    echo '</table>';
  echo '</form>';

	/***************************/
	/* Tableau vue utilisateur */
	/***************************/
  if ($anneeExistante == true AND !empty($listeFilms))
  {
  	echo '<table class="table_movie_house">';
      // Titres du tableau
      echo '<tr>';
      	echo '<td class="table_titres" style="border: 0; border-bottom: 1px solid #e3e3e3;"></td>';
      	echo '<td class="init_table_dates">Date de sortie</td>';
      	echo '<td class="init_table_dates">Fiche</td>';
      	echo '<td class="init_table_dates">Bande-annonce</td>';
      	echo '<td class="init_table_dates">Doodle</td>';
      	echo '<td class="init_table_dates">Date proposée</td>';
        echo '<td class="init_table_dates">Commentaires</td>';
      	echo '<td class="init_table_dates">Personnes intéressées</td>';
      	echo '<td class="init_table_dates">Vote</td>';
      	echo '<td class="init_table_dates">Actions</td>';
      echo '</tr>';

      $date_jour         = date("Ymd");
      $date_jour_present = false;
      $i                 = 0;

      // Calcul durée à partir de laquelle cacher les films (si on n'affiche pas tous les films)
      list($affichage, $type, $duree) = explode(";", $preferences->getView_old_movies());

      if ($_GET['year'] == date("Y") AND $affichage != "T")
      {
        $date_hide = dureeOldMovies($type, $duree);

        // On n'affiche pas le bandeau des films cachés s'il n'y en a pas
        if ($date_hide > $listeFilms[0]->getDate_theater())
        {
          // Films cachés en fonction de la préférence utilisateur
          echo '<tr class="hidden_films">';
            echo '<td colspan="10">';
              echo '<a onclick="afficherMasquerTbody(\'hidden_films\', \'show_hidden\');" id="show_hidden" class="show_hidden_films"><div class="symbol_hidden">+</div> Films cachés</a>';
            echo '</td>';
          echo '</tr>';

          echo '<tbody id="hidden_films" style="display: none;">';
            foreach ($listeFilms as $film)
            {
              // Liste des films cachés
              if ($film->getDate_theater() < $date_hide)
              {
                if ($i % 2 == 0)
                  echo '<tr class="ligne_tableau_movie_house">';
                else
                  echo '<tr class="ligne_tableau_movie_house_2">';
                  // Nom du film
                  echo '<td class="table_titres">';
                    echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter" id="' . $film->getId() . '" class="link_film">' . $film->getFilm() . '</a>';
                  echo '</td>';

                  // Date de sortie cinéma
                  echo '<td class="table_dates">';
                    if (!empty($film->getDate_theater()))
                    {
                      if (isBlankDate($film->getDate_theater()))
                        echo 'N.C.';
                      else
                        echo formatDateForDisplay($film->getDate_theater());
                    }
                  echo '</td>';

                  // Fiche du film
                  echo '<td class="table_dates">';
                    if (!empty($film->getLink()))
                      echo '<a href="' . $film->getLink() . '" target="_blank"><img src="../../includes/icons/moviehouse/pellicule.png" alt="pellicule" title="Fiche du film" class="logo_tableau_films" /></a>';
                  echo '</td>';

                  // Bande-annonce
                  echo '<td class="table_dates">';
                    if (!empty($film->getTrailer()))
                      echo '<a href="' . $film->getTrailer() . '" target="_blank"><img src="../../includes/icons/moviehouse/youtube.png" alt="youtube" title="Bande-annonce du film" class="logo_tableau_films" /></a>';
                  echo '</td>';

                  // Lien Doodle
                  echo '<td class="table_dates">';
                    if (!empty($film->getDoodle()))
                      echo '<a href="' . $film->getDoodle() . '" target="_blank"><img src="../../includes/icons/moviehouse/doodle.png" alt="doodle" title="Lien Doodle" class="logo_tableau_films" /></a>';
                    else
                      echo '<a href="https://doodle.com/fr/" onclick="location.href=\'saisie.php?update_id=' . $film->getId() . '&action=goModifier\';" target="_blank"><img src="../../includes/icons/moviehouse/doodle_grey.png" alt="doodle_grey" title="Doodle" class="logo_tableau_films" /></a>';
                  echo '</td>';

                  // Date de sortie proposée
                  echo '<td class="table_dates">';
                    if (!empty($film->getDate_doodle()))
                      echo formatDateForDisplay($film->getDate_doodle());
                  echo '</td>';

                  // Commentaires
                  echo '<td class="table_dates">';
                    echo $film->getNb_comments();
                  echo '</td>';

                  // Nombre de personnes intéressées
                  echo '<td class="table_dates">';
                    echo $film->getNb_users();
                  echo '</td>';

                  // Etoiles utilisateur + couleur de participation/vue
                  if ($film->getParticipation() == "S")
                    echo '<td class="table_users" style="background-color: #74cefb;">';
                  elseif ($film->getParticipation() == "P")
                    echo '<td class="table_users" style="background-color: #91d784;">';
                  else
                    echo '<td class="table_users">';

                      echo '<a onclick="afficherMasquer(\'preference[' . $film->getId() . ']\'); afficherMasquer(\'preference2[' . $film->getId() . ']\');" id="preference[' . $film->getId() . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
                        echo '<img src="../../includes/icons/moviehouse/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="star" />';
                      echo '</a>';

                      echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $film->getId() . '&action=doVoterFilm" id="preference2[' . $film->getId() . ']" style="display: none; min-width: 240px; padding-top: 10px; padding-bottom: 10px;">';
                        // Boutons vote
                        for ($j = 0; $j <= 5; $j++)
                        {
                          echo '<img src="../../includes/icons/moviehouse/stars/star' . $j .'.png" alt="star' . $j . '" class="star_2" />';

                          if ($j == $film->getStars_user())
                            echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="border-bottom: solid 3px #c81932;" />';
                          else
                            echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
                        }

                        // Bouton annulation
                        echo '<a onclick="afficherMasquer(\'preference[' . $film->getId() . ']\'); afficherMasquer(\'preference2[' . $film->getId() . ']\');" title="Annuler" class="link_vote">';
                          echo '<img src="../../includes/icons/moviehouse/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
                        echo '</a>';
                      echo '</form>';
                  echo '</td>';

                  // Actions
                  echo '<td class="table_dates">';
                    if ($film->getStars_user() != 0)
                    {
                      echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $film->getId() . '&action=doParticiperFilm" class="form_not_interested">';
                        // Je participe
                        echo '<input type="submit" name="participate" value="" title="Je participe !" class="participate" />';
                        // J'ai vu
                        echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="seen" />';
                      echo '</form>';
                    }

                    // Mailing
                    if ($film->getNb_users() > 0)
                    {
                      echo '<a href="mailing.php?id_film=' . $film->getId() . '&action=goConsulter">';
                        echo '<img src="../../includes/icons/moviehouse/mailing_red.png" alt="mailing_red" title="Envoyer mail" class="mailing" />';
                      echo '</a>';
                    }
                  echo '</td>';
                echo '</tr>';

                $i++;
              }
              else
                break;
            }

            // Fin films cachés
            echo '<tr class="hidden_films">';
              echo '<td colspan="10">';
                echo '<a onclick="afficherMasquerTbody(\'hidden_films\', \'show_hidden\');" class="show_hidden_films"><div class="symbol_hidden">-</div> Films cachés</a>';
              echo '</td>';
            echo '</tr>';
          echo '</tbody>';
        }
      }

      // Affichage films non cachés
      foreach ($listeFilms as $film)
      {
        // On affiche la date du jour
  			if (date("Y") == $_GET['year'])
  			{
  				if ($film->getDate_theater() >= $date_jour AND $date_jour_present == false AND $preferences->getToday_movie_house() == "Y")
  				{
            echo '<tr class="ligne_tableau_movie_house">';
              echo '<td class="table_date_jour" colspan="10">';
                echo '<div class="banderole_left_1"></div><div class="banderole_left_2"></div>';
                echo 'Aujourd\'hui, le ' . date("d/m/Y");
                echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
              echo '</td>';
            echo '</tr>';

  					$date_jour_present = true;
  				}
  			}

        // Affichage en fonction des préférences utilisateur
        if ($affichage == "T" OR $_GET['year'] != date("Y") OR ($affichage != "T" AND isset($date_hide) AND $film->getDate_theater() >= $date_hide))
        {
          if ($i % 2 == 0)
            echo '<tr class="ligne_tableau_movie_house">';
          else
            echo '<tr class="ligne_tableau_movie_house_2">';
            // Nom du film
            echo '<td class="table_titres">';
              echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter" id="' . $film->getId() . '" class="link_film">' . $film->getFilm() . '</a>';
            echo '</td>';

            // Date de sortie cinéma
            echo '<td class="table_dates">';
              if (!empty($film->getDate_theater()))
              {
                if (isBlankDate($film->getDate_theater()))
                  echo 'N.C.';
                else
                  echo formatDateForDisplay($film->getDate_theater());
              }
            echo '</td>';

            // Fiche du film
            echo '<td class="table_dates">';
              if (!empty($film->getLink()))
                echo '<a href="' . $film->getLink() . '" target="_blank"><img src="../../includes/icons/moviehouse/pellicule.png" alt="pellicule" title="Fiche du film" class="logo_tableau_films" /></a>';
            echo '</td>';

            // Bande-annonce
            echo '<td class="table_dates">';
              if (!empty($film->getTrailer()))
                echo '<a href="' . $film->getTrailer() . '" target="_blank"><img src="../../includes/icons/moviehouse/youtube.png" alt="youtube" title="Bande-annonce du film" class="logo_tableau_films" /></a>';
            echo '</td>';

            // Lien Doodle
            echo '<td class="table_dates">';
              if (!empty($film->getDoodle()))
                echo '<a href="' . $film->getDoodle() . '" target="_blank"><img src="../../includes/icons/moviehouse/doodle.png" alt="doodle" title="Lien Doodle" class="logo_tableau_films" /></a>';
              else
                echo '<a href="https://doodle.com/fr/" onclick="location.href=\'saisie.php?update_id=' . $film->getId() . '&action=goModifier\';" target="_blank"><img src="../../includes/icons/moviehouse/doodle_grey.png" alt="doodle_grey" title="Doodle" class="logo_tableau_films" /></a>';
            echo '</td>';

            // Date de sortie proposée
            echo '<td class="table_dates">';
              if (!empty($film->getDate_doodle()))
                echo formatDateForDisplay($film->getDate_doodle());
            echo '</td>';

            // Commentaires
            echo '<td class="table_dates">';
              echo $film->getNb_comments();
            echo '</td>';

            // Nombre de personnes intéressées
            echo '<td class="table_dates">';
              echo $film->getNb_users();
            echo '</td>';

            // Etoiles utilisateur + couleur de participation/vue
            if ($film->getParticipation() == "S")
              echo '<td class="table_users" style="background-color: #74cefb;">';
            elseif ($film->getParticipation() == "P")
              echo '<td class="table_users" style="background-color: #91d784;">';
            else
              echo '<td class="table_users">';

                echo '<a onclick="afficherMasquer(\'preference[' . $film->getId() . ']\'); afficherMasquer(\'preference2[' . $film->getId() . ']\');" id="preference[' . $film->getId() . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
                  echo '<img src="../../includes/icons/moviehouse/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="star" />';
                echo '</a>';

                echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $film->getId() . '&action=doVoterFilm" id="preference2[' . $film->getId() . ']" style="display: none; min-width: 240px; padding-top: 10px; padding-bottom: 10px;">';
                  // Boutons vote
                  for ($j = 0; $j <= 5; $j++)
                  {
                    echo '<img src="../../includes/icons/moviehouse/stars/star' . $j .'.png" alt="star' . $j . '" class="star_2" />';

                    if ($j == $film->getStars_user())
                      echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="border-bottom: solid 3px #c81932;" />';
                    else
                      echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
                  }

                  // Bouton annulation
                  echo '<a onclick="afficherMasquer(\'preference[' . $film->getId() . ']\'); afficherMasquer(\'preference2[' . $film->getId() . ']\');" title="Annuler" class="link_vote">';
                    echo '<img src="../../includes/icons/moviehouse/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
                  echo '</a>';
                echo '</form>';
            echo '</td>';

            // Actions
    				echo '<td class="table_dates">';
    					if ($film->getStars_user() != 0)
    					{
    						echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $film->getId() . '&action=doParticiperFilm" class="form_not_interested">';
    							// Je participe
    							echo '<input type="submit" name="participate" value="" title="Je participe !" class="participate" />';
    							// J'ai vu
    							echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="seen" />';
    						echo '</form>';
    					}

              // Mailing
              if ($film->getNb_users() > 0)
              {
                echo '<a href="mailing.php?id_film=' . $film->getId() . '&action=goConsulter">';
                  echo '<img src="../../includes/icons/moviehouse/mailing_red.png" alt="mailing_red" title="Envoyer mail" class="mailing" />';
                echo '</a>';
              }
    				echo '</td>';
          echo '</tr>';

          $i++;
        }
      }

      // Date du jour dernière ligne
      if (date("Y") == $_GET['year'])
      {
        if ($date_jour_present == false AND $preferences->getToday_movie_house() == "Y")
        {
          echo '<tr class="ligne_tableau_movie_house">';
            echo '<td class="table_date_jour" colspan="10">';
              echo 'Aujourd\'hui, le ' . date("d/m/Y");
              echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
            echo '</td>';
          echo '</tr>';

          $date_jour_present = true;
        }
      }

      // Titres du tableau
      echo '<tr>';
        echo '<td class="table_titres" style="border: 0; border-top: 1px solid #e3e3e3;"></td>';
        echo '<td class="init_table_dates">Date de sortie</td>';
        echo '<td class="init_table_dates">Fiche</td>';
        echo '<td class="init_table_dates">Bande-annonce</td>';
        echo '<td class="init_table_dates">Doodle</td>';
        echo '<td class="init_table_dates">Date proposée</td>';
        echo '<td class="init_table_dates">Commentaires</td>';
        echo '<td class="init_table_dates">Personnes intéressées</td>';
        echo '<td class="init_table_dates">Vote</td>';
        echo '<td class="init_table_dates">Actions</td>';
      echo '</tr>';
      echo '</table>';
    }
    else
      echo '<p class="no_films">Pas encore de films pour cette année...</p>';

  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide" style="margin-bottom: 0px;">';
    echo '<table class="table_saisie_rapide">';
      echo '<tr>';
        echo '<td class="td_saisie_film">';
          echo '<input type="text" name="nom_film" value="' . $_SESSION['save']['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
        echo '</td>';

        echo '<td class="td_saisie_date">';
          echo '<input type="text" name="date_theater" value="' . $_SESSION['save']['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" autocomplete="off" id="datepicker_sortie_2" class="date_saisie_rapide" />';
        echo '</td>';

        echo '<td class="td_saisie_ajouter">';
          echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
        echo '</td>';
      echo '</tr>';
    echo '</table>';
  echo '</form>';
?>
