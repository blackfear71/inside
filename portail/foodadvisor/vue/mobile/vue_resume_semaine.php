<?php
    echo '<div class="zone_propositions_resume">';
        // Titre
        echo '<div id="titre_propositions_resume" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/week_grey.png" alt="week_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">Le résumé de la semaine</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
        echo '</div>';

        // Affichage du résulé de la semaine
        echo '<div id="afficher_propositions_resume" class="zone_propositions_resume_semaine">';
            // Choix
            foreach ($choixSemaine as $choixJour)
            {
                if (!empty($choixJour['choix']))
                {
                    echo '<div class="zone_proposition proposition_normal">';
                        // Jour
                        echo '<div class="zone_resume_jour">' . formatDayForDisplayLight($choixJour['jour']) . '</div>';

                        // Restaurant
                        echo '<div class="nom_resume">' . formatString($choixJour['choix']->getName(), 20) . '</div>';

                        // Réserveur
                        if (!empty($choixJour['choix']->getCaller()))
                        {
                            $avatarFormatted = formatAvatar($choixJour['choix']->getAvatar(), $choixJour['choix']->getPseudo(), 2, 'avatar');

                            echo '<div class="caller_normal">';
                                echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="caller_proposition" />';
                            echo '</div>';
                        }
                        else
                        {
                            // Suppression si disponible
                            if (empty($choixJour['choix']->getCaller()) AND ($choixJour['date'] < date('Ymd') OR ($choixJour['date'] == date('Ymd') AND date('H') >= 13)))
                            {
                                echo '<form id="delete_resume_' . $choixJour['date'] . '" method="post" action="foodadvisor.php?action=doSupprimerResume" class="form_delete_choix">';
                                    echo '<input type="hidden" name="id_resume" value="' . $choixJour['choix']->getId_restaurant() . '" />';
                                    echo '<input type="hidden" name="date_resume" value="' . $choixJour['choix']->getDate() . '" />';
                                    echo '<input type="hidden" name="date" value="' . $_GET['date'] . '" />';
                                    echo '<input type="submit" name="delete_resume" value="" title="Supprimer le choix" class="bouton_delete_choix eventConfirm" />';
                                    echo '<input type="hidden" value="Supprimer ce choix ?" class="eventMessage" />';
                                echo '</form>';
                            }
                        }
                    echo '</div>';
                }
                else
                {
                    if ($choixJour['date'] < date('Ymd') OR ($choixJour['date'] == date('Ymd') AND date('H') >= 13))
                    {
                        echo '<a id="jour_saisie_resume_' . $choixJour['date'] . '" class="zone_proposition proposition_normal afficherSaisieResume">';
                            // Jour
                            echo '<div class="zone_resume_jour">' . formatDayForDisplayLight($choixJour['jour']) . '</div>';

                            // Choix absent
                            echo '<div class="nom_resume">Ajouter un choix</div>';

                            // Symbole ajout
                            echo '<div class="bouton_ajout_resume">';
                                echo '<span class="fond_plus_resume">+</span>';
                            echo '</div>';
                        echo '</a>';
                    }
                    else
                    {
                        echo '<div class="zone_proposition proposition_normal">';
                            // Jour
                            echo '<div class="zone_resume_jour">' . formatDayForDisplayLight($choixJour['jour']) . '</div>';

                            // Choix absent
                            echo '<div class="no_proposal">Pas de proposition pour ce jour...</div>';
                        echo '</div>';
                    }
                }
            }
        echo '</div>';
    echo '</div>';
?>