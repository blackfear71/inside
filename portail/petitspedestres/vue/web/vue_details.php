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
                        // Modification parcours
                        echo '<a id="modifierParcours" title="Modifier le parcours" class="lien_categorie">';
                            echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/edit.png" alt="edit" class="image_lien" /></div>';
                            echo '<div class="zone_texte_lien">Modifier le parcours</div>';
                        echo '</a>';

                        // Suppression parcours
                        echo '<form id="delete_parcours" method="post" action="details.php?action=doSupprimerParcours" class="lien_categorie">';
                            echo '<input type="hidden" name="id_parcours" value="' . $detailsParcours->getId() . '" />';
                            echo '<input type="hidden" name="team_parcours" value="' . $detailsParcours->getTeam() . '" />';

                            echo '<div class="zone_logo_lien">';
                                echo '<input type="submit" name="delete_parcours" value="" title="Supprimer le parcours" class="image_formulaire_suppression eventConfirm" />';
                            echo '</div>';

                            echo '<input type="submit" name="delete_parcours" value="Supprimer le parcours" title="Supprimer le parcours" class="zone_texte_formulaire eventConfirm" />';
                            echo '<input type="hidden" value="Demander la suppression de ce parcours ?" class="eventMessage" />';
                        echo '</form>';
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
                    // Parcours
                    include('vue/web/vue_details_parcours.php');

                    // Participations
                    include('vue/web/vue_participations.php');
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
            var pageAppelante = 'details';
            
            // Récupération des détails du parcours pour le script
            var detailsParcours = <?php if (isset($detailsParcoursJson) AND !empty($detailsParcoursJson)) echo $detailsParcoursJson; else echo '{}'; ?>;
            
            // Récupération de la liste des participations pour le script
            var listeParticipations = <?php if (isset($listeParticipationsJson) AND !empty($listeParticipationsJson)) echo $listeParticipationsJson; else echo '{}'; ?>;
        </script>
    </body>
</html>