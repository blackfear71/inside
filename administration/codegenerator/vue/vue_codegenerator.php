<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Code Generator';
            $styleHead       = 'styleAdmin.css';
            $scriptHead      = 'scriptAdmin.js';
            $chatHead        = false;
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
            <?php
                $title = 'Générateur de code';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section>
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <article>
                <?php
                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /*********************************************/
                    /* Aide au développement d'une nouvelle page */
                    /*********************************************/
                    echo '<div class="zone_generator_left margin_right_20">';
                        // Fiche des impacts de développements
                        echo '<div class="titre_section"><img src="../../includes/icons/admin/download_grey.png" alt="download_grey" class="logo_titre_section" /><div class="texte_titre_section">Fiche des impacts de développements</div></div>';

                        echo '<div class="explications_generator">';
                            echo 'Ce bouton sert à télécharger une fiche détaillée permettant de déterminer un maximum d\'impacts pouvant intervenir lors de nouveaux développements sur le site.';
                        echo '</div>';

                        echo '<a href="../../fiche_impacts_developpements.xlsx" class="bouton_fiche_generator" download>Télécharger la fiche</a>';

                        // Web
                        echo '<div class="titre_section"><img src="../../includes/icons/admin/informations_grey.png" alt="informations_grey" class="logo_titre_section" /><div class="texte_titre_section">Aide au développement d\'une nouvelle page (web)</div></div>';

                        echo '<div class="explications_generator">';
                            echo 'Lors du développement d\'une nouvelle section, il est impératif de suivre certains points :';

                            echo '<ul>';
                                echo '<li>Respecter l\'<strong>architecture MVC</strong> du site</li>';
                                echo '<li>Ajouter une <strong>icône</strong> sur la page d\'index</li>';
                                echo '<li>Si la nouvelle section implique l\'utilisation d\'une <strong>préférence utilisateur</strong>, en tenir compte à l\'<strong>inscription</strong> d\'un nouvel utilisateur</li>';
                                echo '<li>Si la nouvelle section implique la création d\'<strong>enregistrements personnalisés</strong>, en tenir compte à la <strong>désinscription</strong></li>';
                                echo '<li>Modifier les commentaires dans le <strong>contrôleur</strong> généré</li>';
                                echo '<li>Si c\'est une page utilisateur, ajouter un <strong>lien sur le portail</strong> principal</li>';
                                echo '<li>Si c\'est une page utilisateur, ajouter un <strong>lien dans les onglets</strong> de navigation</li>';
                                echo '<li>Si c\'est une page utilisateur, rajouter la page dans la <strong>liste des pages éligibles aux missions</strong> (fonction generateMission() dans metier_commun.php)</li>';
                                echo '<li>Gérer la nouvelle page dans la <strong>section des logs</strong> (modifier les 2 fonctions getCategories())</li>';
                                echo '<li>Mettre à jour le fichier <strong>readme.md</strong> si besoin (pour GitHub)</li>';
                            echo '</ul>';
                        echo '</div>';

                        // Mobile
                        echo '<div class="titre_section"><img src="../../includes/icons/admin/mobile_grey.png" alt="mobile_grey" class="logo_titre_section" /><div class="texte_titre_section">Aide au développement d\'une nouvelle page (mobile)</div></div>';

                        echo '<div class="explications_generator">';
                            echo 'Les développements mobiles reprennent les règles du développement web. Les contrôleurs et les métiers sont communs, seule la vue est spécifique pour lui adapter un style particulier. Certains points particuliers sont à suivre :';

                            echo '<ul>';
                                echo '<li>Lors de l\'ajout d\'une nouvelle section, celle-ci <strong>doit être autorisée</strong> dans la fonction isAccessibleMobile() de metier_commun.php</li>';
                                echo '<li>Débloquer la section dans <strong>le portail</strong> dans la fonction getPortail() de metier_portail.php</li>';
                                echo '<li>Ajouter un lien vers la section dans <strong>le menu latéral</strong> de gauche (aside_mobile.php)</li>';
                                echo '<li>Ajouter du contenu pour <strong>Celsius</strong> dans celsius.php</li>';
                            echo '</ul>';
                        echo '</div>';
                    echo '</div>';

                    /********************************/
                    /* Données de la page à générer */
                    /********************************/
                    echo '<div class="zone_generator_right">';
                        include('vue/vue_saisie_codegenerator.php');
                    echo '</div>';

                    /***************/
                    /* Code généré */
                    /***************/
                    include('vue/vue_code_generated.php');
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>