<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Collector Room';
            $styleHead       = 'styleCO.css';
            $scriptHead      = 'scriptCO.js';
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
                $celsius = 'collector';

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

                    /***********/
                    /* Saisies */
                    /***********/
                    include('vue/mobile/vue_saisie_phrase_culte.php');
                    include('vue/mobile/vue_saisie_image_culte.php');

                    /********************/
                    /* Vote utilisateur */
                    /********************/
                    include('vue/mobile/vue_vote_user.php');

                    /***************************/
                    /* Votes tous utilisateurs */
                    /***************************/
                    include('vue/mobile/vue_votes_collector.php');

                    /********************/
                    /* Boutons d'action */
                    /********************/
                    // Saisie phrase culte
                    echo '<a id="afficherSaisiePhraseCulte" title="Saisir une phrase culte" class="lien_green lien_demi margin_lien">';
                        echo '<img src="../../includes/icons/collector/collector_grey.png" alt="collector_grey" class="image_lien" />';
                        echo '<div class="titre_lien">PHRASE CULTE</div>';
                    echo '</a>';

                    // Saisie image culte
                    echo '<a id="afficherSaisieImageCulte" title="Saisir une image culte" class="lien_green lien_demi">';
                        echo '<img src="../../includes/icons/collector/images_grey.png" alt="images_grey" class="image_lien" />';
                        echo '<div class="titre_lien">IMAGE CULTE</div>';
                    echo '</a>';

                    /*******************/
                    /* Tris et filtres */
                    /*******************/
                    include('vue/mobile/vue_tris_filtres.php');

                    /***********/
                    /* Contenu */
                    /***********/
                    include('vue/mobile/vue_liste_collectors.php');

                    /**************/
                    /* Pagination */
                    /**************/
                    include('vue/mobile/vue_pagination.php');
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
        </footer>

        <!-- Données JSON -->
        <script>
            // Récupération de l'équipe pour le script
            var equipeUser = <?php if (isset($equipeJson) AND !empty($equipeJson)) echo $equipeJson; else echo '{}'; ?>;

            // Récupération de la liste des utilisateurs pour le script
            var listeUsers = <?php if (isset($listeUsersJson) AND !empty($listeUsersJson)) echo $listeUsersJson; else echo '{}'; ?>;

            // Récupération de la liste des phrases / images cultes pour le script
            var listeCollectors = <?php if (isset($listeCollectorsJson) AND !empty($listeCollectorsJson)) echo $listeCollectorsJson; else echo '{}'; ?>;
        </script>
    </body>
</html>