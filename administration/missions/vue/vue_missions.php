<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Missions';
            $styleHead       = 'styleAdmin.css';
            $scriptHead      = 'scriptAdmin.js';
            $chatHead        = false;
            $datepickerHead  = true;
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
                $title = 'Gestion missions';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section>
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <article>
                <?php
                    /*********/
                    /* Liens */
                    /*********/
                    if ($_GET['action'] == 'goConsulter')
                    {
                        echo '<div class="zone_liens_saisie">';
                            // Saisie mission
                            echo '<a href="missions.php?action=goAjouter" title="Ajouter une mission" class="lien_categorie">';
                                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/missions.png" alt="missions" class="image_lien" /></div>';
                                echo '<div class="zone_texte_lien">Ajouter une mission</div>';
                            echo '</a>';
                        echo '</div>';
                    }

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Contenu */
                    /***********/
                    switch ($_GET['action'])
                    {
                        case 'goConsulter':
                            include('vue/vue_liste_missions.php');
                            break;

                        case 'goAjouter':
                        case 'goModifier':
                            include('vue/vue_saisie_mission.php');
                            break;

                        default:
                            break;
                    }
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>