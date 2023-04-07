<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Succès';
            $styleHead       = 'styleAdmin.css';
            $scriptHead      = 'scriptAdmin.js';
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
                $title = 'Gestion succès';

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

                    /***********************/
                    /* Explications succès */
                    /***********************/
                    // Titre
                    echo '<div class="titre_section"><img src="../../includes/icons/admin/informations_grey.png" alt="informations_grey" class="logo_titre_section" /><div class="texte_titre_section">Modifier les succès</div></div>';

                    // Explications
                    echo '<div class="avertissement_succes">';
                        echo 'Il est possible de modifier ici le niveau, l\'ordonnancement, le titre, la description, la condition et les explications des succès. Bien contrôler l\'ordonnancement par rapport au niveau pour éviter les doublons. Il n\'est pas possible de modifier la référence ni l\'image, il faut donc supprimer le succès via l\'écran précédent. Pour les explications, insérer les caractères <i>%limit%</i> permet de les remplacer par la valeur de la conditon d\'obtention du succès.';
                    echo '</div>';

                    /************************/
                    /* Affichage des succès */
                    /************************/
                    $lvl = 0;

                    echo '<form method="post" action="success.php?action=doModifier" class="zone_succes_admin" style="display: none;">';
                        foreach ($listeSuccess as $keySuccess => $success)
                        {
                            if ($success->getLevel() != $lvl)
                            {
                                // Formatage du titre du niveau
                                echo formatLevelTitle($success->getLevel());
                                $lvl = $success->getLevel();

                                // Définit une zone pour appliquer la Masonry
                                echo '<div class="zone_niveau_mod_succes_admin">';
                            }

                            if ($success->getDefined() == 'Y')
                                echo '<div class="succes_liste_mod">';
                            else
                                echo '<div class="succes_liste_mod succes_liste_mod_undefined">';

                                echo '<div class="succes_mod_left">';
                                    // Id succès (caché)
                                    echo '<input type="hidden" name="id[' . $success->getId() . ']" value="' . $success->getId() . '" />';

                                    // Logo succès
                                    echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes" />';

                                    // Référence
                                    echo '<div class="reference_succes">Ref. ' . $success->getReference() . '</div>';

                                    // Niveau
                                    echo '<div class="titre_succes">Niveau :</div>';
                                    echo '<input type="text" value="' . $success->getLevel() . '" name="level[' . $success->getId() . ']" maxlength="4" class="saisie_modification_succes" required />';

                                    // Ordonnancement
                                    echo '<div class="titre_succes">Ordre :</div>';
                                    echo '<input type="text" value="' . $success->getOrder_success() . '" name="order_success[' . $success->getId() . ']" maxlength="3" class="saisie_modification_succes" required />';

                                    // Unicité
                                    echo '<div class="titre_succes">Unique :</div>';
                                    echo '<div class="defined_succes">';
                                        if ($success->getUnicity() == 'Y')
                                        {
                                            echo '<input type="radio" name="unicity[' . $success->getId() . ']" value="Y" checked /><div class="radio_space">Oui</div>';
                                            echo '<input type="radio" name="unicity[' . $success->getId() . ']" value="N" />Non';
                                        }
                                        else
                                        {
                                            echo '<input type="radio" name="unicity[' . $success->getId() . ']" value="Y" /><div class="radio_space">Oui</div>';
                                            echo '<input type="radio" name="unicity[' . $success->getId() . ']" value="N" checked />Non';
                                        }
                                    echo '</div>';
                                echo '</div>';

                                echo '<div class="succes_mod_right">';
                                    // Titre succès
                                    echo '<div class="titre_succes">Titre :</div>';
                                    echo '<input type="text" value="' . $success->getTitle() . '" name="title[' . $success->getId() . ']" class="saisie_modification_succes" required />';

                                    // Description succès
                                    echo '<div class="titre_succes">Description :</div>';
                                    echo '<textarea name="description[' . $success->getId() . ']" class="textarea_modification_succes" required>' . $success->getDescription() . '</textarea>';

                                    // Condition succès
                                    echo '<div class="titre_succes">Condition :</div>';
                                    echo '<input type="text" value="' . formatNumericForDisplay($success->getLimit_success()) . '" name="limit_success[' . $success->getId() . ']" maxlength="10" class="saisie_modification_succes" required />';

                                    // Mission liée
                                    echo '<div class="titre_succes">Mission liée :</div>';
                                    echo '<select name="mission[' . $success->getId() . ']" class="select_modification_succes">';
                                        // Choix par défaut
                                        if (empty($success->getMission()))
                                            echo '<option value="" selected>Aucune mission liée</option>';
                                        else
                                            echo '<option value="">Aucune mission liée</option>';

                                        // Liste des missions
                                        echo '<optgroup label="Missions non terminées">';
                                            $indicateurMissionsTerminees = false;

                                            foreach ($listeMissions as $mission)
                                            {
                                                if ($indicateurMissionsTerminees == false AND $mission->getDate_fin() < date('Ymd'))
                                                {
                                                    echo '</optgroup>';
                                                    echo '<optgroup label="Missions terminées">';

                                                    $indicateurMissionsTerminees = true;
                                                }

                                                if (!empty($success->getMission()) AND $success->getMission() == $mission->getReference())
                                                    echo '<option value="' . $mission->getReference() . '" selected>' . $mission->getMission() . '</option>';
                                                else
                                                    echo '<option value="' . $mission->getReference() . '">' . $mission->getMission() . '</option>';
                                            }
                                        echo '</optgroup>';
                                    echo '</select>';

                                    // Code défini
                                    echo '<div class="titre_succes">Code défini :</div>';
                                    echo '<div class="defined_succes">';
                                        if ($success->getDefined() == 'Y')
                                        {
                                            echo '<input type="radio" name="defined[' . $success->getId() . ']" value="Y" checked /><div class="radio_space">Oui</div>';
                                            echo '<input type="radio" name="defined[' . $success->getId() . ']" value="N" />Non';
                                        }
                                        else
                                        {
                                            echo '<input type="radio" name="defined[' . $success->getId() . ']" value="Y" /><div class="radio_space">Oui</div>';
                                            echo '<input type="radio" name="defined[' . $success->getId() . ']" value="N" checked />Non';
                                        }
                                    echo '</div>';
                                echo '</div>';

                                echo '<div class="succes_mod_bottom">';
                                    // Explications
                                    echo '<div class="titre_succes">Explications :</div>';
                                    echo '<textarea name="explanation[' . $success->getId() . ']" class="textarea_modification_succes_2" required>' . $success->getExplanation() . '</textarea>';
                                echo '</div>';
                            echo '</div>';

                            if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
                            {
                                // Termine la zone Masonry du niveau
                                echo '</div>';
                            }
                        }

                        echo '<input type="submit" value="Mettre à jour les succès" class="bouton_saisie_gris" />';
                    echo '</form>';
                ?>
            </article>
        </section>

        <!-- Pied de page -->
        <footer>
            <?php include('../../includes/common/web/footer.php'); ?>
        </footer>
    </body>
</html>