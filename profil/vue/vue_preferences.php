<?php
  echo '<div class="zone_profil_bottom_right">';
    echo '<div class="titre_section"><img src="../includes/icons/profil/settings_grey.png" alt="settings_grey" class="logo_titre_section" />Préférences</div>';

    echo '<form method="post" action="profil.php?user=' . $profil->getIdentifiant() . '&action=doUpdatePreferences">';
      echo '<div class="zone_profil_contributions">';
        /*********************/
        /*** Notifications ***/
        /*********************/
        echo '<div class="zone_contributions">';
          echo '<div class="titre_contribution"><img src="../includes/icons/profil/notifications_grey.png" alt="notifications_grey" class="logo_titre_contribution" />NOTIFICATIONS</div>';

          // Vue par défaut
          echo '<div class="sous_titre_contribution">Vue par défaut</div>';

          echo '<div class="zone_contribution large">';
            if ($preferences->getView_notifications() == "M")
            {
              echo '<div id="bouton_me" class="switch_default_view_notifications bouton_checked">';
                echo '<input id="me" type="radio" name="notifications_view" value="M" checked required />';
                echo '<label for="me" class="label_switch">Moi</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_me" class="switch_default_view_notifications">';
                echo '<input id="me" type="radio" name="notifications_view" value="M" required />';
                echo '<label for="me" class="label_switch">Moi</label>';
              echo '</div>';
            }

            if ($preferences->getView_notifications() == "T")
            {
              echo '<div id="bouton_today" class="switch_default_view_notifications bouton_checked">';
                echo '<input id="today" type="radio" name="notifications_view" value="T" checked required />';
                echo '<label for="today" class="label_switch">Aujourd\'hui</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_today" class="switch_default_view_notifications">';
                echo '<input id="today" type="radio" name="notifications_view" value="T" required />';
                echo '<label for="today" class="label_switch">Aujourd\'hui</label>';
              echo '</div>';
            }

            echo '<div></div>';

            if ($preferences->getView_notifications() == "W")
            {
              echo '<div id="bouton_week" class="switch_default_view_notifications margin_bottom bouton_checked">';
                echo '<input id="week" type="radio" name="notifications_view" value="W" checked required />';
                echo '<label for="week" class="label_switch">7 jours</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_week" class="switch_default_view_notifications margin_bottom">';
                echo '<input id="week" type="radio" name="notifications_view" value="W" required />';
                echo '<label for="week" class="label_switch">7 jours</label>';
              echo '</div>';
            }

            if ($preferences->getView_notifications() == "A")
            {
              echo '<div id="bouton_all_n" class="switch_default_view_notifications margin_bottom bouton_checked">';
                echo '<input id="all_n" type="radio" name="notifications_view" value="A" checked required />';
                echo '<label for="all_n" class="label_switch">Toutes</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_all_n" class="switch_default_view_notifications margin_bottom">';
                echo '<input id="all_n" type="radio" name="notifications_view" value="A" required />';
                echo '<label for="all_n" class="label_switch">Toutes</label>';
              echo '</div>';
            }
          echo '</div>';
        echo '</div>';

        /*******************/
        /*** Movie House ***/
        /*******************/
        echo '<div class="zone_contributions">';
          echo '<div class="titre_contribution"><img src="../includes/icons/profil/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_contribution" />MOVIE HOUSE</div>';

          // Vue par défaut
          echo '<div class="sous_titre_contribution">Vue par défaut</div>';

          echo '<div class="zone_contribution large">';
            if ($preferences->getView_movie_house() == "H")
            {
              echo '<div id="bouton_accueil" class="switch_default_view_movies bouton_checked">';
                echo '<input id="home" type="radio" name="movie_house_view" value="H" checked required />';
                echo '<label for="home" class="label_switch">Accueil</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_accueil" class="switch_default_view_movies">';
                echo '<input id="home" type="radio" name="movie_house_view" value="H" required />';
                echo '<label for="home" class="label_switch">Accueil</label>';
              echo '</div>';
            }

            if ($preferences->getView_movie_house() == "C")
            {
              echo '<div id="bouton_cards" class="switch_default_view_movies bouton_checked">';
                echo '<input id="cards" type="radio" name="movie_house_view" value="C" checked required />';
                echo '<label for="cards" class="label_switch">Fiches</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_cards" class="switch_default_view_movies">';
                echo '<input id="cards" type="radio" name="movie_house_view" value="C" required />';
                echo '<label for="cards" class="label_switch">Fiches</label>';
              echo '</div>';
            }

            echo '<div></div>';

            if ($preferences->getView_movie_house() == "S")
            {
              echo '<div id="bouton_synthese" class="switch_default_view_movies bouton_checked">';
                echo '<input id="synthese" type="radio" name="movie_house_view" value="S" checked required />';
                echo '<label for="synthese" class="label_switch">Synthèse</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_synthese" class="switch_default_view_movies">';
                echo '<input id="synthese" type="radio" name="movie_house_view" value="S" required />';
                echo '<label for="synthese" class="label_switch">Synthèse</label>';
              echo '</div>';
            }

            if ($preferences->getView_movie_house() == "D")
            {
              echo '<div id="bouton_details" class="switch_default_view_movies bouton_checked">';
                echo '<input id="details" type="radio" name="movie_house_view" value="D" checked required />';
                echo '<label for="details" class="label_switch">Détails</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_details" class="switch_default_view_movies">';
                echo '<input id="details" type="radio" name="movie_house_view" value="D" required />';
                echo '<label for="details" class="label_switch">Détails</label>';
              echo '</div>';
            }
          echo '</div>';

          // Catégories accueil
          echo '<div class="sous_titre_contribution">Catégories accueil</div>';

          echo '<div class="zone_contribution large">';
            list($films_waited, $films_way_out) = explode(';', $preferences->getCategories_home());

            if ($films_waited == "Y")
            {
              echo '<div id="bouton_waited" class="switch_default_view_categories bouton_checked">';
                echo '<input id="films_waited" type="checkbox" name="films_waited" checked />';
                echo '<label for="films_waited" class="label_switch">Les plus attendus</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_waited" class="switch_default_view_categories">';
                echo '<input id="films_waited" type="checkbox" name="films_waited" />';
                echo '<label for="films_waited" class="label_switch">Les plus attendus</label>';
              echo '</div>';
            }

            if ($films_way_out == "Y")
            {
              echo '<div id="bouton_way_out" class="switch_default_view_categories bouton_checked">';
                echo '<input id="films_way_out" type="checkbox" name="films_way_out" checked />';
                echo '<label for="films_way_out" class="label_switch">Les prochaines sorties</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_way_out" class="switch_default_view_categories">';
                echo '<input id="films_way_out" type="checkbox" name="films_way_out" />';
                echo '<label for="films_way_out" class="label_switch">Les prochaines sorties</label>';
              echo '</div>';
            }
          echo '</div>';

          // Affichage date du jour (tableaux)
          echo '<div class="sous_titre_contribution">Date du jour (tableaux)</div>';

          echo '<div class="zone_contribution large">';
            if ($preferences->getToday_movie_house() == "Y")
            {
              echo '<div id="bouton_date" class="switch_default_view_date bouton_checked">';
                echo '<input id="show_date" type="checkbox" name="affiche_date" checked />';
                echo '<label for="show_date" class="label_switch">Afficher</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_date" class="switch_default_view_date">';
                echo '<input id="show_date" type="checkbox" name="affiche_date" />';
                echo '<label for="show_date" class="label_switch">Afficher</label>';
              echo '</div>';
            }
          echo '</div>';

          // Masquage films (tableaux)
          echo '<div class="sous_titre_contribution">Affichage des films (tableaux)</div>';

          echo '<div class="zone_contribution large">';
            list($affichage, $type, $duree) = explode(";", $preferences->getView_old_movies());

            if ($affichage == "T")
            {
              echo '<div id="bouton_tous" class="switch_show_films bouton_checked">';
                echo '<input id="tous" type="radio" name="old_movies_view" value="T" checked required />';
                echo '<label for="tous" class="label_switch">Tous</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_tous" class="switch_show_films">';
                echo '<input id="tous" type="radio" name="old_movies_view" value="T" required />';
                echo '<label for="tous" class="label_switch">Tous</label>';
              echo '</div>';
            }

            if ($affichage == "P")
            {
              echo '<div id="bouton_partiel" class="switch_show_films bouton_checked">';
                echo '<input id="partiel" type="radio" name="old_movies_view" value="P" checked required />';
                echo '<label for="partiel" class="label_switch">Partiel</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_partiel" class="switch_show_films">';
                echo '<input id="partiel" type="radio" name="old_movies_view" value="P" required />';
                echo '<label for="partiel" class="label_switch">Partiel</label>';
              echo '</div>';
            }

            if ($affichage == "P")
            {
              echo '<div id="saisie_old_movies">';
                echo '<input type="text" name="duration" id="input_old_movies" placeholder="Durée" value="' . $duree . '" maxlength="3" class="duration_old_movies" required />';

                switch ($type)
                {
                  case 'J':
                    echo '<select name="type_duration" class="type_duration">';
                      echo '<option value="J" selected>Jours</option>';
                      echo '<option value="S">Semaines</option>';
                      echo '<option value="M">Mois</option>';
                    echo '</select>';
                    break;

                  case 'S':
                    echo '<select name="type_duration" class="type_duration">';
                      echo '<option value="J">Jours</option>';
                      echo '<option value="S" selected>Semaines</option>';
                      echo '<option value="M">Mois</option>';
                    echo '</select>';
                    break;

                  case 'M':
                  default:
                    echo '<select name="type_duration" class="type_duration">';
                      echo '<option value="J">Jours</option>';
                      echo '<option value="S">Semaines</option>';
                      echo '<option value="M" selected>Mois</option>';
                    echo '</select>';
                    break;
                }
              echo '</div>';
            }
            else
            {
              echo '<div id="saisie_old_movies" style="display: none;">';
                echo '<input type="text" name="duration" id="input_old_movies" placeholder="Durée" value="' . $duree . '" maxlength="3" class="duration_old_movies" />';

                switch ($type)
                {
                  case 'J':
                    echo '<select name="type_duration" class="type_duration">';
                      echo '<option value="J" selected>Jours</option>';
                      echo '<option value="S">Semaines</option>';
                      echo '<option value="M">Mois</option>';
                    echo '</select>';
                    break;

                  case 'S':
                    echo '<select name="type_duration" class="type_duration">';
                      echo '<option value="J">Jours</option>';
                      echo '<option value="S" selected>Semaines</option>';
                      echo '<option value="M">Mois</option>';
                    echo '</select>';
                    break;

                  case 'M':
                  default:
                    echo '<select name="type_duration" class="type_duration">';
                      echo '<option value="J">Jours</option>';
                      echo '<option value="S">Semaines</option>';
                      echo '<option value="M" selected>Mois</option>';
                    echo '</select>';
                    break;
                }
              echo '</div>';
            }
          echo '</div>';





          // Masquage films (fiches)
          /*echo '<div class="sous_titre_contribution">Affichage des films (fiches)</div>';
          echo 'à faire';*/









        echo '</div>';

        /***************/
        /*** #TheBox ***/
        /***************/
        echo '<div class="zone_contributions">';
          echo '<div class="titre_contribution"><img src="../includes/icons/profil/ideas_grey.png" alt="ideas_grey" class="logo_titre_contribution" />#THEBOX</div>';

          // Vue par défaut
          echo '<div class="sous_titre_contribution">Vue par défaut</div>';

          echo '<div class="zone_contribution large">';
            if ($preferences->getView_the_box() == "A")
            {
              echo '<div id="bouton_all" class="switch_default_view_ideas bouton_checked">';
                echo '<input id="all" type="radio" name="the_box_view" value="A" checked required />';
                echo '<label for="all" class="label_switch">Toutes</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_all" class="switch_default_view_ideas">';
                echo '<input id="all" type="radio" name="the_box_view" value="A" required />';
                echo '<label for="all" class="label_switch">Toutes</label>';
              echo '</div>';
            }

            if ($preferences->getView_the_box() == "P")
            {
              echo '<div id="bouton_inprogress" class="switch_default_view_ideas bouton_checked">';
                echo '<input id="inprogress" type="radio" name="the_box_view" value="P" checked required />';
                echo '<label for="inprogress" class="label_switch">En cours</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_inprogress" class="switch_default_view_ideas">';
                echo '<input id="inprogress" type="radio" name="the_box_view" value="P" required />';
                echo '<label for="inprogress" class="label_switch">En cours</label>';
              echo '</div>';
            }

            echo '<div></div>';

            if ($preferences->getView_the_box() == "M")
            {
              echo '<div id="bouton_mine" class="switch_default_view_ideas margin_bottom bouton_checked">';
                echo '<input id="mine" type="radio" name="the_box_view" value="M" checked required />';
                echo '<label for="mine" class="label_switch">En charge</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_mine" class="switch_default_view_ideas margin_bottom">';
                echo '<input id="mine" type="radio" name="the_box_view" value="M" required />';
                echo '<label for="mine" class="label_switch">En charge</label>';
              echo '</div>';
            }

            if ($preferences->getView_the_box() == "D")
            {
              echo '<div id="bouton_done" class="switch_default_view_ideas margin_bottom bouton_checked">';
                echo '<input id="done" type="radio" name="the_box_view" value="D" checked required />';
                echo '<label for="done" class="label_switch">Terminées & rejetées</label>';
              echo '</div>';
            }
            else
            {
              echo '<div id="bouton_done" class="switch_default_view_ideas margin_bottom">';
                echo '<input id="done" type="radio" name="the_box_view" value="D" required />';
                echo '<label for="done" class="label_switch">Terminées & rejetées</label>';
              echo '</div>';
            }
          echo '</div>';
        echo '</div>';
      echo '</div>';

      // Bouton validation
      echo '<input type="submit" name="saisie_preferences" value="Mettre à jour" class="bouton_validation margin_top" />';
    echo '</form>';
  echo '</div>';
?>
