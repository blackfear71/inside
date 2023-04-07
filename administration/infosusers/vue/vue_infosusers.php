<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Infos utilisateurs';
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
                $title = 'Informations utilisateurs';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section>
            <article>
                <?php
                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Equipes */
                    /***********/
                    include('vue/vue_liste_equipes.php');

                    /**********************/
                    /* Liste utilisateurs */
                    /**********************/
                    include('vue/vue_liste_utilisateurs.php');
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>