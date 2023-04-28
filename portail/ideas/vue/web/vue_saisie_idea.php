<?php
    /**************************/
    /* Zone de saisie rapport */
    /**************************/
    echo '<div id="zone_saisie_idee" class="fond_saisie">';
        echo '<div class="zone_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                // Texte
                echo '<div class="texte_titre_saisie">Proposer une idée</div>';

                // Bouton fermeture
                echo '<a id="fermerIdee" class="bouton_fermeture_saisie"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="image_fermeture_saisie" /></a>';
            echo '</div>';

            // Saisie idée
            echo '<form method="post" action="ideas.php?view=' . $_GET['view'] . '&action=doAjouterIdee" class="form_saisie">';
                // Explications
                echo '<div class="zone_saisie_left">';
                    echo '<div class="titre_saisie_idee">';
                        echo 'Le cycle de vie d\'une idée';
                    echo '</div>';

                    echo '<img src="../../includes/images/ideas/cycle_idee.png" alt="cycle_idee" class="cycle_de_vie" />';
                echo '</div>';

                // Zone saisie idée
                echo '<div class="zone_saisie_right">';
                    // Titre de l'idée
                    echo '<input type="text" name="sujet_idee" placeholder="Titre" maxlength="255" class="saisie_titre" required />';

                    // Boutons d'action
                    echo '<div class="zone_bouton_saisie">';
                        // Ajouter
                        echo '<input type="submit" name="new_idea" value="Soumettre l\'idée" id="bouton_saisie_idee" class="saisie_bouton" />';
                    echo '</div>';

                    // Contenu de l'idée
                    echo '<textarea placeholder="Description de l\'idée" name="contenu_idee" class="saisie_contenu" required></textarea>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>