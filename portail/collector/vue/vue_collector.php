<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head        = "CO";
      $style_head        = "styleCO.css";
      $script_head       = "scriptCO.js";
      $chat_head         = true;
      $masonry_head      = true;
      $image_loaded_head = true;

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Collector Room";

        include('../../includes/header.php');
			  include('../../includes/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
        <?php
          /*******************/
          /* Liens de saisie */
          /*******************/
          echo '<div class="zone_liens_saisie">';
            echo '<a onclick="afficherMasquerSaisiePhraseCulte();" title="Ajouter une phrase culte" class="lien_saisie_collector">';
              echo '<div class="zone_logo_add"><img src="../../includes/icons/collector/phrases.png" alt="comments" class="image_saisie_collector"/></div>';
              echo '<div class="zone_texte_add">Ajouter une phrase culte</div>';
            echo '</a>';

            echo '<a onclick="afficherMasquerSaisieImage();" title="Ajouter une image" class="lien_saisie_collector">';
              echo '<div class="zone_logo_add"><img src="../../includes/icons/collector/images.png" alt="images" class="image_saisie_collector"/></div>';
              echo '<div class="zone_texte_add">Ajouter une image</div>';
            echo '</a>';
          echo '</div>';

          /**********************************/
          /* Zone de saisie de phrase culte */
          /**********************************/
          echo '<div id="zone_add_collector" style="display: none;" class="fond_saisie_collector">';
            echo '<div class="zone_saisie_collector">';
              // Titre
              echo '<div class="titre_saisie_collector">Ajouter une phrase culte</div>';

              // Bouton fermeture
              echo '<a onclick="afficherMasquerSaisiePhraseCulte();" class="close_index"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

              // Saisie phrase culte
              echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" class="form_saisie_collector">';
                echo '<table class="table_saisie_collector">';
                  // Type de saisie
                  echo '<input type="hidden" name="type_collector" value="T" />';

                  // Tableau de saisie
                  echo '<tr>';
                    // Saisie speaker
                    if (!empty($_SESSION['save']['other_speaker']))
                      echo '<td class="td_saisie_collector_user" id="td_other" style="width: 20%;">';
                    else
                      echo '<td class="td_saisie_collector_user" id="td_other">';
                        echo '<select name="speaker" id="speaker" onchange="afficherOther(\'td_other\', \'speaker\', \'other_speaker\', \'other_name\');" class="saisie_speaker" required>';
                          echo '<option value="" hidden>Choisissez...</option>';

                          foreach ($listeUsers as $user)
                          {
                            if ($user->getIdentifiant() == $_SESSION['save']['speaker'])
                              echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
                            else
                              echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                          }

                          if (!empty($_SESSION['save']['other_speaker']))
                            echo '<option value="other" selected>Autre</option>';
                          else
                            echo '<option value="other">Autre</option>';
                      echo '</select>';
                    echo '</td>';

                    // Saisie "Autre"
                    if (!empty($_SESSION['save']['other_speaker']))
                      echo '<td class="td_saisie_collector_name" id="other_speaker">';
                    else
                      echo '<td class="td_saisie_collector_name" id="other_speaker" style="display: none;">';
                        echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name" class="saisie_other_collector" />';
                    echo '</td>';

                    // Saisie date
                    echo '<td class="td_saisie_collector_date">';
                      echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" id="datepicker1" class="saisie_date_collector" required />';
                    echo '</td>';

                    // Bouton
                    echo '<td class="td_saisie_collector_add">';
                      echo '<input type="submit" name="insert_collector" value="Ajouter" class="saisie_bouton" />';
                    echo '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    // Saisie phrase
                    echo '<td colspan="100%" class="td_saisie_collector">';
                      echo '<textarea placeholder="Phrase culte" name="collector" class="saisie_collector" required>' . $_SESSION['save']['collector'] . '</textarea>';
                    echo '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    // Saisie contexte
                    echo '<td colspan="100%" class="td_saisie_collector_cont">';
                      echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['save']['context'] . '</textarea>';
                    echo '</td>';
                  echo '</tr>';
                echo '</table>';
              echo '</form>';
            echo '</div>';
          echo '</div>';

          /**************************/
          /* Zone de saisie d'image */
          /**************************/
          echo '<div id="zone_add_image" style="display: none;" class="fond_saisie_collector">';
            echo '<div class="zone_saisie_collector">';
              // Titre
              echo '<div class="titre_saisie_collector">Ajouter une image</div>';

              // Bouton fermeture
              echo '<a onclick="afficherMasquerSaisieImage();" class="close_index"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

              // Saisie image
              echo '<form method="post" action="collector.php?action=doAjouter&page=' . $_GET['page'] . '" enctype="multipart/form-data" runat="server" class="form_saisie_collector">';
                echo '<table class="table_saisie_collector">';
                  // Type de saisie
                  echo '<input type="hidden" name="type_collector" value="I" />';

                  // Tableau de saisie
                  echo '<tr>';
                    // Saisie image
                    echo '<td rowspan="2" class="td_saisie_collector_image">';
                      echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

                      echo '<div class="zone_parcourir_image">';
                        echo '<div class="symbole_saisie_image">+</div>';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image" onchange="loadFile(event, \'image_collector\')" required />';
                      echo '</div>';

                      echo '<div class="mask_image">';
                        echo '<img id="image_collector" class="image" />';
                      echo '</div>';
                    echo '</td>';

                    // Saisie speaker
                    if (!empty($_SESSION['save']['other_speaker']))
                      echo '<td class="td_saisie_collector_user" id="td_other_2" style="width: 20%;">';
                    else
                      echo '<td class="td_saisie_collector_user" id="td_other_2">';
                        echo '<select name="speaker" id="speaker_2" onchange="afficherOther(\'td_other_2\', \'speaker_2\', \'other_speaker_2\', \'other_name_2\');" class="saisie_speaker" required>';
                          echo '<option value="" hidden>Choisissez...</option>';

                          foreach ($listeUsers as $user)
                          {
                            if ($user->getIdentifiant() == $_SESSION['save']['speaker'])
                              echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
                            else
                              echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                          }

                          if (!empty($_SESSION['save']['other_speaker']))
                            echo '<option value="other" selected>Autre</option>';
                          else
                            echo '<option value="other">Autre</option>';
                      echo '</select>';
                    echo '</td>';

                    // Saisie "Autre"
                    if (!empty($_SESSION['save']['other_speaker']))
                      echo '<td class="td_saisie_collector_name" id="other_speaker_2">';
                    else
                      echo '<td class="td_saisie_collector_name" id="other_speaker_2" style="display: none;">';
                        echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="100" id="other_name_2" class="saisie_other_collector" />';
                    echo '</td>';

                    // Saisie date
                    echo '<td class="td_saisie_collector_date">';
                      echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" id="datepicker2" class="saisie_date_collector" required />';
                    echo '</td>';

                    // Bouton
                    echo '<td class="td_saisie_collector_add">';
                      echo '<input type="submit" name="insert_collector" value="Ajouter" class="saisie_bouton" />';
                    echo '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    // Saisie contexte
                    echo '<td colspan="100%" class="td_saisie_collector_cont">';
                      echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['save']['context'] . '</textarea>';
                    echo '</td>';
                  echo '</tr>';
                echo '</table>';
              echo '</form>';
            echo '</div>';
          echo '</div>';

          /********************************/
          /* Affichage des phrases cultes */
          /********************************/
          include('vue/table_collectors.php');

          // Pagination
          if ($nbPages > 1)
          {
            echo '<div class="zone_pagination">';
              for ($i = 1; $i <= $nbPages; $i++)
              {
                if ($i == $_GET['page'])
                  echo '<div class="numero_page_active">' . $i . '</div>';
                else
                {
                  echo '<div class="numero_page_inactive">';
                    echo '<a href="collector.php?action=goConsulter&page=' . $i . '" class="lien_pagination">' . $i . '</a>';
                  echo '</div>';
                }
              }
            echo '</div>';
          }
        ?>
      </article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
