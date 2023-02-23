<?php
    echo '<div class="zone_ideas_right">';
        // Titre
        switch ($_GET['view'])
        {
            case 'inprogress':
                echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Idées proposées</div></div>';
                break;

            case 'mine':
                echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Idées que j\'ai en charge</div></div>';
                break;

            case 'done':
                echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Idées terminées ou rejetées</div></div>';
                break;

            case 'all':
            default:
                echo '<div class="titre_section"><img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="logo_titre_section" /><div class="texte_titre_section">Toutes les idées</div></div>';
                break;
        }

        // Idées
        if (!empty($listeIdees))
        {
            echo '<div class="zone_ideas">';
                foreach ($listeIdees as $idee)
                {
                    echo '<div class="zone_idea">';
                        echo '<div id="zone_shadow_' . $idee->getId() . '" class="zone_shadow">';
                            // Titre
                            echo '<div class="zone_idea_top" id="' . $idee->getId() . '">';
                                // Libellé
                                echo '<div class="zone_idea_titre">' . $idee->getSubject() . '</div>';

                                // Numéro
                                echo '<div class="zone_idea_id">#' . $idee->getId() . '</div>';
                            echo '</div>';

                            // Infos
                            echo '<div class="zone_idea_middle">';
                                // Avatar
                                $avatarFormatted = formatAvatar($idee->getAvatar_author(), $idee->getPseudo_author(), 2, 'avatar');

                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_idea" />';

                                // Pseudo
                                echo '<div class="pseudo_idea">' . formatUnknownUser($idee->getPseudo_author(), true, true) . '</div>';

                                // Date
                                echo '<div class="date_idea">';
                                    echo '<img src="../../includes/icons/ideas/date.png" alt="date" class="icone_idea" />';
                                    echo formatDateForDisplay($idee->getDate());
                                echo '</div>';

                                switch ($idee->getStatus())
                                {
                                    // Prise en charge
                                    case 'C':
                                        echo '<div class="idea_status idea_in_charge">Prise en charge</div>';
                                        break;

                                    // En progrès
                                    case 'P':
                                        echo '<div class="idea_status idea_in_progress">En cours de développement</div>';
                                        break;

                                    // Terminée
                                    case 'D':
                                        echo '<div class="idea_status idea_ended">Terminée</div>';
                                        break;

                                    // Rejetée
                                    case 'R':
                                        echo '<div class="idea_status idea_rejected">Rejetée</div>';
                                        break;

                                    // Ouverte
                                    case 'O':
                                    default:
                                        echo '<div class="idea_status idea_proposed">Proposée</div>';
                                        break;
                                }
                            echo '</div>';

                            // Développeur
                            if (!empty($idee->getDevelopper()))
                            {
                                switch ($idee->getStatus())
                                {
                                    // Prise en charge
                                    case 'C':
                                        echo '<div class="zone_idea_dev idea_in_charge">';
                                        break;

                                    // En progrès
                                    case 'P':
                                        echo '<div class="zone_idea_dev idea_in_progress">';
                                        break;

                                    // Terminée
                                    case 'D':
                                        echo '<div class="zone_idea_dev idea_ended">';
                                        break;

                                    // Rejetée
                                    case 'R':
                                        echo '<div class="zone_idea_dev idea_rejected">';
                                        break;

                                    // Ouverte
                                    case 'O':
                                    default:
                                        echo '<div class="zone_idea_dev idea_proposed">';
                                        break;
                                }

                                    // Avatar
                                    $avatarFormatted = formatAvatar($idee->getAvatar_developper(), $idee->getPseudo_developper(), 2, 'avatar');

                                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_idea" />';

                                    // Pseudo
                                    echo '<div class="pseudo_idea white">' . formatUnknownUser($idee->getPseudo_developper(), true, true) . '</div>';
                                echo '</div>';
                            }

                            // Contenu
                            echo '<div class="zone_idea_bottom">';
                                echo '<div class="content_idea">' . nl2br($idee->getContent()) . '</div>';
                            echo '</div>';

                            // Actions
                            if ( empty($idee->getDevelopper())
                            OR (!empty($idee->getDevelopper()) AND $idee->getDevelopper() == $_SESSION['user']['identifiant'])
                            OR (!empty($idee->getDevelopper()) AND empty($idee->getPseudo_developper()))
                            OR  $idee->getStatus() == 'D'
                            OR  $idee->getStatus() == 'R')
                            {
                                // Boutons de prise en charge (disponibles si personne n'a pris en charge OU si le développeur est sur la page OU si l'idée est terminée / rejetée)
                                echo '<div class="zone_idea_actions">';
                                    echo '<form method="post" action="ideas.php?view=' . $_GET['view'] . '&action=doChangerStatut" class="form_manage_idea">';
                                        echo '<input type="hidden" name="id_idea" value="' . $idee->getId() . '" />';

                                        switch ($idee->getStatus())
                                        {
                                            // Ouverte
                                            case 'O':
                                                echo '<input type="submit" name="take" value="Prendre en charge" title="Prendre en charge" class="saisie_bouton margin_button" />';
                                                break;

                                            // Prise en charge
                                            case 'C':
                                                echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';

                                                if (!empty($idee->getPseudo_developper()))
                                                {
                                                    echo '<input type="submit" name="developp" value="Développer" title="Commencer les développements" class="saisie_bouton margin_button" />';
                                                    echo '<input type="submit" name="reject" value="Rejeter" title="Annuler l\'idée" class="saisie_bouton margin_button" />';
                                                }
                                                break;

                                            // En progrès
                                            case 'P':
                                                echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';

                                                if (!empty($idee->getPseudo_developper()))
                                                {
                                                    echo '<input type="submit" name="take" value="Remise à prise en charge" title="Remettre à prise en charge" class="saisie_bouton margin_button" />';
                                                    echo '<input type="submit" name="end" value="Terminer" title="Finaliser l\'idée" class="saisie_bouton margin_button" />';
                                                }
                                                break;

                                            // Terminée
                                            case 'D':
                                            // Rejetée
                                            case 'R':
                                                echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="saisie_bouton margin_button" />';
                                                break;

                                            default:
                                                break;
                                        }
                                    echo '</form>';
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';
        }
        else
        {
            switch ($_GET['view'])
            {
                case 'mine':
                    echo '<div class="empty">Pas d\'idées en charge...</div>';
                    break;

                case 'done':
                    echo '<div class="empty">Pas d\'idées terminées ou rejetées...</div>';
                    break;

                case 'all':
                case 'inprogress':
                default:
                    echo '<div class="empty">Pas d\'idées proposées...</div>';
                    break;
            }
        }
    echo '</div>';
?>