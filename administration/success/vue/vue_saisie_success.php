<?php
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
?>