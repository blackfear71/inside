<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Succès';
            $styleHead       = 'styleAdmin.css';
            $scriptHead      = 'scriptAdmin.js';
            $chatHead        = false;
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
                $title = 'Gestion succès';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section>
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <article>
                <?php
                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /****************/
                    /* Ajout succès */
                    /****************/
                    include('vue/vue_saisie_success.php');

                    /******************/
                    /* Gestion succès */
                    /******************/
                    include('vue/vue_gestion_success.php');

                    /********************/
                    /* Affichage succès */
                    /********************/
                    include('vue/vue_liste_success.php');
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>