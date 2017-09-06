<?php
  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide">';
    echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
    echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker" class="date_saisie_rapide" />';
    echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
  echo '</form>';

  /************************/
	/* Tableau vue générale */
	/************************/
  if ($anneeExistante == true AND !empty($tableauFilms))
  {
    echo '<table class="table_movie_house">';
      echo '<tr>';
        echo '<td class="table_titres" style="border: 0;"></td>';
        echo '<td class="init_table_dates" style="width: 120px;">Date de sortie</td>';
        foreach ($listeUsers as $user)
        {
          echo '<td class="init_table_users">';
  					echo '<div class="zone_avatar_films">';
  						if (!empty($user->getAvatar()))
  							echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getFull_name() . '" class="avatar_films" />';
  						else
  							echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getFull_name() . '" class="avatar_films" />';
  					echo '</div>';

  					echo '<span class="full_name_films">' . $user->getFull_name() . '</span>';
  				echo '</td>';
        }
      echo '</tr>';

      $date_jour = date("Ymd");
      $date_jour_present = false;
      $i = 0;

      foreach ($tableauFilms as $ligneFilm)
      {
        // On affiche la date du jour
        if (date("Y") == $_GET['year'])
        {
          if ($ligneFilm['date_theater'] >= $date_jour AND $date_jour_present == false AND $preferences->getToday_movie_house() == "Y")
          {
            echo '<tr class="ligne_tableau_movie_house">';
              echo '<td class="table_date_jour" colspan="100%">Aujourd\'hui, le ' . date("d/m/Y") . '</td>';
            echo '</tr>';

            $date_jour_present = true;
          }
        }

        // Liste des films
        echo '<tr class="ligne_tableau_movie_house">';
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
            if ($stars['identifiant'] == $_SESSION['identifiant'])
              echo '<td class="table_users" style="background-color: #fffde8;">';
            elseif ($stars['participation'] == "S")
              echo '<td class="table_users" style="background-color: #74cefb;">';
            elseif ($stars['participation'] == "P")
              echo '<td class="table_users" style="background-color: #91d784;">';
            else
              echo '<td class="table_users">';
                if ($stars['identifiant'] == $_SESSION['identifiant'])
                {
                  echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
                    echo '<img src="icons/stars/star' . $stars['stars'] . '.png" alt="star' . $stars['stars'] . '" class="new_star" />';
                  echo '</a>';

                  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $ligneFilm['id_film'] . '&action=doVoterFilm" id="preference2[' . $i . ']" style="display: none; min-width: 240px;">';
                    // Boutons vote
                    for($j = 0; $j <= 5; $j++)
                    {
                      echo '<img src="icons/stars/star' . $j .'.png" alt="star' . $j . '" class="new_star_2" />';
                      if ($j == $stars['stars'])
                        echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="padding-bottom: 8px; border-bottom: solid 3px rgb(200, 25, 50);" />';
                      else
                        echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
                    }

                    // Bouton annulation
                    echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Annuler" class="link_vote">';
                      echo '<img src="icons/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
                    echo '</a>';
                  echo '</form>';
                }
                else
                {
                  echo '<img src="icons/stars/star' . $stars['stars'] . '.png" alt="star' . $stars['stars'] . '" class="new_star" />';
                }
            echo '</td>';
          }
        echo '</tr>';

        $i++;
      }

      echo '<tr>';
        echo '<td class="table_titres" style="border: 0;"></td>';
        echo '<td class="init_table_dates" style="width: 120px;">Date de sortie</td>';
        foreach ($listeUsers as $user)
        {
          echo '<td class="init_table_users">';
            echo '<div class="zone_avatar_films">';
              if (!empty($user->getAvatar()))
                echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getFull_name() . '" class="avatar_films" />';
              else
                echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getFull_name() . '" class="avatar_films" />';
            echo '</div>';

            echo '<span class="full_name_films">' . $user->getFull_name() . '</span>';
          echo '</td>';
        }
      echo '</tr>';
    echo '</table>';
  }
  else
    echo '<p class="wrong_date">Pas encore de films pour cette année...</p>';

  // Saisie rapide
  echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doSaisieRapide" class="form_saisie_rapide" style="margin-bottom: 0px;">';
    echo '<input type="text" name="nom_film" value="' . $_SESSION['nom_film_saisi'] . '" placeholder="Nom du film" maxlength="255" class="name_saisie_rapide" required />';
    echo '<input type="text" name="date_theater" value="' . $_SESSION['date_theater_saisie'] . '" placeholder="Date de sortie cinéma (jj/mm/yyyy)" maxlength="10" id="datepicker2" class="date_saisie_rapide" />';
    echo '<input type="submit" name="saisie_rapide" value="Ajouter à la liste" class="add_saisie_rapide" />';
  echo '</form>';
?>

<script type="text/javascript">
  function afficherMasquer(id)
  {
    if (document.getElementById(id).style.display == "none")
      document.getElementById(id).style.display = "block";
    else
      document.getElementById(id).style.display = "none";
  }
</script>
