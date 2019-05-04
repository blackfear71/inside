<?php
  echo '<div class="zone_films">';
    /****************/
    /* Fiches films */
    /****************/
    echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" />';
      echo 'Les films de ' . $_GET['year'];

      echo '<div class="zone_actions">';
        if (!empty($listeFilms))
        {
          echo '<a onclick="afficherMasquerFilms(\'fold_all\')" class="bouton_fold">Tout plier</a>';
          echo '<a onclick="afficherMasquerFilms(\'unfold_all\')" class="bouton_fold">Tout déplier</a>';
        }
      echo '</div>';
    echo '</div>';

    $prevMonth  = "";
    $listMonths = array("01" => "Janvier",
                        "02" => "Février",
                        "03" => "Mars",
                        "04" => "Avril",
                        "05" => "Mai",
                        "06" => "Juin",
                        "07" => "Juillet",
                        "08" => "Août",
                        "09" => "Septembre",
                        "10" => "Octobre",
                        "11" => "Novembre",
                        "12" => "Décembre",
                       );

    if (!empty($listeFilms))
    {
      foreach ($listeFilms as $keyFilm => $film)
      {
        $currentMonth = substr($film->getDate_theater(), 4, 2);

        if ($currentMonth != $prevMonth)
        {
          $prevMonth = $currentMonth;

          if ($currentMonth == date("m"))
            echo '<div class="titre_mois_films titre_bleu"><a id="lien_hide_' . $currentMonth . '" onclick="afficherMasquerFilms(\'' . $currentMonth . '\')" class="fond_hide">-</a>' . $listMonths[$currentMonth] . '</div>';
          else
            echo '<div class="titre_mois_films"><a id="lien_hide_' . $currentMonth . '" onclick="afficherMasquerFilms(\'' . $currentMonth . '\')" class="fond_hide">-</a>' . $listMonths[$currentMonth] . '</div>';
          echo '<div class="zone_fiches_films" id="hide_films_' . $currentMonth . '">';
        }

        echo '<div class="zone_fiche_film" id="' . $film->getId() . '">';

        if ($film->getParticipation() == "S")
          echo '<div id="zone_shadow_' . $film->getId() . '" class="zone_shadow border_radius border_blue">';
        elseif ($film->getParticipation() == "P")
          echo '<div id="zone_shadow_' . $film->getId() . '" class="zone_shadow border_radius border_green">';
        else
          echo '<div id="zone_shadow_' . $film->getId() . '" class="zone_shadow border_radius">';
            echo '<div class="zone_fiche_top">';
              // Poster
              echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter">';
                if (!empty($film->getPoster()))
                  echo '<img src="' . $film->getPoster() . '" alt="poster" title="' . $film->getFilm() . '" class="image_fiche" />';
                else
                  echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $film->getFilm() . '" class="image_fiche" />';
              echo '</a>';

              // Film
              echo '<div class="zone_fiche_infos">';
                // Titre et dates
                echo '<div class="zone_fiche_infos_top">';
                  // Titre
                  echo '<div class="titre_fiche">' . $film->getFilm() . '</div>';

                  // Date sortie cinéma
                  echo '<div class="date_fiche">';
                    echo '<img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" class="icone_fiche" />';

                    if (isBlankDate($film->getDate_theater()))
                      echo 'N.C.';
                    else
                      echo formatDateForDisplay($film->getDate_theater());
                  echo '</div>';

                  // Date sortie Doodle
                  if (!empty($film->getDate_doodle()))
                  {
                    echo '<div class="date_fiche">';
                      echo '<img src="../../includes/icons/moviehouse/doodle_grey.png" alt="doodle_grey" class="icone_fiche" />';
                      echo formatDateForDisplay($film->getDate_doodle());
                    echo '</div>';
                  }
                echo '</div>';

                // Liens
                echo '<div class="zone_fiche_infos_middle">';
                  // Lien film (Allociné...)
                  if (!empty($film->getLink()))
                    echo '<a href="' . $film->getLink() . '" target="_blank"><img src="../../includes/icons/moviehouse/pellicule.png" alt="pellicule" title="Fiche du film" class="icone_fiche_2" /></a>';

                  // Bande-annonce
                  if (!empty($film->getTrailer()))
                    echo '<a href="' . $film->getTrailer() . '" target="_blank"><img src="../../includes/icons/moviehouse/youtube.png" alt="youtube" title="Bande-annonce" class="icone_fiche_2" /></a>';

                  // Doodle
                  if (!empty($film->getDoodle()))
                    echo '<a href="' . $film->getDoodle() . '" target="_blank"><img src="../../includes/icons/moviehouse/doodle.png" alt="doodle" title="Lien Doodle" class="icone_fiche_2" /></a>';
                  else
                    echo '<a href="https://doodle.com/fr/" onclick="location.href=\'details.php?id_film=' . $film->getId() . '&doodle=true&action=goConsulter\';" target="_blank"><img src="../../includes/icons/moviehouse/doodle_none.png" alt="doodle_none" title="Doodle" class="icone_fiche_2" /></a>';

                  // Mail
                  if ($film->getNb_users() > 0)
                  {
                    echo '<a href="mailing.php?id_film=' . $film->getId() . '&action=goConsulter">';
                      echo '<img src="../../includes/icons/moviehouse/mailing_red.png" alt="mailing_red" title="Envoyer mail" class="icone_fiche_2" />';
                    echo '</a>';
                  }

                  // Commentaires
                  echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter&anchor=comments" title="Nombre de commentaires" >';
                    echo '<img src="../../includes/icons/moviehouse/comments_grey.png" alt="comments_grey" class="icone_fiche_2" />';
                    echo '<div class="nb_commentaires_fiche">' . $film->getNb_comments() . '</div>';
                  echo '</a>';
                echo '</div>';

                // Actions
                echo '<div class="zone_fiche_infos_bottom">';
                  // Vote utilisateur
                  echo '<a onclick="afficherSaisiePreference(\'' . $film->getFilm() . '\', \'' . $film->getStars_user() . '\', \'' . $_GET['view'] . '\', \'' . $_GET['year'] . '\', \'' . $film->getId() . '\');" class="vote_fiche">';
                    echo '<img src="../../includes/icons/moviehouse/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="icone_fiche_3" />';
                  echo '</a>';

                  if ($film->getStars_user() != 0)
                  {
                    // Participation / vue
                    echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $film->getId() . '&action=doParticiperFilm" class="form_participation">';
                      // Je participe
                      echo '<input type="submit" name="participate" value="" title="Je participe !" class="input_participate" />';
                      // J'ai vu
                      echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="input_seen" />';
                    echo '</form>';
                  }
                echo '</div>';
              echo '</div>';

              // Choix utilisateurs
              if (!empty($listeEtoiles[$film->getId()]))
              {
                echo '<div class="zone_fiche_bottom">';
                  $previousStars = 0;

                  foreach ($listeEtoiles[$film->getId()] as $etoilesFilm)
                  {
                    if ($etoilesFilm['stars'] == $previousStars)
                    {
                      if (!empty($etoilesFilm['avatar']))
                        echo '<img src="../../includes/images/profil/avatars/' . $etoilesFilm['avatar'] . '" alt="avatar" title="' . $etoilesFilm['pseudo'] . '" class="avatar_fiche" />';
                      else
                        echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $etoilesFilm['pseudo'] . '" class="avatar_fiche" />';
                    }
                    else
                    {
                      if ($previousStars != 0)
                        echo '</div><div class="ligne_stars">';
                      else
                        echo '<div class="ligne_stars">';

                      echo '<img src="../../includes/icons/moviehouse/stars/star' . $etoilesFilm['stars'] . '.png" alt="star' . $etoilesFilm['stars'] . '" class="icone_fiche_4" />';

                      if (!empty($etoilesFilm['avatar']))
                        echo '<img src="../../includes/images/profil/avatars/' . $etoilesFilm['avatar'] . '" alt="avatar" title="' . $etoilesFilm['pseudo'] . '" class="avatar_fiche" />';
                      else
                        echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $etoilesFilm['pseudo'] . '" class="avatar_fiche" />';

                      $previousStars = $etoilesFilm['stars'];
                    }
                  }
                  echo '</div>';
                echo '</div>';
              }
            echo '</div>';
          echo '</div>';
        echo '</div>';

        // Termine la zone Masonry du mois
        if (!isset($listeFilms[$keyFilm + 1]) OR $currentMonth != substr($listeFilms[$keyFilm + 1]->getDate_theater(), 4, 2))
          echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas de films pour cette année...</div>';
  echo '</div>';
?>
