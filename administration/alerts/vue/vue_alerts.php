<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Alertes';
      $styleHead       = 'styleAdmin.css';
      $scriptHead      = 'scriptAdmin.js';
      $angularHead     = false;
      $chatHead        = false;
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
      <?php
        $title = 'Gestion alertes';

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
          /* Ajout alerte */
          /****************/
          echo '<div class="titre_section"><img src="../../includes/icons/admin/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Ajouter une alerte</div></div>';

          echo '<form method="post" action="alerts.php?action=doAjouter" class="form_saisie_alerte">';
            echo '<table class="table_saisie_alerte">';
              echo '<tr>';
                // Type
                echo '<td class="td_saisie_type">';
                  echo '<select name="type_alert" class="saisie_alerte_type" required>';
                    echo '<option value="" hidden>Type d\'alerte</option>';

                    if ($_SESSION['save']['type_alert'] == 'info')
                      echo '<option value="info" selected>Info</option>';
                    else
                      echo '<option value="info">Info</option>';

                    if ($_SESSION['save']['type_alert'] == 'erreur')
                      echo '<option value="erreur" selected>Erreur</option>';
                    else
                      echo '<option value="erreur">Erreur</option>';
                  echo '</select>';
                echo '</td>';

                // Catégorie
                echo '<td class="td_saisie_alerte_categorie">';
                  echo '<input type="text" name="category_alert" placeholder="Catégorie" value="' . $_SESSION['save']['category_alert'] . '" maxlength="100" class="saisie_alerte" required />';
                echo '</td>';

                // Référence
                echo '<td class="td_saisie_alerte_reference">';
                  echo '<input type="text" name="reference_alert" placeholder="Référence" value="' . $_SESSION['save']['reference_alert'] . '" maxlength="100" class="saisie_alerte" required />';
                echo '</td>';

                // Bouton envoi
                echo '<td class="td_saisie_alerte_envoyer">';
                  echo '<input type="submit" name="send" value="" class="bouton_saisie_alerte" />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Message
                echo '<td colspan="4" class="td_saisie_alerte_message">';
                  echo '<textarea placeholder="Message d\'alerte" name="message_alert" class="saisie_alerte_message" required>' . $_SESSION['save']['message_alert'] . '</textarea>';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
          echo '</form>';

          /***********************/
          /* Tableau des alertes */
          /***********************/
          echo '<div class="titre_section"><img src="../../includes/icons/admin/alerts_grey.png" alt="alerts_grey" class="logo_titre_section" /><div class="texte_titre_section">Liste des alertes</div></div>';

          echo '<table class="table_admin">';
            echo '<tr>';
              echo '<td class="width_10">Type</td>';
              echo '<td class="width_15">Catégorie</td>';
              echo '<td class="width_15">Référence</td>';
              echo '<td class="width_40">Message</td>';
              echo '<td class="width_20">Actions</td>';
            echo '</tr>';

            if (!empty($listeAlertes))
            {
              foreach ($listeAlertes as $alerte)
              {
                /***************************************************/
                /* Ligne visualisation normale (sans modification) */
                /***************************************************/
                echo '<tr id="modifier_alerte_2_' . $alerte->getId() . '">';
                  // Type
                  echo '<td class="td_table_admin_premier" id=' . $alerte->getId() . '>';
                    switch ($alerte->getType())
                    {
                      case 'info':
                        echo '<img src="../../includes/icons/common/information.png" alt="information" title="Information" class="icone_table_admin" />';
                        break;

                      case 'erreur':
                        echo '<img src="../../includes/icons/common/alert.png" alt="alert" title="Alerte" class="icone_table_admin" />';
                        break;

                      default:
                        break;
                    }
                  echo '</td>';

                  // Catégorie
                  echo '<td class="td_table_admin_normal">' . $alerte->getCategory() . '</td>';

                  // Référence
                  echo '<td class="td_table_admin_important">' . $alerte->getAlert() . '</td>';

                  // Message
                  echo '<td class="td_table_admin_normal">' . $alerte->getMessage() . '</td>';

                  // Boutons d'action
                  echo '<td class="td_table_admin_actions">';
                    // Modification ligne
                    echo '<span class="lien_action_table_admin">';
                      echo '<a id="alerte_' . $alerte->getId() . '" title="Modifier la ligne" class="icone_modifier_table_admin modifierAlerte"></a>';
                    echo '</span>';

                    // Suppression ligne
                    echo '<form id="delete_alert_' . $alerte->getId() . '" method="post" action="alerts.php?action=doSupprimer" class="lien_action_table_admin">';
                      echo '<input type="hidden" name="id_alert" value="' . $alerte->getId() . '" />';
                      echo '<input type="submit" name="delete_alert" value="" title="Supprimer l\'alerte" class="icone_supprimer_table_admin eventConfirm" />';
                      echo '<input type="hidden" value="Supprimer l\'alerte "' . $alerte->getAlert() . '" (' . $alerte->getCategory() . ') ?" class="eventMessage" />';
                    echo '</form>';
                  echo '</td>';
                echo '</tr>';

                /**********************************/
                /* Ligne cachée pour modification */
                /**********************************/
                echo '<tr id="modifier_alerte_' . $alerte->getId() . '" style="display: none;">';
                  echo '<form method="post" action="alerts.php?action=doModifier">';
                    echo '<input type="hidden" name="id_alert" value="' . $alerte->getId() . '" />';

                    // Type
                    echo '<td class="td_table_admin_premier">';
                      echo '<select name="type_alert" required>';
                        echo '<option value="" hidden>Type d\'alerte</option>';

                        if ($alerte->getType() == 'info')
                          echo '<option value="info" selected>Info</option>';
                        else
                          echo '<option value="info">Info</option>';

                        if ($alerte->getType() == 'erreur')
                          echo '<option value="erreur" selected>Erreur</option>';
                        else
                          echo '<option value="erreur">Erreur</option>';
                      echo '</select>';
                    echo '</td>';

                    // Catégorie
                    echo '<td class="td_table_admin_normal">';
                      echo '<input type="text" name="category_alert" placeholder="Catégorie" value="' . $alerte->getCategory() . '" maxlength="100" required />';
                    echo '</td>';

                    // Référence
                    echo '<td class="td_table_admin_important">';
                      echo '<input type="text" name="reference_alert" placeholder="Référence" value="' . $alerte->getAlert() . '" maxlength="100" required />';
                    echo '</td>';

                    // Message
                    echo '<td class="td_table_admin_normal">';
                      echo '<textarea placeholder="Message d\'alerte" name="message_alert" required>' . $alerte->getMessage() . '</textarea>';
                    echo '</td>';

                    // Boutons d'action
                    echo '<td class="td_table_admin_actions">';
                      // Validation modification
                      echo '<span class="lien_action_table_admin">';
                        echo '<input type="submit" name="update_alert" value="" title="Valider la modification" class="icone_valider_table_admin" />';
                      echo '</span>';

                      // Annulation modification ligne
                      echo '<span class="lien_action_table_admin">';
                        echo '<a id="annuler_alerte_' . $alerte->getId() . '" title="Annuler la modification" class="icone_annuler_table_admin annulerAlerte"></a>';
                      echo '</span>';
                    echo '</td>';
                  echo '</form>';
                echo '</tr>';
              }
            }
            else
            {
              echo '<tr class="tr_table_admin_empty">';
                echo '<td colspan="5" class="empty">Pas d\'alertes paramétrées...</td>';
              echo '</tr>';
            }
          echo '</table>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/web/footer.php'); ?>
		</footer>
  </body>
</html>
