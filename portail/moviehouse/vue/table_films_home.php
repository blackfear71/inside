<div class="zone_home">
  <!-- Ajouts récents -->
  <div class="titre_home">
    <img src="../../includes/icons/moviehouse/recent.png" alt="recent" class="icone_home" />Ajouts récents
  </div>

  <div class="zone_home_films">
    <?php
      foreach ($listeRecents as $filmRecent)
      {
        echo '<a href="details.php?id_film=' . $filmRecent->getId() . '&action=goConsulter" class="link_home_film">';
          // Poster
          if (!empty($filmRecent->getPoster()))
            echo '<img src="' . $filmRecent->getPoster() . '" alt="poster" title="' . $filmRecent->getFilm() . '" class="img_home_film" />';
          else
            echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmRecent->getFilm() . '" class="img_home_film" />';

          // Titre du film
          echo '<div class="titre_home_film">';
            echo $filmRecent->getFilm();
          echo '</div>';
        echo '</a>';
      }
    ?>
  </div>

  <?php
    // Les plus attendus
    if ($films_waited == "Y")
    {
      echo '<div class="titre_home">';
        echo '<img src="../../includes/icons/moviehouse/waited.png" alt="recent" class="icone_home" />Les plus attendus en ' . $_GET['year'];
      echo '</div>';

      echo '<div class="zone_home_films">';
        if (!empty($listeAttendus))
        {
          foreach ($listeAttendus as $filmAttendu)
          {
            echo '<a href="details.php?id_film=' . $filmAttendu->getId() . '&action=goConsulter" class="link_home_film">';
              // Poster
              if (!empty($filmAttendu->getPoster()))
                echo '<img src="' . $filmAttendu->getPoster() . '" alt="poster" title="' . $filmAttendu->getFilm() . '" class="img_home_film" />';
              else
                echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmAttendu->getFilm() . '" class="img_home_film" />';

              // Titre du film
              echo '<div class="titre_home_film">';
                echo $filmAttendu->getFilm();
              echo '</div>';

              // Nombre d'utilisateurs intéressés et moyenne des étoiles
              echo '<div class="stats_home_film">';
                echo '<div class="stats_home_icon_1"></div>';
                echo '<div class="stats_home_number">' . $filmAttendu->getNb_users() . '</div>';
                echo '<div class="stats_home_icon_2"></div>';
                echo '<div class="stats_home_number">' . $filmAttendu->getAverage() . ' / 5</div>';
              echo '</div>';
            echo '</a>';
          }
        }
        else
        {
          echo '<div class="no_films_waited">Pas de films encore attendus pour cette année...</div>';
        }
      echo '</div>';
    }

    if ($films_way_out == "Y")
    {
      echo '<div class="titre_home">';
        echo '<img src="../../includes/icons/moviehouse/way_out.png" alt="recent" class="icone_home" />Les prochaines sorties';
      echo '</div>';

      echo '<div class="zone_home_films">';
        if (!empty($listeSorties))
        {
          foreach ($listeSorties as $filmSortie)
          {
            echo '<a href="details.php?id_film=' . $filmSortie->getId() . '&action=goConsulter" class="link_home_film">';
              // Poster
              if (!empty($filmSortie->getPoster()))
                echo '<img src="' . $filmSortie->getPoster() . '" alt="poster" title="' . $filmSortie->getPoster() . '" class="img_home_film" />';
              else
                echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmSortie->getPoster() . '" class="img_home_film" />';

              // Titre du film
              echo '<div class="titre_home_film">';
                echo $filmSortie->getFilm();
              echo '</div>';

              // Date de sortie
              echo '<div class="date_home_film">';
                echo 'Sortie le ' . formatDateForDisplay($filmSortie->getDate_doodle());
              echo '</div>';
            echo '</a>';
          }
        }
        else
        {
          echo '<div class="no_films_waited">Pas encore de sorties prévues prochainement...</div>';
        }
      echo '</div>';
    }
  ?>
</div>
