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
            $masonryHead     = false;
            $exifHead        = true;
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
                $celsius = 'restaurants';

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

                    /**********/
                    /* Saisie */
                    /**********/
                    include('vue/mobile/vue_saisie_restaurant.php');

                    /********************/
                    /* Boutons d'action */
                    /********************/
                    // Ajouter un restaurant
                    echo '<a id="afficherSaisieRestaurant" title="Saisir un restaurant" class="lien_green lien_demi margin_lien">';
                        echo '<img src="../../includes/icons/foodadvisor/restaurants_grey.png" alt="restaurants_grey" class="image_lien" />';
                        echo '<div class="titre_lien">AJOUTER</div>';
                    echo '</a>';

                    // Propositions
                    echo '<a href="foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter" title="Les propositions" class="lien_green lien_demi">';
                        echo '<img src="../../includes/icons/foodadvisor/propositions_grey.png" alt="propositions_grey" class="image_lien" />';
                        echo '<div class="titre_lien">PROPOSITIONS</div>';
                    echo '</a>';

                    /***********/
                    /* Détails */
                    /***********/
                    include('vue/mobile/vue_details_restaurant.php');

                    /*********/
                    /* Lieux */
                    /*********/
                    include('vue/mobile/vue_lieux_restaurants.php');

                    /***************/
                    /* Restaurants */
                    /***************/
                    include('vue/mobile/vue_fiches_restaurants.php');
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
        </footer>

        <!-- Données JSON -->
        <script>
            // Récupération de la liste des restaurants pour le script
            var listeRestaurantsJson = <?php if (isset($listeRestaurantsJson) AND !empty($listeRestaurantsJson)) echo $listeRestaurantsJson; else echo '{}'; ?>;

            // Récupération de la liste des choix pour le script
            var mesChoixJson = <?php if (isset($mesChoixJson) AND !empty($mesChoixJson)) echo $mesChoixJson; else echo '{}'; ?>;
        </script>
    </body>
</html>