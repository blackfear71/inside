<?php
  /******************************/
  /*** Fiches des restaurants ***/
  /******************************/
  echo '<div class="zone_restaurants" style="display: none;">';
    foreach ($listeRestaurants as $lieu => $restaurantsParLieux)
    {
      echo '<div class="titre_section" id="' . formatId($lieu) . '"><img src="../../includes/icons/foodadvisor/location_grey.png" alt="location" class="logo_titre_section" /><div class="texte_titre_section">' . $lieu . '</div></div>';

      echo '<div class="zone_fiches_restaurants">';
        foreach ($restaurantsParLieux as $restaurant)
        {
          /*********************************************/
          /* Visualisation normale (sans modification) */
          /*********************************************/
          echo '<div class="fiche_restaurant" id="visualiser_restaurant_' . $restaurant->getId() . '">';
            echo '<div id="zone_shadow_' . $restaurant->getId() . '" class="zone_shadow">';
              echo '<div class="zone_fiche_left">';
                // Image
                if (!empty($restaurant->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="restaurant" title="' . $restaurant->getName() . '" class="image_restaurant" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" title="' . $restaurant->getName() . '" class="image_restaurant" />';
              echo '</div>';

              echo '<div class="zone_fiche_middle">';
                // Nom
                echo '<div class="nom_restaurant" id="' . $restaurant->getId() . '">' . $restaurant->getName() . '</div>';

                // Prix
                if (!empty($restaurant->getMin_price()) AND !empty($restaurant->getMax_price()))
                {
                  if ($restaurant->getMin_price() == $restaurant->getMax_price())
                    echo '<div class="price">Prix moy. ' . $restaurant->getMin_price() . '€</div>';
                  else
                  {
                    echo '<div class="price">Prix min. ' . $restaurant->getMin_price() . '€</div>';
                    echo '<div class="price">Prix max. ' . $restaurant->getMax_price() . '€</div>';
                  }
                }

                // Types
                echo '<div class="zone_types_fiche">';
                  $explodedTypes = explode(";", $restaurant->getTypes());

                  foreach ($explodedTypes as $exploded)
                  {
                    if (!empty($exploded))
                      echo '<span class="type_restaurant">' . $exploded . '</span>';
                  }
                echo '</div>';

                // Jours d'ouverture, site web et plan
                echo '<div class="zone_icones_fiches">';
                  // Jours d'ouverture
                  $explodedOpened = explode(";", $restaurant->getOpened());
                  $semaine_short  = array("Lu", "Ma", "Me", "Je", "Ve");
                  $k              = 0;

                  foreach ($explodedOpened as $opened)
                  {
                    if (!empty($opened))
                    {
                      if ($opened == "Y")
                        echo '<div class="jour_oui">' . $semaine_short[$k] . '</div>';
                      else
                        echo '<div class="jour_non">' . $semaine_short[$k] . '</div>';
                    }

                    $k++;
                  }

                  // Site web
                  if (!empty($restaurant->getWebsite()))
                  {
                    echo '<a href="' . $restaurant->getWebsite() . '" target="_blank">';
                      echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="icone_fiche" style="margin-left: 2px;"/>';
                    echo '</a>';
                  }

                  // Plan
                  if (!empty($restaurant->getPlan()))
                  {
                    echo '<a href="' . $restaurant->getPlan() . '" target="_blank">';
                      echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Plan" class="icone_fiche" />';
                    echo '</a>';
                  }

                  // LaFourchette
                  if (!empty($restaurant->getLafourchette()))
                  {
                    echo '<a href="' . $restaurant->getLafourchette() . '" target="_blank">';
                      echo '<img src="../../includes/icons/foodadvisor/lafourchette.png" alt="lafourchette" title="LaFourchette" class="icone_fiche" />';
                    echo '</a>';
                  }
                echo '</div>';

                // Numéro
                if (!empty($restaurant->getPhone()))
                {
                  echo '<div class="numero_restaurant">';
                    echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Téléphone" class="icone_fiche_phone" />';
                    echo '<div class="phone_number">' . formatPhoneNumber($restaurant->getPhone()) . '</div>';
                  echo '</div>';
                }
              echo '</div>';

              echo '<div class="zone_fiche_right">';
                // Modification
                echo '<a id="modifier_' . $restaurant->getId() . '" title="Modifier le restaurant" class="lien_modifier_restaurant modifierRestaurant">';
                  echo '<img src="../../includes/icons/common/edit.png" alt="edit" class="icone_modify_restaurant" />';
                echo '</a>';

                // Suppression
                echo '<form id="delete_restaurant_' . $restaurant->getId() . '" method="post" action="restaurants.php?action=doSupprimer">';
                  echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';
                  echo '<input type="submit" name="delete_restaurant" value="" title="Supprimer le restaurant" class="icon_delete_restaurant eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer ce restaurant de la liste ?" class="eventMessage" />';
                echo '</form>';

                // Choix rapide
                if ($choixRapide == true)
                {
                  echo '<form method="post" action="restaurants.php?action=doChoixRapide">';
                    echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';
                    echo '<input type="submit" name="fast_restaurant" value="" title="Proposer ce restaurant" class="icon_fast_restaurant" />';
                  echo '</form>';
                }
              echo '</div>';

              // Description
              if (!empty($restaurant->getDescription()))
              {
                $longueur_max = 300;

                echo '<div class="description_restaurant">';
                  if (strlen($restaurant->getDescription()) > $longueur_max)
                  {
                    echo '<div id="long_description_' . $restaurant->getId() . '" style="display: none;">' . nl2br($restaurant->getDescription()) . '</div>';
                    echo '<div class="short_description" id="short_description_' . $restaurant->getId() . '">' . substr(nl2br($restaurant->getDescription()), 0, $longueur_max) . '...</div>';
                    echo '<a id="description_' . $restaurant->getId() . '" class="descriptionRestaurant"><img src="../../includes/icons/foodadvisor/expand.png" alt="expand" class="expand_description" /></a>';
                  }
                  else
                    echo '<div>' . nl2br($restaurant->getDescription()) . '</div>';
                echo '</div>';
              }
            echo '</div>';
          echo '</div>';

          /***************************/
          /* Caché pour modification */
          /***************************/
          echo '<div class="fiche_restaurant" id="modifier_restaurant_' . $restaurant->getId() . '" style="display: none; position: relative; z-index: 2;">';
            echo '<form method="post" action="restaurants.php?action=doModifier" enctype="multipart/form-data" class="zone_shadow">';
              echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';

              // Image
              echo '<div class="zone_fiche_left">';
                echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                echo '<span class="zone_parcourir_restaurant_update">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="update_image_restaurant_' . $restaurant->getId() . '" id="modifier_image_' . $restaurant->getId() . '" class="bouton_parcourir_restaurant_update loadModifierRestaurant" /></span>';

                echo '<div class="mask_update_restaurant">';
                  if (!empty($restaurant->getPicture()))
                    echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="" id="img_restaurant_' . $restaurant->getId() . '" class="update_image_restaurant" />';
                  else
                    echo '<img id="img_restaurant_' . $restaurant->getId() . '" alt="" class="update_image_restaurant" />';
                echo '</div>';
              echo '</div>';

              echo '<div class="zone_fiche_middle">';
                // Nom
                echo '<input type="text" name="update_name_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getName() . '" placeholder="Nom du restaurant" class="update_nom_restaurant" required />';

                // Lieu
                echo '<select name="update_location_' . $restaurant->getId() . '" id="update_location_' . $restaurant->getId() . '" class="update_lieu_restaurant changeLieu" required>';
                  echo '<option value="" hidden>Choisissez...</option>';

                  foreach ($listeLieux as $lieu)
                  {
                    if ($lieu == $restaurant->getLocation())
                      echo '<option value="' . $lieu . '" selected>' . $lieu . '</option>';
                    else
                      echo '<option value="' . $lieu . '">' . $lieu . '</option>';
                  }

                  echo '<option value="other_location">Autre</option>';
                echo '</select>';

                // Lieu "Autre"
                echo '<input type="text" name="update_other_location_' . $restaurant->getId() . '" placeholder="Lieu personnalisé" maxlength="255" id="other_location_' . $restaurant->getId() . '" class="update_lieu_autre_restaurant" style="display: none;" />';

                // Jours d'ouverture
                echo '<div class="opened_restaurant_update">';
                  $explodedOpened = explode(";", $restaurant->getOpened());
                  $semaine        = array("Lu" => "lundi",
                                          "Ma" => "mardi",
                                          "Me" => "mercredi",
                                          "Je" => "jeudi",
                                          "Ve" => "vendredi");

                  $l = 0;

                  foreach ($semaine as $j => $jour)
                  {
                    $id_opened    = "checkbox_update_ouverture_" . $jour . "_" . $restaurant->getId();
                    $label_opened = "label_update_ouverture_" . $jour . "_" . $restaurant->getId();

                    if ($explodedOpened[$l] == "Y")
                    {
                      echo '<input type="checkbox" id="' . $id_opened . '" name="update_ouverture_restaurant_' . $restaurant->getId() . '[' . $l . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" checked />';
                      echo '<label for="' . $id_opened . '" id="' . $label_opened . '" class="update_label_jour_checked checkDayUpdate">' . $j . '</label>';
                    }
                    else
                    {
                      echo '<input type="checkbox" id="' . $id_opened . '" name="update_ouverture_restaurant_' . $restaurant->getId() . '[' . $l . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" />';
                      echo '<label for="' . $id_opened . '" id="' . $label_opened . '" class="update_label_jour checkDayUpdate">' . $j . '</label>';
                    }

                    $l++;
                  }
                echo '</div>';

                // Prix
                echo '<div class="zone_update_prix">';
                  echo '<input type="text" name="update_prix_min_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getMin_price() . '" maxlength="5" placeholder="Prix min." class="update_prix_min_restaurant" /> €';
                  echo '<input type="text" name="update_prix_max_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getMax_price() . '" maxlength="5" placeholder="Prix max." class="update_prix_max_restaurant" /> €';
                echo '</div>';
              echo '</div>';

              echo '<div class="zone_fiche_right">';
                // Validation modification
                echo '<input type="submit" name="modify_restaurant_' . $restaurant->getId() . '" value="" title="Valider" class="icon_validate_restaurant" />';

                // Annulation modification
                echo '<a id="annuler_' . $restaurant->getId() . '" title="Annuler" class="icone_cancel_restaurant annulerRestaurant"></a>';
              echo '</div>';

              echo '<div class="zone_fiche_bottom">';
                // Types
                echo '<div id="update_types_restaurants_' . $restaurant->getId() . '" class="zone_update_types">';
                  echo '<a id="type_update_' . $restaurant->getId() . '" class="bouton_type_autre addTypeUpdate"><span class="fond_plus">+</span>Autre</a>';

                  $explodedTypes = explode(";", $restaurant->getTypes());
                  $k             = 0;

                  foreach ($listeTypes as $type)
                  {
                    $id_type  = "type_" . formatId($type) . "_" . $restaurant->getId();
                    $matching = false;

                    foreach ($explodedTypes as $exploded)
                    {
                      if (!empty($exploded) AND $exploded == $type)
                      {
                        $matching = true;
                        break;
                      }
                    }

                    if ($matching == true)
                    {
                      echo '<div id="bouton_' . $id_type . '" class="switch_types bouton_checked">';
                        echo '<input id="' . $id_type . '" type="checkbox" value="' . $type . '" name="update_types_restaurants_' . $restaurant->getId() . '[' . $k . ']" checked />';
                        echo '<label for="' . $id_type . '" class="label_switch checkTypeUpdate">' . $type . '</label>';
                      echo '</div>';
                    }
                    else
                    {
                      echo '<div id="bouton_' . $id_type . '" class="switch_types">';
                        echo '<input id="' . $id_type . '" type="checkbox" value="' . $type . '" name="update_types_restaurants_' . $restaurant->getId() . '[' . $k . ']" />';
                        echo '<label for="' . $id_type . '" class="label_switch checkTypeUpdate">' . $type . '</label>';
                      echo '</div>';
                    }

                    $k++;
                  }
                echo '</div>';

                // Site web
                echo '<a href="https://www.google.fr/" target="_blank">';
                  echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Google" class="update_icone_fiche" />';
                echo '</a>';

                echo '<input type="text" name="update_website_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getWebsite() . '" placeholder="Site web" class="update_lien_restaurant" />';

                // Plan
                echo '<a href="https://www.google.fr/maps" target="_blank">';
                  echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Google Maps" class="update_icone_fiche" />';
                echo '</a>';

                echo '<input type="text" name="update_plan_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getPlan() . '" placeholder="Plan" class="update_lien_restaurant" />';

                // LaFourchette
                echo '<a href="https://www.lafourchette.com/" target="_blank">';
                  echo '<img src="../../includes/icons/foodadvisor/lafourchette.png" alt="lafourchette" title="LaFourchette" class="update_icone_fiche" />';
                echo '</a>';

                echo '<input type="text" name="update_plan_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getLafourchette() . '" placeholder="LaFourchette" class="update_lien_restaurant" />';

                // Téléphone
                echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Téléphone" class="update_icone_fiche" />';
                echo '<input type="text" name="update_phone_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getPhone() . '" maxlength="15" placeholder="Téléphone du restaurant" class="update_lien_restaurant" />';
              echo '</div>';

              // Description
              echo '<div class="description_restaurant">';
                echo '<textarea placeholder="Description" name="update_description_restaurant_' . $restaurant->getId() . '" class="textarea_update_description_restaurant">' . $restaurant->getDescription() . '</textarea>';
              echo '</div>';

            echo '</form>';
          echo '</div>';
        }
      echo '</div>';
    }
  echo '</div>';
?>
