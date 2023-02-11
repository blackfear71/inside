<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Administration';
            $styleHead       = 'styleAdmin.css';
            $scriptHead      = 'scriptAdmin.js';
            $angularHead     = false;
            $chatHead        = false;
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
                $title = 'Administration';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section>
            <article>
                <?php
                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Portail */
                    /***********/
                    echo '<div class="menu_portail">';
                        // Liens des catégories
                        foreach ($portail as $lienPortail)
                        {
                            if ($lienPortail['alert'] == true)
                                echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail lien_portail_alerte">';
                            else
                                echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail">';
                                // Alerte
                                if ($lienPortail['alert'] == true)
                                    echo '<img src="../../includes/icons/admin/alerts.png" alt="alerts" title="Alerte" class="icone_alerte_lien_portail" />';

                                // Logo
                                echo '<div class="zone_image_portail">';
                                    echo '<img src="' . $lienPortail['image'] . '" alt="' . $lienPortail['alt'] . '" class="image_lien_portail" />';
                                echo '</div>';

                                // Titre
                                if ($lienPortail['alert'] == true)
                                    echo '<div class="zone_texte_portail zone_texte_portail_alerte">';
                                else
                                    echo '<div class="zone_texte_portail">';
                                    echo '<div class="texte_portail">' . $lienPortail['categorie'] . '</div>';
                                echo '</div>';
                            echo '</a>';
                        }

                        // Accès phpMyAdmin
                        echo '<a href="/phpmyadmin/" title="phpMyAdmin" target="_blank" class="lien_portail">';
                            // Logo
                            echo '<div class="zone_image_portail">';
                                echo '<img src="../../includes/icons/admin/php.png" alt="php" class="image_lien_portail" />';
                            echo '</div>';

                            // Titre
                            echo '<div class="zone_texte_portail">';
                                echo '<div class="texte_portail">PHPMYADMIN</div>';
                            echo '</div>';
                        echo '</a>';

                        // Sauvegarde BDD
                        echo '<form method="post" action="portail.php?action=doExtract" title="Sauvegarde BDD" class="lien_portail">';
                            // Logo
                            echo '<div class="zone_image_portail">';
                                echo '<img src="../../includes/icons/admin/download.png" alt="download" class="image_lien_portail" />';
                            echo '</div>';

                            // Titre
                            echo '<div class="zone_texte_portail">';
                                echo '<div class="texte_portail">SAUVEGARDE<br />BDD</div>';
                            echo '</div>';

                            // Action
                            echo '<input type="submit" name="export" value="" class="export_bdd" />';
                        echo '</form>';
                    echo '</div>';
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>