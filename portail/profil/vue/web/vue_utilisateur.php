<?php
    echo '<div class="zone_profil_bottom_left">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/profil/connexion_grey.png" alt="connexion_grey" class="logo_titre_section" /><div class="texte_titre_section">Utilisateur</div></div>';

        // Mot de passe
        echo '<div class="zone_action_user">';
            echo '<div class="titre_contribution">CHANGER MOT DE PASSE</div>';

            // Modification mot de passe
            echo '<form method="post" action="profil.php?action=doUpdatePassword">';
                echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_saisie_2" required />';
                echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_saisie_2" required />';
                echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_saisie_2" required />';

                echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_validation" />';
            echo '</form>';

            // Annulation demande
            if ($profil->getStatus() == 'P')
            {
                echo '<div class="message_profil margin_top_20">Si vous avez fait la demande de réinitialisation de mot de passe mais que vous souhaitez l\'annuler car vous l\'avez retrouvé, cliquez sur ce bouton.</div>';

                echo '<form method="post" action="profil.php?action=cancelResetPassword" class="margin_top_20">';
                    echo '<input type="submit" name="cancel_reset" value="Annuler la demande" class="bouton_validation" />';
                echo '</form>';

                echo '<div class="message_profil bold margin_top_20">Une demande est en cours.</div>';
            }
        echo '</div>';

        // Changement d'équipe
        echo '<div class="zone_action_user">';
            echo '<div class="titre_contribution">CHANGER D\'ÉQUIPE</div>';

            echo '<div class="message_profil">Vous pouvez demander à changer d\'equipe à l\'administrateur ici. En cas de validation, les idées en charge non terminées seront réinitialisées et les recettes que vous n\'aurez pas encore réalisées seront supprimées.</div>';

            // Choix de l'équipe
            if ($profil->getStatus() == 'T')
                echo '<div class="message_profil bold margin_top_20">Une demande est déjà en cours.</div>';
            else
            {
                echo '<form method="post" action="profil.php?action=doUpdateEquipe">';
                    echo '<select name="equipe" class="select_form_saisie" required>';
                        echo '<option value="" hidden>Choisir une équipe</option>';

                        foreach ($listeEquipes as $equipe)
                        {
                            if ($equipe->getReference() == $profil->getTeam())
                                echo '<option value="' . $equipe->getReference() . '" selected>' . $equipe->getTeam() . '</option>';
                            else
                                echo '<option value="' . $equipe->getReference() . '">' . $equipe->getTeam() . '</option>';
                        }

                        echo '<option value="other">Créer une équipe</option>';
                    echo '</select>';

                    // Saisie "Autre"
                    echo '<input type="text" name="autre_equipe" value="" placeholder="Nom de l\'équipe" id="autre_equipe" class="monoligne_saisie_2" style="display: none;" />';

                    // Bouton validation
                    echo '<input type="submit" name="update_team" value="Changer d\'équipe" class="bouton_validation" />';
                echo '</form>';
            }
        echo '</div>';

        // Désinscription
        echo '<div class="zone_action_user">';
            echo '<div class="titre_contribution">DÉSINSCRIPTION</div>';

            echo '<div class="message_profil">Si vous souhaitez vous désinscrire, vous pouvez en faire la demande à l\'administrateur à l\'aide de ce bouton. Il validera votre choix après vérification.</div>';

            if ($profil->getStatus() == 'D')
            {
                // Annulation
                echo '<form method="post" action="profil.php?action=cancelDesinscription" class="margin_top_20">';
                    echo '<input type="submit" name="cancel_desinscription" value="Annuler la demande" class="bouton_validation" />';
                echo '</form>';

                echo '<div class="message_profil bold margin_top_20">Une demande est déjà en cours.</div>';
            }
            else
            {
                // Désinscription
                echo '<form method="post" action="profil.php?action=askDesinscription" class="margin_top_20">';
                    echo '<input type="submit" name="ask_desinscription" value="Désinscription" class="bouton_validation" />';
                echo '</form>';

                echo '<div class="message_profil bold margin_top_20">Aucune demande en cours.</div>';
            }
        echo '</div>';
    echo '</div>';
?>