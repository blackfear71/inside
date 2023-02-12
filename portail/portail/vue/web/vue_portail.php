<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Portail';
            $styleHead       = 'stylePortail.css';
            $scriptHead      = 'scriptPortail.js';
            $angularHead     = false;
            $chatHead        = true;
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
                $title = 'Portail';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section class="section_no_nav">
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <!-- Déblocage succès -->
            <?php include('../../includes/common/success.php'); ?>

            <article>
                <?php
                    /********************/
                    /* Boutons missions */
                    /********************/
                    $zoneInside = 'article';

                    include('../../includes/common/missions.php');

                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***************/
                    /* Inside News */
                    /***************/
                    echo '<div class="zone_portail_left">';
                        // Titre
                        echo '<div class="titre_section"><img src="../../includes/icons/common/inside_red.png" alt="inside_red" class="logo_titre_section" /><div class="texte_titre_section">INSIDE News</div></div>';

                        echo '<div class="timeline">';
                            foreach ($news as $messageNews)
                            {
                                echo '<div class="trait_timeline"></div>';

                                if (empty($messageNews->getLink()))
                                {
                                    if ($messageNews == end($news))
                                        echo '<div class="zone_news_end">';
                                    else
                                        echo '<div class="zone_news">';
                                }
                                else
                                {
                                    if ($messageNews == end($news))
                                        echo '<a href="' . $messageNews->getLink() . '" class="zone_news_end">';
                                    else
                                        echo '<a href="' . $messageNews->getLink() . '" class="zone_news">';
                                }

                                echo '<img src="../../includes/icons/common/' . $messageNews->getLogo() . '.png" alt="' . $messageNews->getLogo() . '" class="logo_news" />';

                                echo '<div class="zone_contenu_news">';
                                    echo '<div class="titre_news">' . $messageNews->getTitle() . '</div>';
                                    echo '<div class="contenu_news">' . $messageNews->getContent() . '</div>';
                                echo '</div>';

                                if (!empty($messageNews->getDetails()))
                                {
                                    echo '<div class="zone_details_news">';
                                        echo $messageNews->getDetails();
                                    echo '</div>';
                                }

                                if (empty($messageNews->getLink()))
                                    echo '</div>';
                                else
                                    echo '</a>';
                            }
                        echo '</div>';
                    echo '</div>';

                    /***********/
                    /* Portail */
                    /***********/
                    echo '<div class="zone_portail_right">';
                        // Titre
                        echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Portail</div></div>';

                        echo '<div class="menu_portail">';
                            // Liens des catégories
                            foreach ($portail as $lienPortail)
                            {
                                echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail">';
                                    // Logo
                                    echo '<div class="zone_image_portail">';
                                        echo '<img src="' . $lienPortail['image'] . '" alt="' . $lienPortail['alt'] . '" class="image_lien_portail" />';
                                    echo '</div>';

                                    // Titre
                                    echo '<div class="zone_texte_portail">';
                                        echo '<div class="texte_portail">' . $lienPortail['categorie'] . '</div>';
                                    echo '</div>';
                                echo '</a>';
                            }
                        echo '</div>';
                    echo '</div>';
                ?>
            </article>

            <!-- Chat -->
            <?php include('../../includes/common/chat/chat.php'); ?>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>