<?php
    /**************************/
    /*** Historique semaine ***/
    /**************************/
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/foodadvisor/week_grey.png" alt="week_grey" class="logo_titre_section" /><div class="texte_titre_section">Le résumé de la semaine</div></div>';

    // Résumé de la semaine
    echo '<div class="zone_propositions">';
        foreach ($choixSemaine as $choixJour)
        {
            if (!empty($choixJour['choix']))
            {
                echo '<div class="zone_proposition_resume">';
                    // Suppression si disponible
                    if (empty($choixJour['choix']->getCaller()) AND ($choixJour['date'] < date('Ymd') OR ($choixJour['date'] == date('Ymd') AND date('H') >= 13)))
                    {
                        echo '<form id="delete_resume_' . $choixJour['date'] . '" method="post" action="foodadvisor.php?action=doSupprimerResume">';
                            echo '<input type="hidden" name="id_resume" value="' . $choixJour['choix']->getId_restaurant() . '" />';
                            echo '<input type="hidden" name="date_resume" value="' . $choixJour['choix']->getDate() . '" />';
                            echo '<input type="hidden" name="date" value="' . $_GET['date'] . '" />';
                            echo '<input type="submit" name="delete_resume" value="" title="Supprimer le choix" class="icon_delete_resume eventConfirm" />';
                            echo '<input type="hidden" value="Supprimer ce choix ?" class="eventMessage" />';
                        echo '</form>';
                    }

                    // Jour
                    if (substr($choixJour['date'], 0, 4) == date('Y'))
                        echo '<div class="jour_semaine_resume">' . $choixJour['jour'] . '<br />' . formatDateForDisplayLight($choixJour['date']) . '</div>';
                    else
                        echo '<div class="jour_semaine_resume">' . $choixJour['jour'] . '<br />' . formatDateForDisplay($choixJour['date']) . '</div>';

                    // Image + lien
                    echo '<a href="restaurants.php?action=goConsulter&anchor=' . $choixJour['choix']->getId_restaurant() . '" class="lien_mon_choix">';
                        if (!empty($choixJour['choix']->getPicture()))
                            echo '<img src="../../includes/images/foodadvisor/' . $choixJour['choix']->getPicture() . '" alt="' . $choixJour['choix']->getPicture() . '" title="' . $choixJour['choix']->getName() . '" class="image_mon_choix image_rounded" />';
                        else
                            echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $choixJour['choix']->getName() . '" class="image_mon_choix" />';
                    echo '</a>';

                    // Nom du restaurant
                    echo '<div class="nom_mon_choix">' . $choixJour['choix']->getName() . '</div>';

                    // Lieu et participants
                    echo '<div class="zone_icones_mon_choix">';
                        // Lieu
                        echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $choixJour['choix']->getLocation() . '</span>';

                        // Nombre de participants
                        if ($choixJour['choix']->getNb_participants() >= 1)
                        {
                            if ($choixJour['choix']->getNb_participants() == 1)
                                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $choixJour['choix']->getNb_participants() . ' participant</span>';
                            else
                                echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $choixJour['choix']->getNb_participants() . ' participants</span>';
                        }
                    echo '</div>';

                    // Appelant si renseigné
                    if (!empty($choixJour['choix']->getCaller()))
                    {
                        echo '<div class="caller_resume">';
                            echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

                            // Avatar
                            $avatarFormatted = formatAvatar($choixJour['choix']->getAvatar(), $choixJour['choix']->getPseudo(), 2, 'avatar');

                            echo '<div class="zone_avatar_caller">';
                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_caller" />';
                            echo '</div>';
                        echo '</div>';
                    }
                echo '</div>';
            }
            else
            {
                echo '<div class="zone_proposition_resume">';
                    // Jour
                    if (substr($choixJour['date'], 0, 4) == date('Y'))
                        echo '<div class="jour_semaine_resume">' . $choixJour['jour'] . '<br />' . formatDateForDisplayLight($choixJour['date']) . '</div>';
                    else
                        echo '<div class="jour_semaine_resume">' . $choixJour['jour'] . '<br />' . formatDateForDisplay($choixJour['date']) . '</div>';

                    // Pas de proposition
                    echo '<div id="no_proposal_' . $choixJour['date'] . '" class="no_proposal">Pas de proposition pour ce jour...</div>';

                    // Bouton ajout choix (si pas de choix fait dans la matinée)
                    if ($choixJour['date'] < date('Ymd') OR ($choixJour['date'] == date('Ymd') AND date('H') >= 13))
                    {
                        echo '<a id="choix_resume_' . $choixJour['date'] . '" class="bouton_resume afficherResume">';
                            echo '<span class="fond_plus">+</span>';
                            echo 'Ajouter un choix';
                        echo '</a>';
                    }
                echo '</div>';
            }
        }
    echo '</div>';
?>