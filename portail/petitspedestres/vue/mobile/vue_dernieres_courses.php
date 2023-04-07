<?php
    /*************************/
    /*** Dernières courses ***/
    /*************************/
    echo '<div class="zone_dernieres_courses">';
        // Titre
        echo '<div id="titre_dernieres_courses" class="titre_section">';
            echo '<img src="../../includes/icons/petitspedestres/path_grey.png" alt="path_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Mes dernières courses</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
        echo '</div>';

        // Dernières courses
        echo '<div id="afficher_dernieres_courses">';
            if (!empty($dernieresCourses))
            {
                foreach ($dernieresCourses as $course)
                {
                    echo '<a href="details.php?id_parcours=' . $course->getId_parcours() . '&action=goConsulter&anchor=' . $course->getDate() . '" class="zone_derniere_course">';
                        // Nom
                        echo '<div class="nom_derniere_course">' . formatString($course->getNom_parcours(), 30) . '</div>';

                        // Date
                        echo '<div class="date_derniere_course">' . formatDateForDisplay($course->getDate()) . '</div>';

                        // Compétition
                        if ($course->getCompetition() == 'Y')
                            echo '<img src="../../includes/icons/petitspedestres/cup.png" alt="cup" title="Compétition" class="icone_derniere_course" />';
                        else
                            echo '<img src="../../includes/icons/petitspedestres/cup_white.png" alt="cup_white" title="Classique" class="icone_derniere_course" />';
                    echo '</a>';
                }
            }
            else
                echo '<div class="empty">Aucune course réalisée...</div>';
        echo '</div>';
    echo '</div>';
?>