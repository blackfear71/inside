<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/alerts_grey.png" alt="alerts_grey" class="logo_titre_section" /><div class="texte_titre_section">Liste des alertes</div></div>';

    // Liste des alertes
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
                        echo '<input type="hidden" value="Supprimer l\'alerte &quot;' . $alerte->getAlert() . '&quot; (' . $alerte->getCategory() . ') ?" class="eventMessage" />';
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