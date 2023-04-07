<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            switch ($_GET['view'])
            {
                case 'settings':
                    $titleHead = 'Paramètres';
                    break;

                case 'success':
                case 'ranking':
                    $titleHead = 'Succès';
                    break;

                case 'themes':
                    $titleHead = 'Thèmes';
                    break;

                case 'profile':
                default:
                    $titleHead = 'Profil';
                    break;
            }

            $styleHead       = 'styleProfil.css';
            $scriptHead      = 'scriptProfil.js';
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
                switch ($_GET['view'])
                {
                    case 'settings':
                        $celsius = 'settings';
                        break;

                    case 'success':
                    case 'ranking':
                        $celsius = 'success';
                        break;

                    case 'themes':
                        $celsius = 'themes';
                        break;

                    case 'profile':
                    default:
                        $celsius = 'profil';
                        break;
                }

                include('../../includes/common/mobile/celsius_mobile.php');
            ?>

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

                    /*************/
                    /* Affichage */
                    /*************/
                    switch ($_GET['view'])
                    {
                        case 'settings':
                            include('vue/mobile/vue_settings.php');
                            break;

                        case 'success':
                        case 'ranking':
                            include('vue/mobile/vue_success.php');
                            break;

                        case 'themes':
                            include('vue/mobile/vue_themes.php');
                            break;

                        case 'profile':
                        default:
                            include('vue/mobile/vue_informations.php');
                            break;
                    }
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
            // Récupération de la liste des succès débloqués
            var listeSuccess = <?php if (isset($listeSuccessJson) AND !empty($listeSuccessJson)) echo $listeSuccessJson; else echo '{}'; ?>;
        </script>
    </body>
</html>