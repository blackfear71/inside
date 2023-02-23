<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Journal des modifications';
            $styleHead       = 'styleCL.css';
            $scriptHead      = 'scriptCL.js';
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
                $title = 'Journal des modifications';

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
                $celsius = 'changelog';

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

                    /**********/
                    /* Années */
                    /**********/
                    include('vue/web/vue_onglets.php');

                    switch ($_GET['action'])
                    {
                        case 'goConsulterHistoire':
                            /********************/
                            /* Histoire du site */
                            /********************/
                            include('vue/web/vue_history.php');
                            break;

                        case 'goConsulter':
                            /*****************************/
                            /* Journaux de modifications */
                            /*****************************/
                            include('vue/web/vue_journaux.php');
                            break;

                        default:
                            break;
                    }
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