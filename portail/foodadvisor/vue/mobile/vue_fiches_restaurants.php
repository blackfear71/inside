<?php
  /******************************/
  /*** Fiches des restaurants ***/
  /******************************/
  echo '<div class="zone_restaurants">';
    if (!empty($listeRestaurants))
    {
      // Liste des restaurants par lieu
      foreach ($listeRestaurants as $lieu => $restaurantsParLieux)
      {
        // Titre lieu
        echo '<div id="' . formatId($lieu) . '">';
          echo '<div id="titre_' . formatId($lieu) . '" class="titre_section">';
            echo '<img src="../../includes/icons/foodadvisor/location_grey.png" alt="location_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section_fleche">' . $lieu . '</div>';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
          echo '</div>';
        echo '</div>';

        echo '<div id="afficher_' . formatId($lieu) . '">';
          // Liste des restaurants d'un lieu
          foreach ($restaurantsParLieux as $restaurant)
          {
            echo '<div id="zone_shadow_' . $restaurant->getId() . '" class="zone_shadow">';
              echo '<div id="' . $restaurant->getId() . '" class="zone_restaurant afficherDetailsRestaurant">';
                // Image
                echo '<div class="image_normal">';
                  if (!empty($restaurant->getPicture()))
                    echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="' . $restaurant->getPicture() . '" title="' . $restaurant->getName() . '" class="image_restaurant image_rounded" />';
                  else
                    echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $restaurant->getName() . '" class="image_restaurant" />';
                echo '</div>';

                // Nom restaurant
                echo '<div class="nom_restaurant">' . formatString($restaurant->getName(), 20) . '</div>';

                // Détermination jour disponible
                $explodedOpened = explode(';', $restaurant->getOpened());
                $availableDay   = true;

                foreach ($explodedOpened as $keyOpened => $opened)
                {
                  if (!empty($opened) AND $opened == 'N' AND date('N') == $keyOpened + 1)
                      $availableDay = false;
                }

                // Choix rapide
                if ($choixRapide == true AND $availableDay == true)
                {
                  $alreadyVoted = false;

                  // Contrôle choix déjà effectué
                  foreach ($mesChoix as $monChoix)
                  {
                    if ($restaurant->getId() == $monChoix->getId_restaurant())
                    {
                      $alreadyVoted = true;
                      break;
                    }
                  }

                  // Affichage du bouton de choix rapide
                  if ($alreadyVoted == false)
                  {
                    echo '<form method="post" action="restaurants.php?action=doChoixRapide" class="form_saisie_rapide">';
                      echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';
                      echo '<input type="submit" name="fast_restaurant" value="" title="Proposer ce restaurant" class="bouton_saisie_rapide" />';
                    echo '</form>';
                  }
                }
              echo '</div>';
            echo '</div>';
          }
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas encore de restaurants...</div>';
  echo '</div>';
?>
