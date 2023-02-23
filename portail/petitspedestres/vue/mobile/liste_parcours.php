<!DOCTYPE html>
<html lang="fr" ng-app="parcoursApp">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Les Petits Pédestres';
            $styleHead       = 'stylePP.css';
            $scriptHead      = 'scriptPP.js';
            $angularHead     = true;
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
                $celsius = 'petitspedestres';
                
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

                    /*********/
                    /* Liens */
                    /*********/
                    // Ajout parcours
                    echo '<a href="parcours.php?action=goAjouter" title="Ajouter un parcours" class="lien_red">';
                        echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="image_lien" />';
                        echo '<div class="titre_lien">AJOUTER PARCOURS</div>';
                    echo '</a>';

                    /************/
                    /* Parcours */
                    /************/
                    if (!empty($listeParcours))
                        echo '<parcours-list></parcours-list>';
                    else
                        echo '<div class="empty">Aucun parcours disponible...</div>';
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
        </footer>

        <script>
            var listeParcoursJson = <?php echo $listeParcoursJson; ?>;
        </script>
    </body>
</html>