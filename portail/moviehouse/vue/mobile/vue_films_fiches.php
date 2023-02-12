<?php
    // Fiches
    echo '<div class="zone_films_fiches">';
        if ($_GET['year'] == 'none')
        {
            // Titre
            echo '<div id="titre_fiches_nc" class="titre_section">';
                echo '<img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section_fleche">Date de sortie inconnue</div>';
                echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
            echo '</div>';

            // Liste des films (date de sortie non communiquée)
            echo '<div id="afficher_fiches_nc">';
                if (!empty($listeFilms))
                {
                    foreach ($listeFilms as $keyFilm => $film)
                    {
                        // Fiches
                        echo '<div id="zone_shadow_' . $film->getId() . '" class="zone_shadow">';
                            echo '<div class="zone_fiche_film" id="' . $film->getId() . '">';
                            // Lien avec poster et titre
                            echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter">';
                                // Poster
                                echo '<div class="zone_fiche_poster">';
                                    if (!empty($film->getPoster()))
                                        echo '<img src="' . $film->getPoster() . '" alt="poster" title="' . $film->getFilm() . '" class="poster_fiche" />';
                                    else
                                        echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $film->getFilm() . '" class="poster_fiche" />';
                                echo '</div>';

                                // Titre
                                echo '<div class="nom_fiche">' . formatString($film->getFilm(), 20) . '</div>';
                            echo '</a>';

                            // Actions
                            if ($film->getParticipation() == 'S')
                                echo '<a id="preference_fiche_' . $film->getId() . '" class="zone_fiche_actions film_vu afficherSaisiePreference">';
                            elseif ($film->getParticipation() == 'P')
                                echo '<a id="preference_fiche_' . $film->getId() . '" class="zone_fiche_actions film_participe afficherSaisiePreference">';
                            else
                                echo '<a id="preference_fiche_' . $film->getId() . '" class="zone_fiche_actions afficherSaisiePreference">';
                                    echo '<input type="hidden" id="titre_film_' . $film->getId() . '" value="' . $film->getFilm() . '" />';
                                    echo '<input type="hidden" id="vote_film_' . $film->getId() . '" value="' . $film->getStars_user() . '" />';
                                    echo '<input type="hidden" id="participation_film_' . $film->getId() . '" value="' . $film->getParticipation() . '" />';
                                    echo '<img src="../../includes/icons/moviehouse/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="icone_actions_fiche" />';
                                echo '</a>';
                            echo '</div>';
                        echo '</div>';
                    }
                }
                else
                    echo '<div class="empty">Pas de films...</div>';
            echo '</div>';
        }
        else
        {
            if (!empty($listeFilms))
            {
                // Liste des films par mois
                $moisPrecedent = '';

                foreach ($listeFilms as $keyFilm => $film)
                {
                    $moisCourant = substr($film->getDate_theater(), 4, 2);

                    if ($moisCourant != $moisPrecedent)
                    {
                        // Titre
                        echo '<div id="titre_fiches_' . $moisCourant . '" class="titre_section">';
                            echo '<img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" />';
                            echo '<div class="texte_titre_section_fleche">' . formatMonthForDisplay($moisCourant) . '</div>';
                            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                        echo '</div>';

                        $moisPrecedent = $moisCourant;

                        // Début de la zone des films du mois courant
                        echo '<div id="afficher_fiches_' . $moisCourant . '">';
                    }

                    // Fiche
                    echo '<div id="zone_shadow_' . $film->getId() . '" class="zone_shadow">';
                        echo '<div class="zone_fiche_film" id="' . $film->getId() . '">';
                        // Lien avec poster et titre
                        echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter">';
                            // Poster
                            echo '<div class="zone_fiche_poster">';
                                if (!empty($film->getPoster()))
                                    echo '<img src="' . $film->getPoster() . '" alt="poster" title="' . $film->getFilm() . '" class="poster_fiche" />';
                                else
                                    echo '<img src="../../includes/images/moviehouse/cinema.jpg" alt="poster" title="' . $film->getFilm() . '" class="poster_fiche" />';
                            echo '</div>';

                            // Titre
                            echo '<div class="nom_fiche">' . formatString($film->getFilm(), 20) . '</div>';
                        echo '</a>';

                        // Actions
                        if ($film->getParticipation() == 'S')
                            echo '<a id="preference_fiche_' . $film->getId() . '" class="zone_fiche_actions film_vu afficherSaisiePreference">';
                        elseif ($film->getParticipation() == 'P')
                            echo '<a id="preference_fiche_' . $film->getId() . '" class="zone_fiche_actions film_participe afficherSaisiePreference">';
                        else
                            echo '<a id="preference_fiche_' . $film->getId() . '" class="zone_fiche_actions afficherSaisiePreference">';
                                echo '<input type="hidden" id="titre_film_' . $film->getId() . '" value="' . $film->getFilm() . '" />';
                                echo '<input type="hidden" id="vote_film_' . $film->getId() . '" value="' . $film->getStars_user() . '" />';
                                echo '<input type="hidden" id="participation_film_' . $film->getId() . '" value="' . $film->getParticipation() . '" />';
                                echo '<img src="../../includes/icons/moviehouse/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="icone_actions_fiche" />';
                            echo '</a>';
                        echo '</div>';
                    echo '</div>';

                    // Fin de la zone des films du mois courant
                    if (!isset($listeFilms[$keyFilm + 1]) OR ($moisCourant < substr($listeFilms[$keyFilm + 1]->getDate_theater(), 4, 2)))
                        echo '</div>';
                }
            }
            else
                echo '<div class="empty">Pas de films pour cette année...</div>';
        }
    echo '</div>';
?>