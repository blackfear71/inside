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
            echo '<div class="empty">Pas de films ajoutés pour cette année...</div>';

        /*************************/
        /* Sorties de la semaine */
        /*************************/
        if ($filmsSemaine == 'Y' AND $afficherSemaine == true)
        {
            echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">Ils sortent cette semaine</div></div>';

            if (!empty($listeSemaine))
            {
                echo '<div class="zone_films_accueil">';
                    foreach ($listeSemaine as $filmSemaine)
                    {
                        echo '<a href="details.php?id_film=' . $filmSemaine->getId() . '&action=goConsulter" class="zone_film_accueil">';
                            // Poster
                            if (!empty($filmSemaine->getPoster()))
                                echo '<img src="' . $filmSemaine->getPoster() . '" alt="poster" title="' . $filmSemaine->getFilm() . '" class="image_accueil" />';
                            else
                                echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmSemaine->getFilm() . '" class="image_accueil" />';

                            // Titre du film
                            echo '<div class="titre_film_accueil">' . $filmSemaine->getFilm() . '</div>';
                        echo '</a>';
                    }
                echo '</div>';
            }
            else
                echo '<div class="empty">Aucun nouveau film cette semaine...</div>';
        }

        /*********************/
        /* Les plus attendus */
        /*********************/
        if ($filmsWaited == 'Y')
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
                                echo '<div class="users_interested">';
                                    echo '<img src="../../includes/icons/moviehouse/users.png" alt="users" class="icone_accueil" />';
                                    echo '<div class="texte_icones_accueil">' . $filmAttendu->getNb_users() . '</div>';
                                echo '</div>';

                                echo '<div class="average_star">';
                                    echo '<img src="../../includes/icons/moviehouse/star.png" alt="star" class="icone_accueil" />';
                                    echo '<div class="texte_icones_accueil">' . $filmAttendu->getAverage() . ' / 5</div>';
                                echo '</div>';
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
        if ($filmsWayOut == 'Y')
        {
            if ($_GET['year'] >= date('Y'))
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