<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Les enfants ! À table !';
            $styleHead       = 'styleFA.css';
            $scriptHead      = 'scriptFA.js';
            $angularHead     = false;
            $chatHead        = true;
            $datepickerHead  = false;
            $masonryHead     = true;
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

            <article>
                <?php
                    /********************/
                    /* Boutons missions */
                    /********************/
                    $zoneInside = 'article';

                    include('../../includes/common/missions.php');

                    /*********/
                    /* Liens */
                    /*********/
                    echo '<div class="zone_liens_saisie">';
                        // Saisie utilisateur
                        if ($actions['saisir_choix'] == true)
                        {
                            echo '<a id="saisiePropositions" title="Proposer où manger" class="lien_categorie">';
                                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/food_advisor.png" alt="food_advisor" class="image_lien" /></div>';
                                echo '<div class="zone_texte_lien">Proposer où manger</div>';
                            echo '</a>';
                        }

                        // Restaurants
                        echo '<a href="restaurants.php?action=goConsulter" title="Les restaurants" class="lien_categorie">';
                            echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" class="image_lien" /></div>';
                            echo '<div class="zone_texte_lien">Les restaurants</div>';
                        echo '</a>';
                    echo '</div>';

                    /****************/
                    /* Saisie choix */
                    /****************/
                    if ($actions['saisir_choix'] == true)
                        include('vue/web/vue_saisie_propositions.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /*************************/
                    /* Détails détermination */
                    /*************************/
                    include('vue/web/vue_details_determination.php');

                    /***********************************************/
                    /* Propositions, choix et résumé de la semaine */
                    /***********************************************/
                    echo '<div class="zone_propositions_determination">';
                    // Utilisateurs
                    include('vue/web/vue_utilisateurs.php');

                    // Propositions
                    include('vue/web/vue_propositions.php');

                    // Mes choix
                    include('vue/web/vue_mes_choix.php');

                    // Résumé de la semaine
                    include('vue/web/vue_resume_semaine.php');
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

        <!-- Données JSON -->
        <script>
            // Récupération de la liste des lieux du résumé pour le script
            var listeLieuxResume = <?php if (isset($listeLieuxResumeJson) AND !empty($listeLieuxResumeJson)) echo $listeLieuxResumeJson; else echo '{}'; ?>;

            // Récupération de la liste des restaurants du résumé pour le script
            var listeRestaurantsResume = <?php if (isset($listeRestaurantsResumeJson) AND !empty($listeRestaurantsResumeJson)) echo $listeRestaurantsResumeJson; else echo '{}'; ?>;

            // Récupération de la liste des lieux pour le script
            var listeLieux = <?php if (isset($listeLieuxJson) AND !empty($listeLieuxJson)) echo $listeLieuxJson; else echo '{}'; ?>;

            // Récupération de la liste des restaurants pour le script
            var listeRestaurants = <?php if (isset($listeRestaurantsJson) AND !empty($listeRestaurantsJson)) echo $listeRestaurantsJson; else echo '{}'; ?>;

            // Récupération des détails des propositions pour le script
            var detailsPropositions = <?php if (isset($detailsPropositions) AND !empty($detailsPropositions)) echo $detailsPropositions; else echo '{}'; ?>;

            // Récupération utilisateur connecté
            var userSession = <?php echo json_encode($_SESSION['user']['identifiant']); ?>;
        </script>
    </body>
</html>