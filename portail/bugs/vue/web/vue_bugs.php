<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Demandes d\'évolution';
            $styleHead       = 'styleBugs.css';
            $scriptHead      = 'scriptBugs.js';
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
            <?php
                $title = 'Demandes d\'évolution';

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
                $celsius = 'bugs';

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
                        echo '<a id="ajouterRapport" title="Rapporter un bug ou une évolution" class="lien_categorie">';
                            echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/alert.png" alt="alert" class="image_lien" /></div>';
                            echo '<div class="zone_texte_lien">Rapporter un bug ou une évolution</div>';
                        echo '</a>';
                    echo '</div>';

                    /*****************************/
                    /* Zone de saisie de rapport */
                    /*****************************/
                    include('vue/web/vue_saisie_rapport.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Onglets */
                    /***********/
                    include('vue/web/vue_onglets.php');

                    /************/
                    /* Rapports */
                    /************/
                    include('vue/web/vue_liste_rapports.php');
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