<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Calendars';
            $styleHead       = 'styleCA.css';
            $scriptHead      = 'scriptCA.js';
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
                $title = 'Calendars';

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
                $celsius = 'calendars';

                include('../../includes/common/web/celsius.php');
            ?>

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
                    if ($preferences->getManage_calendars() == 'Y')
                    {
                        echo '<div class="zone_liens_saisie">';
                            // Création calendrier
                            echo '<a href="calendars_generator.php?action=goConsulter" title="Créer un calendrier" class="lien_categorie">';
                                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/edit.png" alt="edit" class="image_lien" /></div>';
                                echo '<div class="zone_texte_lien">Créer un nouveau calendrier ou une annexe</div>';
                            echo '</a>';
                        echo '</div>';
                    }

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /**********/
                    /* Années */
                    /**********/
                    echo '<div class="zone_calendars_onglets">';
                        include('vue/web/vue_onglets.php');
                    echo '</div>';

                    /***********/
                    /* Contenu */
                    /***********/
                    if ($_GET['action'] == 'goConsulterAnnexes')
                        include('vue/web/vue_liste_annexes.php');
                    else
                        include('vue/web/vue_liste_calendars.php');
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