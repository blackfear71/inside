<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Missions : Insider';
            $styleHead       = 'styleMI.css';
            $scriptHead      = 'scriptMI.js';
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
                $title = 'Missions : Insider';

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

                    /**********************/
                    /* Liste des missions */
                    /**********************/
                    if (!empty($listeMissions))
                    {
                        $titreEnCours = false;
                        $titreAVenir  = false;
                        $titrePassees = false;

                        echo '<div class="zone_missions">';
                            foreach ($listeMissions as $keyMission => $mission)
                            {
                                // Missions futures
                                if ($mission->getStatut() == 'V')
                                {
                                    if ($titreAVenir != true)
                                    {
                                        echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" /><div class="texte_titre_section">Missions à venir</div></div>';
                                        $titreAVenir = true;
                                    }

                                    echo '<div class="zone_mission_default">';
                                        echo '<img src="../../includes/icons/missions/default_mission.png" alt="default_mission" title="A venir" class="image_mission_default" />';
                                        echo '<div class="titre_mission_default">Revenez pour une nouvelle mission à partir du ' . formatDateForDisplay($mission->getDate_deb()) . ' à ' . formatTimeForDisplayLight($mission->getHeure()) . ' !</div>';
                                    echo '</div>';
                                }
                                // Missions en cours
                                elseif ($mission->getStatut() == 'C')
                                {
                                    if ($titreEnCours != true)
                                    {
                                        echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Missions en cours</div></div>';
                                        $titreEnCours = true;

                                        // Définit une zone pour appliquer la Masonry
                                        echo '<div class="zone_missions_accueil">';
                                    }

                                    echo '<a href="details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="zone_mission_accueil">';
                                        echo '<img src="../../includes/images/missions/banners/' . $mission->getReference() . '.png" alt="' . $mission->getReference() . '" title="' . $mission->getMission() . '" class="image_mission_accueil" />';
                                        echo '<div class="titre_mission_accueil">' . $mission->getMission() . '</div>';
                                    echo '</a>';
                                }
                                // Missions précédentes
                                elseif ($mission->getStatut() == 'A')
                                {
                                    if ($titrePassees != true)
                                    {
                                        echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_ended.png" alt="missions_ended" class="logo_titre_section" /><div class="texte_titre_section">Anciennes missions</div></div>';
                                        $titrePassees = true;

                                        // Définit une zone pour appliquer la Masonry
                                        echo '<div class="zone_missions_accueil">';
                                    }

                                    echo '<a href="details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="zone_mission_accueil">';
                                        echo '<img src="../../includes/images/missions/banners/' . $mission->getReference() . '.png" alt="' . $mission->getReference() . '" title="' . $mission->getMission() . '" class="image_mission_accueil" />';
                                        echo '<div class="titre_mission_accueil">' . $mission->getMission() . '</div>';
                                    echo '</a>';
                                }

                                if  ($mission->getStatut() != 'V'
                                AND (!isset($listeMissions[$keyMission + 1])
                                OR   $mission->getStatut() != $listeMissions[$keyMission + 1]->getStatut()))
                                {
                                    // Termine la zone Masonry du niveau
                                    echo '</div>';
                                }
                            }
                        echo '</div>';
                    }
                    else
                    {
                        // Titre
                        echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Rien à signaler</div></div>';
                        
                        echo '<div class="empty">Pas encore de missions...</div>';
                    }
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