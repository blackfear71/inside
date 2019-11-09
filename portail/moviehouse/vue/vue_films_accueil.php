<?php
  echo '<div class="zone_home">';
    /******************/
    /* Ajouts récents */
    /******************/
    echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/recent_grey.png" alt="recent_grey" class="logo_titre_section" /><div class="texte_titre_section">Les derniers films ajoutés en ' . $_GET['year'] . '</div></div>';

    if (!empty($listeRecents))
    {
      echo '<div class="zone_films_accueil">';
        foreach ($listeRecents as $filmRecent)
        {
          echo '<a href="details.php?id_film=' . $filmRecent->getId() . '&action=goConsulter" class="zone_film_accueil">';
            // Poster
            if (!empty($filmRecent->getPoster()))
              echo '<img src="' . $filmRecent->getPoster() . '" alt="poster" title="' . $filmRecent->getFilm() . '" class="image_accueil" />';
            else
              echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmRecent->getFilm() . '" class="image_accueil" />';

            // Titre du film
            echo '<div class="titre_film_accueil">' . $filmRecent->getFilm() . '</div>';
          echo '</a>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas de films encore ajoutés pour cette année...</div>';

    /*********************/
    /* Les plus attendus */
    /*********************/
    if ($films_waited == "Y")
    {
      echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/waited_grey.png" alt="waited_grey" class="logo_titre_section" /><div class="texte_titre_section">Les films les plus attendus en ' . $_GET['year'] . '</div></div>';

      if (!empty($listeAttendus))
      {
        echo '<div class="zone_films_accueil">';
          foreach ($listeAttendus as $filmAttendu)
          {
            echo '<a href="details.php?id_film=' . $filmAttendu->getId() . '&action=goConsulter" class="zone_film_accueil">';
              // Poster
              if (!empty($filmAttendu->getPoster()))
                echo '<img src="' . $filmAttendu->getPoster() . '" alt="poster" title="' . $filmAttendu->getFilm() . '" class="image_accueil" />';
              else
                echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmAttendu->getFilm() . '" class="image_accueil" />';

              // Titre du film
              echo '<div class="titre_film_accueil">' . $filmAttendu->getFilm() . '</div>';

              // Nombre d'utilisateurs intéressés et moyenne des étoiles
              echo '<div class="zone_icones_accueil">';
                echo '<span class="users_interested"><img src="../../includes/icons/moviehouse/users.png" alt="users" class="icone_accueil" />' . $filmAttendu->getNb_users() . '</span>';
                echo '<span class="average_star"><img src="../../includes/icons/moviehouse/star.png" alt="star" class="icone_accueil" />' . $filmAttendu->getAverage() . ' / 5</span>';
              echo '</div>';
            echo '</a>';
          }
        echo '</div>';
      }
      else
        echo '<div class="empty">Pas de films encore attendus pour cette année...</div>';
    }

    /**************************/
    /* Les prochaines sorties */
    /**************************/
    if ($films_way_out == "Y")
    {
      if ($_GET['year'] >= date("Y"))
        echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/way_out_grey.png" alt="way_out_grey" class="logo_titre_section" /><div class="texte_titre_section">Les prochaines sorties organisées en ' . $_GET['year'] . '</div></div>';
      else
        echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/way_out_grey.png" alt="way_out_grey" class="logo_titre_section" /><div class="texte_titre_section">Les anciennes sorties organisées en ' . $_GET['year'] . '</div></div>';

      if (!empty($listeSorties))
      {
        echo '<div class="zone_films_accueil">';
          foreach ($listeSorties as $filmSortie)
          {
            echo '<a href="details.php?id_film=' . $filmSortie->getId() . '&action=goConsulter" class="zone_film_accueil">';
              // Poster
              if (!empty($filmSortie->getPoster()))
                echo '<img src="' . $filmSortie->getPoster() . '" alt="poster" title="' . $filmSortie->getFilm() . '" class="image_accueil" />';
              else
                echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmSortie->getFilm() . '" class="image_accueil" />';

              // Titre du film
              echo '<div class="titre_film_accueil">' . $filmSortie->getFilm() . '</div>';

              // Date de sortie
              echo '<div class="zone_icones_accueil">';
                echo '<span class="average_star"><img src="../../includes/icons/moviehouse/date.png" alt="date" class="icone_accueil" />Sortie le ' . formatDateForDisplay($filmSortie->getDate_doodle()) . '</span>';
              echo '</div>';
            echo '</a>';
          }
        echo '</div>';
      }
      else
        echo '<div class="empty">Pas de sorties prévues prochainement...</div>';
    }
  echo '</div>';
?>
