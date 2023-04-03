<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Recherche';
            $styleHead       = 'styleSearch.css';
            $scriptHead      = '';
            $angularHead     = false;
            $chatHead        = true;
            $datepickerHead  = false;
            $masonryHead     = false;
            $exifHead        = false;
            $html2canvasHead = false;
            $jqueryCsv       = false;

            include('../../includes/common/head.php');
        ?>
    </head>

    <body>
        <!-- Entête -->
        <header>
            <?php
                $title = 'Recherche';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section class="section_no_nav">
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <!-- Déblocage succès -->
            <?php include('../../includes/common/success.php'); ?>

            <!-- Celsius -->
            <?php
                $celsius = 'search';

                include('../../includes/common/web/celsius.php');
            ?>

            <article>
                <?php
                    /********************/
                    /* Boutons missions */
                    /********************/
                    $zoneInside = 'article';

                    include('../../includes/common/missions.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********************/
                    /* Résultats recherche */
                    /***********************/
                    echo '<div class="zone_recherche">';
                        if (!empty($resultats))
                        {
                            // Message pas de résultats
                            if (empty($resultats['movie_house'])
                            AND empty($resultats['food_advisor'])
                            AND empty($resultats['cooking_box'])
                            AND empty($resultats['petits_pedestres'])
                            AND empty($resultats['missions']))
                                echo '<div class="titre_section"><img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" /><div class="texte_titre_section">Pas de résultats trouvés pour "' . $_SESSION['search']['text_search'] . '" !</div></div>';

                            // Résultats films
                            if (!empty($resultats['movie_house']))
                            {
                                // Titre
                                echo '<div class="titre_section">';
                                    echo '<img src="../../includes/icons/search/movie_house.png" alt="movie_house" class="logo_titre_section" />';
                                    
                                    echo '<div class="texte_titre_section_fold">Movie House</div>';
                                    
                                    // Compteur
                                    echo '<div class="count_search">' . $resultats['nb_movie_house'] . '</div>';
                                echo '</div>';

                                // Résultats
                                foreach ($resultats['movie_house'] as $resultatsMH)
                                {
                                    echo '<a href="../moviehouse/details.php?id_film=' . $resultatsMH->getId() . '&action=goConsulter" class="lien_resultat">';
                                        echo '<table class="zone_resultat">';
                                            echo '<tr>';
                                                echo '<td class="zone_resultat_titre">';
                                                    echo $resultatsMH->getFilm();
                                                echo '</td>';

                                                echo '<td class="zone_resultat_info">';
                                                    if (!empty($resultatsMH->getDate_theater()))
                                                        echo 'Sortie au cinéma le ' . formatDateForDisplay($resultatsMH->getDate_theater());
                                                    else
                                                        echo 'Sortie au cinéma non communiquée';
                                                echo '</td>';
                                            echo '</tr>';
                                        echo '</table>';
                                    echo '</a>';
                                }
                            }

                            // Résultats restaurants
                            if (!empty($resultats['food_advisor']))
                            {
                                // Titre
                                echo '<div class="titre_section">';
                                    echo '<img src="../../includes/icons/search/restaurants.png" alt="restaurants" class="logo_titre_section" />';
                                    
                                    echo '<div class="texte_titre_section_fold">Restaurants</div>';
                                    
                                    // Compteur
                                    echo '<div class="count_search">' . $resultats['nb_food_advisor'] . '</div>';
                                echo '</div>';

                                // Résultats
                                foreach ($resultats['food_advisor'] as $resultatsFA)
                                {
                                    echo '<a href="../foodadvisor/restaurants.php?action=goConsulter&anchor=' . $resultatsFA->getId() . '" class="lien_resultat">';
                                        echo '<table class="zone_resultat">';
                                            echo '<tr>';
                                                echo '<td class="zone_resultat_titre">';
                                                    echo $resultatsFA->getName();
                                                echo '</td>';

                                                echo '<td class="zone_resultat_info">';
                                                    echo $resultatsFA->getLocation();
                                                echo '</td>';
                                            echo '</tr>';
                                        echo '</table>';
                                    echo '</a>';
                                }
                            }

                            // Résultats recettes
                            if (!empty($resultats['cooking_box']))
                            {
                                // Titre
                                echo '<div class="titre_section">';
                                    echo '<img src="../../includes/icons/search/cooking_box.png" alt="cooking_box" class="logo_titre_section" />';
                                    
                                    echo '<div class="texte_titre_section_fold">Cooking Box</div>';
                                    
                                    // Compteur
                                    echo '<div class="count_search">' . $resultats['nb_cooking_box'] . '</div>';
                                echo '</div>';

                                // Résultats
                                foreach ($resultats['cooking_box'] as $resultatsCB)
                                {
                                    echo '<a href="../cookingbox/cookingbox.php?year=' . $resultatsCB->getYear() . '&action=goConsulter&anchor=' . $resultatsCB->getId() . '" class="lien_resultat">';
                                        echo '<table class="zone_resultat">';
                                            echo '<tr>';
                                                echo '<td class="zone_resultat_titre">';
                                                    echo $resultatsCB->getName();
                                                echo '</td>';

                                                echo '<td class="zone_resultat_info">';
                                                    echo 'Semaine ' . formatWeekForDisplay($resultatsCB->getWeek()) . ' (' . $resultatsCB->getYear() . ')';
                                                echo '</td>';
                                            echo '</tr>';
                                        echo '</table>';
                                    echo '</a>';
                                }
                            }

                            // Résultats parcours
                            if (!empty($resultats['petits_pedestres']))
                            {
                                // Titre
                                echo '<div class="titre_section">';
                                    echo '<img src="../../includes/icons/search/petits_pedestres.png" alt="petits_pedestres" class="logo_titre_section" />';
                                    
                                    echo '<div class="texte_titre_section_fold">Les Petits Pédestres</div>';
                                    
                                    // Compteur
                                    echo '<div class="count_search">' . $resultats['nb_petits_pedestres'] . '</div>';
                                echo '</div>';

                                // Résultats
                                foreach ($resultats['petits_pedestres'] as $resultatsPP)
                                {
                                    echo '<a href="../petitspedestres/details.php?id_parcours=' . $resultatsPP->getId() . '&action=goConsulter" class="lien_resultat">';
                                        echo '<table class="zone_resultat">';
                                            echo '<tr>';
                                                echo '<td class="zone_resultat_titre">';
                                                    echo $resultatsPP->getName();
                                                echo '</td>';

                                                echo '<td class="zone_resultat_info">';
                                                    echo formatDistanceForDisplay($resultatsPP->getDistance());
                                                echo '</td>';
                                            echo '</tr>';
                                        echo '</table>';
                                    echo '</a>';
                                }
                            }

                            // Résultats missions
                            if (!empty($resultats['missions']))
                            {
                                // Titre
                                echo '<div class="titre_section">';
                                    echo '<img src="../../includes/icons/search/missions.png" alt="missions" class="logo_titre_section" />';
                                    
                                    echo '<div class="texte_titre_section_fold">Missions</div>';
                                    
                                    // Compteur
                                    echo '<div class="count_search">' . $resultats['nb_missions'] . '</div>';
                                echo '</div>';
                                
                                // Résultats
                                foreach ($resultats['missions'] as $resultatsMI)
                                {
                                    echo '<a href="../missions/details.php?id_mission=' . $resultatsMI->getId() . '&action=goConsulter" class="lien_resultat">';
                                        echo '<table class="zone_resultat">';
                                            echo '<tr>';
                                                echo '<td class="zone_resultat_titre">';
                                                    echo $resultatsMI->getMission();
                                                echo '</td>';

                                                echo '<td class="zone_resultat_info">';
                                                    if (date('Ymd') > $resultatsMI->getDate_fin())
                                                        echo 'Terminée le ' . formatDateForDisplay($resultatsMI->getDate_fin());
                                                    else
                                                        echo 'Débutée le ' . formatDateForDisplay($resultatsMI->getDate_deb());
                                                echo '</td>';
                                            echo '</tr>';
                                        echo '</table>';
                                    echo '</a>';
                                }
                            }
                        }
                        else
                        {
                            // Titre
                            echo '<div class="titre_section"><img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" /><div class="texte_titre_section">Pas de résultats</div></div>';

                            echo '<div class="empty">Veuillez saisir et relancer la recherche afin d\'obtenir des résultats...</div>';
                        }
                    echo '</div>';
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>