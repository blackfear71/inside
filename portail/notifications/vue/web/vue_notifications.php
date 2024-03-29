<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Notifications';
            $styleHead       = 'styleNO.css';
            $scriptHead      = 'scriptNO.js';
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
            <?php
                $title = 'Notifications';

                include('../../includes/common/web/header.php');
            ?>
        </header>

        <!-- Contenu -->
        <section class="section_no_nav">
            <!-- Messages d'alerte -->
            <?php include('../../includes/common/alerts.php'); ?>

            <!-- Déblocage succès -->
            <?php include('../../includes/common/success.php'); ?>

            <!-- Celsius -->
            <?php
                $celsius = 'notifications';

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
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /***********/
                    /* Onglets */
                    /***********/
                    include('vue/web/vue_onglets.php');

                    /*****************/
                    /* Notifications */
                    /*****************/
                    echo '<div class="zone_notifications_right">';
                        if (!empty($notifications))
                        {
                            $dateNotification = '';

                            foreach ($notifications as $notification)
                            {
                                if (!empty($notification->getIcon()) AND !empty($notification->getSentence()))
                                {
                                    // Date
                                    if ($notification->getDate() != $dateNotification)
                                    {
                                        // Titre
                                        echo '<div class="titre_section"><img src="../../includes/icons/notifications/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">' . formatDateForDisplay($notification->getDate()) . '</div></div>';
                                        $dateNotification = $notification->getDate();
                                    }

                                    // Lien si présent
                                    if (!empty($notification->getLink()))
                                        if ($notification->getCategory() == 'doodle')
                                            echo '<a href="' . $notification->getLink() . '" id="lien_details_' . $notification->getContent() . '" target="_blank" class="lien_notification lienDetails">';
                                        else
                                            echo '<a href="' . $notification->getLink() . '" class="lien_notification">';
                                    else
                                        echo '<div class="lien_notification">';

                                    // Contenu (icône, phrase & date)
                                    echo '<table class="zone_notification">';
                                        echo '<tr>';
                                            echo '<td class="zone_notification_icone">';
                                                echo '<img src="../../includes/icons/common/' . $notification->getIcon() . '.png" alt="' . $notification->getIcon() . '" class="icone_notification" />';
                                            echo '</td>';

                                            echo '<td class="zone_notification_contenu">';
                                                echo $notification->getSentence();
                                            echo '</td>';

                                            echo '<td class="zone_notification_date">';
                                                echo formatTimeForDisplay($notification->getTime());
                                            echo '</td>';
                                        echo '</tr>';
                                    echo '</table>';

                                    if (!empty($notification->getLink()))
                                        echo '</a>';
                                    else
                                        echo '</div>';
                                }
                            }
                        }
                        else
                        {
                            // Titre
                            echo '<div class="titre_section"><img src="../../includes/icons/notifications/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">' . formatDateForDisplay(date('Ymd')) . '</div></div>';

                            echo '<div class="empty">Pas encore de notifications...</div>';
                        }
                    echo '</div>';

                    /**************/
                    /* Pagination */
                    /**************/
                    include('vue/web/vue_pagination.php');
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