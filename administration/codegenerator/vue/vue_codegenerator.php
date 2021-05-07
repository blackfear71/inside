<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Code Generator';
      $styleHead      = 'styleAdmin.css';
      $scriptHead     = 'scriptAdmin.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Générateur de code';

        include('../../includes/common/header.php');
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

          /*********************************************/
          /* Aide au développement d'une nouvelle page */
          /*********************************************/
          echo '<div class="zone_generator_left margin_right_20">';
            // Web
            echo '<div class="titre_section"><img src="../../includes/icons/admin/informations_grey.png" alt="informations_grey" class="logo_titre_section" /><div class="texte_titre_section">Aide au développement d\'une nouvelle page (web)</div></div>';

            echo '<div class="explications_generator">';
              echo 'Lors du développement d\'une nouvelle section, il est impératif de suivre certains points :';

              echo '<ul>';
                echo '<li>Respecter l\'<strong>architecture MVC</strong> du site</li>';
                echo '<li>Ajouter une <strong>icône</strong> sur la page d\'index</li>';
                echo '<li>Si la nouvelle section implique l\'utilisation d\'une <strong>préférence utilisateur</strong>, en tenir compte à l\'<strong>inscription</strong> d\'un nouvel utilisateur</li>';
                echo '<li>Si la nouvelle section implique la création d\'<strong>enregistrements personnalisés</strong>, en tenir compte à la <strong>désinscription</strong></li>';
                echo '<li>Modifier les commentaires dans le <strong>contrôleur</strong> généré</li>';
                echo '<li>Si c\'est une page utilisateur, ajouter un <strong>lien sur le portail</strong> principal</li>';
                echo '<li>Si c\'est une page utilisateur, ajouter un <strong>lien dans les onglets</strong> de navigation</li>';
                echo '<li>Si c\'est une page utilisateur, rajouter la page dans la <strong>liste des pages éligibles aux missions</strong> (fonction generateMissions() dans metier_commun.php)</li>';
                echo '<li>Gérer la nouvelle page dans la <strong>section des logs</strong> (modifier les 2 fonctions getCategories())</li>';
                echo '<li>Mettre à jour le fichier <strong>readme.md</strong> si besoin (pour GitHub)</li>';
              echo '</ul>';
            echo '</div>';

            // Mobile
            echo '<div class="titre_section"><img src="../../includes/icons/admin/mobile_grey.png" alt="mobile_grey" class="logo_titre_section" /><div class="texte_titre_section">Aide au développement d\'une nouvelle page (mobile)</div></div>';

            echo '<div class="explications_generator">';
              echo 'Les développements mobiles reprennent les règles du développement web. Les contrôleurs et les métiers sont communs, seule la vue est spécifique pour lui adapter un style particulier. Certains points particuliers sont à suivre :';

              echo '<ul>';
                echo '<li>Lors de l\'ajout d\'une nouvelle section, celle-ci <strong>doit être autorisée</strong> dans la fonction isAccessibleMobile() de metier_commun.php</li>';
                echo '<li>Débloquer la section dans <strong>le portail</strong> dans la fonction getPortail() de metier_portail.php</li>';
                echo '<li>Ajouter un lien vers la section dans <strong>le menu latéral</strong> de gauche (aside_mobile.php)</li>';
                echo '<li>Ajouter du contenu pour <strong>Celsius</strong> dans celsius.php</li>';
              echo '</ul>';
            echo '</div>';
          echo '</div>';

          /********************************/
          /* Données de la page à générer */
          /********************************/
          echo '<div class="zone_generator_right">';
            // Titre
            echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Données de la page à générer</div></div>';

            // Paramétrage des données
            echo '<form method="post" action="codegenerator.php?action=generateCode">';
              // Titre
              echo '<div class="categorie_generator">Données générales</div>';

              // Nom fonctionnel
              echo '<input type="text" name="nom_fonctionnel" placeholder="Nom de la section (ex : Portail)" value="' . $generatorParameters->getNom_section() . '" class="saisie_generator margin_right_20" required>';

              // Nom technique
              echo '<input type="text" name="nom_technique" placeholder="Nom technique (ex : portail)" value="' . $generatorParameters->getNom_technique() . '" class="saisie_generator" required>';

              // Nom head
              echo '<input type="text" name="nom_head" placeholder="Nom de l\'onglet du navigateur (ex : Portail)" value="' . $generatorParameters->getNom_head() . '" class="saisie_generator_full" required>';

              // Style spécifique
              echo '<input type="text" name="style_specifique" placeholder="Style CSS spécifique (ex : stylePortail)" value="' . $generatorParameters->getStyle_specifique() . '" class="saisie_generator margin_right_20">';

              // Script spécifique
              echo '<input type="text" name="script_specifique" placeholder="Script JS spécifique (ex : scriptPortail)" value="' . $generatorParameters->getScript_specifique() . '" class="saisie_generator">';

              // Options
              $old_category = '';

              foreach ($generatorParameters->getOptions() as $generatorOption)
              {
                $current_category = $generatorOption->getCategorie();

                if ($current_category != $old_category)
                {
                  $old_category = $current_category;
                  echo '<div class="categorie_generator">' . $current_category . '</div>';
                }

                if ($generatorOption->getChecked() == 'Y')
                {
                  echo '<div id="switch_' . $generatorOption->getOption() . '" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_' . $generatorOption->getOption() . '" name="' . $generatorOption->getOption() . '" value="' . $generatorOption->getOption() . '" checked />';
                    echo '<label for="checkbox_' . $generatorOption->getOption() . '" id="label_' . $generatorOption->getOption() . '" class="label_switch">' . $generatorOption->getTitre() . '</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_' . $generatorOption->getOption() . '" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_' . $generatorOption->getOption() . '" name="' . $generatorOption->getOption() . '" value="' . $generatorOption->getOption() . '" />';
                    echo '<label for="checkbox_' . $generatorOption->getOption() . '" id="label_' . $generatorOption->getOption() . '" class="label_switch">' . $generatorOption->getTitre() . '</label>';
                  echo '</div>';
                }
              }

              // Bouton
              echo '<input type="submit" name="generate_code" value="Générer le code" class="bouton_generator" />';
            echo '</form>';
          echo '</div>';

          /***************/
          /* Code généré */
          /***************/
          if (!empty($generatorParameters->getNom_section()) AND !empty($generatorParameters->getNom_technique()))
          {
            echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Code généré</div></div>';

            // Code généré
            echo '<div class="margin_bottom_moins_30">';
              // Partie Métier
              echo '<div class="zone_generated_left margin_right_20">';
                // Zone Métier
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                  // Entête du fichier
                  echo '<div class="zone_entete_fichier_generator">';
                    // Type et actions
                    echo '<div class="entete_fichier_generator">';
                      echo 'Fichier : métier';
                      echo '<input type="hidden" name="file_name" value="' . $metier['filename'] . '" />';

                      // Boutons
                      echo '<div class="zone_boutons_generator">';
                        // Bouton Télécharger
                        echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                        // Bouton Copier
                        echo '<a id="metier" class="bouton_action_generator copyCode">Copier</a>';
                      echo '</div>';
                    echo '</div>';

                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Nom : ' . $metier['filename'];
                    echo '</div>';
                  echo '</div>';

                  // Contenu du fichier
                  echo '<textarea name="download_zone" id="code_metier" class="code_generator_metier">' . $metier['content'] . '</textarea>';
                echo '</form>';

                // Zone Contrôles
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                  // Entête du fichier
                  echo '<div class="zone_entete_fichier_generator">';
                    // Type et actions
                    echo '<div class="entete_fichier_generator">';
                      echo 'Fichier : contrôles';
                      echo '<input type="hidden" name="file_name" value="' . $controles['filename'] . '" />';

                      // Boutons
                      echo '<div class="zone_boutons_generator">';
                        // Bouton Télécharger
                        echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                        // Bouton Copier
                        echo '<a id="controles" class="bouton_action_generator copyCode">Copier</a>';
                      echo '</div>';
                    echo '</div>';

                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Nom : ' . $controles['filename'];
                    echo '</div>';
                  echo '</div>';

                  // Contenu du fichier
                  echo '<textarea name="download_zone" id="code_controles" class="code_generator_metier">' . $controles['content'] . '</textarea>';
                echo '</form>';

                // Zone Physique
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                  // Entête du fichier
                  echo '<div class="zone_entete_fichier_generator">';
                    // Type et actions
                    echo '<div class="entete_fichier_generator">';
                      echo 'Fichier : physique';
                      echo '<input type="hidden" name="file_name" value="' . $physique['filename'] . '" />';

                      // Boutons
                      echo '<div class="zone_boutons_generator">';
                        // Bouton Télécharger
                        echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                        // Bouton Copier
                        echo '<a id="physique" class="bouton_action_generator copyCode">Copier</a>';
                      echo '</div>';
                    echo '</div>';

                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Nom : ' . $physique['filename'];
                    echo '</div>';
                  echo '</div>';

                  // Contenu du fichier
                  echo '<textarea name="download_zone" id="code_physique" class="code_generator_metier">' . $physique['content'] . '</textarea>';
                echo '</form>';
              echo '</div>';

              // Partie Vue
              echo '<div class="zone_generated_middle margin_right_20">';
                // Zone Vue (web)
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                  // Entête du fichier
                  echo '<div class="zone_entete_fichier_generator">';
                    // Type et actions
                    echo '<div class="entete_fichier_generator">';
                      echo 'Fichier : vue (web)';
                      echo '<input type="hidden" name="file_name" value="' . $listeVues['vue_web']['filename'] . '" />';

                      // Boutons
                      echo '<div class="zone_boutons_generator">';
                        // Bouton Télécharger
                        echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                        // Bouton Copier
                        echo '<a id="vue_web" class="bouton_action_generator copyCode">Copier</a>';
                      echo '</div>';
                    echo '</div>';

                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Nom : ' . $listeVues['vue_web']['filename'];
                    echo '</div>';
                  echo '</div>';

                  // Contenu du fichier
                  if (!empty($listeVues['vue_mobile']))
                    echo '<textarea name="download_zone" id="code_vue_web" class="code_generator_vue_mobile">';
                  else
                    echo '<textarea name="download_zone" id="code_vue_web" class="code_generator_vue">';
                    echo $listeVues['vue_web']['content'];
                  echo '</textarea>';
                echo '</form>';

                // Zone Vue (mobile)
                if (!empty($listeVues['vue_mobile']))
                {
                  echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                      // Type et actions
                      echo '<div class="entete_fichier_generator">';
                        echo 'Fichier : vue (mobile)';
                        echo '<input type="hidden" name="file_name" value="' . $listeVues['vue_mobile']['filename'] . '" />';

                        // Boutons
                        echo '<div class="zone_boutons_generator">';
                          // Bouton Télécharger
                          echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                          // Bouton Copier
                          echo '<a id="vue_mobile" class="bouton_action_generator copyCode">Copier</a>';
                        echo '</div>';
                      echo '</div>';

                      // Nom du fichier
                      echo '<div class="nom_fichier_generator">';
                        echo 'Nom : ' . $listeVues['vue_mobile']['filename'];
                      echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea name="download_zone" id="code_vue_mobile" class="code_generator_vue_mobile">' . $listeVues['vue_mobile']['content'] . '</textarea>';
                  echo '</form>';
                }
              echo '</div>';

              // Partie Contrôleur
              echo '<div class="zone_generated_right">';
                // Zone contrôleur
                echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                  // Entête du fichier
                  echo '<div class="zone_entete_fichier_generator">';
                    // Type et actions
                    echo '<div class="entete_fichier_generator">';
                      echo 'Fichier : contrôleur';
                      echo '<input type="hidden" name="file_name" value="' . $controler['filename'] . '" />';

                      // Boutons
                      echo '<div class="zone_boutons_generator">';
                        // Bouton Télécharger
                        echo '<input type="submit" name="download_php" value="Télécharger" class="bouton_action_generator" />';

                        // Bouton Copier
                        echo '<a id="controler" class="bouton_action_generator copyCode">Copier</a>';
                      echo '</div>';
                    echo '</div>';

                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Nom : ' . $controler['filename'];
                    echo '</div>';
                  echo '</div>';

                  // Contenu du fichier
                  if (!empty($generatorParameters->getScript_specifique()))
                    echo '<textarea name="download_zone" id="code_controler" class="code_generator_controler_js">';
                  else
                    echo '<textarea name="download_zone" id="code_controler" class="code_generator_controler">';
                    echo $controler['content'];
                  echo '</textarea>';
                echo '</form>';

                // Zone Javascript
                if (!empty($generatorParameters->getScript_specifique()))
                {
                  echo '<form method="post" action="codegenerator.php?action=doDownload" class="zone_code_generator">';
                    // Entête du fichier
                    echo '<div class="zone_entete_fichier_generator">';
                      // Type et actions
                      echo '<div class="entete_fichier_generator">';
                        echo 'Fichier : javascript';
                        echo '<input type="hidden" name="file_name" value="' . $javascript['filename'] . '" />';

                        // Boutons
                        echo '<div class="zone_boutons_generator">';
                          // Bouton Télécharger
                          echo '<input type="submit" name="download_js" value="Télécharger" class="bouton_action_generator" />';

                          // Bouton Copier
                          echo '<a id="javascript" class="bouton_action_generator copyCode">Copier</a>';
                        echo '</div>';
                      echo '</div>';

                      // Nom du fichier
                      echo '<div class="nom_fichier_generator">';
                        echo 'Nom : ' . $javascript['filename'];
                      echo '</div>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea name="download_zone" id="code_javascript" class="code_generator_controler_js">' . $javascript['content'] . '</textarea>';
                  echo '</form>';
                }
              echo '</div>';
            echo '</div>';
          }
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
