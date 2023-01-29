<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Succès';
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

          /****************/
          /* Ajout succès */
          /****************/
          // Titre
          echo '<div class="titre_section"><img src="../../includes/icons/admin/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Ajouter un succès</div></div>';

          // Saisie du succès
          echo '<form method="post" action="success.php?action=doAjouter" class="form_saisie_admin" enctype="multipart/form-data">';
            // Titre
            echo '<input type="text" name="title" placeholder="Titre" value="' . $_SESSION['save']['title_success'] . '" class="saisie_succes_titre" required />';

            // Référence
            echo '<input type="text" name="reference" placeholder="Référence" value="' . $_SESSION['save']['reference_success'] . '" maxlength="255" class="saisie_succes_reference" required />';

            // Niveau
            echo '<input type="text" name="level" placeholder="Niveau" value="' . $_SESSION['save']['level'] . '" maxlength="4" class="saisie_succes_niveau" required />';

            // Ordonnancement
            echo '<input type="text" name="order_success" placeholder="Ordonnancement" value="' . $_SESSION['save']['order_success'] . '" maxlength="3" class="saisie_succes_ordonnancement" required />';

            // Bouton parcourir
            echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

            echo '<div class="zone_parcourir_succes">';
              echo '<div class="label_parcourir">Parcourir</div>';
              echo '<input type="file" accept=".png" name="success" class="bouton_parcourir_succes" required />';
            echo '</div>';

            // Bouton d'envoi
            echo '<input type="submit" name="send" value="" class="bouton_saisie_succes" />';

            // Description
            echo '<input type="text" name="description" placeholder="Description" value="' . $_SESSION['save']['description_success'] . '" class="saisie_succes_description" required />';

            // Condition
            echo '<input type="text" name="limit_success" placeholder="Condition" value="' . $_SESSION['save']['limit_success'] . '" maxlength="3" class="saisie_succes_condition" required />';

            // Mission liée
            echo '<select name="mission" class="saisie_succes_mission">';
              // Choix par défaut
              if (!empty($_SESSION['save']['mission']))
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

                  if (!empty($_SESSION['save']['mission']) AND $_SESSION['save']['mission'] == $mission->getReference())
                    echo '<option value="' . $mission->getReference() . '" selected>' . $mission->getMission() . '</option>';
                  else
                    echo '<option value="' . $mission->getReference() . '">' . $mission->getMission() . '</option>';
                }
              echo '</optgroup>';
            echo '</select>';
            
            // Unicité
            if ($_SESSION['save']['unicity'] == 'Y')
            {
              echo '<div class="switch_success_unicite switch_checked">';
                echo '<input type="checkbox" id="checkbox_unicity" name="unicity" checked/>';
                echo '<label for="checkbox_unicity" id="label_checkbox_unicity" class="label_switch">Unique</label>';
              echo '</div>';
            }
            else
            {
              echo '<div class="switch_success_unicite">';
                echo '<input type="checkbox" id="checkbox_unicity" name="unicity" />';
                echo '<label for="checkbox_unicity" id="label_checkbox_unicity" class="label_switch">Unique</label>';
              echo '</div>';
            }

            // Explications
            echo '<input type="text" name="explanation" placeholder="Explications (utiliser %limit%)" value="' . $_SESSION['save']['explanation_success'] . '" class="saisie_succes_explications" required />';
          echo '</form>';

          // Indications ajout succès
          echo '<div class="titre_explications">Lors de l\'ajout d\'un succès</div>';

          echo '<div class="contenu_explications">';
            echo 'Ne pas oublier d\'ajouter le code de la fonction <strong>initializeSuccess()</strong> dans <strong>metier_administration.php</strong> ainsi que la fonction
            <strong>insertOrUpdateSuccesValue()</strong> dans <strong>metier_commun.php</strong>.';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'Si c\'est un succès relatif à un <u>niveau</u>, mettre à jour également la fonction <strong>insertOrUpdateSuccesLevel()</strong> dans <strong>metier_commun.php</strong>.
            Une fois le code ajouté, vérifier que le succès est à "<strong>Unique</strong>".';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'Si c\'est un succès relatif à une <u>mission</u>, mettre à jour également ce succès (dans le cas où cela n\'a pas déjà été fait à la création) en liant la référence de la mission dans la modification des succès.';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'Une fois ces étapes réalisées, modifier le succès pour changer son état à "<strong>Défini</strong>".';
          echo '</div>';

          // Indications suppression succès
          echo '<div class="titre_explications">Lors de la suppression d\'un succès</div>';

          echo '<div class="contenu_explications">';
            echo 'Ne pas oublier de supprimer le code de la fonction <strong>initializeSuccess()</strong> dans <strong>metier_administration.php</strong> et
            <strong>insertOrUpdateSuccesValue()</strong> dans <strong>metier_commun.php</strong>.';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'Si c\'est un succès relatif à un <u>niveau</u>, mettre à jour également la fonction <strong>insertOrUpdateSuccesLevel()</strong> dans <strong>metier_commun.php</strong>.';
          echo '</div>';

          echo '<div class="contenu_explications">';
            echo 'La suppression d\'une <u>mission</u> supprime automatiquement le lien avec le succès.';
          echo '</div>';

          /******************/
          /* Gestion succès */
          /******************/
          echo '<div class="titre_section"><img src="../../includes/icons/admin/settings_grey.png" alt="settings_grey" class="logo_titre_section" /><div class="texte_titre_section">Gérer les succès des utilisateurs</div></div>';

          // Modification
          echo '<div class="zone_gestion_succes">';
            echo '<a href="/inside/administration/success/success.php?action=goModifier" class="bouton_gestion_succes">';
              echo 'Modifier les succès';
            echo '</a>';

            echo '<div class="explications_gestion_succes">';
              echo 'Il est possible de modifier le niveau, l\'ordonnancement, le titre, la description, la condition et les explications des succès. Bien contrôler l\'ordonnancement par rapport au
              niveau pour éviter les doublons. Il n\'est pas possible de modifier la référence ni l\'image, il faut donc supprimer le succès via cet écran. Pour les explications, insérer les caractères
              <i>%limit%</i> permet de les remplacer par la valeur de la conditon d\'obtention du succès.';
            echo '</div>';
          echo '</div>';

          // Purge
          echo '<div class="zone_gestion_succes margin_top_20">';
            echo '<form id="purgeSuccess" method="post" action="success.php?action=doPurger" class="form_gestion_succes">';
              echo '<input type="submit" name="purge_success" value="Purger les succès" class="bouton_gestion_succes eventConfirm" />';
              echo '<input type="hidden" value="Voulez-vous vraiment purger les succès ? Ceci est définitif." class="eventMessage" />';
            echo '</form>';

            echo '<div class="explications_gestion_succes">';
              echo 'Ce bouton permet d\'effacer tous les succès des utilisateurs dans la base de données sauf les suivants :';

              echo '<ul class="margin_top_0 margin_bottom_0">';
                echo '<li>J\'étais là. (beginning)</li>';
                echo '<li>Je l\'ai fait ! (developper)</li>';
                echo '<li>Véritable Jedi (padawan)</li>';
                echo '<li>Economie de marché (greedy)</li>';
                echo '<li>Radar à bouffe (restaurant-finder)</li>';
              echo '</ul>';
            echo '</div>';
          echo '</div>';

          // Initialisation
          echo '<div class="zone_gestion_succes margin_top_20">';
            echo '<form id="initializeSuccess" method="post" action="success.php?action=doInitialiser" class="form_gestion_succes">';
              echo '<input type="submit" name="init_success" value="Initialiser les succès" class="bouton_gestion_succes eventConfirm" />';
              echo '<input type="hidden" value="Voulez-vous vraiment initialiser les succès ?" class="eventMessage" />';
            echo '</form>';

            echo '<div class="explications_gestion_succes">';
              echo 'Ce bouton permet d\'initialiser les succès pour tous les utilisateurs. Il faut faire attention lors de son utilisation car il va remplacer les valeurs déjà
              acquises par tous les utilisateurs et potentiellement bloquer des succès déjà débloqués. Le traitement peut prendre du temps en fonction du nombre de succès et d\'utilisateurs. Une
              purge est effectuée en fin de traitement sur tous les éventuels succès à 0.';
            echo '</div>';
          echo '</div>';

          /********************/
          /* Affichage succès */
          /********************/
          $lvl = 0;

          echo '<div class="zone_succes_admin" style="display: none;">';
            foreach ($listeSuccess as $keySuccess => $success)
            {
              if ($success->getLevel() != $lvl)
              {
                // Formatage du titre du niveau
                echo formatLevelTitle($success->getLevel());
                $lvl = $success->getLevel();

                // Définit une zone pour appliquer la Masonry
                echo '<div class="zone_niveau_succes_admin">';
              }

              echo '<div class="ensemble_succes">';
                // Suppression succès
                echo '<form method="post" id="delete_success_' . $success->getId() . '" action="success.php?action=doSupprimer" class="form_suppression_succes">';
                  echo '<input type="hidden" name="id_success" value="' . $success->getId() . '" />';
                  echo '<input type="submit" name="delete_success" value="" title="Supprimer le succès" class="bouton_delete eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer le succès &quot;' . formatOnclick($success->getTitle()) . '&quot; ?" class="eventMessage" />';
                echo '</form>';

                if ($success->getDefined() == 'Y')
                  echo '<div class="succes_liste">';
                else
                  echo '<div class="succes_liste_undefined">';

                  // Ordonnancement
                  echo '<div class="ordonnancement_succes">' . $success->getOrder_success() . '</div>';

                  // Conditions
                  echo '<div class="zone_conditions_succes">';
                    // Mission liée
                    if (!empty($success->getMission()))
                      echo '<img src="../../includes/icons/admin/missions_grey.png" alt="missions_grey" title="Mission liée" class="mission_succes" />';

                    // Condition
                    if ($success->getUnicity() == 'Y')
                      echo '<div class="condition_succes">Unique</div>';
                    else
                      echo '<div class="condition_succes">/ ' . $success->getLimit_success() . '</div>';
                  echo '</div>';

                  // Logo succès
                  echo '<img src="../../includes/images/profil/success/' . $success->getReference() . '.png" alt="' . $success->getReference() . '" class="logo_succes" />';

                  // Titre succès
                  echo '<div class="titre_succes">' . $success->getTitle() . '</div>';

                  // Description succès
                  echo '<div class="description_succes">' . $success->getDescription() . '</div>';

                  // Explications succès
                  if ($success->getDefined() == 'Y')
                    echo '<div class="explications_succes">' . formatExplanation($success->getExplanation(), $success->getLimit_success(), '%limit%') . '</div>';
                  else
                    echo '<div class="explications_succes_undefined">' . formatExplanation($success->getExplanation(), $success->getLimit_success(), '%limit%') . '</div>';
                echo '</div>';
              echo '</div>';

              if (!isset($listeSuccess[$keySuccess + 1]) OR $success->getLevel() != $listeSuccess[$keySuccess + 1]->getLevel())
              {
                // Termine la zone Masonry du niveau
                echo '</div>';
              }
            }
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
