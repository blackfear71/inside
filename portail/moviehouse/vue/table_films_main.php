<?php
  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide">';
    echo '<table class="table_saisie_rapide">';
      echo '<tr>';
        echo '<td class="td_saisie_film">';
          echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
        echo '</td>';

        echo '<td class="td_saisie_date">';
          echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="date_saisie_rapide" />';
        echo '</td>';

        echo '<td class="td_saisie_ajouter">';
          echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
        echo '</td>';
      echo '</tr>';
    echo '</table>';
  echo '</form>';

  /************************/
	/* Tableau vue générale */
	/************************/
  if ($anneeExistante == true AND !empty($tableauFilms))
  {
    echo '<table class="table_movie_house">';
      // Entete tableau
      echo '<tr>';
        echo '<td class="table_titres" style="border: 0; border-bottom: 1px solid #e3e3e3;"></td>';
        echo '<td class="init_table_dates">Date de sortie</td>';
        foreach ($listeUsers as $user)
        {
          echo '<td class="init_table_users">';
  					echo '<div class="zone_avatar_films">';
  						if (!empty($user->getAvatar()))
  							echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_films" />';
  						else
  							echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_films" />';
  					echo '</div>';

  					echo '<div class="pseudo_films">' . $user->getPseudo() . '</div>';
  				echo '</td>';
        }
      echo '</tr>';

      $date_jour         = date("Ymd");
      $date_jour_present = false;
      $i                 = 0;

      // Calcul durée à partir de laquelle cacher les films (si on n'affiche pas tous les films)
      list($affichage, $type, $duree) = explode(";", $preferences->getView_old_movies());

      if ($_GET['year'] == date("Y") AND $affichage != "T")
      {
        $date_hide = dureeOldMovies($type, $duree);

        // Films cachés en fonction de la préférence utilisateur
        echo '<tr class="hidden_films">';
          echo '<td colspan="100%">';
            echo '<a onclick="afficherMasquerTbody(\'hidden_films\', \'show_hidden\');" id="show_hidden" class="show_hidden_films"><div class="symbol_hidden">+</div> Films cachés</a>';
          echo '</td>';
        echo '</tr>';

        echo '<tbody id="hidden_films" style="display: none;">';
          foreach ($tableauFilms as $ligneFilm)
          {
            if ($ligneFilm['date_theater'] < $date_hide)
            {
              // Liste des films cachés
              if ($i % 2 == 0)
                echo '<tr class="ligne_tableau_movie_house">';
              else
                echo '<tr class="ligne_tableau_movie_house_2">';
                // Noms des films sur la 1ère colonne
                echo '<td class="table_titres">';
                  echo '<a href="details.php?id_film=' . $ligneFilm['id_film'] . '&action=goConsulter" id="' . $ligneFilm['id_film'] . '" class="link_film">' . $ligneFilm['film'] . '</a>';
                echo '</td>';

                // Date de sortie des films sur la 2ème colonne
        				echo '<td class="table_dates">';
        					if (!empty($ligneFilm['date_theater']))
        					{
        						if (isBlankDate($ligneFilm['date_theater']))
        							echo 'N.C.';
        						else
        							echo formatDateForDisplay($ligneFilm['date_theater']);
        					}
        				echo '</td>';

                // Etoiles utilisateurs
                foreach ($ligneFilm['tableStars'] as $stars)
                {
                  if ($stars['identifiant'] == $_SESSION['identifiant'] AND $stars['participation'] != "S" AND $stars['participation'] != "P")
                    echo '<td class="table_users" style="background-color: #fffde8;">';
                  elseif ($stars['participation'] == "S")
                    echo '<td class="table_users" style="background-color: #74cefb;">';
                  elseif ($stars['participation'] == "P")
                    echo '<td class="table_users" style="background-color: #91d784;">';
                  else
                    echo '<td class="table_users">';
                      if ($stars['identifiant'] == $_SESSION['identifiant'])
                      {
                        echo '<a onclick="afficherMasquer(\'preference[' . $ligneFilm['id_film'] . ']\'); afficherMasquer(\'preference2[' . $ligneFilm['id_film'] . ']\');" id="preference[' . $ligneFilm['id_film'] . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
                          echo '<img src="icons/stars/star' . $stars['stars'] . '.png" alt="star' . $stars['stars'] . '" class="star" />';
                        echo '</a>';

                        echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $ligneFilm['id_film'] . '&action=doVoterFilm" id="preference2[' . $ligneFilm['id_film'] . ']" style="display: none; min-width: 240px; padding-top: 10px; padding-bottom: 10px;">';
                          // Boutons vote
                          for ($j = 0; $j <= 5; $j++)
                          {
                            echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="star_2" />';
                            if ($j == $stars['stars'])
                              echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="border-bottom: solid 3px rgb(200, 25, 50);" />';
                            else
                              echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
                          }

                          // Bouton annulation
                          echo '<a onclick="afficherMasquer(\'preference[' . $ligneFilm['id_film'] . ']\'); afficherMasquer(\'preference2[' . $ligneFilm['id_film'] . ']\');" id="preference[' . $ligneFilm['id_film'] . ']" title="Annuler" class="link_vote">';
                            echo '<img src="icons/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
                          echo '</a>';
                        echo '</form>';
                      }
                      else
                      {
                        echo '<span class="link_vote" style="cursor: default;">';
                          echo '<img src="icons/stars/star' . $stars['stars'] . '.png" alt="star' . $stars['stars'] . '" class="star" />';
                        echo '</span>';
                      }
                  echo '</td>';
                }
              echo '</tr>';

              $i++;
            }
            else
              break;
          }

          // Fin films cachés
          echo '<tr class="hidden_films">';
            echo '<td colspan="100%">';
              echo '<a onclick="afficherMasquerTbody(\'hidden_films\', \'show_hidden\');" class="show_hidden_films"><div class="symbol_hidden">-</div> Films cachés</a>';
            echo '</td>';
          echo '</tr>';
        echo '</tbody>';
      }

      foreach ($tableauFilms as $ligneFilm)
      {
        // On affiche la date du jour
        if (date("Y") == $_GET['year'])
        {
          if ($ligneFilm['date_theater'] >= $date_jour AND $date_jour_present == false AND $preferences->getToday_movie_house() == "Y")
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

        // Affichage en fonction des préférences utilisateur
        if ($affichage == "T" OR $_GET['year'] != date("Y") OR ($affichage != "T" AND isset($date_hide) AND $ligneFilm['date_theater'] >= $date_hide))
        {
          // Liste des films
          if ($i % 2 == 0)
            echo '<tr class="ligne_tableau_movie_house">';
          else
            echo '<tr class="ligne_tableau_movie_house_2">';
            // Noms des films sur la 1ère colonne
            echo '<td class="table_titres">';
              echo '<a href="details.php?id_film=' . $ligneFilm['id_film'] . '&action=goConsulter" id="' . $ligneFilm['id_film'] . '" class="link_film">' . $ligneFilm['film'] . '</a>';
            echo '</td>';

            // Date de sortie des films sur la 2ème colonne
    				echo '<td class="table_dates">';
    					if (!empty($ligneFilm['date_theater']))
    					{
    						if (isBlankDate($ligneFilm['date_theater']))
    							echo 'N.C.';
    						else
    							echo formatDateForDisplay($ligneFilm['date_theater']);
    					}
    				echo '</td>';

            // Etoiles utilisateurs
            foreach ($ligneFilm['tableStars'] as $stars)
            {
              if ($stars['identifiant'] == $_SESSION['identifiant'] AND $stars['participation'] != "S" AND $stars['participation'] != "P")
                echo '<td class="table_users" style="background-color: #fffde8;">';
              elseif ($stars['participation'] == "S")
                echo '<td class="table_users" style="background-color: #74cefb;">';
              elseif ($stars['participation'] == "P")
                echo '<td class="table_users" style="background-color: #91d784;">';
              else
                echo '<td class="table_users">';
                  if ($stars['identifiant'] == $_SESSION['identifiant'])
                  {
                    echo '<a onclick="afficherMasquer(\'preference[' . $ligneFilm['id_film'] . ']\'); afficherMasquer(\'preference2[' . $ligneFilm['id_film'] . ']\');" id="preference[' . $ligneFilm['id_film'] . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
                      echo '<img src="icons/stars/star' . $stars['stars'] . '.png" alt="star' . $stars['stars'] . '" class="star" />';
                    echo '</a>';

                    echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $ligneFilm['id_film'] . '&action=doVoterFilm" id="preference2[' . $ligneFilm['id_film'] . ']" style="display: none; min-width: 240px; padding-top: 10px; padding-bottom: 10px;">';
                      // Boutons vote
                      for ($j = 0; $j <= 5; $j++)
                      {
                        echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="star_2" />';
                        if ($j == $stars['stars'])
                          echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="border-bottom: solid 3px rgb(200, 25, 50);" />';
                        else
                          echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
                      }

                      // Bouton annulation
                      echo '<a onclick="afficherMasquer(\'preference[' . $ligneFilm['id_film'] . ']\'); afficherMasquer(\'preference2[' . $ligneFilm['id_film'] . ']\');" id="preference[' . $ligneFilm['id_film'] . ']" title="Annuler" class="link_vote">';
                        echo '<img src="icons/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
                      echo '</a>';
                    echo '</form>';
                  }
                  else
                  {
                    echo '<span class="link_vote" style="cursor: default;">';
                      echo '<img src="icons/stars/star' . $stars['stars'] . '.png" alt="star' . $stars['stars'] . '" class="star" />';
                    echo '</span>';
                  }
              echo '</td>';
            }
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
            echo '<td class="table_date_jour" colspan="100%"">';
              echo 'Aujourd\'hui, le ' . date("d/m/Y");
              echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
            echo '</td>';
          echo '</tr>';

          $date_jour_present = true;
        }
      }

      // Pied du tableau
      echo '<tr>';
        echo '<td class="table_titres" style="border: 0; border-top: 1px solid #e3e3e3;"></td>';
        echo '<td class="init_table_dates">Date de sortie</td>';
        foreach ($listeUsers as $user)
        {
          echo '<td class="init_table_users">';
            echo '<div class="zone_avatar_films">';
              if (!empty($user->getAvatar()))
                echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_films" />';
              else
                echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_films" />';
            echo '</div>';

            echo '<div class="pseudo_films">' . $user->getPseudo() . '</div>';
          echo '</td>';
        }
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
          echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
        echo '</td>';

        echo '<td class="td_saisie_date">';
          echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="date_saisie_rapide" />';
        echo '</td>';

        echo '<td class="td_saisie_ajouter">';
          echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
        echo '</td>';
      echo '</tr>';
    echo '</table>';
  echo '</form>';
?>
