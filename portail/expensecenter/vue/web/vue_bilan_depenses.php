<?php
    /**********************/
    /*** Bilan & Années ***/
    /**********************/
    echo '<div class="zone_expenses_left">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/expensecenter/total_grey.png" alt="total_grey" class="logo_titre_section" /><div class="texte_titre_section">Bilan</div></div>';

        // Bilan
        echo '<div class="zone_bilan_expenses">';
            foreach ($listeUsers as $user)
            {
                if ($user->getTeam() == $_SESSION['user']['equipe'])
                {
                    if ($user->getExpenses() <= -6)
                        echo '<div class="zone_bilan_user bilan_rouge">';
                    elseif ($user->getExpenses() <= -3 AND $user->getExpenses() > -6)
                        echo '<div class="zone_bilan_user bilan_orange">';
                    elseif ($user->getExpenses() < -0.01 AND $user->getExpenses() > -3)
                        echo '<div class="zone_bilan_user bilan_jaune">';
                    elseif ($user->getExpenses() > 0.01 AND $user->getExpenses() < 5)
                        echo '<div class="zone_bilan_user bilan_vert">';
                    elseif ($user->getExpenses() > 0.01 AND $user->getExpenses() >= 5)
                        echo '<div class="zone_bilan_user bilan_vert_fonce">';
                    else
                        echo '<div class="zone_bilan_user">';
                        // Avatar
                        $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar" />';

                        // Pseudo
                        echo '<div class="pseudo_bilan">' . $user->getPseudo() . '</div>';

                        // Total
                        if ($user->getExpenses() > -0.01 AND $user->getExpenses() < 0.01)
                            echo '<div class="total_bilan">' . formatAmountForDisplay('') . '</div>';
                        else
                            echo '<div class="total_bilan">' . formatAmountForDisplay($user->getExpenses()) . '</div>';
                    echo '</div>';
                }
            }
        echo '</div>';

        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/expensecenter/year_grey.png" alt="year_grey" class="logo_titre_section" /><div class="texte_titre_section">Années</div></div>';

        // Années
        echo '<div class="zone_annees_expenses">';
            if (!empty($onglets))
            {
                $i            = 0;
                $previousYear = $onglets[0];
                $lastYear     = true;

                foreach ($onglets as $annee)
                {
                    // Année inexistante (première ou au milieu)
                    if ($lastYear != false AND $anneeExistante == false AND (($_GET['year'] < $previousYear AND $_GET['year'] > $annee) OR $_GET['year'] > $onglets[0]))
                    {
                        if ($i % 2 == 0)
                            echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
                        else
                            echo '<span class="year active">' . $_GET['year'] . '</span>';

                        $lastYear = false;
                        $i++;
                    }

                    // Année existante
                    if ($i % 2 == 0)
                    {
                        if (isset($_GET['year']) AND $annee == $_GET['year'])
                            echo '<span class="year active margin_right_20">' . $annee . '</span>';
                        else
                            echo '<a href="expensecenter.php?year=' . $annee . '&filter=' . $_GET['filter'] . '&action=goConsulter" class="year inactive margin_right_20">' . $annee . '</a>';
                    }
                    else
                    {
                        if (isset($_GET['year']) AND $annee == $_GET['year'])
                            echo '<span class="year active">' . $annee . '</span>';
                        else
                            echo '<a href="expensecenter.php?year=' . $annee . '&filter=' . $_GET['filter'] . '&action=goConsulter" class="year inactive">' . $annee . '</a>';
                    }

                    $previousYear = $annee;
                    $i++;
                }

                // Année inexistante (dernière)
                if ($lastYear == true AND $anneeExistante == false)
                    echo '<span class="year active">' . $_GET['year'] . '</span>';
            }
            else
                echo '<span class="year active margin_right_20">' . $_GET['year'] . '</span>';
        echo '</div>';
    echo '</div>';
?>