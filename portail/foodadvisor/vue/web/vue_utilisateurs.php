<?php
    /********************/
    /*** Utilisateurs ***/
    /********************/
    echo '<div class="zone_propositions_left">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" /><div class="texte_titre_section">Utilisateurs</div></div>';

        // Faire bande à part
        if ($actions['solo'] == true)
        {
            echo '<form method="post" action="foodadvisor.php?action=doSolo">';
                echo '<input type="hidden" name="date" value="' . $_GET['date'] . '" />';
                echo '<input type="submit" name="solo" value="Faire bande à part" class="bouton_solo" />';
            echo '</form>';
        }

        // Bande à part et votants restants
        if (!empty($solos) OR !empty($sansPropositions))
        {
            // Bande à part
            if (!empty($solos))
            {
                echo '<div class="zone_proposition_solo_no_votes">';
                    echo '<div class="titre_solo">Bande à part</div>';

                    foreach ($solos as $solo)
                    {
                        echo '<div class="zone_solo">';
                            // Avatar
                            $avatarFormatted = formatAvatar($solo->getAvatar(), $solo->getPseudo(), 2, 'avatar');

                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_solo" />';

                            // Pseudo
                            echo '<div class="pseudo_solo">' . formatString($solo->getPseudo(), 15) . '</div>';
                        echo '</div>';
                    }
                echo '</div>';
            }

            // Utilisateurs sans votes
            if (!empty($sansPropositions))
            {
                echo '<div class="zone_proposition_solo_no_votes">';
                    if (($_GET['date'] >  date('Ymd') AND date('N', strtotime($_GET['date'])) <= 5)
                    OR  ($_GET['date'] == date('Ymd') AND date('N') <= 5 AND date('H') < 13))
                        echo '<div class="titre_solo">Ils n\'ont pas encore fait de choix</div>';
                    else
                        echo '<div class="titre_solo">Ils n\'ont pas fait de choix aujourd\'hui</div>';

                    foreach ($sansPropositions as $userNoChoice)
                    {
                        echo '<div class="zone_no_vote">';
                            // Avatar
                            $avatarFormatted = formatAvatar($userNoChoice->getAvatar(), $userNoChoice->getPseudo(), 2, 'avatar');

                            echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_no_vote" />';

                            // Pseudo
                            echo '<div class="pseudo_no_vote">' . formatString($userNoChoice->getPseudo(), 15) . '</div>';
                        echo '</div>';
                    }
                echo '</div>';
            }
        }
        else
            echo '<div class="empty">Rien à signaler...</div>';
    echo '</div>';
?>