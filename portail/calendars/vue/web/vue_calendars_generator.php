<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Head commun & spécifique-->
        <?php
            $titleHead       = 'Calendars';
            $styleHead       = 'styleCA.css';
            $scriptHead      = 'scriptCA.js';
            $chatHead        = true;
            $datepickerHead  = false;
            $masonryHead     = false;
            $exifHead        = false;
            $html2canvasHead = true;
            $jqueryCsv       = false;

            include('../../includes/common/head.php');
        ?>
    </head>

    <body>
        <!-- Entête -->
        <header>
            <?php
                $title = 'Calendars';

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

            <!-- Celsius -->
            <?php
                $celsius = 'calendars_generator';

                include('../../includes/common/web/celsius.php');
            ?>

            <article>
                <?php
                    /*******************/
                    /* Chargement page */
                    /*******************/
                    echo '<div class="zone_loading_page">';
                        echo '<div id="loading_page" class="loading_page"></div>';
                    echo '</div>';

                    /**************************************/
                    /* Création de calendriers et annexes */
                    /**************************************/
                    if ($preferences->getManage_calendars() == 'Y')
                    {
                        /********************/
                        /* Données communes */
                        /********************/
                        $anneeDebut = date('Y') - 2;
                        $anneeFin   = date('Y') + 2;

                        /*******************************************/
                        /* Génération du calendrier au format HTML */
                        /*******************************************/
                        if (isset($calendarParameters) AND !empty($calendarParameters->getMonth()) AND !empty($calendarParameters->getYear()))
                            include('vue/web/vue_calendar_generated.php');

                        /*****************************************/
                        /* Génération de l'annexe au format HTML */
                        /*****************************************/
                        if (isset($annexeParameters) AND !empty($annexeParameters->getName()))
                            include('vue/web/vue_annexe_generated.php');

                        /*****************************/
                        /* Générateur de calendriers */
                        /*****************************/
                        echo '<div class="zone_calendars_top">';
                            // Titre
                            echo '<div class="titre_section"><img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Générer un nouveau calendrier</div></div>';

                            // Saisie
                            echo '<div class="zone_calendrier_generator_left">';
                                // Saisie des informations
                                echo '<form method="post" action="calendars_generator.php?action=doGenererCalendrier" enctype="multipart/form-data">';
                                    echo '<div class="zone_saisie_calendrier">';
                                        // Image
                                        echo '<div class="titre_option_calendrier">Image de fond</div>';

                                        echo '<div class="zone_saisie_image">';
                                            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                                            echo '<div class="zone_parcourir_image_generator">';
                                                echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                                                echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="picture_calendar" class="bouton_parcourir_image_generator loadCalendrierGenere" />';
                                            echo '</div>';

                                            echo '<div class="mask_image_generator">';
                                                if (isset($calendarParameters) AND !empty($calendarParameters->getPicture()))
                                                {
                                                    echo '<img src="../../includes/images/calendars/temp/' . $calendarParameters->getPicture() . '" id="image_calendars_generated" alt="" class="image" />';
                                                    echo '<input type="hidden" name="picture_calendar_generated" value="' . $calendarParameters->getPicture() . '" />';
                                                }
                                                else
                                                    echo '<img id="image_calendars_generated" alt="" class="image" />';
                                            echo '</div>';
                                        echo '</div>';

                                        // Période
                                        echo '<div class="titre_option_calendrier">Période</div>';

                                        // Listbox mois
                                        echo '<select name="month_calendar" class="listbox" required>';
                                            echo '<option value="" disabled selected hidden>Mois</option>';

                                            foreach ($listeMois as $numeroMois => $mois)
                                            {
                                                if ($numeroMois == $calendarParameters->getMonth())
                                                    echo '<option value="' . $numeroMois . '" selected>' . $mois . '</option>';
                                                else
                                                    echo '<option value="' . $numeroMois . '">' . $mois . '</option>';
                                            }
                                        echo '</select>';

                                        // Listbox année
                                        echo '<select name="year_calendar" class="listbox" required>';
                                            echo '<option value="" disabled selected hidden>Année</option>';

                                            for ($i = $anneeDebut; $i <= $anneeFin; $i++)
                                            {
                                                if ($i == $calendarParameters->getYear())
                                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                                else
                                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        echo '</select>';
                                    echo '</div>';

                                    // Options du calendrier
                                    echo '<div class="zone_options_calendrier">';
                                        // Jours fériés
                                        echo '<div id="zone_option_jours_feries" class="zone_option_calendrier">';
                                            echo '<div class="titre_option_calendrier">Jours fériés</div>';

                                            if ($calendarParameters->getHolidays() == 'Y')
                                            {
                                                echo '<div id="checkbox_jours_feries" class="zone_bouton_option bouton_checked">';
                                                    echo '<input type="checkbox" id="checkbox_jours_feries_alsace" name="jours_feries_alsace" checked />';
                                                    echo '<label for="checkbox_jours_feries_alsace" class="label_option">Alsace</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div id="checkbox_jours_feries" class="zone_bouton_option">';
                                                    echo '<input type="checkbox" id="checkbox_jours_feries_alsace" name="jours_feries_alsace" />';
                                                    echo '<label for="checkbox_jours_feries_alsace" class="label_option">Alsace</label>';
                                                echo '</div>';
                                            }
                                        echo '</div>';

                                        // Vacances scolaires
                                        echo '<div id="zone_option_vacances_scolaires" class="zone_option_calendrier">';
                                            echo '<div class="titre_option_calendrier">Vacances scolaires</div>';

                                            if (empty($calendarParameters->getVacations()))
                                            {
                                                echo '<div id="radio_zone_aucune" class="zone_bouton_option bouton_checked">';
                                                    echo '<input id="zone_aucune" type="radio" name="choix_vacances_scolaires" value="none" required checked />';
                                                    echo '<label for="zone_aucune" class="label_option">Masquer</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div id="radio_zone_aucune" class="zone_bouton_option">';
                                                    echo '<input id="zone_aucune" type="radio" name="choix_vacances_scolaires" value="none" required />';
                                                    echo '<label for="zone_aucune" class="label_option">Masquer</label>';
                                                echo '</div>';
                                            }
                                            
                                            if (!empty($calendarParameters->getVacations()) AND $calendarParameters->getVacations() == 'a')
                                            {
                                                echo '<div id="radio_zone_a" class="zone_bouton_option bouton_checked">';
                                                    echo '<input id="zone_a" type="radio" name="choix_vacances_scolaires" value="a" required checked />';
                                                    echo '<label for="zone_a" class="label_option">Zone A</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div id="radio_zone_a" class="zone_bouton_option">';
                                                    echo '<input id="zone_a" type="radio" name="choix_vacances_scolaires" value="a" required />';
                                                    echo '<label for="zone_a" class="label_option">Zone A</label>';
                                                echo '</div>';
                                            }

                                            if (!empty($calendarParameters->getVacations()) AND $calendarParameters->getVacations() == 'b')
                                            {
                                                echo '<div id="radio_zone_b" class="zone_bouton_option bouton_checked">';
                                                    echo '<input id="zone_b" type="radio" name="choix_vacances_scolaires" value="b" required checked />';
                                                    echo '<label for="zone_b" class="label_option">Zone B</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div id="radio_zone_b" class="zone_bouton_option">';
                                                    echo '<input id="zone_b" type="radio" name="choix_vacances_scolaires" value="b" required />';
                                                    echo '<label for="zone_b" class="label_option">Zone B</label>';
                                                echo '</div>';
                                            }

                                            if (!empty($calendarParameters->getVacations()) AND $calendarParameters->getVacations() == 'c')
                                            {
                                                echo '<div id="radio_zone_c" class="zone_bouton_option bouton_checked">';
                                                    echo '<input id="zone_c" type="radio" name="choix_vacances_scolaires" value="c" required checked />';
                                                    echo '<label for="zone_c" class="label_option">Zone C</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div id="radio_zone_c" class="zone_bouton_option">';
                                                    echo '<input id="zone_c" type="radio" name="choix_vacances_scolaires" value="c" required />';
                                                    echo '<label for="zone_c" class="label_option">Zone C</label>';
                                                echo '</div>';
                                            }
                                        echo '</div>';

                                        // Couleur
                                        echo '<div id="zone_option_couleur" class="zone_option_calendrier">';
                                            echo '<div class="titre_option_calendrier">Couleurs</div>';

                                            if (!empty($calendarParameters->getColor()) AND $calendarParameters->getColor() == 'R')
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_rouge">';
                                                    echo '<input id="radio_couleur_rouge" type="radio" name="choix_couleur_calendrier" value="R" required checked />';
                                                    echo '<label for="radio_couleur_rouge" class="label_option">Inside Red</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_rouge">';
                                                    echo '<input id="radio_couleur_rouge" type="radio" name="choix_couleur_calendrier" value="R" required />';
                                                    echo '<label for="radio_couleur_rouge" class="label_option">Inside Red</label>';
                                                echo '</div>';
                                            }

                                            if (!empty($calendarParameters->getColor()) AND $calendarParameters->getColor() == 'V')
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_vert">';
                                                    echo '<input id="radio_couleur_vert" type="radio" name="choix_couleur_calendrier" value="V" required checked />';
                                                    echo '<label for="radio_couleur_vert" class="label_option">Go Green</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_vert">';
                                                    echo '<input id="radio_couleur_vert" type="radio" name="choix_couleur_calendrier" value="V" required />';
                                                    echo '<label for="radio_couleur_vert" class="label_option">Go Green</label>';
                                                echo '</div>';
                                            }
                                            
                                            if (!empty($calendarParameters->getColor()) AND $calendarParameters->getColor() == 'B')
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_bleu">';
                                                    echo '<input id="radio_couleur_bleu" type="radio" name="choix_couleur_calendrier" value="B" required checked />';
                                                    echo '<label for="radio_couleur_bleu" class="label_option">Sky Blue</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_bleu">';
                                                    echo '<input id="radio_couleur_bleu" type="radio" name="choix_couleur_calendrier" value="B" required />';
                                                    echo '<label for="radio_couleur_bleu" class="label_option">Sky Blue</label>';
                                                echo '</div>';
                                            }
                                            
                                            if (!empty($calendarParameters->getColor()) AND $calendarParameters->getColor() == 'J')
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_jaune">';
                                                    echo '<input id="radio_couleur_jaune" type="radio" name="choix_couleur_calendrier" value="J" required checked />';
                                                    echo '<label for="radio_couleur_jaune" class="label_option">Sunny Yellow</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_jaune">';
                                                    echo '<input id="radio_couleur_jaune" type="radio" name="choix_couleur_calendrier" value="J" required />';
                                                    echo '<label for="radio_couleur_jaune" class="label_option">Sunny Yellow</label>';
                                                echo '</div>';
                                            }

                                            if (!empty($calendarParameters->getColor()) AND $calendarParameters->getColor() == 'P')
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_violet">';
                                                    echo '<input id="radio_couleur_violet" type="radio" name="choix_couleur_calendrier" value="P" required checked />';
                                                    echo '<label for="radio_couleur_violet" class="label_option">Malabar Purple</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_violet">';
                                                    echo '<input id="radio_couleur_violet" type="radio" name="choix_couleur_calendrier" value="P" required />';
                                                    echo '<label for="radio_couleur_violet" class="label_option">Malabar Purple</label>';
                                                echo '</div>';
                                            }

                                            if (!empty($calendarParameters->getColor()) AND $calendarParameters->getColor() == 'W')
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_blanc">';
                                                    echo '<input id="radio_couleur_blanc" type="radio" name="choix_couleur_calendrier" value="W" required checked />';
                                                    echo '<label for="radio_couleur_blanc" class="label_option">Ivory White</label>';
                                                echo '</div>';
                                            }
                                            else
                                            {
                                                echo '<div class="zone_bouton_option zone_bouton_option_blanc">';
                                                    echo '<input id="radio_couleur_blanc" type="radio" name="choix_couleur_calendrier" value="W" required />';
                                                    echo '<label for="radio_couleur_blanc" class="label_option">Ivory White</label>';
                                                echo '</div>';
                                            }
                                        echo '</div>';
                                    echo '</div>';

                                    // Bouton validation
                                    echo '<div class="zone_bouton_saisie">';
                                        echo '<input type="submit" name="send" value="Générer le calendrier" id="bouton_saisie_generator" class="saisie_bouton" />';
                                    echo '</div>';
                                echo '</form>';
                            echo '</div>';

                            // Affichage du calendrier généré sous forme d'image
                            if (isset($calendarParameters) AND !empty($calendarParameters->getMonth()) AND !empty($calendarParameters->getYear()))
                            {
                                echo '<div class="zone_calendrier_generator_middle">';
                                    // Affichage du calendrier au format JPEG (une fois généré)
                                    echo '<img src="" title="Calendrier généré" id="generated_calendar" class="image_rendu_calendrier_generator" />';

                                    // Formulaire de sauvegarde de l'image générée
                                    echo '<form method="post" action="calendars_generator.php?action=doSauvegarderCalendrier" enctype="multipart/form-data" class="form_sauvegarde_calendrier">';
                                        // Image générée
                                        echo '<input type="hidden" name="calendar_generator" id="calendar_generator" value="" />';

                                        // Nom fichiers temporaires
                                        echo '<input type="hidden" name="temp_name_generator" value="' . $calendarParameters->getPicture() . '" />';

                                        // Mois
                                        echo '<input type="hidden" name="month_generator" value="' . $calendarParameters->getMonth() . '" />';

                                        // Année
                                        echo '<input type="hidden" name="year_generator" value="' . $calendarParameters->getYear() . '" />';

                                        // Bouton sauvegarde
                                        echo '<div class="zone_bouton_saisie">';
                                            echo '<input type="submit" name="save" value="Sauvegarder le calendrier" id="bouton_saisie_generated" class="saisie_bouton" />';
                                        echo '</div>';
                                    echo '</form>';
                                echo '</div>';
                            }

                            // Explications
                            echo '<div class="zone_calendrier_generator_right">';
                                echo '<div class="titre_explications_calendrier_generator">A propos du générateur de calendriers</div>';

                                echo '<div class="explications_calendrier_generator">';
                                    echo 'Ceci est le générateur de calendriers automatisé. La saisie des données permet de générer un calendrier sur-mesure puis de le
                                          sauvegarder sur le site. Les données disponibles sont :';

                                    echo '<ul>';
                                        echo '<li>L\'image de fond (non obligatoire)</li>';
                                        echo '<li>Le mois (obligatoire)</li>';
                                        echo '<li>L\'année (obligatoire)</li>';
                                        echo '<li>L\'affichage des jours fériés supplémentaires pour l\'Alsace (obligatoire)</li>';
                                        echo '<li>La zone pour l\'affichage des vacances scolaires (obligatoire)</li>';
                                        echo '<li>La couleur du bandeau et des vacances scolaires (obligatoire)</li>';
                                    echo '</ul>';

                                    echo 'Le calendrier généré est formaté en fonction des données saisies, les jours fériés sont calculés automatiquement ainsi que les jours de vacances scolaires.
                                          En cas de problème, n\'hésitez pas à contacter l\'administrateur.';

                                    // Alerte vacances scolaires
                                    if (isset($vacances) AND empty($vacances) AND !empty($calendarParameters->getVacations()))
                                    {
                                        echo '<div class="avertissement_calendrier_generator_1">';
                                            echo 'Attention, les dates de vacances ne sont pas disponibles pour le mois de ' . $listeMois[$calendarParameters->getMonth()] . ' ' . $calendarParameters->getYear() . '.
                                                  Les données ne sont pas à jour ou non accessibles et ne peuvent pas être affichées sur le calendrier généré. Pour toute information, veuillez contacter l\'administrateur.';
                                        echo '</div>';
                                    }

                                    // Avertissement sauvegarde
                                    echo '<div class="avertissement_calendrier_generator_2">';
                                        echo 'Ne pas oublier d\'utiliser le bouton "Sauvegarder le calendrier" après l\'avoir généré pour le rendre disponible sur le site.';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';

                        /************************/
                        /* Générateur d'annexes */
                        /************************/
                        echo '<div id="scrollGenerator" class="zone_calendars_top">';
                            // Titre
                            echo '<div class="titre_section"><img src="../../includes/icons/calendars/annexes_grey.png" alt="annexes_grey" class="logo_titre_section" /><div class="texte_titre_section">Générer une nouvelle annexe</div></div>';

                            // Saisie
                            echo '<div class="zone_annexe_generator_left">';
                                // Saisie des informations
                                echo '<form method="post" action="calendars_generator.php?action=doGenererAnnexe" enctype="multipart/form-data">';
                                    echo '<div class="zone_saisie_calendrier">';
                                        // Image
                                        echo '<div class="zone_saisie_image">';
                                            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                                            echo '<div class="zone_parcourir_annexe_generator">';
                                                echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';

                                                if (isset($annexeParameters) AND !empty($annexeParameters->getPicture()))
                                                    echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="picture_annexe" class="bouton_parcourir_annexe_generator loadAnnexeGeneree" />';
                                                else
                                                    echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="picture_annexe" class="bouton_parcourir_annexe_generator loadAnnexeGeneree" required />';
                                            echo '</div>';

                                            echo '<div class="mask_annexe_generator">';
                                                if (isset($annexeParameters) AND !empty($annexeParameters->getPicture()))
                                                {
                                                    echo '<img src="../../includes/images/calendars/temp/' . $annexeParameters->getPicture() . '" id="image_annexe_generated" alt="" class="image" />';
                                                    echo '<input type="hidden" name="picture_annexe_generated" value="' . $annexeParameters->getPicture() . '" />';
                                                }
                                                else
                                                    echo '<img id="image_annexe_generated" alt="" class="image" />';
                                            echo '</div>';
                                        echo '</div>';

                                        // Titre annexe
                                        echo '<input type="text" name="name_annexe" value="' . $annexeParameters->getName() . '" placeholder="Nom" maxlength="255" class="titre_annexe" required />';
                                    echo '</div>';

                                    // Bouton validation
                                    echo '<div class="zone_bouton_saisie">';
                                        echo '<input type="submit" name="send_annexe" value="Générer l\'annexe" id="bouton_saisie_annexe_generator" class="saisie_bouton" />';
                                    echo '</div>';
                                echo '</form>';
                            echo '</div>';

                            // Affichage de l'annexe générée sous forme d'image
                            if (isset($annexeParameters) AND !empty($annexeParameters->getName()))
                            {
                                echo '<div class="zone_annexe_generator_middle">';
                                    // Affichage de l'annexe au format JPEG (une fois généré)
                                    echo '<img src="" title="Annexe générée" id="generated_annexe" class="image_rendu_annexe_generator" />';

                                    // Formulaire de sauvegarde de l'image générée
                                    echo '<form method="post" action="calendars_generator.php?action=doSauvegarderAnnexe" enctype="multipart/form-data" class="form_sauvegarde_annexe">';
                                        // Image générée
                                        echo '<input type="hidden" name="annexe_generator" id="annexe_generator" value="" />';

                                        // Nom fichiers temporaires
                                        echo '<input type="hidden" name="temp_name_annexe_generator" value="' . $annexeParameters->getPicture() . '" />';

                                        // Nom
                                        echo '<input type="hidden" name="title_generator" value="' . $annexeParameters->getName() . '" />';

                                        // Bouton sauvegarde
                                        echo '<div class="zone_bouton_saisie">';
                                            echo '<input type="submit" name="save" value="Sauvegarder l\'annexe" id="bouton_saisie_annexe_generated" class="saisie_bouton" />';
                                        echo '</div>';
                                    echo '</form>';
                                echo '</div>';
                            }

                            // Explications
                            echo '<div class="zone_annexe_generator_right">';
                                echo '<div class="titre_explications_calendrier_generator">A propos du générateur d\'annexes</div>';

                                echo '<div class="explications_calendrier_generator">';
                                    echo 'Ceci est le générateur d\'annexes automatisé. La saisie des données permet de générer une annexe contenant des étiquettes à utiliser avec les calendriers puis de la sauvegarder sur le site.
                                          Les données disponibles sont :';

                                    echo '<ul>';
                                        echo '<li>L\'image (obligatoire)</li>';
                                        echo '<li>Le nom (obligatoire)</li>';
                                    echo '</ul>';

                                    echo 'L\'annexe générée est formatée en fonction des données saisies. En cas de problème, n\'hésitez pas à contacter l\'administrateur.';

                                    echo '<div class="avertissement_calendrier_generator_2">';
                                        echo 'Ne pas oublier d\'utiliser le bouton "Sauvegarder l\'annexe" après l\'avoir générée pour la rendre disponible sur le site.';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';

                        /***********************/
                        /* Ajout de calendrier */
                        /***********************/
                        echo '<div class="zone_calendars_left">';
                            // Titre
                            echo '<div class="titre_section"><img src="../../includes/icons/calendars/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Saisir un calendrier</div></div>';

                            // Saisie
                            echo '<div class="zone_saisie_calendars_left">';
                                echo '<div class="zone_saisie_calendrier">';
                                    echo '<form method="post" action="calendars_generator.php?action=doAjouterCalendrier" enctype="multipart/form-data">';
                                        // Image
                                        echo '<div class="zone_saisie_image">';
                                            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                                            echo '<div class="zone_parcourir_image">';
                                                echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                                                echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="calendar" class="bouton_parcourir_image loadCalendrier" required />';
                                            echo '</div>';

                                            echo '<div class="mask_image">';
                                                echo '<img id="image_calendars" alt="" class="image" />';
                                            echo '</div>';
                                        echo '</div>';

                                        // Listbox mois
                                        echo '<select name="month_calendar" class="listbox" required>';
                                            echo '<option value="" disabled selected hidden>Mois</option>';

                                            foreach ($listeMois as $numeroMois => $mois)
                                            {
                                                echo '<option value="' . $numeroMois . '">' . $mois . '</option>';
                                            }
                                        echo '</select>';

                                        // Listbox année
                                        echo '<select name="year_calendar" class="listbox" required>';
                                            echo '<option value="" disabled selected hidden>Année</option>';

                                            for ($i = $anneeDebut; $i <= $anneeFin; $i++)
                                            {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        echo '</select>';

                                        // Bouton validation
                                        echo '<div class="zone_bouton_saisie">';
                                            echo '<input type="submit" name="send" value="Valider" id="bouton_saisie_calendrier" class="saisie_bouton" />';
                                        echo '</div>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';

                            // Explications
                            echo '<div class="zone_saisie_calendars_right">';
                                echo '<div class="titre_explications_calendrier_generator">A propos de la saisie de calendriers</div>';

                                echo '<div class="explications_calendrier_generator">';
                                    echo 'Ceci est l\'ancien outil de mise en ligne de calendriers. Celui-ci fonctionne toujours normalement et permet de mettre en ligne
                                          un calendrier personnalisé. Toutes les saisies sont obligatoires. Les données à saisir sont :';

                                    echo '<ul>';
                                        echo '<li>Le calendrier</li>';
                                        echo '<li>Le mois</li>';
                                        echo '<li>L\'année</li>';
                                    echo '</ul>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';

                        /******************/
                        /* Ajout d'annexe */
                        /******************/
                        echo '<div class="zone_calendars_right">';
                            // Titre
                            echo '<div class="titre_section"><img src="../../includes/icons/calendars/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Saisir une annexe</div></div>';

                            // Saisie
                            echo '<div class="zone_saisie_calendars_left">';
                                echo '<div class="zone_saisie_calendrier">';
                                    echo '<form method="post" action="calendars_generator.php?action=doAjouterAnnexe" enctype="multipart/form-data">';
                                        // Image
                                        echo '<div class="zone_saisie_image">';
                                            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                                            echo '<div class="zone_parcourir_image">';
                                                echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                                                echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="annexe" class="bouton_parcourir_image loadAnnexe" required />';
                                            echo '</div>';

                                            echo '<div class="mask_image">';
                                                echo '<img id="image_annexes" alt="" class="image" />';
                                            echo '</div>';
                                        echo '</div>';

                                        // Titre annexe
                                        echo '<input type="text" name="title" value="" placeholder="Nom" maxlength="255" class="titre_annexe" required />';

                                        // Bouton validation
                                        echo '<div class="zone_bouton_saisie">';
                                            echo '<input type="submit" name="send_annexe" value="Valider" id="bouton_saisie_annexe" class="saisie_bouton" />';
                                        echo '</div>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';

                            // Explications
                            echo '<div class="zone_saisie_calendars_right">';
                                echo '<div class="titre_explications_calendrier_generator">A propos de la saisie d\'annexes</div>';

                                echo '<div class="explications_calendrier_generator">';
                                    echo 'Ceci est l\'ancien outil de mise en ligne d\'annexes aux calendriers. Celui-ci fonctionne toujours normalement et permet de mettre en ligne
                                          une annexe personnalisée. Toutes les saisies sont obligatoires. Les données à saisir sont :';

                                    echo '<ul>';
                                        echo '<li>L\'annexe</li>';
                                        echo '<li>Le nom de l\'annexe</li>';
                                    echo '</ul>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
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