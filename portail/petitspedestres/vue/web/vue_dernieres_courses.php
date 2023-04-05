<?php
    /*************************/
    /*** Dernières courses ***/
    /*************************/
    echo '<div class="zone_dernieres_courses">';
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" class="logo_titre_section" /><div class="texte_titre_section">Mes dernières courses</div></div>';

        // Dernières courses
        if (!empty($dernieresCourses))
        {
            foreach ($dernieresCourses as $course)
            {
                echo '<a href="details.php?id_parcours=' . $course->getId_parcours() . '&action=goConsulter&anchor=' . $course->getDate() . '" class="zone_derniere_course">';
                    // Nom
                    echo '<div class="nom_derniere_course">' . $course->getNom_parcours() . '</div>';

                    // Date
                    echo '<div class="date_derniere_course">' . formatDateForDisplay($course->getDate()) . '</div>';

                    // Distance
                    if (!empty($course->getDistance()))
                        echo '<div class="distance_derniere_course">' . formatDistanceForDisplay($course->getDistance()) . '</div>';
                    else
                        echo '<div class="distance_derniere_course">N/A</div>';

                    // Temps
                    if (!empty($course->getTime()))
                        echo '<div class="temps_derniere_course">' . formatSecondsForDisplay($course->getTime()) . '</div>';
                    else
                        echo '<div class="temps_derniere_course">N/A</div>';

                    // Compétition
                    if ($course->getCompetition() == 'Y')
                        echo '<img src="../../includes/icons/petitspedestres/cup_grey.png" alt="cup_grey" title="Compétition" class="icone_derniere_course" />';
                    else
                        echo '<img src="../../includes/icons/petitspedestres/cup_white.png" alt="cup_white" title="Classique" class="icone_derniere_course" />';
                echo '</a>';
            }
        }
        else
            echo '<div class="empty">Aucune course réalisée...</div>';
    echo '</div>';
?>