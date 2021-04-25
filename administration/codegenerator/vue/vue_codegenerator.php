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
            echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Données de la page à générer</div></div>';
              // Paramétrage des données
              echo '<form method="post" action="codegenerator.php?action=generateCode">';
                // Nom fonctionnel
                echo '<input type="text" name="nom_fonctionnel" placeholder="Nom de la section (à afficher)" value="' . $generatorParameters->getNom_section() . '" class="saisie_generator margin_right_20" required>';

                // Nom technique
                echo '<input type="text" name="nom_technique" placeholder="Nom technique (dossiers)" value="' . $generatorParameters->getNom_technique() . '" class="saisie_generator" required>';

                // Nom head
                echo '<input type="text" name="nom_head" placeholder="Nom Head (onglet)" value="' . $generatorParameters->getNom_head() . '" class="saisie_generator margin_right_20" required>';

                // Style spécifique
                echo '<input type="text" name="style_specifique" placeholder="Style CSS spécifique" value="' . $generatorParameters->getStyle_specifique() . '" class="saisie_generator margin_right_20">';

                // Script spécifique
                echo '<input type="text" name="script_specifique" placeholder="Script JS spécifique" value="' . $generatorParameters->getScript_specifique() . '" class="saisie_generator">';

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
                echo '<div class="zone_code_generator">';
                  // Nom du fichier
                  echo '<div class="nom_fichier_generator">';
                    echo 'Métier : ' . $metier['filename'];

                    echo '<a id="metier" class="copie_generator copyCode">Copier</a>';
                  echo '</div>';

                  // Contenu du fichier
                  echo '<textarea id="code_metier" class="code_generator_metier">';
                    echo $metier['content'];
                  echo '</textarea>';
                echo '</div>';

                // Zone Contrôles
                echo '<div class="zone_code_generator">';
                  // Nom du fichier
                  echo '<div class="nom_fichier_generator">';
                    echo 'Contrôles : ' . $controles['filename'];

                    echo '<a id="controles" class="copie_generator copyCode">Copier</a>';
                  echo '</div>';

                  // Contenu du fichier
                  echo '<textarea id="code_controles" class="code_generator_metier">';
                    echo $controles['content'];
                  echo '</textarea>';
                echo '</div>';

                // Zone Physique
                echo '<div class="zone_code_generator">';
                  // Nom du fichier
                  echo '<div class="nom_fichier_generator">';
                    echo 'Physique : ' . $physique['filename'];

                    echo '<a id="physique" class="copie_generator copyCode">Copier</a>';
                  echo '</div>';

                  // Contenu du fichier
                  echo '<textarea id="code_physique" class="code_generator_metier">';
                    echo $physique['content'];
                  echo '</textarea>';
                echo '</div>';
              echo '</div>';

              // Partie Vue
              echo '<div class="zone_generated_middle margin_right_20">';
                // Zone Vue (web)
                echo '<div class="zone_code_generator">';
                  // Nom du fichier
                  echo '<div class="nom_fichier_generator">';
                    echo 'Vue (web) : ' . $listeVues['vue_web']['filename'];

                    echo '<a id="vue_web" class="copie_generator copyCode">Copier</a>';
                  echo '</div>';

                  // Contenu du fichier
                  if (!empty($listeVues['vue_mobile']))
                    echo '<textarea id="code_vue_web" class="code_generator_vue_mobile">';
                  else
                    echo '<textarea id="code_vue_web" class="code_generator_vue">';
                    echo $listeVues['vue_web']['content'];
                  echo '</textarea>';
                echo '</div>';

                // Zone Vue (mobile)
                if (!empty($listeVues['vue_mobile']))
                {
                  echo '<div class="zone_code_generator">';
                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Vue (mobile) : ' . $listeVues['vue_mobile']['filename'];

                      echo '<a id="vue_mobile" class="copie_generator copyCode">Copier</a>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea id="code_vue_mobile" class="code_generator_vue_mobile">';
                      echo $listeVues['vue_mobile']['content'];
                    echo '</textarea>';
                  echo '</div>';
                }
              echo '</div>';

              // Partie Contrôleur
              echo '<div class="zone_generated_right">';
                // Zone contrôleur
                echo '<div class="zone_code_generator">';
                  // Nom du fichier
                  echo '<div class="nom_fichier_generator">';
                    echo 'Contrôleur : ' . $controler['filename'];

                    echo '<a id="controler" class="copie_generator copyCode">Copier</a>';
                  echo '</div>';

                  // Contenu du fichier
                  if (!empty($generatorParameters->getScript_specifique()))
                    echo '<textarea id="code_controler" class="code_generator_controler_js">';
                  else
                    echo '<textarea id="code_controler" class="code_generator_controler">';
                    echo $controler['content'];
                  echo '</textarea>';
                echo '</div>';

                // Zone Javascript
                if (!empty($generatorParameters->getScript_specifique()))
                {
                  echo '<div class="zone_code_generator">';
                    // Nom du fichier
                    echo '<div class="nom_fichier_generator">';
                      echo 'Javascript : ' . $javascript['filename'];

                      echo '<a id="javascript" class="copie_generator copyCode">Copier</a>';
                    echo '</div>';

                    // Contenu du fichier
                    echo '<textarea id="code_javascript" class="code_generator_controler_js">';
                      echo $javascript['content'];
                    echo '</textarea>';
                  echo '</div>';
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
