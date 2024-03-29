<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Cooking Box';
            $styleHead       = 'styleCB.css';
            $scriptHead      = 'scriptCB.js';
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
                $title = 'Cooking Box';

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
                $celsius = 'cookingbox';
                
                include('../../includes/common/web/celsius.php');
            ?>

            <article>
                <?php
                    /********************/
                    /* Boutons missions */
                    /********************/
                    $zoneInside = 'article';

                    include('../../includes/common/missions.php');

                    if (!empty($listeSemaines))
                    {
                        /*******************/
                        /* Liens de saisie */
                        /*******************/
                        echo '<div class="zone_liens_saisie">';
                            // Bouton saisie
                            echo '<a id="ajouterRecette" title="Ajouter un gâteau ou une recette" class="lien_categorie">';
                                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/cooking_box.png" alt="cooking_box" class="image_lien" /></div>';
                                echo '<div class="zone_texte_lien">Ajouter un gâteau ou une recette</div>';
                            echo '</a>';
                        echo '</div>';
                    }

                    /*****************************/
                    /* Zone de saisie de recette */
                    /*****************************/
                    include('vue/web/vue_saisie_recette.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /*********************************/
                    /* Gâteaux des semaines n et n+1 */
                    /*********************************/
                    include('vue/web/vue_semaines.php');

                    /**********/
                    /* Années */
                    /**********/
                    include('vue/web/vue_onglets.php');

                    /************/
                    /* Recettes */
                    /************/
                    include('vue/web/vue_recettes.php');
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
            // Récupération des semaines pour le script
            var currentWeek = <?php if (isset($currentWeekJson) AND !empty($currentWeekJson)) echo $currentWeekJson; else echo '{}'; ?>;
            var nextWeek    = <?php if (isset($nextWeekJson) AND !empty($nextWeekJson)) echo $nextWeekJson; else echo '{}'; ?>;

            // Récupération de la liste des semaines par années pour le script
            var listWeeks = <?php if (isset($listeSemainesJson) AND !empty($listeSemainesJson)) echo $listeSemainesJson; else echo '{}'; ?>;

            // Récupération de la liste des utilisateurs pour le script
            var listCookers = <?php if (isset($listeCookersJson) AND !empty($listeCookersJson)) echo $listeCookersJson; else echo '{}'; ?>;

            // Récupération de la liste des recettes pour le script
            var listRecipes = <?php if (isset($recettesJson) AND !empty($recettesJson)) echo $recettesJson; else echo '{}'; ?>;

            // Récupération utilisateur connecté
            var userSession = <?php echo json_encode($_SESSION['user']['identifiant']); ?>;
        </script>
    </body>
</html>