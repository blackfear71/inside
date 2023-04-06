<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/profil/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Mes contributions</div></div>';

    // Contributions
    echo '<div class="zone_profil_contributions">';
        // Movie House
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_contribution" />MOVIE HOUSE</div>';

            // Films ajoutés
            echo '<div class="zone_contribution">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_films_ajoutes() . '</div>';
                echo '<div class="texte_contribution">films ajoutés</div>';
            echo '</div>';

            // Commentaires
            echo '<div class="zone_contribution border_left">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_comments() . '</div>';
                echo '<div class="texte_contribution">commentaires</div>';
            echo '</div>';
        echo '</div>';

        // Food Advisor
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/food_advisor_grey.png" alt="food_advisor_grey" class="logo_titre_contribution" />LES ENFANTS ! À TABLE !</div>';

            // Réservations
            echo '<div class="zone_contribution">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_reservations() . '</div>';
                echo '<div class="texte_contribution">réservations</div>';
            echo '</div>';
        echo '</div>';

        // Cooking Box
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/cooking_box_grey.png" alt="cooking_box_grey" class="logo_titre_contribution" />COOKING BOX</div>';

            // Gâteaux faits
            echo '<div class="zone_contribution">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_gateaux() . '</div>';
                echo '<div class="texte_contribution">gâteaux faits</div>';
            echo '</div>';

            // Recettes saisies
            echo '<div class="zone_contribution border_left">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_recettes() . '</div>';
                echo '<div class="texte_contribution">recettes saisies</div>';
            echo '</div>';
        echo '</div>';

        // Expense Center
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_contribution" />EXPENSE CENTER</div>';

            // Solde
            echo '<div class="zone_contribution large">';
                if ($statistiques->getExpenses() > -0.01 AND $statistiques->getExpenses() < 0.01)
                    echo '<div class="stat_contribution">' . formatAmountForDisplay('') . '</div>';
                else
                    echo '<div class="stat_contribution">' . formatAmountForDisplay($statistiques->getExpenses()) . '</div>';
                echo '<div class="texte_contribution">solde des dépenses</div>';
            echo '</div>';
        echo '</div>';

        // Collector Room
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/collector_grey.png" alt="collector_grey" class="logo_titre_contribution" />COLLECTOR ROOM</div>';

            // Phrases cultes rapportées
            echo '<div class="zone_contribution large">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_collectors() . '</div>';
                echo '<div class="texte_contribution">phrases cultes rapportées</div>';
            echo '</div>';
        echo '</div>';

        // Les Petits Pédestres
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/parcours_grey.png" alt="parcours_grey" class="logo_titre_contribution" />LES PETITS PÉDESTRES</div>';

            // Parcours ajoutés
            echo '<div class="zone_contribution">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_parcours() . '</div>';
                echo '<div class="texte_contribution">parcours ajoutés</div>';
            echo '</div>';

            // Participations
            echo '<div class="zone_contribution border_left">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_participations() . '</div>';
                echo '<div class="texte_contribution">participations</div>';
            echo '</div>';
        echo '</div>';
        
        // #TheBox
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/ideas_grey.png" alt="ideas_grey" class="logo_titre_contribution" />#THEBOX</div>';

            // Idées soumises
            echo '<div class="zone_contribution">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_ideas() . '</div>';
                echo '<div class="texte_contribution">idées soumises</div>';
            echo '</div>';
        echo '</div>';

        // Bugs & Evolutions
        echo '<div class="zone_contributions">';
            echo '<div class="titre_contribution"><img src="../../includes/icons/profil/bugs_grey.png" alt="bugs_grey" class="logo_titre_contribution" />BUGS & ÉVOLUTIONS</div>';

            // Bugs
            echo '<div class="zone_contribution">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_bugs() . '</div>';
                echo '<div class="texte_contribution">bugs rapportés</div>';
            echo '</div>';

            // Evolutions
            echo '<div class="zone_contribution border_left">';
                echo '<div class="stat_contribution">' . $statistiques->getNb_evolutions() . '</div>';
                echo '<div class="texte_contribution">évolutions proposées</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
?>