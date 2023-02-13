<?php
    if (isset($mesChoix) AND !empty($mesChoix) AND $isSolo != true)
    {
        echo '<div class="zone_propositions_choix">';
            // Titre
            echo '<div id="titre_propositions_mes_choix" class="titre_section">';
                echo '<img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section_fleche">Mes choix</div>';
                echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
            echo '</div>';

            // Affichage des choix utilisateur
            echo '<div id="afficher_propositions_mes_choix" class="zone_propositions_mes_choix">';
                // Supprimer tous les choix
                if ($actions['supprimer_choix'] == true)
                {
                    echo '<form method="post" id="delete_choices" action="foodadvisor.php?action=doSupprimerChoix">';
                        echo '<input type="submit" name="delete_choices" value="Supprimer tous mes choix" class="lien_red_normal eventConfirm" />';
                        echo '<input type="hidden" value="Supprimer tous les choix saisis ?" class="eventMessage" />';
                    echo '</form>';
                }

                // Choix
                foreach ($mesChoix as $monChoix)
                {
                    echo '<div class="zone_proposition proposition_normal">';
                        // Image
                        echo '<div class="image_normal">';
                            if (!empty($monChoix->getPicture()))
                                echo '<img src="../../includes/images/foodadvisor/' . $monChoix->getPicture() . '" alt="' . $monChoix->getPicture() . '" title="' . $monChoix->getName() . '" class="image_proposition image_rounded" />';
                            else
                                echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $monChoix->getName() . '" class="image_proposition" />';
                        echo '</div>';

                        // Nom restaurant
                        echo '<div class="nom_proposition">' . formatString($monChoix->getName(), 20) . '</div>';

                        // Suppression choix
                        if ($actions['choix'] == true)
                        {
                            echo '<form id="delete_choice_' . $monChoix->getId() . '" method="post" action="foodadvisor.php?action=doSupprimer" class="form_delete_choix">';
                                echo '<input type="hidden" name="id_choix" value="' . $monChoix->getId() . '" />';
                                echo '<input type="submit" name="delete_choice" value="" title="Supprimer le choix" class="bouton_delete_choix eventConfirm" />';
                                echo '<input type="hidden" value="Supprimer ce choix ?" class="eventMessage" />';
                            echo '</form>';
                        }
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';
    }
?>