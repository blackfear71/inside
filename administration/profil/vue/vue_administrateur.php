<?php
    // Mot de passe
    echo '<div class="zone_profil_bottom">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/profil/connexion_grey.png" alt="connexion_grey" class="logo_titre_section" /><div class="texte_titre_section">Administrateur</div></div>';

        // Saisie mot de passe
        echo '<div class="zone_action_user">';
            echo '<div class="titre_contribution">CHANGER MOT DE PASSE</div>';

            // Modification mot de passe
            echo '<form method="post" action="profil.php?action=doModifierMotDePasse">';
                echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_saisie" required />';
                echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_saisie" required />';
                echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_saisie" required />';

                echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_validation" />';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>