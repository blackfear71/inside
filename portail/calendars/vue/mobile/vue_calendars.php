<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Calendars';
            $styleHead       = 'styleCA.css';
            $scriptHead      = 'scriptCA.js';
            $angularHead     = false;
            $chatHead        = true;
            $datepickerHead  = false;
            $masonryHead     = false;
            $exifHead        = false;
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
                $celsius = 'calendars';

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

                    /********/
                    /* Vues */
                    /********/
                    include('vue/mobile/vue_vues.php');

                    /********************/
                    /* Boutons d'action */
                    /********************/
                    if ($preferences->getManage_calendars() == 'Y')
                    {
                        // Vues
                        echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_red lien_demi margin_lien">';
                            echo '<img src="../../includes/icons/calendars/year_grey.png" alt="year_grey" class="image_lien" />';

                            if ($_GET['action'] == 'goConsulterAnnexes')
                                echo '<div class="titre_lien">ANNEXES</div>';
                            else
                                echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
                        echo '</a>';

                        // Créer calendrier ou annexe
                        echo '<a href="calendars_generator.php?action=goConsulter" title="Créer un calendrier ou une annexe" class="lien_green lien_demi">';
                            echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="image_lien" />';
                            echo '<div class="titre_lien">CRÉER</div>';
                        echo '</a>';
                    }
                    else
                    {
                        // Vues
                        echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_red margin_lien">';
                            echo '<img src="../../includes/icons/calendars/year_grey.png" alt="year_grey" class="image_lien" />';

                            if ($_GET['action'] == 'goConsulterAnnexes')
                                echo '<div class="titre_lien">ANNEXES</div>';
                            else
                                echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
                        echo '</a>';
                    }

                    /***********/
                    /* Contenu */
                    /***********/
                    if ($_GET['action'] == 'goConsulterAnnexes')
                        include('vue/mobile/vue_liste_annexes.php');
                    else
                        include('vue/mobile/vue_liste_calendars.php');
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
        </footer>
    </body>
</html>