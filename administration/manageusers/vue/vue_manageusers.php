<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Gestion utilisateurs';
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
                $title = 'Gestion utilisateurs';

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

                    /***********************************/
                    /* Mot de passe (réinitialisation) */
                    /***********************************/
                    if (isset($_SESSION['save']['user_ask_id'])   AND !empty($_SESSION['save']['user_ask_id'])
                    AND isset($_SESSION['save']['user_ask_name']) AND !empty($_SESSION['save']['user_ask_name'])
                    AND isset($_SESSION['save']['new_password'])  AND !empty($_SESSION['save']['new_password']))
                    {
                        echo '<div class="zone_reinitialisation_mdp">';
                            echo '<div class="message_reinitialisation_mdp">Le mot de passe a été réinitialisé pour l\'utilisateur <b>' . $_SESSION['save']['user_ask_id'] . ' / ' . $_SESSION['save']['user_ask_name'] . '</b> : </div>';
                            echo '<div class="mdp_reinitialisation_mdp">' . $_SESSION['save']['new_password'] . '</div>';
                        echo '</div>';

                        $_SESSION['save']['user_ask_id']   = '';
                        $_SESSION['save']['user_ask_name'] = '';
                        $_SESSION['save']['new_password']  = '';
                    }

                    /****************************/
                    /* Tableau des utilisateurs */
                    /****************************/
                    include('vue/vue_table_users.php');

                    /****************************/
                    /* Tableau des statistiques */
                    /****************************/
                    include('vue/vue_statistiques_users.php');
                ?>

            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>

        <!-- Données JSON -->
        <script>
            // Récupération de la liste des statistiques cultes pour le script
            var tableauStatistiquesInsJson = <?php if (isset($tableauStatistiquesInsJson) AND !empty($tableauStatistiquesInsJson)) echo $tableauStatistiquesInsJson; else echo '{}'; ?>;
            var tableauStatistiquesDesJson = <?php if (isset($tableauStatistiquesDesJson) AND !empty($tableauStatistiquesDesJson)) echo $tableauStatistiquesDesJson; else echo '{}'; ?>;
        </script>
    </body>
</html>