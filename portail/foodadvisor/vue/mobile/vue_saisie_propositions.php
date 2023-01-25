<?php
  echo '<div id="zone_saisie_propositions" class="fond_saisie">';
    echo '<form method="post" action="foodadvisor.php?action=doAjouterMobile" class="form_saisie">';
      // Titre
      echo '<div class="zone_titre_saisie">';
        echo 'Proposer où manger';
      echo '</div>';

      // Recherche
      echo '<div class="zone_recherche_live">';
        // Logo
        echo '<img src="../../includes/icons/common/search.png" alt="search" title="Rechercher" class="logo_recherche_live" />';

        // Zone de saisie
        echo '<input type="text" autocomplete="off" id="recherche_live_propositions" placeholder="Rechercher" class="input_recherche_live" />';

        // Effacer
        echo '<img src="../../includes/icons/common/cancel.png" alt="cancel" title="Effacer" id="reset_recherche_live_propositions" class="logo_recherche_live size_logo_recherche_live" />';
      echo '</div>';

      // Saisie
      echo '<div class="zone_contenu_saisie_live">';
        echo '<div class="contenu_saisie">';
          // Message vide
          echo '<div class="empty_recherche_live">Aucun résultat n\'a été trouvé...</div>';

          // Restaurants par lieu
          foreach ($listeRestaurants as $lieuRestaurants => $restaurantsParLieux)
          {
            // Lieu
            echo '<div class="zone_recherche_conteneur">';
              echo '<div id="titre_saisie_' . formatId($lieuRestaurants) . '" class="titre_section">';
                echo '<img src="../../includes/icons/foodadvisor/location_grey.png" alt="location_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section_fleche">' . $lieuRestaurants . '</div>';
                echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
              echo '</div>';

              // Restaurants
              echo '<div id="afficher_saisie_' . formatId($lieuRestaurants) . '" class="zone_recherche_contenu">';
                foreach ($restaurantsParLieux as $restaurant)
                {
                  echo '<label for="proposition_restaurant_' . $restaurant->getId() . '" id="label_proposition_' . $restaurant->getId() . '" class="zone_recherche_item">';
                    echo '<div class="zone_proposition proposition_normal">';
                      echo '<div class="image_normal">';
                        // Image
                        if (!empty($restaurant->getPicture()))
                          echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="' . $restaurant->getPicture() . '" title="' . $restaurant->getName() . '" class="image_proposition image_rounded" />';
                        else
                          echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $restaurant->getName() . '" class="image_proposition" />';
                      echo '</div>';

                      // Nom restaurant
                      echo '<div class="nom_proposition nom_normal">' . formatString($restaurant->getName(), 20) . '</div>';
                      echo '<div class="nom_proposition_complet">' . $restaurant->getName() . '</div>';

                      // Case à cocher
                      echo '<div class="zone_checkbox_proposition">';
                        echo '<input type="checkbox" id="proposition_restaurant_' . $restaurant->getId() . '" name="restaurants[' . $restaurant->getId() . ']" class="checkbox_proposition" />';
                      echo '</div>';
                    echo '</div>';
                  echo '</label>';
                }
              echo '</div>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';

      // Boutons
      echo '<div class="zone_boutons_saisie">';
        // Valider
        echo '<input type="submit" name="submit_choices" value="Valider" id="validerSaisiePropositions" class="bouton_saisie_gauche" />';

        // Annuler
        echo '<a id="fermerSaisiePropositions" class="bouton_saisie_droite">Annuler</a>';
      echo '</div>';
    echo '</form>';
  echo '</div>';
?>
