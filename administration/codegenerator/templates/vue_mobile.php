<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = /*title_head*/;
            $styleHead       = /*style_specifique*/;
            $scriptHead      = /*script_specifique*/;
            $angularHead     = /*angular_head*/;
            $chatHead        = /*chat_head*/;
            $datepickerHead  = /*datepicker_head*/;
            $masonryHead     = /*masonry_head*/;
            $exifHead        = /*exif_head*/;
            $html2canvasHead = /*html2canvas_head*/;
            $jqueryCsv       = /*jquerycsv_head*/;

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
                $celsius = /*celsius*/;
                include('../../includes/common/mobile/celsius.php');
            ?>

            <!-- Contenu -->
            <article>
                <?php/*missions*/
                    /*********************/
                    /* Zone de recherche */
                    /*********************/
                    include('../../includes/common/mobile/search_mobile.php');

                    /*********/
                    /* Titre */
                    /*********/
                    echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

                    /*********/
                    /* Liens */
                    /*********/

                    /**********/
                    /* Saisie */
                    /**********/

                    /********************/
                    /* Boutons d'action */
                    /********************/

                    /***********/
                    /* Contenu */
                    /***********/
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
        </footer>
    </body>
</html>