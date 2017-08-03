<div class="zone_home">
  <div class="titre_home" style="margin-top: 0;">
    Ajouts récents
  </div>

  <div class="zone_home_films">
    <?php
      $reponse1 = $bdd->query('SELECT * FROM movie_house ORDER BY SUBSTR(date_add, 4, 4) DESC, id DESC LIMIT 5');

      while($donnees1 = $reponse1->fetch())
      {
        echo '<a href="moviehouse/details_film.php?id_film=' . $donnees1['id'] . '" class="link_home_film">';
          // Poster
          if (!empty($donnees1['poster']))
            echo '<img src="' . $donnees1['poster'] . '" alt="poster" title="' . $donnees1['film'] . '" class="img_home_film" />';
          else
            echo '<img src="moviehouse/images/cinema.jpg" alt="poster" title="' . $donnees1['film'] . '" class="img_home_film" />';

          // Titre du film
          echo '<div class="titre_home_film">';
            echo $donnees1['film'];
          echo '</div>';
        echo '</a>';
      }

      $reponse1->closeCursor();
    ?>
  </div>

  <div class="titre_home">
    Les plus attendus en <?php echo $_GET['year']; ?>
  </div>

  <div class="zone_home_films">
    <?php
      // Calcul de la moyenne des étoiles de tous les films (tableau id film/moyenne/total utilisateurs) dont la date est supérieure ou égale à date du jour - 1 mois
      $i = 0;
      $total_stars = 0;
      $total_users = 0;
      $moyenne_stars = array();
      $id_film_nouveau = "";
      $id_film_ancien = "";
      $date_du_jour_moins_1_mois = date("Ymd", strtotime('now -1 Month'));

      $reponse2 = $bdd->query('SELECT id, id_film, stars FROM movie_house_users ORDER BY id_film ASC');

      while($donnees2 = $reponse2->fetch())
      {
        // On ne tient pas compte de tous les films à sortir à date du jour - 1 mois
        $reponse3 = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 5, 4) = ' . $_GET['year'] . ' AND id = ' . $donnees2['id_film']);

        $donnees3 = $reponse3->fetch();
        {
          $date_film = substr($donnees3['date_theater'], 4, 4) . substr($donnees3['date_theater'], 0, 2) . substr($donnees3['date_theater'], 2, 2);

          if ($date_film > $date_du_jour_moins_1_mois)
          {
            $id_film_nouveau = $donnees2['id_film'];

            if (empty($id_film_ancien) OR $id_film_nouveau == $id_film_ancien)
            {
              $total_stars += $donnees2['stars'];
              $total_users++;
            }
            elseif (!empty($id_film_ancien) AND $id_film_nouveau != $id_film_ancien)
            {
              $moyenne_stars[$i][1] = $id_film_ancien;
              $moyenne_stars[$i][2] = $total_stars / $total_users;
              $moyenne_stars[$i][3] = $total_users;

              $i++;
              $total_stars = $donnees2['stars'];
              $total_users = 1;
            }

            $id_film_ancien = $id_film_nouveau;
          }
        }

        $reponse3->closeCursor();
      }

      $reponse2->closeCursor();

      // On affiche seulement si on a trouvé des films attendus pour cette année
      if (!empty($moyenne_stars))
      {
        // On trie le film par nombre d'utilisateur en premier et par moyenne en 2ème
        $moyenne_stars_tri = $moyenne_stars;
        $tri_1 = NULL;
        $tri_2 = NULL;

        foreach($moyenne_stars as $ligne)
        {
          $tri_1[] = $ligne[3];
          $tri_2[] = $ligne[2];
        }

        array_multisort($tri_1, SORT_DESC, $tri_2, SORT_DESC, $moyenne_stars_tri);

        // On extrait les 5 premières moyennes des films les plus attentus
        $moyenne_stars_tri_coupe = array_slice($moyenne_stars_tri, 0, 5);

        // On affiche les 5 films
        foreach ($moyenne_stars_tri_coupe as $ligne)
        {
          // On affiche les données correspondantes
          $reponse4 = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $ligne[1]);

          $donnees4 = $reponse4->fetch();
          {
            echo '<a href="moviehouse/details_film.php?id_film=' . $donnees4['id'] . '" class="link_home_film">';
              // Poster
              if (!empty($donnees4['poster']))
                echo '<img src="' . $donnees4['poster'] . '" alt="poster" title="' . $donnees4['film'] . '" class="img_home_film" />';
              else
                echo '<img src="moviehouse/images/cinema.jpg" alt="poster" title="' . $donnees4['film'] . '" class="img_home_film" />';

              // Titre du film
              echo '<div class="titre_home_film">';
                echo $donnees4['film'];
              echo '</div>';
            echo '</a>';
          }

          $reponse4->closeCursor();
        }
      }
      else
      {
        echo '<div class="no_films_waited">Pas de films encore attendus pour cette année...</div>';
      }
    ?>
  </div>
</div>
