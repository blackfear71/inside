<?php
    include_once('../../includes/classes/bugs.php');
    include_once('../../includes/classes/calendars.php');
    include_once('../../includes/classes/collectors.php');
    include_once('../../includes/classes/expenses.php');
    include_once('../../includes/classes/gateaux.php');
    include_once('../../includes/classes/movies.php');
    include_once('../../includes/classes/profile.php');
    include_once('../../includes/classes/restaurants.php');
    include_once('../../includes/classes/teams.php');

    // METIER : Lecture de la liste des équipes
    // RETOUR : Liste des équipes
    function getListeEquipes()
    {
        // Lecture de la liste des équipes
        $listeEquipes = physiqueListeEquipes();

        // Retour
        return $listeEquipes;
    }

    // METIER : Lecture liste des utilisateurs
    // RETOUR : Tableau d'utilisateurs
    function getUsers()
    {
        // Récupération liste des utilisateurs par équipe
        $listeUsersParEquipe = physiqueUsersParEquipe();

        // Retour
        return $listeUsersParEquipe;
    }

    // METIER : Modification succès "beginning" ou "developper"
    // RETOUR : Aucun
    function updateSuccesAdmin($post, $succes)
    {
        // Récupération des données saisies
        $identifiant  = $post['user_infos'];
        $valeurSucces = $post['top_infos'];

        if ($valeurSucces == 1)
            $nouvelleValeur = 0;
        else
            $nouvelleValeur = 1;

        // Mise à jour succès
        insertOrUpdateSuccesValue($succes, $identifiant, $nouvelleValeur);
    }

    // METIER : Mise à jour d'une équipe
    // RETOUR : Aucun
    function updateEquipe($post)
    {
        // Récupération des données
        $reference = $post['reference'];
        $team      = $post['team'];

        // Mise à jour du nom de l'équipe
        physiqueUpdateEquipe($reference, $team);
    }

    // METIER : Suppression d'une équipe
    // RETOUR : Aucun
    function deleteEquipe($post)
    {
        // Récupération des données
        $equipe = $post['team'];

        // Lecture des bugs / évolutions de l'équipe
        $listeBugsEvolutions = physiqueLectureTableEquipe('bugs', 'id, team, picture', 'BugEvolution', $equipe);

        // Suppression des images des bugs / évolutions
        if (!empty($listeBugsEvolutions))
        {
            foreach ($listeBugsEvolutions as $bugEvolution)
            {
                if (!empty($bugEvolution->getPicture()))
                    unlink('../../includes/images/reports/' . $bugEvolution->getPicture());
            }
        }

        // Lecture des calendriers de l'équipe
        $listeCalendriers = physiqueLectureTableEquipe('calendars', 'id, team, year, calendar', 'Calendrier', $equipe);

        // Suppression des images des calendriers
        if (!empty($listeCalendriers))
        {
            foreach ($listeCalendriers as $calendrier)
            {
                if (!empty($calendrier->getCalendar()))
                {
                    unlink('../../includes/images/calendars/' . $calendrier->getYear() . '/' . $calendrier->getCalendar());
                    unlink('../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar());
                }
            }
        }

        // Lecture des annexes de l'équipe
        $listeAnnexes = physiqueLectureTableEquipe('calendars_annexes', 'id, team, annexe', 'Annexe', $equipe);

        // Suppression des images des annexes
        if (!empty($listeAnnexes))
        {
            foreach ($listeAnnexes as $annexe)
            {
                if (!empty($annexe->getAnnexe()))
                    unlink('../../includes/images/calendars/annexes/' . $annexe->getAnnexe());
            }
        }

        // Lecture des phrases / images cultes de l'équipe
        $listeCollectors = physiqueLectureTableEquipe('collector', 'id, type_collector, team, collector', 'Collector', $equipe);

        // Suppression des images des phrases cultes
        if (!empty($listeCollectors))
        {
            foreach ($listeCollectors as $collector)
            {
                if ($collector->getType_collector() == 'I' AND !empty($collector->getCollector()))
                    unlink('../../includes/images/collector/' . $collector->getCollector());
            }
        }

        // Lecture des recettes de l'équipe
        $listeRecettes = physiqueLectureTableEquipe('cooking_box', 'id, team, year, picture', 'WeekCake', $equipe);

        // Suppression des images des recettes
        if (!empty($listeRecettes))
        {
            foreach ($listeRecettes as $recette)
            {
                if (!empty($recette->getPicture()))
                    unlink('../../includes/images/cookingbox/' . $recette->getYear() . '/' . $recette->getPicture());
            }
        }

        // Lecture des restaurants de l'équipe
        $listeRestaurants = physiqueLectureTableEquipe('food_advisor_restaurants', 'id, team, picture', 'Restaurant', $equipe);

        // Suppression des images des restaurants
        if (!empty($listeRestaurants))
        {
            foreach ($listeRestaurants as $restaurant)
            {
                if (!empty($restaurant->getPicture()))
                    unlink('../../includes/images/foodadvisor/' . $restaurant->getPicture());
            }
        }

        // Lecture des films de l'équipe
        $listeFilms = physiqueLectureTableEquipe('movie_house', 'id, team', 'Movie', $equipe);

        // Suppression des commentaires et étoiles des films
        if (!empty($listeFilms))
        {
            foreach ($listeFilms as $film)
            {
                // Suppression des commentaires
                physiqueDeleteElementTable('movie_house_comments', 'id_film', $film->getId());

                // Suppression des votes
                physiqueDeleteElementTable('movie_house_users', 'id_film', $film->getId());
            }
        }

        // Lecture des parcours de l'équipe
        $listeParcours = physiqueLectureTableEquipe('petits_pedestres_parcours', 'id, team, picture, document, type', 'Parcours', $equipe);

        // Suppression des images et documents des parcours
        if (!empty($listeParcours))
        {
            foreach ($listeParcours as $parcours)
            {
                // Suppression des images
                if (!empty($parcours->getPicture()))
                    unlink('../../includes/images/petitspedestres/pictures/' . $parcours->getPicture());

                // Suppression des documents
                if (!empty($parcours->getDocument()))
                {
                    switch ($parcours->getType())
                    {
                        case 'document':
                            unlink('../../includes/datas/petitspedestres/' . $parcours->getDocument());
                            break;

                        case 'picture':
                            unlink('../../includes/images/petitspedestres/documents/' . $parcours->getDocument());
                            break;
                            
                        default:
                            break;
                    }
                }
            }
        }

        // Liste des tables à purger
        $listeTables = array(
            'bugs',
            'calendars',
            'calendars_annexes',
            'collector',
            'collector_users',
            'cooking_box',
            'expense_center',
            'expense_center_users',
            'food_advisor_choices',
            'food_advisor_restaurants',
            'food_advisor_users',
            'ideas',
            'missions_users',
            'movie_house',
            'notifications',
            'petits_pedestres_parcours',
            'petits_pedestres_users'
        );

        // Purge des tables
        foreach ($listeTables as $table)
        {
            physiqueDeleteTableEquipe($table, $equipe);
        }

        // Suppression du fichier de chat
        if (file_exists('../../includes/datas/conversations/content_chat_' . $equipe . '.xml'))
            unlink('../../includes/datas/conversations/content_chat_' . $equipe . '.xml');

        // Suppression de l'équipe
        physiqueDeleteEquipe($equipe);

        // Message d'alerte
        $_SESSION['alerts']['team_deleted'] = true;
    }
?>