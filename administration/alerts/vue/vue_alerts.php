<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Alertes';
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
        $title = 'Gestion alertes';

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

          /****************/
          /* Ajout alerte */
          /****************/
          echo '<div class="titre_section"><img src="../../includes/icons/admin/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Ajouter une alerte</div></div>';

          echo '<form method="post" action="alerts.php?action=doAjouter" class="form_saisie_alert">';
            echo '<table class="table_saisie_alert">';
              echo '<tr>';
                // Type
                echo '<td class="td_saisie_typ">';
                  echo '<select name="type_alert" class="saisie_alert_typ" required>';
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
                echo '<td class="td_saisie_alert_cat">';
                  echo '<input type="text" name="category_alert" placeholder="Catégorie" value="' . $_SESSION['save']['category_alert'] . '" maxlength="100" class="saisie_alert_cat" required />';
                echo '</td>';

                // Référence
                echo '<td class="td_saisie_alert_ref">';
                  echo '<input type="text" name="reference_alert" placeholder="Référence" value="' . $_SESSION['save']['reference_alert'] . '" maxlength="100" class="saisie_alert_ref" required />';
                echo '</td>';

                // Bouton envoi
                echo '<td rowspan="2" class="td_saisie_alert_env">';
                  echo '<input type="submit" name="send" value="" class="send_alert" />';
                echo '</td>';
              echo '</tr>';

              echo '<tr>';
                // Message
                echo '<td colspan="3" class="td_saisie_alert_mes">';
                  echo '<textarea placeholder="Message d\'alerte" name="message_alert" class="saisie_alert_mes" required>' . $_SESSION['save']['message_alert'] . '</textarea>';
                echo '</td>';
              echo '</tr>';
            echo '</table>';
          echo '</form>';

          /***********************/
          /* Tableau des alertes */
          /***********************/
          echo '<div class="titre_section"><img src="../../includes/icons/admin/alerts_grey.png" alt="alerts_grey" class="logo_titre_section" /><div class="texte_titre_section">Liste des alertes</div></div>';

          echo '<table class="table_alerts">';
            echo '<tr class="title_table_alerts">';
              echo '<td class="title_type_alerts">Type</td>';
              echo '<td class="title_category_alerts">Catégorie</td>';
              echo '<td class="title_reference_alerts">Référence</td>';
              echo '<td class="title_message_alerts">Message</td>';
              echo '<td class="title_actions_alerts">Actions</td>';
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
                  echo '<td class="td_type_alerts" id=' . $alerte->getId() . '>';
                    switch ($alerte->getType())
                    {
                      case 'info':
                        echo '<img src="../../includes/icons/common/information.png" alt="information" title="Information" class="img_alert" />';
                        break;

                      case 'erreur':
                        echo '<img src="../../includes/icons/common/alert.png" alt="alert" title="Alerte" class="img_alert" />';
                        break;

                      default:
                        break;
                    }
                  echo '</td>';

                  // Catégorie
                  echo '<td class="td_alerts">' . $alerte->getCategory() . '</td>';

                  // Référence
                  echo '<td class="td_reference_alerts">' . $alerte->getAlert() . '</td>';

                  // Message
                  echo '<td class="td_alerts">' . $alerte->getMessage() . '</td>';

                  // Boutons d'action
                  echo '<td class="actions_alerte">';
                    // Modification ligne
                    echo '<span class="link_action_alerte">';
                      echo '<a id="alerte_' . $alerte->getId() . '" title="Modifier la ligne" class="icone_modifier_alerte modifierAlerte"></a>';
                    echo '</span>';

                    // Suppression ligne
                    echo '<form id="delete_alert_' . $alerte->getId() . '" method="post" action="alerts.php?action=doSupprimer" class="link_action_alerte">';
                      echo '<input type="hidden" name="id_alert" value="' . $alerte->getId() . '" />';
                      echo '<input type="submit" name="delete_alert" value="" title="Supprimer l\'alerte" class="icone_supprimer_alerte eventConfirm" />';
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
                    echo '<td class="td_type_alerts">';
                      echo '<select name="type_alert" class="saisie_alert_typ" required>';
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
                    echo '<td class="td_alerts">';
                      echo '<input type="text" name="category_alert" placeholder="Catégorie" value="' . $alerte->getCategory() . '" maxlength="100" class="saisie_alert_cat" required />';
                    echo '</td>';

                    // Référence
                    echo '<td class="td_alerts">';
                      echo '<input type="text" name="reference_alert" placeholder="Référence" value="' . $alerte->getAlert() . '" maxlength="100" class="saisie_alert_ref" required />';
                    echo '</td>';

                    // Message
                    echo '<td class="td_alerts">';
                      echo '<textarea placeholder="Message d\'alerte" name="message_alert" class="saisie_alert_mes" required>' . $alerte->getMessage() . '</textarea>';
                    echo '</td>';

                    // Boutons d'action
                    echo '<td class="actions_alerte">';
                      // Validation modification
                      echo '<span class="link_action_alerte">';
                        echo '<input type="submit" name="update_alert" value="" title="Valider la modification" class="icone_valider_alerte" />';
                      echo '</span>';

                      // Annulation modification ligne
                      echo '<span class="link_action_alerte">';
                        echo '<a id="annuler_alerte_' . $alerte->getId() . '" title="Annuler la modification" class="icone_annuler_alerte annulerAlerte"></a>';
                      echo '</span>';
                    echo '</td>';
                  echo '</form>';
                echo '</tr>';
              }
            }
            else
            {
              echo '<tr>';
                echo '<td colspan="5" class="empty">Pas d\'alertes paramétrées...</td>';
              echo '</tr>';
            }
          echo '</table>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
