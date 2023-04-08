<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/send_grey.png" alt="send_grey" class="logo_titre_section" /><div class="texte_titre_section">Ajouter une alerte</div></div>';

    // Saisie de l'alerte
    echo '<form method="post" action="alerts.php?action=doAjouterAlerte" class="form_saisie_admin">';
        // Type
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

        // Catégorie
        echo '<input type="text" name="category_alert" placeholder="Catégorie" value="' . $_SESSION['save']['category_alert'] . '" maxlength="100" class="saisie_alerte_donnee" required />';

        // Référence
        echo '<input type="text" name="reference_alert" placeholder="Référence" value="' . $_SESSION['save']['reference_alert'] . '" maxlength="100" class="saisie_alerte_donnee" required />';

        // Bouton d'envoi
        echo '<input type="submit" name="send" value="" class="bouton_saisie_alerte" />';
            
        // Message
        echo '<textarea placeholder="Message d\'alerte" name="message_alert" class="saisie_alerte_message" required>' . $_SESSION['save']['message_alert'] . '</textarea>';
    echo '</form>';
?>