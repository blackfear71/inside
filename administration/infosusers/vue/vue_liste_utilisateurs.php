<?php
    echo '<div class="zone_infos_equipes">';
        $i = 0;

        foreach ($listeUsersParEquipe as $referenceEquipe => $usersParEquipe)
        {
            // Titre
            echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section_fold">' . $listeEquipes[$referenceEquipe]->getTeam() . '</div>';

                echo '<a id="fold_zone_infos_' . $i . '" class="bouton_fold">';
                    echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_bouton_fold" />';
                echo '</a>';
            echo '</div>';

            // Liste des équipes par équipe
            echo '<div id="zone_infos_' . $i . '" class="zone_infos">';
                foreach ($usersParEquipe as $user)
                {
                    echo '<div class="zone_infos_user">';
                        // Avatar
                        $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                        echo '<div class="circle_avatar">';
                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="infos_avatar" />';
                        echo '</div>';

                        // Pseudo
                        echo '<div class="infos_pseudo">' . $user->getPseudo() . '</div>';

                        // Identifiant
                        echo '<div class="infos_identifiant">' . $user->getIdentifiant() . '</div>';

                        // Anniversaire
                        if (!empty($user->getAnniversary()))
                            echo '<div class="infos_identifiant"><img src="../../includes/icons/admin/anniversary_grey.png" alt="anniversary_grey" class="logo_infos" />' . formatDateForDisplay($user->getAnniversary()) . '</div>';

                        // Niveau
                        echo '<div class="infos_niveau"><img src="../../includes/icons/admin/inside_red.png" alt="inside_red" class="logo_infos" />Niveau ' . $user->getLevel() . ' (' . $user->getExperience() . ' XP)</div>';

                        // Email
                        if (!empty($user->getEmail()))
                            echo '<div class="infos_mail"><img src="../../includes/icons/admin/mailing_red.png" alt="mailing_red" class="logo_infos" />' . $user->getEmail() . '</div>';

                        // Date de dernière connexion
                        echo '<div class="date_infos_users">';
                            echo 'Date de dernière connexion : ';

                            if (!empty($user->getPing()))
                                echo formatDateForDisplay(substr($user->getPing(), 0, 4) . substr($user->getPing(), 5, 2) . substr($user->getPing(), 8, 2));
                            else
                                echo 'Pas de connexion récente';
                        echo '</div>';

                        echo '<div class="zone_form_users">';
                            // Formulaire True Insider
                            echo '<form method="post" action="infosusers.php?action=changeBeginnerStatus" class="form_infos_users">';
                                echo '<input type="hidden" name="user_infos" value="' . $user->getIdentifiant() . '" />';
                                echo '<input type="hidden" name="top_infos" value="' . $user->getBeginner() . '" />';

                                if ($user->getBeginner() == '1')
                                    echo '<input type="submit" value="True Insider" class="beginner" />';
                                else
                                    echo '<input type="submit" value="True Insider" class="not_beginner" />';
                            echo '</form>';

                            // Formulaire Developpeur
                            echo '<form method="post" action="infosusers.php?action=changeDevelopperStatus" class="form_infos_users">';
                                echo '<input type="hidden" name="user_infos" value="' . $user->getIdentifiant() . '" />';
                                echo '<input type="hidden" name="top_infos" value="' . $user->getDevelopper() . '" />';

                                if ($user->getDevelopper() == '1')
                                    echo '<input type="submit" value="Développeur" class="developper" />';
                                else
                                    echo '<input type="submit" value="Développeur" class="not_developper" />';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';

            $i++;
        }
    echo '</div>';
?>