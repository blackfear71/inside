<?php
    // Titre
    echo '<div id="titre_propositions_users" class="titre_section">';
        echo '<img src="../../includes/icons/foodadvisor/propositions_grey.png" alt="propositions_grey" class="logo_titre_section" />';

        echo '<div class="texte_titre_section_fleche">';
            if ($_GET['date'] == date('Ymd'))
                echo 'Les propositions du jour';
            else
            {
                if (substr($_GET['date'], 0, 4) == date('Y'))
                    echo 'Les propositions du ' . formatDateForDisplayLight($_GET['date']);
                else
                    echo 'Les propositions du ' . formatDateForDisplay($_GET['date']);
            }
        echo '</div>';

        echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Affichage des propositions
    if (isset($mesChoix) AND !empty($mesChoix) AND $isSolo != true)
        echo '<div id="afficher_propositions_users" class="zone_propositions_users_with_choices">';
    else
        echo '<div id="afficher_propositions_users" class="zone_propositions_users">';
        if (!empty($propositions))
        {
            foreach ($propositions as $proposition)
            {
                // Détermination classe à appliquer
                if ($proposition->getDetermined() == 'Y' AND $proposition == $propositions[0])
                    $classProposition = 'determined';
                elseif ($proposition->getDetermined() == 'Y' AND $proposition != $propositions[0])
                    $classProposition = 'determined';
                elseif ($proposition->getClassement() == 1 AND $proposition == $propositions[0])
                    $classProposition = 'top';
                elseif ($proposition->getClassement() == 1 AND $proposition != $propositions[0])
                    $classProposition = 'top';
                elseif ($proposition == $propositions[0])
                    $classProposition = 'normal';
                else
                    $classProposition = 'normal';

                // Proposition
                echo '<a id="details_proposition_' . $proposition->getId_restaurant() . '" class="zone_proposition proposition_' . $classProposition . ' afficherDetailsProposition">';
                    echo '<div class="image_' . $classProposition . '">';
                        // Indicateur réservation
                        if ($proposition->getReserved() == 'Y')
                            echo '<div class="reserved_proposition">R</div>';

                        // Image
                        if (!empty($proposition->getPicture()))
                            echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="' . $proposition->getPicture() . '" title="' . $proposition->getName() . '" class="image_proposition image_rounded" />';
                        else
                            echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $proposition->getName() . '" class="image_proposition" />';

                        // Nombre de participants
                        echo '<div class="nombre_participants_proposition">' . $proposition->getNb_participants() . '</div>';
                    echo '</div>';

                    // Nom restaurant
                    echo '<div class="nom_proposition nom_' . $classProposition . '">' . formatString($proposition->getName(), 20) . '</div>';

                    // Réserveur
                    if ($proposition->getDetermined() == 'Y' AND !empty($proposition->getCaller()))
                    {
                        $avatarFormatted = formatAvatar($proposition->getAvatar(), $proposition->getPseudo(), 2, 'avatar');

                        echo '<div class="caller_' . $classProposition . '">';
                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="caller_proposition" />';
                        echo '</div>';
                    }
                echo '</a>';
            }
        }
        else
        {
            if (date('N', strtotime($_GET['date'])) > 5)
            {
                if ($_GET['date'] == date('Ymd'))
                    echo '<div class="empty">Il est impossible de voter pour aujourd\'hui...</div>';
                else
                    echo '<div class="empty">Il est impossible de voter pour ce jour...</div>';
            }
            else
            {
                if ($_GET['date'] == date('Ymd'))
                {
                    if (date('H') >= 13)
                        echo '<div class="empty">Il n\'est plus possible de voter pour aujourd\'hui...</div>';
                    else
                        echo '<div class="empty">Il n\'y a pas encore de propositions pour aujourd\'hui...</div>';
                }
                elseif ($_GET['date'] < date('Ymd'))
                    echo '<div class="empty">Il n\'y a pas de propositions pour ce jour...</div>';
                else
                    echo '<div class="empty">Il n\'y a pas encore de propositions pour ce jour...</div>';
            }
        }
    echo '</div>';
?>