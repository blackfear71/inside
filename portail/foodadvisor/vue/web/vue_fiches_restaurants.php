<?php
  /******************************/
  /*** Fiches des restaurants ***/
  /******************************/
  echo '<div class="zone_restaurants" style="display: none;">';
    if (!empty($listeRestaurants))
    {
      foreach ($listeRestaurants as $lieu => $restaurantsParLieux)
      {
        // Titre
        echo '<div class="titre_section" id="' . formatId($lieu) . '">';
          echo '<img src="../../includes/icons/foodadvisor/location_grey.png" alt="location" class="logo_titre_section" />';
          echo '<div class="texte_titre_section_fold">' . $lieu . '</div>';

          echo '<a id="fold_fiches_' . formatId($lieu) . '" class="bouton_fold">';
            echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_bouton_fold" />';
          echo '</a>';
        echo '</div>';

        // Fiches
        echo '<div class="zone_fiches_restaurants" id="fiches_' . formatId($lieu) . '">';
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
                    echo '<img src="../../includes/images/foodadvisor/' . $restaurant->getPicture() . '" alt="' . $restaurant->getPicture() . '" title="' . $restaurant->getName() . '" class="image_restaurant image_rounded" />';
                  else
                    echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" title="' . $restaurant->getName() . '" class="image_restaurant" />';
                echo '</div>';

                echo '<div class="zone_fiche_middle">';
                  // Nom
                  echo '<div class="nom_restaurant" id="' . $restaurant->getId() . '">' . $restaurant->getName() . '</div>';

                  // Prix
                  if (!empty($restaurant->getMin_price()) AND !empty($restaurant->getMax_price()))
                  {
                    if ($restaurant->getMin_price() == $restaurant->getMax_price())
                      echo '<div class="price">Prix moy. ' . formatAmountForDisplay($restaurant->getMin_price()) . '</div>';
                    else
                    {
                      echo '<div class="price">Prix min. ' . formatAmountForDisplay($restaurant->getMin_price()) . '</div>';
                      echo '<div class="price">Prix max. ' . formatAmountForDisplay($restaurant->getMax_price()) . '</div>';
                    }
                  }

                  // Types
                  echo '<div class="zone_types_fiche">';
                    $explodedTypes = explode(';', $restaurant->getTypes());

                    foreach ($explodedTypes as $exploded)
                    {
                      if (!empty($exploded))
                        echo '<span class="type_restaurant">' . $exploded . '</span>';
                    }
                  echo '</div>';

                  // Jours d'ouverture, site web et plan
                  echo '<div class="zone_icones_fiches">';
                    // Jours d'ouverture
                    $explodedOpened = explode(';', $restaurant->getOpened());
                    $semaineShort   = array(0 => 'Lu', 1 => 'Ma', 2 => 'Me', 3 => 'Je', 4 => 'Ve');
                    $availableDay   = true;

                    foreach ($explodedOpened as $keyOpened => $opened)
                    {
                      if (!empty($opened))
                      {
                        if ($opened == 'Y')
                          echo '<div class="jour_oui">' . $semaineShort[$keyOpened] . '</div>';
                        else
                          echo '<div class="jour_non">' . $semaineShort[$keyOpened] . '</div>';

                        if (date('N') == $keyOpened + 1 AND $opened == 'N')
                          $availableDay = false;
                      }
                    }

                    // Site web
                    if (!empty($restaurant->getWebsite()))
                    {
                      echo '<a href="' . $restaurant->getWebsite() . '" target="_blank">';
                        echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="icone_fiche" />';
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
                    echo '<img src="../../includes/icons/common/edit.png" alt="edit" class="icone_update_restaurant" />';
                  echo '</a>';

                  // Suppression
                  echo '<form id="delete_restaurant_' . $restaurant->getId() . '" method="post" action="restaurants.php?action=doSupprimer" class="lien_supprimer_restaurant">';
                    echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';
                    echo '<input type="submit" name="delete_restaurant" value="" title="Supprimer le restaurant" class="icon_delete_restaurant eventConfirm" />';
                    echo '<input type="hidden" value="Supprimer ce restaurant de la liste ?" class="eventMessage" />';
                  echo '</form>';

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
                      echo '<form method="post" action="restaurants.php?action=doChoixRapide" class="lien_choix_rapide_restaurant">';
                        echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';
                        echo '<input type="submit" name="fast_restaurant" value="" title="Proposer ce restaurant" class="icon_fast_restaurant" />';
                      echo '</form>';
                    }
                  }
                echo '</div>';

                // Description
                if (!empty($restaurant->getDescription()))
                {
                  $longueurMax = 300;

                  echo '<div class="description_restaurant">';
                    if (strlen($restaurant->getDescription()) > $longueurMax)
                    {
                      echo '<div id="long_description_' . $restaurant->getId() . '" style="display: none;">' . nl2br($restaurant->getDescription()) . '</div>';
                      echo '<div class="short_description" id="short_description_' . $restaurant->getId() . '">' . substr(nl2br($restaurant->getDescription()), 0, $longueurMax) . '...</div>';
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
            echo '<div class="fiche_restaurant fiche_restaurant_top" id="modifier_restaurant_' . $restaurant->getId() . '" style="display: none;">';
              echo '<form method="post" action="restaurants.php?action=doModifier" enctype="multipart/form-data" class="zone_shadow">';
                echo '<input type="hidden" name="id_restaurant" value="' . $restaurant->getId() . '" />';

                // Image
                echo '<div class="zone_fiche_left">';
                  echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                  echo '<span class="zone_parcourir_restaurant_update">';
                    echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image_2" />';
                    echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="update_image_restaurant_' . $restaurant->getId() . '" id="modifier_image_' . $restaurant->getId() . '" class="bouton_parcourir_restaurant_update loadModifierRestaurant" />';
                  echo '</span>';

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
                    $explodedOpened = explode(';', $restaurant->getOpened());
                    $semaine        = array('Lu' => 'lundi',
                                            'Ma' => 'mardi',
                                            'Me' => 'mercredi',
                                            'Je' => 'jeudi',
                                            'Ve' => 'vendredi');

                    $l = 0;

                    foreach ($semaine as $j => $jour)
                    {
                      $idOpened    = 'checkbox_update_ouverture_' . $jour . '_' . $restaurant->getId();
                      $labelOpened = 'label_update_ouverture_' . $jour . '_' . $restaurant->getId();

                      if ($explodedOpened[$l] == 'Y')
                      {
                        echo '<input type="checkbox" id="' . $idOpened . '" name="update_ouverture_restaurant_' . $restaurant->getId() . '[' . $l . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" checked />';
                        echo '<label for="' . $idOpened . '" id="' . $labelOpened . '" class="update_label_jour_checked checkDayUpdate">' . $j . '</label>';
                      }
                      else
                      {
                        echo '<input type="checkbox" id="' . $idOpened . '" name="update_ouverture_restaurant_' . $restaurant->getId() . '[' . $l . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" />';
                        echo '<label for="' . $idOpened . '" id="' . $labelOpened . '" class="update_label_jour checkDayUpdate">' . $j . '</label>';
                      }

                      $l++;
                    }
                  echo '</div>';

                  // Prix
                  echo '<div class="zone_update_prix">';
                    // Prix min
                    echo '<input type="text" name="update_prix_min_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getMin_price() . '" maxlength="5" placeholder="Prix min." class="update_prix_min_restaurant" />';
                    echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro" />';

                    // Prix max
                    echo '<input type="text" name="update_prix_max_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getMax_price() . '" maxlength="5" placeholder="Prix max." class="update_prix_max_restaurant" />';
                    echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro" />';
                  echo '</div>';
                echo '</div>';

                echo '<div class="zone_fiche_right">';
                  // Validation modification
                  echo '<div id="zone_bouton_validation_' . $restaurant->getId() . '" class="zone_bouton_validation">';
                    echo '<input type="submit" name="update_restaurant_' . $restaurant->getId() . '" value="" title="Valider" id="bouton_validation_restaurant_' . $restaurant->getId() . '" class="icone_valider_restaurant" />';
                  echo '</div>';

                  // Annulation modification
                  echo '<a id="annuler_update_restaurant_' . $restaurant->getId() . '" title="Annuler" class="icone_annuler_restaurant annulerRestaurant"></a>';
                echo '</div>';

                echo '<div class="zone_fiche_bottom">';
                  // Types
                  echo '<div id="update_types_restaurants_' . $restaurant->getId() . '" class="zone_update_types">';
                    // Types existants
                    $explodedTypes = explode(';', $restaurant->getTypes());
                    $k             = 0;

                    foreach ($listeTypes as $type)
                    {
                      $idType   = 'type_' . formatId($type) . '_' . $restaurant->getId();
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
                        echo '<div id="bouton_' . $idType . '" class="switch_types bouton_checked">';
                          echo '<input id="' . $idType . '" type="checkbox" value="' . $type . '" name="update_types_restaurants_' . $restaurant->getId() . '[' . $k . ']" checked />';
                          echo '<label for="' . $idType . '" class="label_switch checkTypeUpdate">' . $type . '</label>';
                        echo '</div>';
                      }
                      else
                      {
                        echo '<div id="bouton_' . $idType . '" class="switch_types">';
                          echo '<input id="' . $idType . '" type="checkbox" value="' . $type . '" name="update_types_restaurants_' . $restaurant->getId() . '[' . $k . ']" />';
                          echo '<label for="' . $idType . '" class="label_switch checkTypeUpdate">' . $type . '</label>';
                        echo '</div>';
                      }

                      $k++;
                    }

                    // Bouton "Autre"
                    echo '<a id="type_update_' . $restaurant->getId() . '" class="bouton_update_type_autre addTypeUpdate"><span class="fond_plus">+</span>Autre</a>';
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

                  echo '<input type="text" name="update_lafourchette_restaurant_' . $restaurant->getId() . '" value="' . $restaurant->getLafourchette() . '" placeholder="LaFourchette" class="update_lien_restaurant" />';

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
    }
    else
      echo '<div class="empty">Pas encore de restaurants...</div>';
  echo '</div>';
?>
