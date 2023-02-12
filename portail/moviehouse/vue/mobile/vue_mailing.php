<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Movie House';
            $styleHead       = 'styleMH.css';
            $scriptHead      = '';
            $angularHead     = false;
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
                $celsius = 'moviehouse';

                include('../../includes/common/mobile/celsius.php');
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
                    echo '<div class="titre_section_mobile">' . mb_strtoupper('ENVOYER UN E-MAIL') . '</div>';

                    /***************/
                    /* Modèle mail */
                    /***************/
                    $modeleMail = getModeleMailFilm($detailsFilm, $listeEtoiles);
                    echo $modeleMail;

                    /*************************/
                    /* Encadré destinataires */
                    /*************************/
                    echo '<div class="zone_destinataires_mail">';
                        $emailPresent = false;

                        foreach ($listeEtoiles as $participant)
                        {
                            if (!empty($participant->getEmail()))
                            {
                                if ($emailPresent == false)
                                {
                                    echo '<div class="avertissement_mail_1">L\'e-mail sera envoyé aux personnes suivantes :</div>';
                                    $emailPresent = true;
                                }
                                
                                echo '<div class="destinataires">';
                                    // Avatar
                                    $avatarFormatted = formatAvatar($participant->getAvatar(), $participant->getPseudo(), 2, 'avatar');

                                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_dest" />';

                                    echo '<div class="pseudo_dest">' . $participant->getPseudo() . '</div>';
                                echo '</div>';
                            }
                        }

                        if ($emailPresent == false)
                            echo '<div class="avertissement_mail_2">Aucune personne ne sera avertie car aucun e-mail n\'a été renseigné.</div>';
                        else
                            echo '<div class="avertissement_mail_2">N\'oubliez pas d\'avertir les éventuelles personnes n\'ayant pas renseigné d\'adresse mail.</div>';
                    echo '</div>';

                    /*********************/
                    /* Bouton envoi mail */
                    /*********************/
                    if ($emailPresent == true)
                    {
                        echo '<form method="post" action="mailing.php?action=sendMail">';
                            echo '<input type="hidden" name="id_film" value="' . $detailsFilm->getId() . '" />';
                            echo '<input type="submit" name="send_mail_film" value="Envoyer l\'e-mail" class="send_mail_film" />';
                        echo '</form>';
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
    </body>
</html>