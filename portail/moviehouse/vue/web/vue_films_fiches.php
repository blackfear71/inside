<?php
    echo '<div class="zone_films">';
        /****************/
        /* Fiches films */
        /****************/
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">';
                if ($_GET['year'] != 'none')
                    echo 'Les films de ' . $_GET['year'];
                else
                    echo 'Les films (date de sortie non communiquée)';

                if ($_GET['year'] != 'none' AND !empty($listeFilms))
                {
                    echo '<div class="zone_actions_films">';
                        echo '<a id="fold_all" class="bouton_fold_films">Tout plier</a>';
                        echo '<a id="unfold_all" class="bouton_fold_films">Tout déplier</a>';
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';

        $prevMonth = '';

        if (!empty($listeFilms))
        {
            // Début de la Masonry si liste des films N. C.
            if ($_GET['year'] == 'none')
                echo '<div class="zone_fiches_films">';

                foreach ($listeFilms as $keyFilm => $film)
                {
                    // Titre du mois et début de la zone Masonry du mois
                    if (!empty($film->getDate_theater()))
                    {
                        $currentMonth = substr($film->getDate_theater(), 4, 2);

                        if ($currentMonth != $prevMonth)
                        {
                            $prevMonth = $currentMonth;

                            if ($currentMonth == date('m') AND $_GET['year'] == date('Y'))
                                echo '<div class="titre_mois_films titre_bleu"><a id="lien_hide_' . $currentMonth . '" class="fond_hide cacherFilms">-</a>' . formatMonthForDisplay($currentMonth) . '</div>';
                            else
                                echo '<div class="titre_mois_films"><a id="lien_hide_' . $currentMonth . '" class="fond_hide cacherFilms">-</a>' . formatMonthForDisplay($currentMonth) . '</div>';

                            echo '<div class="zone_fiches_films" id="hide_films_' . $currentMonth . '">';
                        }
                    }

                    // Fiche
                    echo '<div class="zone_fiche_film" id="' . $film->getId() . '">';

                    if ($film->getParticipation() == 'S')
                        echo '<div id="zone_shadow_' . $film->getId() . '" class="zone_shadow border_radius border_blue">';
                    elseif ($film->getParticipation() == 'P')
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

                                        if (!empty($film->getDate_theater()))
                                            echo formatDateForDisplay($film->getDate_theater());
                                        else
                                            echo 'Non communiquée';
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
                                        echo '<a href="https://doodle.com/fr/" id="lien_details_' . $film->getId() . '" target="_blank" class="lienDetails"><img src="../../includes/icons/moviehouse/doodle_none.png" alt="doodle_none" title="Doodle" class="icone_fiche_2" /></a>';

                                    // Mailing (si on participe)
                                    if ($film->getStars_user() > 0)
                                    {
                                        echo '<a href="mailing.php?id_film=' . $film->getId() . '&action=goConsulter">';
                                            echo '<img src="../../includes/icons/moviehouse/mailing_red.png" alt="mailing_red" title="Envoyer mail" class="icone_fiche_2" />';
                                        echo '</a>';
                                    }

                                    // Commentaires
                                    echo '<a href="details.php?id_film=' . $film->getId() . '&action=goConsulter&anchor=comments" title="Nombre de commentaires">';
                                        echo '<img src="../../includes/icons/moviehouse/comments_grey.png" alt="comments_grey" class="icone_fiche_2" />';
                                        echo '<div class="nb_commentaires_fiche">' . $film->getNb_comments() . '</div>';
                                    echo '</a>';
                                echo '</div>';

                                // Actions
                                echo '<div class="zone_fiche_infos_bottom">';
                                    // Vote utilisateur
                                    echo '<a id="fiche_' . $film->getId() . '" class="vote_fiche afficherPreference">';
                                        echo '<input type="hidden" id="titre_film_' . $film->getId() . '" value="' . $film->getFilm() . '" />';
                                        echo '<input type="hidden" id="vote_film_' . $film->getId() . '" value="' . $film->getStars_user() . '" />';
                                        echo '<img src="../../includes/icons/moviehouse/stars/star' . $film->getStars_user() . '.png" alt="star' . $film->getStars_user() . '" class="icone_fiche_3" />';
                                    echo '</a>';

                                    if ($film->getStars_user() > 0)
                                    {
                                        // Participation / vue
                                        echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doParticiperFilm" class="form_participation">';
                                            echo '<input type="hidden" name="id_film" value="' . $film->getId() . '" />';

                                            // Je participe
                                            if ($film->getParticipation() == 'P')
                                                echo '<input type="submit" name="participate" value="" title="Je participe !" class="input_participate_yes" />';
                                            else
                                                echo '<input type="submit" name="participate" value="" title="Je participe !" class="input_participate_no" />';

                                            // J'ai vu
                                            if ($film->getParticipation() == 'S')
                                                echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="input_seen_yes" />';
                                            else
                                                echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="input_seen_no" />';
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
                                            if ($etoilesFilm->getStars() == $previousStars)
                                            {
                                                // Avatar
                                                $avatarFormatted = formatAvatar($etoilesFilm->getAvatar(), $etoilesFilm->getPseudo(), 2, 'avatar');

                                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_fiche" />';
                                            }
                                            else
                                            {
                                                if ($previousStars != 0)
                                                    echo '</div><div class="ligne_stars">';
                                                else
                                                    echo '<div class="ligne_stars">';

                                                echo '<img src="../../includes/icons/moviehouse/stars/star' . $etoilesFilm->getStars() . '.png" alt="star' . $etoilesFilm->getStars() . '" class="icone_fiche_4" />';

                                                // Avatar
                                                $avatarFormatted = formatAvatar($etoilesFilm->getAvatar(), $etoilesFilm->getPseudo(), 2, 'avatar');

                                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_fiche" />';

                                                $previousStars = $etoilesFilm->getStars();
                                            }
                                        }

                                        echo '</div>';
                                    echo '</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';

                    // Fin de la zone Masonry du mois
                    if (!empty($film->getDate_theater()) AND (!isset($listeFilms[$keyFilm + 1]) OR (!empty($film->getDate_theater()) AND $currentMonth < substr($listeFilms[$keyFilm + 1]->getDate_theater(), 4, 2))))
                        echo '</div>';
                }

            // Fin de la zone Masonry si liste des films N. C.
            if ($_GET['year'] == 'none')
                echo '</div>';
        }
        else
            echo '<div class="empty">Pas de films pour cette année...</div>';
    echo '</div>';
?>