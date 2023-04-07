<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Movie House';
            $styleHead       = 'styleMH.css';
            $scriptHead      = 'scriptMH.js';
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
            <?php include('../../includes/common/mobile/header_mobile.php'); ?>
        </header>

        <!-- Contenu -->
        <section>
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <!-- Déblocage succès -->
            <?php include('../../includes/common/success.php'); ?>

            <!-- Menus -->
            <aside>
                <?php include('../../includes/common/mobile/aside_mobile.php'); ?>
            </aside>

            <!-- Chargement page -->
            <div class="zone_loading_image">
                <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
            </div>

            <!-- Celsius -->
            <?php
                $celsius = 'moviehouse';

                include('../../includes/common/mobile/celsius_mobile.php');
            ?>

            <!-- Contenu -->
            <article>
                <?php
                    /********************/
                    /* Boutons missions */
                    /********************/
                    $zoneInside = 'article';

                    include('../../includes/common/missions.php');

                    /*********************/
                    /* Zone de recherche */
                    /*********************/
                    include('../../includes/common/mobile/search_mobile.php');

                    /*********/
                    /* Titre */
                    /*********/
                    echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

                    /********/
                    /* Vues */
                    /********/
                    include('vue/mobile/vue_vues.php');

                    /**********/
                    /* Années */
                    /**********/
                    include('vue/mobile/vue_annees.php');

                    /*****************/
                    /* Participation */
                    /*****************/
                    include('vue/mobile/vue_saisie_preference.php');

                    /**********/
                    /* Saisie */
                    /**********/
                    include('vue/mobile/vue_saisie_film.php');

                    /********************/
                    /* Boutons d'action */
                    /********************/
                    // Vues
                    echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_green lien_demi margin_lien">';
                        echo '<img src="../../includes/icons/moviehouse/view_grey.png" alt="view_grey" class="image_lien" />';

                        switch ($_GET['view'])
                        {
                            case 'home':
                                echo '<div class="titre_lien">ACCUEIL</div>';
                                break;

                            case 'cards':
                            default:
                                echo '<div class="titre_lien">FICHES</div>';
                                break;
                        }
                    echo '</a>';

                    // Années
                    echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_green lien_demi">';
                        echo '<img src="../../includes/icons/moviehouse/recent_grey.png" alt="recent_grey" class="image_lien" />';

                        if ($_GET['year'] == 'none')
                            echo '<div class="titre_lien">ANNÉE - N. C.</div>';
                        else
                            echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
                    echo '</a>';

                    // Saisie film
                    echo '<a id="afficherSaisieFilm" title="Saisir un film" class="lien_red">';
                        echo '<img src="../../includes/icons/moviehouse/movie_house_grey.png" alt="movie_house_grey" class="image_lien" />';
                        echo '<div class="titre_lien">AJOUTER UN FILM</div>';
                    echo '</a>';

                    /*********/
                    /* Films */
                    /*********/
                    switch ($_GET['view'])
                    {
                        case 'cards':
                            include('vue/mobile/vue_films_fiches.php');
                            break;

                        case 'home':
                        default:
                            include('vue/mobile/vue_films_accueil.php');
                            break;
                    }
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
        </footer>
    </body>
</html>