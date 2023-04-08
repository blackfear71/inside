<?php
    echo '<div id="zone_saisie_film" class="fond_saisie">';
        if ($_SERVER['PHP_SELF'] == '/inside/portail/moviehouse/moviehouse.php')
            echo '<form method="post" action="moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=doAjouterFilmMobile" class="form_saisie">';
        else
            echo '<form method="post" action="details.php?action=doModifierFilmMobile" class="form_saisie">';
            // Id film (modification)
            echo '<input type="hidden" name="id_film" value="" />';

            // Titre
            echo '<div class="zone_titre_saisie">Ajouter un film</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Liens utiles
                    echo '<div class="zone_liens_saisie_film">';
                        echo '<a href="https://www.allocine.fr/" target="_blank" class="lien_saisie_film lien_allocine">ALLOCINÉ</a>';
                        echo '<a href="https://www.youtube.com/" target="_blank" class="lien_saisie_film lien_youtube">YouTube</a>';
                        echo '<a href="https://www.dailymotion.com/fr" target="_blank" class="lien_saisie_film lien_dailymotion">dailymotion</a>';
                        echo '<a href="https://vimeo.com/fr/watch" target="_blank" class="lien_saisie_film lien_vimeo">vimeo</a>';
                    echo '</div>';

                    // Titre (film)
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">Le film</div>';
                    echo '</div>';

                    // Saisies (film)
                    echo '<div class="zone_saisie_lignes">';
                        // Titre du film
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/titre.png" alt="titre" title="Titre du film" class="icone_saisie" />';
                            echo '<input type="text" name="nom_film" value="' . $_SESSION['save']['nom_film_saisi'] . '" placeholder="Titre du film" maxlength="255" class="saisie_ligne" required />';
                        echo '</div>';

                        // Date de sortie cinéma
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" title="Date de sortie cinéma" class="icone_saisie" />';
                            echo '<input type="date" name="date_theater" value="' . $_SESSION['save']['date_theater_saisie'] . '" placeholder="Date de sortie cinéma" maxlength="10" autocomplete="off" class="saisie_date" />';
                        echo '</div>';

                        // Date de sortie DVD
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/disk_grey.png" alt="disk_grey" title="Date de sortie DVD/Bluray" class="icone_saisie" />';
                            echo '<input type="date" name="date_release" value="' . $_SESSION['save']['date_release_saisie'] . '" placeholder="Date de sortie DVD/Bluray" maxlength="10" autocomplete="off" class="saisie_date" />';
                        echo '</div>';

                        // Lien trailer
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/trailer.png" alt="trailer" title="Trailer" class="icone_saisie" />';
                            echo '<input type="text" name="trailer" value="' . $_SESSION['save']['trailer_saisi'] . '" placeholder="Trailer (lien Youtube, Dailymotion ou Vimeo)" class="saisie_ligne" />';
                        echo '</div>';

                        // Lien fiche
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/pellicule.png" alt="lien" title="Lien" class="icone_saisie" />';
                            echo '<input type="text" name="link" value="' . $_SESSION['save']['link_saisi'] . '" placeholder="Lien (Allociné, Wikipédia...)" class="saisie_ligne" />';
                        echo '</div>';

                        // Lien poster
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/poster.png" alt="poster" title="Poster" class="icone_saisie" />';
                            echo '<input type="text" name="poster" value="' . $_SESSION['save']['poster_saisi'] . '" placeholder="URL poster" class="saisie_ligne" />';
                        echo '</div>';

                        // Synopsis
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/synopsis.png" alt="synopsis" title="Synopsis" class="icone_saisie_textarea" />';
                            echo '<textarea placeholder="Synopsis" name="synopsis" class="saisie_textarea">' . $_SESSION['save']['synopsis_saisi'] . '</textarea>';
                        echo '</div>';
                    echo '</div>';

                    // Saisies (sortie)
                    echo '<div class="zone_saisie_lignes">';
                        // Titre (sortie)
                        echo '<div class="titre_section">';
                            echo '<img src="../../includes/icons/moviehouse/way_out_grey.png" alt="way_out_grey" class="logo_titre_section" />';
                            echo '<div class="texte_titre_section">Organiser une sortie</div>';
                        echo '</div>';

                        // Lien Doodle
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/doodle_grey.png" alt="doodle_grey" title="Doodle" class="icone_saisie" />';
                            echo '<input type="text" name="doodle" value="' . $_SESSION['save']['doodle_saisi'] . '" placeholder="Lien Doodle" class="saisie_ligne" />';
                        echo '</div>';

                        // Date sortie
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/date_grey.png" alt="date_grey" title="Date proposée" class="icone_saisie" />';
                            echo '<input type="date" name="date_doodle" value="' . $_SESSION['save']['date_doodle_saisie'] . '" placeholder="Date proposée" maxlength="10" autocomplete="off" id="datepicker_doodle" class="saisie_date_short" />';

                            // Selection de l'heure
                            echo '<select name="hours_doodle" class="select_time">';
                                if (empty($_SESSION['save']['time_doodle_saisi']))
                                    echo '<option value="" disabled selected hidden>hh</option>';
                                else
                                    echo '<option value="" disabled hidden>hh</option>';

                                for ($i = 0; $i <= 23; $i++)
                                {
                                    if (!empty($_SESSION['save']['time_doodle_saisi']) AND substr($_SESSION['save']['time_doodle_saisi'], 0, 2) == $i)
                                    {
                                        if ($i < 10)
                                            echo '<option value="0' . $i . '" selected>0' . $i . '</option>';
                                        else
                                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                    }
                                    else
                                    {
                                        if (substr($_SESSION['save']['time_doodle_saisi'], 0, 2) == '  ')
                                            echo '<option value="" disabled selected hidden>hh</option>';

                                        if ($i < 10)
                                            echo '<option value="0' . $i . '">0' . $i . '</option>';
                                        else
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                }
                            echo '</select>';

                            // Selection des minutes
                            echo '<select name="minutes_doodle" class="select_time">';
                                if (empty($_SESSION['save']['time_doodle_saisi']))
                                    echo '<option value="" disabled selected hidden>mm</option>';
                                else
                                    echo '<option value="" disabled hidden>mm</option>';

                                for ($i = 0; $i <= 11; $i++)
                                {
                                    if (!empty($_SESSION['save']['time_doodle_saisi']) AND (substr($_SESSION['save']['time_doodle_saisi'], 2, 2) / 5) == $i)
                                    {
                                        if ($i < 2)
                                            echo '<option value="0' . 5 * $i . '" selected>0' . 5 * $i . '</option>';
                                        else
                                            echo '<option value="' . 5 * $i . '" selected>' . 5 * $i . '</option>';
                                    }
                                    else
                                    {
                                        if (substr($_SESSION['save']['time_doodle_saisi'], 2, 2) == '  ')
                                            echo '<option value="" disabled selected hidden>mm</option>';

                                        if ($i < 2)
                                            echo '<option value="0' . 5 * $i . '">0' . 5 * $i . '</option>';
                                        else
                                            echo '<option value="' . 5 * $i . '">' . 5 * $i . '</option>';
                                    }
                                }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>';

                    // Saisies (restaurant)
                    echo '<div class="zone_saisie_lignes">';
                        // Titre (restaurant)
                        echo '<div class="titre_section">';
                            echo '<img src="../../includes/icons/moviehouse/restaurant_grey.png" alt="restaurant_grey" class="logo_titre_section" />';
                            echo '<div class="texte_titre_section">Choisir un restaurant</div>';
                        echo '</div>';

                        // Choix restaurant
                        echo '<div class="zone_saisie_ligne">';
                            if ($_SESSION['save']['restaurant_saisi'] == 'N' OR empty($_SESSION['save']['restaurant_saisi']))
                            {
                                echo '<div id="bouton_none" class="switch_restaurant switch_restaurant_margin bouton_checked">';
                                    echo '<div class="zone_radio_restaurant">';
                                        echo '<input id="none" type="radio" name="restaurant" value="N" class="radio_restaurant" checked />';
                                    echo '</div>';

                                    echo '<label for="none" class="label_switch">Aucun</label>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div id="bouton_none" class="switch_restaurant switch_restaurant_margin">';
                                    echo '<div class="zone_radio_restaurant">';
                                        echo '<input id="none" type="radio" name="restaurant" value="N" class="radio_restaurant" />';
                                    echo '</div>';

                                    echo '<label for="none" class="label_switch">Aucun</label>';
                                echo '</div>';
                            }

                            if ($_SESSION['save']['restaurant_saisi'] == 'B')
                            {
                                echo '<div id="bouton_before" class="switch_restaurant switch_restaurant_margin bouton_checked">';
                                    echo '<div class="zone_radio_restaurant">';
                                        echo '<input id="before" type="radio" name="restaurant" value="B" class="radio_restaurant" checked />';
                                    echo '</div>';

                                    echo '<label for="before" class="label_switch">Avant</label>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div id="bouton_before" class="switch_restaurant switch_restaurant_margin">';
                                    echo '<div class="zone_radio_restaurant">';
                                        echo '<input id="before" type="radio" name="restaurant" value="B" class="radio_restaurant" />';
                                    echo '</div>';

                                    echo '<label for="before" class="label_switch">Avant</label>';
                                echo '</div>';
                            }

                            if ($_SESSION['save']['restaurant_saisi'] == 'A')
                            {
                                echo '<div id="bouton_after" class="switch_restaurant bouton_checked">';
                                    echo '<div class="zone_radio_restaurant">';
                                        echo '<input id="after" type="radio" name="restaurant" value="A" class="radio_restaurant" checked />';
                                    echo '</div>';

                                    echo '<label for="after" class="label_switch">Après</label>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div id="bouton_after" class="switch_restaurant">';
                                    echo '<div class="zone_radio_restaurant">';
                                        echo '<input id="after" type="radio" name="restaurant" value="A" class="radio_restaurant" />';
                                    echo '</div>';

                                    echo '<label for="after" class="label_switch">Après</label>';
                                echo '</div>';
                            }
                        echo '</div>';

                        // Lieu restaurant
                        echo '<div class="zone_saisie_ligne">';
                            echo '<img src="../../includes/icons/moviehouse/restaurants_grey.png" alt="restaurants_grey" title="Restaurant" class="icone_saisie" />';
                            echo '<input type="text" name="place" value="' . $_SESSION['save']['place_saisie'] . '" placeholder="Lieu proposé" class="saisie_ligne" />';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_boutons_saisie">';
                // Valider
                echo '<input type="submit" name="submit_film" value="Valider" id="validerSaisieFilm" class="bouton_saisie_gauche" />';

                // Annuler
                echo '<a id="fermerSaisieFilm" class="bouton_saisie_droite">Annuler</a>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>