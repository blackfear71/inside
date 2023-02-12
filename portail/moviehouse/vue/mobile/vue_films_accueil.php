<?php
    echo '<div class="zone_films_home">';
    /******************/
    /* Ajouts récents */
    /******************/
    // Titre
    echo '<div id="titre_derniers_films_ajoutes" class="titre_section">';
        echo '<img src="../../includes/icons/moviehouse/recent_grey.png" alt="recent_grey" class="logo_titre_section" />';
        echo '<div class="texte_titre_section_fleche">Films ajoutés en ' . $_GET['year'] . '</div>';
        echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Films récents
    echo '<div id="afficher_derniers_films_ajoutes" class="zone_films_accueil">';
        if (!empty($listeRecents))
        {
            $i = 0;

            foreach ($listeRecents as $filmRecent)
            {
                if ($i % 2 == 0)
                    echo '<a href="details.php?id_film=' . $filmRecent->getId() . '&action=goConsulter" class="zone_film_accueil zone_film_accueil_margin">';
                else
                    echo '<a href="details.php?id_film=' . $filmRecent->getId() . '&action=goConsulter" class="zone_film_accueil">';
                    // Poster
                    if (!empty($filmRecent->getPoster()))
                        echo '<img src="' . $filmRecent->getPoster() . '" alt="poster" title="' . $filmRecent->getFilm() . '" class="image_accueil" />';
                    else
                        echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmRecent->getFilm() . '" class="image_accueil" />';

                    // Titre du film
                    echo '<div class="titre_film_accueil">' . $filmRecent->getFilm() . '</div>';
                echo '</a>';

                $i++;
            }
        }
        else
            echo '<div class="empty">Pas de films ajoutés pour cette année...</div>';
    echo '</div>';

    /*************************/
    /* Sorties de la semaine */
    /*************************/
    if ($filmsSemaine == 'Y' AND $afficherSemaine == true)
    {
        // Titre
        echo '<div id="titre_sorties_films_semaine" class="titre_section">';
            echo '<img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Sorties de la semaine</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
        echo '</div>';

        // Sorties de la semaine
        echo '<div id="afficher_sorties_films_semaine" class="zone_films_accueil">';
            if (!empty($listeSemaine))
            {
                $i = 0;

                foreach ($listeSemaine as $filmSemaine)
                {
                    if ($i % 2 == 0)
                        echo '<a href="details.php?id_film=' . $filmSemaine->getId() . '&action=goConsulter" class="zone_film_accueil zone_film_accueil_margin">';
                    else
                        echo '<a href="details.php?id_film=' . $filmSemaine->getId() . '&action=goConsulter" class="zone_film_accueil">';
                        // Poster
                        if (!empty($filmSemaine->getPoster()))
                            echo '<img src="' . $filmSemaine->getPoster() . '" alt="poster" title="' . $filmSemaine->getFilm() . '" class="image_accueil" />';
                        else
                            echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $filmSemaine->getFilm() . '" class="image_accueil" />';

                        // Titre du film
                        echo '<div class="titre_film_accueil">' . $filmSemaine->getFilm() . '</div>';
                    echo '</a>';

                    $i++;
                }
            }
            else
                echo '<div class="empty">Aucun nouveau film cette semaine...</div>';
        echo '</div>';
    }

    /*********************/
    /* Les plus attendus */
    /*********************/
    if ($filmsWaited == 'Y')
    {
        // Titre
        echo '<div id="titre_films_attendus" class="titre_section">';
            echo '<img src="../../includes/icons/moviehouse/waited_grey.png" alt="waited_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Attendus en ' . $_GET['year'] . '</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
        echo '</div>';

        // Les films les plus attendus
        echo '<div id="afficher_films_attendus" class="zone_films_accueil">';
            if (!empty($listeAttendus))
            {
                $i = 0;

                foreach ($listeAttendus as $filmAttendu)
                {
                    if ($i % 2 == 0)
                        echo '<a href="details.php?id_film=' . $filmAttendu->getId() . '&action=goConsulter" class="zone_film_accueil zone_film_accueil_margin">';
                    else
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

                    $i++;
                }
            }
            else
                echo '<div class="empty">Pas de films encore attendus pour cette année...</div>';
        echo '</div>';
    }

    /**************************/
    /* Les prochaines sorties */
    /**************************/
    if ($filmsWayOut == 'Y')
    {
        // Titre
        echo '<div id="titre_sorties_organisees" class="titre_section">';
            echo '<img src="../../includes/icons/moviehouse/way_out_grey.png" alt="way_out_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Sorties prévues en ' . $_GET['year'] . '</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
        echo '</div>';

        // Les sorties organisées
        echo '<div id="afficher_sorties_organisees" class="zone_films_accueil">';
            if (!empty($listeSorties))
            {
                $i = 0;

                foreach ($listeSorties as $filmSortie)
                {
                    if ($i % 2 == 0)
                        echo '<a href="details.php?id_film=' . $filmSortie->getId() . '&action=goConsulter" class="zone_film_accueil zone_film_accueil_margin">';
                    else
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

                    $i++;
                }
            }
            else
                echo '<div class="empty">Pas de sorties prévues prochainement...</div>';
        echo '</div>';
    }
    echo '</div>';
?>