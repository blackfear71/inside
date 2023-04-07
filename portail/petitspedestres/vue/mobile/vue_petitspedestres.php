<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Les Petits Pédestres';
            $styleHead       = 'stylePP.css';
            $scriptHead      = 'scriptPP.js';
            $chatHead        = true;
            $datepickerHead  = true;
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

                    /**********/
                    /* Saisie */
                    /**********/
                    // Parcours
                    include('vue/mobile/vue_saisie_parcours.php');

                    // Participation
                    include('vue/mobile/vue_saisie_participation.php');

                    /********************/
                    /* Boutons d'action */
                    /********************/
                    // Saisie parcours
                    echo '<a id="afficherSaisieParcours" title="Saisir un parcours" class="lien_green">';
                        echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="image_lien" />';
                        echo '<div class="titre_lien">AJOUTER UN PARCOURS</div>';
                    echo '</a>';

                    /***********/
                    /* Contenu */
                    /***********/
                    // Tableau de bord
                    include('vue/mobile/vue_tableau_de_bord.php');

                    // Dernières courses
                    include('vue/mobile/vue_dernieres_courses.php');

                    // Parcours
                    include('vue/mobile/vue_liste_parcours.php');
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
            // Page appelante
            var pageAppelante = 'petitspedestres';
            
            // Récupération de la liste des parcours pour le script
            var listeParcours = <?php if (isset($listeParcoursJson) AND !empty($listeParcoursJson)) echo $listeParcoursJson; else echo '{}'; ?>;
        </script>
    </body>
</html>