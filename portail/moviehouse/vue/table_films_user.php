<?php
  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide">';
    echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
    echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="date_saisie_rapide" />';
    echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
  echo '</form>';

	/***************************/
	/* Tableau vue utilisateur */
	/***************************/
  if ($anneeExistante == true AND !empty($listeFilms))
  {
  	echo '<table class="table_movie_house" style="border-bottom: solid 1px #e3e3e3;">';
  		// Titres du tableau
  		echo '<tr>';
  			echo '<td class="table_titres" style="border: 0; border-bottom: 1px solid #e3e3e3;"></td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Date de sortie</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Fiche</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Bande-annonce</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Doodle</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Date proposée</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Commentaires</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Vote</td>';
  			echo '<td class="init_table_dates" style="width: 120px;">Actions</td>';
  		echo '</tr>';

      $date_jour = date("Ymd");
      $date_jour_present = false;
      $i = 0;

      foreach($listeFilms as $film)
      {
        // On affiche la date du jour
  			if (date("Y") == $_GET['year'])
  			{
  				if ($film->getDate_theater() >= $date_jour AND $date_jour_present == false AND $preferences->getToday_movie_house() == "Y")
  				{
            echo '<tr class="ligne_tableau_movie_house">';
              echo '<td class="table_date_jour" colspan="100%">';
                echo '<div class="banderole_left_1"></div><div class="banderole_left_2"></div>';
                echo 'Aujourd\'hui, le ' . date("d/m/Y");
                echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
              echo '</td>';
            echo '</tr>';

  					$date_jour_present = true;
  				}
  			}

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
              echo '<a href="' . $film->getLink() . '" target="_blank"><img src="icons/pellicule.png" alt="pellicule" title="Fiche du film" class="logo_tableau_films" /></a>';
          echo '</td>';

          // Bande-annonce
          echo '<td class="table_dates">';
            if (!empty($film->getTrailer()))
              echo '<a href="' . $film->getTrailer() . '" target="_blank"><img src="icons/youtube.png" alt="youtube" title="Bande-annonce du film" class="logo_tableau_films" /></a>';
          echo '</td>';

          // Lien Doodle
          echo '<td class="table_dates">';
            if (!empty($film->getDoodle()))
              echo '<a href="' . $film->getDoodle() . '" target="_blank"><img src="icons/doodle.png" alt="doodle" title="Lien Doodle" class="logo_tableau_films" /></a>';
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

          // Etoiles utilisateur + couleur de participation/vue
          if ($film->getParticipation() == "S")
            echo '<td class="table_users" style="background-color: #74cefb;">';
          elseif ($film->getParticipation() == "P")
            echo '<td class="table_users" style="background-color: #91d784;">';
          else
            echo '<td class="table_users">';

              echo '<a onclick="afficherMasquer(\'preference[' . $film->getId() . ']\'); afficherMasquer(\'preference2[' . $film->getId() . ']\');" id="preference[' . $film->getId() . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
                echo '<img src="icons/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="new_star" />';
              echo '</a>';

              echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $film->getId() . '&action=doVoterFilm" id="preference2[' . $film->getId() . ']" style="display: none; min-width: 240px; padding-top: 10px; padding-bottom: 10px;">';
                // Boutons vote
                for($j = 0; $j <= 5; $j++)
                {
                  echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="new_star_2" />';

                  if ($j == $film->getStars_user())
                    echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="border-bottom: solid 3px rgb(200, 25, 50);" />';
                  else
                    echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
                }

                // Bouton annulation
                echo '<a onclick="afficherMasquer(\'preference[' . $film->getId() . ']\'); afficherMasquer(\'preference2[' . $film->getId() . ']\');" id="preference[' . $film->getId() . ']" title="Annuler" class="link_vote">';
                  echo '<img src="icons/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
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
                echo '<img src="icons/mailing_red.png" alt="mailing_red" title="Envoyer mail" class="mailing" />';
              echo '</a>';
            }
  				echo '</td>';
        echo '</tr>';

        $i++;
      }
	     echo '</table>';
    }
    else
      echo '<p class="no_films">Pas encore de films pour cette année...</p>';

  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide" style="margin-bottom: 0px;">';
    echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
    echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="date_saisie_rapide" />';
    echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
  echo '</form>';
?>
