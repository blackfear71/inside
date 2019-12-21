<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Code Generator";
      $style_head  = "styleAdmin.css";
      $script_head = "scriptAdmin.js";

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Générateur de code";

        include('../../includes/common/header.php');
      ?>
		</header>

		<section>
      <!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
				<?php
          // Aide au développement d'une nouvelle page
          echo '<div class="zone_generator_left margin_right_20">';
            echo '<div class="titre_section"><img src="../../includes/icons/admin/informations_grey.png" alt="informations_grey" class="logo_titre_section" /><div class="texte_titre_section">Aide au développement d\'une nouvelle page</div></div>';

            echo '<div class="explications_generator">';
              echo 'Lors du développement d\'une nouvelle section, il est impératif de suivre certains points :';

              echo '<ul>';
                echo '<li>Respecter l\'<strong>architecture MVC</strong> du site</li>';
                echo '<li>Ajouter une <strong>icône</strong> sur la page d\'index</li>';
                echo '<li>Si la nouvelle section implique l\'utilisation d\'une <strong>préférence utilisateur</strong>, en tenir compte à l\'<strong>inscription</strong> d\'un nouvel utilisateur</li>';
                echo '<li>Modifier les commentaires dans le <strong>contrôleur</strong> généré</li>';
                echo '<li>Si c\'est une page utilisateur, ajouter un <strong>lien sur le portail</strong> principal</li>';
                echo '<li>Si c\'est une page utilisateur, ajouter un <strong>lien dans les onglets</strong> de navigation</li>';
                echo '<li>Si c\'est une page utilisateur, rajouter la page dans la <strong>liste des pages éligibles aux missions</strong></li>';
              echo '</ul>';
            echo '</div>';
          echo '</div>';

          // Données de la page à générer
          echo '<div class="zone_generator_right">';
            echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Données de la page à générer</div></div>';
              // Paramétrage des données
              echo '<form method="post" action="codegenerator.php?action=generateCode" class="">';
                // Nom fonctionnel
                echo '<input type="text" name="nom_fonctionnel" placeholder="Nom de la section" class="saisie_generator margin_right_20" required>';

                // Nom technique
                echo '<input type="text" name="nom_technique" placeholder="Nom technique" class="saisie_generator" required>';

                // Style spécifique
                echo '<input type="text" name="style_specifique" placeholder="Style CSS spécifique" class="saisie_generator margin_right_20">';

                // Script spécifique
                echo '<input type="text" name="script_specifique" placeholder="Script JS spécifique" class="saisie_generator">';

                // Options
                foreach ($checkboxes as $checkbox)
                {
                  if ($checkbox['checked'] == "Y")
                  {
                    echo '<div id="switch_' . $checkbox['option'] . '" class="switch_generator switch_checked">';
                      echo '<input type="checkbox" id="checkbox_' . $checkbox['option'] . '" name="' . $checkbox['option'] . '" value="' . $checkbox['option'] . '" checked />';
                      echo '<label for="checkbox_' . $checkbox['option'] . '" id="label_' . $checkbox['option'] . '" class="label_switch">' . $checkbox['titre'] . '</label>';
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div id="switch_' . $checkbox['option'] . '" class="switch_generator">';
                      echo '<input type="checkbox" id="checkbox_' . $checkbox['option'] . '" name="' . $checkbox['option'] . '" value="' . $checkbox['option'] . '" />';
                      echo '<label for="checkbox_' . $checkbox['option'] . '" id="label_' . $checkbox['option'] . '" class="label_switch">' . $checkbox['titre'] . '</label>';
                    echo '</div>';
                  }
                }







                /*// Page admin
                if ($checkbox_admin == "Y")
                {
                  echo '<div id="switch_admin" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_admin" name="admin" value="admin" checked />';
                    echo '<label for="checkbox_admin" id="label_admin" class="label_switch">Page admin</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_admin" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_admin" name="admin" value="admin" />';
                    echo '<label for="checkbox_admin" id="label_admin" class="label_switch">Page admin</label>';
                  echo '</div>';
                }

                // Angular
                if ($checkbox_angular == "Y")
                {
                  echo '<div id="switch_angular" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_angular" name="angular" value="angular" checked />';
                    echo '<label for="checkbox_angular" id="label_angular" class="label_switch">Angular</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_angular" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_angular" name="angular" value="angular" />';
                    echo '<label for="checkbox_angular" id="label_angular" class="label_switch">Angular</label>';
                  echo '</div>';
                }

                // Chat
                if ($checkbox_chat == "Y")
                {
                  echo '<div id="switch_chat" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_chat" name="chat" value="chat" checked />';
                    echo '<label for="checkbox_chat" id="label_chat" class="label_switch">Chat</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_chat" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_chat" name="chat" value="chat" />';
                    echo '<label for="checkbox_chat" id="label_chat" class="label_switch">Chat</label>';
                  echo '</div>';
                }

                // Calendriers
                if ($checkbox_datepicker == "Y")
                {
                  echo '<div id="switch_datepicker" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_datepicker" name="datepicker" value="datepicker" checked />';
                    echo '<label for="checkbox_datepicker" id="label_datepicker" class="label_switch">Datepicker</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_datepicker" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_datepicker" name="datepicker" value="datepicker" />';
                    echo '<label for="checkbox_datepicker" id="label_datepicker" class="label_switch">Datepicker</label>';
                  echo '</div>';
                }

                // Masonry
                if ($checkbox_masonry == "Y")
                {
                  echo '<div id="switch_masonry" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_masonry" name="masonry" value="masonry" checked />';
                    echo '<label for="checkbox_masonry" id="label_masonry" class="label_switch">Masonry</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_masonry" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_masonry" name="masonry" value="masonry" />';
                    echo '<label for="checkbox_masonry" id="label_masonry" class="label_switch">Masonry</label>';
                  echo '</div>';
                }

                // Données EXIF
                if ($checkbox_exif == "Y")
                {
                  echo '<div id="switch_exif" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_exif" name="exif" value="exif" checked />';
                    echo '<label for="checkbox_exif" id="label_exif" class="label_switch">Données EXIF</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_exif" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_exif" name="exif" value="exif" />';
                    echo '<label for="checkbox_exif" id="label_exif" class="label_switch">Données EXIF</label>';
                  echo '</div>';
                }

                // Alertes
                if ($checkbox_alerts == "Y")
                {
                  echo '<div id="switch_alerts" class="switch_generator switch_checked">';
                    echo '<input type="checkbox" id="checkbox_alerts" name="alerts" value="alerts" checked />';
                    echo '<label for="checkbox_alerts" id="label_alerts" class="label_switch">Alertes</label>';
                  echo '</div>';
                }
                else
                {
                  echo '<div id="switch_alerts" class="switch_generator">';
                    echo '<input type="checkbox" id="checkbox_alerts" name="alerts" value="alerts" />';
                    echo '<label for="checkbox_alerts" id="label_alerts" class="label_switch">Alertes</label>';
                  echo '</div>';
                }*/





                

                // Bouton
                echo '<input type="submit" name="generate_code" value="Générer le code" class="bouton_generator" />';
              echo '</form>';
          echo '</div>';

          // Code généré
          echo '<div class="titre_section"><img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" /><div class="texte_titre_section">Code généré</div></div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
