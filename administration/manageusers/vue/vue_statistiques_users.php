<?php
	// Titre
	echo '<div class="titre_section"><img src="../../includes/icons/admin/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Statistiques des utilisateurs</div></div>';

	// Statistiques globales
    echo '<div class="zone_statistiques_categories">';
        // Movie House
        echo '<a id="statistiques_films" class="zone_statistiques_categorie afficherDetailsStatistiques">';
            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_statistiques_categorie" />MOVIE HOUSE</div>';

            // Films ajoutés
            echo '<div class="zone_statistique">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_films_ajoutes_total() . '</div>';
                echo '<div class="texte_statistique">films ajoutés</div>';
            echo '</div>';

            // Commentaires
            echo '<div class="zone_statistique border_left">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_films_comments_total() . '</div>';
                echo '<div class="texte_statistique">commentaires</div>';
            echo '</div>';
        echo '</a>';

        // Food Advisor
        echo '<a id="statistiques_restaurants" class="zone_statistiques_categorie afficherDetailsStatistiques">';
            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/food_advisor_grey.png" alt="food_advisor_grey" class="logo_titre_statistiques_categorie" />LES ENFANTS ! À TABLE !</div>';

            // Réservations
            echo '<div class="zone_statistique zone_statistique_large">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_reservations_total() . '</div>';
                echo '<div class="texte_statistique">réservations</div>';
            echo '</div>';
        echo '</a>';

        // Cooking Box
        echo '<a id="statistiques_gateaux" class="zone_statistiques_categorie afficherDetailsStatistiques">';
            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/cooking_box_grey.png" alt="cooking_box_grey" class="logo_titre_statistiques_categorie" />COOKING BOX</div>';

            // Gâteaux faits
            echo '<div class="zone_statistique">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_gateaux_semaine_total() . '</div>';
                echo '<div class="texte_statistique">gâteaux de la semaine</div>';
            echo '</div>';

            // Recettes saisies
            echo '<div class="zone_statistique border_left">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_recettes_total() . '</div>';
                echo '<div class="texte_statistique">recettes partagées</div>';
            echo '</div>';
        echo '</a>';

        // Expense Center
        if ($totalStatistiques->getExpenses_no_parts() < -0.01 OR $totalStatistiques->getExpenses_no_parts() > 0.01
        OR  $totalStatistiques->getExpenses_total()    < -0.01 OR $totalStatistiques->getExpenses_total()    > 0.01)
            echo '<a id="statistiques_depenses" class="zone_statistiques_categorie zone_statistiques_categorie_alerte afficherDetailsStatistiques">';
        else
            echo '<a id="statistiques_depenses" class="zone_statistiques_categorie afficherDetailsStatistiques">';

            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_statistiques_categorie" />EXPENSE CENTER</div>';

            // Régularisations
            echo '<div class="zone_statistique">';
                if ($totalStatistiques->getExpenses_no_parts() > -0.01 AND $totalStatistiques->getExpenses_no_parts() < 0.01)
                    echo '<div class="valeur_statistique valeur_statistique_petit">' . formatAmountForDisplay('') . '</div>';
                else
                    echo '<div class="valeur_statistique valeur_statistique_petit">' . formatAmountForDisplay($totalStatistiques->getExpenses_no_parts()) . '</div>';

                echo '<div class="texte_statistique">régularisations</div>';
            echo '</div>';

            // Solde
            echo '<div class="zone_statistique border_left">';
                if ($totalStatistiques->getExpenses_total() > -0.01 AND $totalStatistiques->getExpenses_total() < 0.01)
                    echo '<div class="valeur_statistique valeur_statistique_petit">' . formatAmountForDisplay('') . '</div>';
                else
                    echo '<div class="valeur_statistique valeur_statistique_petit">' . formatAmountForDisplay($totalStatistiques->getExpenses_total()) . '</div>';

                echo '<div class="texte_statistique">bilan</div>';
            echo '</div>';
        echo '</a>';

        // Collector Room
        echo '<a id="statistiques_cultes" class="zone_statistiques_categorie afficherDetailsStatistiques">';
            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/collector_grey.png" alt="collector_grey" class="logo_titre_statistiques_categorie" />COLLECTOR ROOM</div>';

            // Phrases cultes rapportées
            echo '<div class="zone_statistique zone_statistique_large">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_collectors_total() . '</div>';
                echo '<div class="texte_statistique">phrases cultes rapportées</div>';
            echo '</div>';
        echo '</a>';

        // Bugs / évolutions
        echo '<a id="statistiques_bugs" class="zone_statistiques_categorie afficherDetailsStatistiques">';
            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/alerts_grey.png" alt="alerts_grey" class="logo_titre_statistiques_categorie" />BUGS & ÉVOLUTIONS</div>';

            // Nombre de demandes
            echo '<div class="zone_statistique">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_bugs_soumis_total() . '</div>';
                echo '<div class="texte_statistique">demandes</div>';
            echo '</div>';

            // Nombre de demandes résolues
            echo '<div class="zone_statistique border_left">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_bugs_resolus_total() . '</div>';
                echo '<div class="texte_statistique">demandes résolues</div>';
            echo '</div>';
        echo '</a>';

        // #THEBOX
        echo '<a id="statistiques_idees" class="zone_statistiques_categorie afficherDetailsStatistiques">';
            echo '<div class="titre_statistiques_categorie"><img src="../../includes/icons/admin/ideas_grey.png" alt="ideas_grey" class="logo_titre_statistiques_categorie" />#THEBOX</div>';

            // Nombre d'idées publiées
            echo '<div class="zone_statistique zone_statistique_petit">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_idees_soumises_total() . '</div>';
                echo '<div class="texte_statistique">idées</div>';
            echo '</div>';

            // Nombre d'idées en charge
            echo '<div class="zone_statistique zone_statistique_petit border_left">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_idees_en_charge_total() . '</div>';
                echo '<div class="texte_statistique">en charge</div>';
            echo '</div>';

            // Nombre d'idées terminées ou rejetées
            echo '<div class="zone_statistique zone_statistique_petit border_left">';
                echo '<div class="valeur_statistique">' . $totalStatistiques->getNb_idees_terminees_total() . '</div>';
                echo '<div class="texte_statistique">terminées</div>';
            echo '</div>';
        echo '</a>';
    echo '</div>';
?>