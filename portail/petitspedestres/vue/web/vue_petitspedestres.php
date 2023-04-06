<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Les Petits Pédestres';
            $styleHead       = 'stylePP.css';
            $scriptHead      = 'scriptPP.js';
            $angularHead     = false;
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
            <?php
                $title = 'Les Petits Pédestres';
                
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
                $celsius = 'petitspedestres';
                
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
                        // Saisie parcours
                        echo '<a id="ajouterParcours" title="Ajouter un parcours" class="lien_categorie">';
                            echo '<div class="zone_logo_lien"><img src="../../includes/icons/petitspedestres/parcours.png" alt="parcours" class="image_lien" /></div>';
                            echo '<div class="zone_texte_lien">Ajouter un parcours</div>';
                        echo '</a>';
                    echo '</div>';

                    /***********/
                    /* Saisies */
                    /***********/
                    // Parcours
                    include('vue/web/vue_saisie_parcours.php');

                    // Participation
                    include('vue/web/vue_saisie_participation.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Contenu */
                    /***********/
                    // Tableau de bord
                    include('vue/web/vue_tableau_de_bord.php');

                    // Dernières courses
                    include('vue/web/vue_dernieres_courses.php');

                    // Parcours
                    include('vue/web/vue_liste_parcours.php');
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
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