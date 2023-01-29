<?php
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