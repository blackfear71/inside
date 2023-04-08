<?php
    /*******************************/
    /*** Zone de saisie de choix ***/
    /*******************************/
    echo '<div id="zone_saisie_propositions" class="fond_saisie_restaurant">';
        echo '<div class="zone_saisie_propositions">';
            // Titre
            echo '<div class="titre_saisie_restaurant">Proposer où manger</div>';

            // Bouton fermeture
            echo '<a id="fermerPropositions" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

            // Saisie
            echo '<form method="post" action="foodadvisor.php?action=doAjouterChoix" class="form_saisie_propositions">';
                // Recherche
                echo '<div class="zone_recherche_live">';
                    // Logo
                    echo '<img src="../../includes/icons/common/search.png" alt="search" title="Rechercher" class="logo_recherche_live" />';

                    // Zone de saisie
                    echo '<input type="text" autocomplete="off" id="recherche_live_propositions" placeholder="Rechercher" class="input_recherche_live" />';

                    // Effacer
                    echo '<img src="../../includes/icons/common/cancel.png" alt="cancel" title="Effacer" id="reset_recherche_live_propositions" class="logo_recherche_live size_logo_recherche_live" />';
                echo '</div>';

                // Date de la saisie
                echo '<input type="hidden" name="date" value="' . $_GET['date'] . '" />';
                
                // Liste des restaurants
                echo '<div class="zone_contenu_saisie_live">';
                    echo '<div class="contenu_saisie">';
                        // Message vide
                        echo '<div class="empty_recherche_live">Aucun résultat n\'a été trouvé...</div>';

                        // Restaurants par lieu
                        if (!empty($listeRestaurants))
                        {
                            foreach ($listeRestaurants as $lieuRestaurants => $restaurantsParLieux)
                            {
                                // Lieu
                                echo '<div class="zone_recherche_conteneur">';
                                    echo '<div class="titre_section">';
                                        echo '<img src="../../includes/icons/foodadvisor/location_grey.png" alt="location_grey" class="logo_titre_section" />';
    
                                        echo '<div class="texte_titre_section_fold">' . $lieuRestaurants . '</div>';
    
                                        echo '<a id="fold_saisie_choix_' . formatId($lieuRestaurants) . '" class="bouton_fold">';
                                            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_bouton_fold" />';
                                        echo '</a>';
                                    echo '</div>';
    
                                    // Restaurants
                                    echo '<div id="saisie_choix_' . formatId($lieuRestaurants) . '" class="zone_recherche_contenu">';
                                        foreach ($restaurantsParLieux as $restaurant)
                                        {
                                            echo '<div id="zone_saisie_proposition_' . $restaurant->getId() . '" class="zone_saisie_proposition zone_recherche_item">';
                                                // Image
                                                echo '<div class="zone_image_proposition">';
                                                    if (!empty($restaurant->getPicture()))
                                                        echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="' . $restaurant->getPicture() . '" title="' . $restaurant->getName() . '" class="image_proposition image_rounded" />';
                                                    else
                                                        echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $restaurant->getName() . '" class="image_proposition" />';
                                                echo '</div>';
    
                                                // Nom restaurant
                                                echo '<div class="nom_proposition">' . formatString($restaurant->getName(), 100) . '</div>';
    
                                                // Choix horaire (masqué par défaut)
                                                echo '<div id="zone_listbox_horaire_' . $restaurant->getId() . '" class="zone_bouton_option_proposition" style="display: none;">';
                                                    echo '<a id="choix_horaire_' . $restaurant->getId() . '" class="bouton_option_proposition afficherHoraire">';
                                                        echo '<span class="fond_plus">+</span>';
                                                        echo 'Horaire';
                                                    echo '</a>';
                                                echo '</div>';
    
                                                // Choix transports (masqué par défaut)
                                                echo '<div id="zone_checkbox_transports_' . $restaurant->getId() . '" class="zone_bouton_option_proposition" style="display: none;">';
                                                    echo '<a id="choix_transports_' . $restaurant->getId() . '" class="bouton_option_proposition afficherTransports">';
                                                        echo '<span class="fond_plus">+</span>';
                                                        echo 'Transports';
                                                    echo '</a>';
                                                echo '</div>';
    
                                                // Menu (masqué par défaut)
                                                echo '<div id="zone_saisie_menu_' . $restaurant->getId() . '" class="zone_bouton_option_proposition" style="display: none;">';
                                                    echo '<a id="choix_menu_' . $restaurant->getId() . '" class="bouton_option_proposition afficherMenu">';
                                                        echo '<span class="fond_plus">+</span>';
                                                        echo 'Menu';
                                                    echo '</a>';
                                                echo '</div>';
    
                                                // Case à cocher
                                                echo '<label for="proposition_restaurant_' . $restaurant->getId() . '">';
                                                    echo '<div class="zone_checkbox_proposition">';
                                                        echo '<input type="checkbox" id="proposition_restaurant_' . $restaurant->getId() . '" name="restaurants[' . $restaurant->getId() . ']" class="checkbox_proposition" />';
                                                    echo '</div>';
                                                echo '</label>';
                                            echo '</div>';
                                        }
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                        else
                            echo '<div class="empty_propositions">Il n\'y a aucun restaurant disponible...</div>';
                    echo '</div>';
                echo '</div>';

                // Bouton
                echo '<div class="zone_boutons_saisie_propositions">';
                    // Soumettre
                    echo '<input type="submit" name="submit_choices" value="Soumettre les propositions" id="bouton_saisie_propositions" class="bouton_saisie_propositions" />';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>