<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Calendars';
      $styleHead       = 'styleCA.css';
      $scriptHead      = 'scriptCA.js';
      $angularHead     = false;
      $chatHead        = false;
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
        $celsius = 'calendars_generator';

        include('../../includes/common/mobile/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********************/
          /* Zone de recherche */
          /*********************/
          include('../../includes/common/mobile/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          echo '<div class="zone_formulaires_calendars">';
            /********************/
            /* Boutons d'action */
            /********************/
            // Liste des calendriers
            echo '<a href="calendars.php?year=' . date('Y') . '&action=goConsulter" title="Liste des calendriers" class="lien_green">';
              echo '<img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="image_lien" />';
              echo '<div class="titre_lien">LES CALENDRIERS</div>';
            echo '</a>';

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
                include('vue/mobile/vue_calendar_generated.php');

              /*****************************************/
              /* Génération de l'annexe au format HTML */
              /*****************************************/
              if (isset($annexeParameters) AND !empty($annexeParameters->getName()))
                include('vue/mobile/vue_annexe_generated.php');

              /*****************************/
              /* Générateur de calendriers */
              /*****************************/
              // Titre
              echo '<div id="titre_generateur_calendrier" class="titre_section">';
                echo '<img src="../../includes/icons/calendars/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Générer un calendrier</div>';
                echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
              echo '</div>';

              // Générateur de calendriers
              echo '<div id="afficher_generateur_calendrier" style="display: none;">';
                // Explications
                if (isset($vacances) AND empty($vacances))
                {
                  echo '<div class="avertissement_calendrier_generator">';
                    echo 'Attention, les dates de vacances ne sont pas disponibles pour le mois de ' . $listeMois[$calendarParameters->getMonth()] . ' ' . $calendarParameters->getYear() . '.
                    Les données ne sont pas à jour ou non accessibles et ne peuvent pas être affichées sur le calendrier généré. Pour toute information, veuillez contacter l\'administrateur.';
                  echo '</div>';
                }

                echo '<div class="titre_explications_calendrier_generator">A propos du générateur de calendriers</div>';

                echo '<div class="explications_calendrier_generator">';
                  echo 'Ceci est le générateur de calendriers automatisé. La saisie des données permet de générer un calendrier sur-mesure puis de le
                  sauvegarder sur le site. Les données disponibles sont :';

                  echo '<ul>';
                    echo '<li>L\'image de fond (non obligatoire)</li>';
                    echo '<li>Le mois (obligatoire)</li>';
                    echo '<li>L\'année (obligatoire)</li>';
                  echo '</ul>';

                  echo 'Le calendrier généré est formaté en fonction des données saisies, les jours fériés sont calculés automatiquement ainsi que les jours de vacances scolaires.
                  En cas de problème, n\'hésitez pas à contacter l\'administrateur.';

                  echo '<div class="avertissement_calendrier_generator_2">';
                    echo 'Ne pas oublier d\'utiliser le bouton "Sauvegarder le calendrier" après l\'avoir généré pour le rendre disponible sur le site.';
                  echo '</div>';
                echo '</div>';

                // Saisie
                echo '<div class="zone_saisie_calendrier">';
                  // Saisie des informations
                  echo '<form method="post" action="calendars_generator.php?action=doGenerer" enctype="multipart/form-data" id="form_saisie_generator">';
                    // Image
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

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                      echo '<input type="submit" name="send" value="Générer le calendrier" id="bouton_saisie_generator" class="saisie_bouton" />';
                    echo '</div>';
                  echo '</form>';
                echo '</div>';

                // Affichage du calendrier généré sous forme d'image
                if (isset($calendarParameters) AND !empty($calendarParameters->getMonth()) AND !empty($calendarParameters->getYear()))
                {
                  echo '<div class="zone_affichage_calendrier">';
                    // Affichage du calendrier au format JPEG (une fois généré)
                    echo '<img src="" title="Calendrier généré" id="generated_calendar" class="image_rendu_generator" />';

                    // Formulaire de sauvegarde de l'image générée
                    echo '<form method="post" action="calendars_generator.php?action=doSauvegarder" enctype="multipart/form-data" id="form_saisie_generated" class="form_sauvegarde_calendrier">';
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
              echo '</div>';

              /************************/
              /* Générateur d'annexes */
              /************************/
              // Titre
              echo '<div id="titre_generateur_annexe" class="titre_section">';
                echo '<img src="../../includes/icons/calendars/annexes_grey.png" alt="annexes_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Générer une annexe</div>';
                echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
              echo '</div>';

              // Générateur d'annexes
              echo '<div id="afficher_generateur_annexe" style="display: none;">';
                // Explications
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

                // Saisie
                echo '<div class="zone_saisie_calendrier">';
                  // Saisie des informations
                  echo '<form method="post" action="calendars_generator.php?action=doGenererAnnexe" enctype="multipart/form-data" id="form_saisie_annexe_generator">';
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
                    echo '<input type="text" name="name_annexe" value="' . $annexeParameters->getName() . '" placeholder="Nom" maxlength="255" class="saisie_titre_annexe" required />';

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                      echo '<input type="submit" name="send_annexe" value="Générer l\'annexe" id="bouton_saisie_annexe_generator" class="saisie_bouton" />';
                    echo '</div>';
                  echo '</form>';
                echo '</div>';

                // Affichage de l'annexe générée sous forme d'image
                if (isset($annexeParameters) AND !empty($annexeParameters->getName()))
                {
                  echo '<div class="zone_affichage_calendrier">';
                    // Affichage de l'annexe au format JPEG (une fois généré)
                    echo '<img src="" title="Annexe générée" id="generated_annexe" class="image_rendu_generator" />';

                    // Formulaire de sauvegarde de l'image générée
                    echo '<form method="post" action="calendars_generator.php?action=doSauvegarderAnnexe" enctype="multipart/form-data" id="form_saisie_annexe_generated" class="form_sauvegarde_annexe">';
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
              echo '</div>';

              /***********************/
              /* Ajout de calendrier */
              /***********************/
              // Titre
              echo '<div id="titre_saisie_calendrier" class="titre_section">';
                echo '<img src="../../includes/icons/calendars/send_grey.png" alt="send_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Saisir un calendrier</div>';
                echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
              echo '</div>';

              // Saisie de calendrier
              echo '<div id="afficher_saisie_calendrier" style="display: none;">';
                // Explications
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

                // Saisie
                echo '<div class="zone_saisie_calendrier">';
                  echo '<form method="post" action="calendars_generator.php?action=doAjouter" enctype="multipart/form-data" id="form_saisie_calendrier">';
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

              /******************/
              /* Ajout d'annexe */
              /******************/
              // Titre
              echo '<div id="titre_saisie_annexe" class="titre_section">';
                echo '<img src="../../includes/icons/calendars/send_grey.png" alt="send_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Saisir une annexe</div>';
                echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
              echo '</div>';

              // Saisie d'annexe
              echo '<div id="afficher_saisie_annexe" style="display: none;">';
                // Explications
                echo '<div class="titre_explications_calendrier_generator">A propos de la saisie d\'annexes</div>';

                echo '<div class="explications_calendrier_generator">';
                  echo 'Ceci est l\'outil de mise en ligne d\'annexes aux calendriers. Toutes les saisies sont obligatoires. Les données à saisir sont :';

                  echo '<ul>';
                    echo '<li>L\'annexe</li>';
                    echo '<li>Le nom de l\'annexe</li>';
                  echo '</ul>';
                echo '</div>';

                echo '<div class="explications_calendrier_generator">';
                  echo 'Un générateur d\'annexes automatisé sera développé prochainement.';
                echo '</div>';

                // Saisie
                echo '<div class="zone_saisie_calendrier">';
                  echo '<form method="post" action="calendars_generator.php?action=doAjouterAnnexe" enctype="multipart/form-data" id="form_saisie_annexe">';
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
                    echo '<input type="text" name="title" value="" placeholder="Nom" maxlength="255" class="saisie_titre_annexe" required />';

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                      echo '<input type="submit" name="send_annexe" value="Valider" id="bouton_saisie_annexe" class="saisie_bouton" />';
                    echo '</div>';
                  echo '</form>';
                echo '</div>';
              echo '</div>';
            }
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>
