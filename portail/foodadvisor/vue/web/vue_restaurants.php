<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Les enfants ! À table !';
            $styleHead       = 'styleFA.css';
            $scriptHead      = 'scriptFA.js';
            $chatHead        = true;
            $datepickerHead  = false;
            $masonryHead     = true;
            $exifHead        = true;
            $html2canvasHead = false;
            $jqueryCsv       = false;

            include('../../includes/common/head.php');
        ?>
    </head>

    <body>
        <!-- Entête -->
        <header>
            <?php
                $title = 'Les enfants ! À table !';

                include('../../includes/common/web/header.php');
                include('../../includes/common/web/onglets.php');
            ?>
        </header>

        <!-- Contenu -->
        <section>
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <!-- Déblocage succès -->
            <?php include('../../includes/common/success.php'); ?>

            <!-- Celsius -->
            <?php
                $celsius = 'restaurants';

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
                    /* Liens de saisie */
                    /*******************/
                    echo '<div class="zone_liens_saisie">';
                        // Ajouter un restaurant
                        echo '<a id="saisieRestaurant" title="Ajouter un restaurant" class="lien_categorie">';
                            echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" class="image_lien" /></div>';
                            echo '<div class="zone_texte_lien">Ajouter un restaurant</div>';
                        echo '</a>';

                        // Les propositions
                        echo '<a href="foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter" title="Les propositions" class="lien_categorie">';
                            echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/food_advisor.png" alt="food_advisor" class="image_lien" /></div>';
                            echo '<div class="zone_texte_lien">Les propositions</div>';
                        echo '</a>';
                    echo '</div>';

                    /*********************/
                    /* Saisie restaurant */
                    /*********************/
                    include('vue/web/vue_saisie_restaurant.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /********************/
                    /* Liens vers lieux */
                    /********************/
                    echo '<div class="zone_liens_lieux">';
                        foreach ($listeLieux as $lieu)
                        {
                            echo '<a id="link_' . formatId($lieu) . '" class="lien_lieu lienLieu">';
                                // Icône
                                echo '<div class="image_lieu"></div>';

                                // Nom du lieu
                                echo '<div class="nom_lieu">' . $lieu . '</div>';
                            echo '</a>';
                        }
                    echo '</div>';

                    /**************************/
                    /* Fiches des restaurants */
                    /**************************/
                    include('vue/web/vue_fiches_restaurants.php');
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