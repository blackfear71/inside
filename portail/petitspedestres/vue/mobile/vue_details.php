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
                    echo '<div class="titre_section_mobile">' . mb_strtoupper($detailsParcours->getName()) . '</div>';

                    /***********/
                    /* Saisies */
                    /***********/
                    // Parcours
                    include('vue/mobile/vue_saisie_parcours.php');

                    // Participation
                    include('vue/mobile/vue_saisie_participation.php');

                    /********************/
                    /* Boutons d'action */
                    /********************/
                    // Modification parcours
                    echo '<a id="afficherModificationParcours" title="Modifier le parcours" class="lien_green lien_demi margin_lien">';
                        echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="image_lien" />';
                        echo '<div class="titre_lien">MODIFIER</div>';
                    echo '</a>';

                    // Suppression parcours
                    echo '<form id="delete_parcours" method="post" action="details.php?action=doSupprimerParcours" title="Supprimer le parcours" class="lien_green lien_demi">';
                        echo '<div class="eventConfirm">';
                            echo '<img src="../../includes/icons/common/delete_grey.png" alt="delete_grey" class="image_lien" />';

                            echo '<input type="hidden" name="id_parcours" value="' . $detailsParcours->getId() . '" />';
                            echo '<input type="hidden" name="team_parcours" value="' . $detailsParcours->getTeam() . '" />';
                            echo '<input type="submit" name="delete_parcours" value="SUPPRIMER" title="Supprimer le parcours" class="titre_lien_formulaire eventConfirm" />';
                            echo '<input type="hidden" value="Demander la suppression de ce parcours ?" class="eventMessage" />';
                        echo '</div>';
                    echo '</form>';

                    /***********/
                    /* Contenu */
                    /***********/
                    // Parcours
                    include('vue/mobile/vue_details_parcours.php');

                    // Participations
                    include('vue/mobile/vue_participations.php');
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
            var pageAppelante = 'details';
            
            // Récupération des détails du parcours pour le script
            var detailsParcours = <?php if (isset($detailsParcoursJson) AND !empty($detailsParcoursJson)) echo $detailsParcoursJson; else echo '{}'; ?>;
            
            // Récupération de la liste des participations pour le script
            var listeParticipations = <?php if (isset($listeParticipationsJson) AND !empty($listeParticipationsJson)) echo $listeParticipationsJson; else echo '{}'; ?>;
        </script>
    </body>
</html>