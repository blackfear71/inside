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
            <?php
                $title = /*title*/;
                include('../../includes/common/web/header.php');/*onglets*/                
            ?>
        </header>

        <!-- Contenu -->
        <section>/*alerts*//*success*/            
            <article>
                <?php/*missions*/
                    /*********/
                    /* Liens */
                    /*********/

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Contenu */
                    /***********/
                ?>
            </article>/*chat*/
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>