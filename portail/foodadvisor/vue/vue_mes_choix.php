<?php
  /*************************/
  /*** Choix utilisateur ***/
  /*************************/
  echo '<div class="titre_section">';
    echo '<img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />';

    echo '<div class="texte_titre_section">';
      echo 'Mes choix du jour';

      echo '<div class="zone_actions">';
        // Supprimer tous les choix
        if ($actions["supprimer_choix"] == true)
        {
          echo '<form method="post" id="delete_choices" action="foodadvisor.php?action=doSupprimerChoix" class="form_action">';
            echo '<input type="submit" name="delete_choices" value="Supprimer tous mes choix" class="bouton_determination eventConfirm" />';
            echo '<input type="hidden" value="Supprimer tous les choix saisis ?" class="eventMessage" />';
          echo '</form>';
        }
      echo '</div>';
    echo '</div>';
  echo '</div>';

  if (!empty($mesChoix) OR $isSolo == true)
  {
    echo '<div class="zone_propositions">';
      // Si choix bande à part
      if ($isSolo == true)
      {
        if ($actions["choix"] == true)
        {
          echo '<div class="zone_proposition">';
            echo '<div class="titre_solo_choix">Vous avez choisi de faire bande à part. Vous pouvez annuler ce choix en cliquant sur ce bouton :</div>';

            // Suppression
            echo '<form method="post" action="foodadvisor.php?action=doSupprimerSolo">';
              echo '<input type="submit" name="delete_solo" value="Ne plus faire bande à part" class="bouton_delete_solo" />';
            echo '</form>';
          echo '</div>';
        }
        else
        {
          echo '<div class="zone_proposition">';
            echo '<div class="titre_solo_choix">Vous avez choisi de faire bande à part aujourd\'hui.</div>';
          echo '</div>';
        }
      }
      // Sinon
      else
      {
        foreach ($mesChoix as $monChoix)
        {
          /*********************************************/
          /* Visualisation normale (sans modification) */
          /*********************************************/
          echo '<div class="zone_proposition" id="visualiser_choix_' . $monChoix->getId() . '">';
            if ($actions["choix"] == true)
            {
              // Modification
              echo '<a id="modifier_' . $monChoix->getId() . '" title="Modifier le choix" class="icone_modify_choix modifierChoix"></a>';

              // Suppression
              echo '<form id="delete_choice_' . $monChoix->getId() . '" method="post" action="foodadvisor.php?action=doSupprimer">';
                echo '<input type="hidden" name="id_choix" value="' . $monChoix->getId() . '" />';
                echo '<input type="submit" name="delete_choice" value="" title="Supprimer le choix" class="icon_delete_choix eventConfirm" />';
                echo '<input type="hidden" value="Supprimer ce choix ?" class="eventMessage" />';
              echo '</form>';
            }

            // Image + lien
            echo '<a href="restaurants.php?action=goConsulter&anchor=' . $monChoix->getId_restaurant() . '" class="lien_mon_choix">';
              if (!empty($monChoix->getPicture()))
                echo '<img src="../../includes/images/foodadvisor/' . $monChoix->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
              else
                echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
            echo '</a>';

            // Nom du restaurant
            echo '<div class="nom_mon_choix">' . $monChoix->getName() . '</div>';

            // Ouverture, lieu, horaire et transports
            echo '<div class="zone_icones_mon_choix">';
              // Jours d'ouverture
              echo '<div class="zone_ouverture_mes_choix">';
                $explodedOpened = explode(";", $monChoix->getOpened());
                $semaine_short  = array("Lu", "Ma", "Me", "Je", "Ve");
                $i              = 0;

                foreach ($explodedOpened as $opened)
                {
                  if (!empty($opened))
                  {
                    if ($opened == "Y")
                      echo '<div class="jour_oui_fa">' . $semaine_short[$i] . '</div>';
                    else
                      echo '<div class="jour_non_fa">' . $semaine_short[$i] . '</div>';
                  }

                  $i++;
                }
              echo '</div>';

              // Lieu
              echo '<span class="lieu_mon_choix"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $monChoix->getLocation() . '</span>';

              // Horaire souhaitée
              if (!empty($monChoix->getTime()))
                echo '<span class="horaire_mon_choix">' . formatTimeForDisplayLight($monChoix->getTime()) . '</span>';

              // Moyens de transport
              if (!empty($monChoix->getTransports()))
              {
                $explodedTransports = explode(";", $monChoix->getTransports());

                echo '<div class="zone_transports_mes_choix">';
                  foreach ($explodedTransports as $transport)
                  {
                    switch ($transport)
                    {
                      case "F":
                        echo '<img src="../../includes/icons/foodadvisor/feet.png" alt="feet" class="icone_mon_choix" />';
                        break;

                      case "B":
                        echo '<img src="../../includes/icons/foodadvisor/bike.png" alt="bike" class="icone_mon_choix" />';
                        break;

                      case "T":
                        echo '<img src="../../includes/icons/foodadvisor/tram.png" alt="tram" class="icone_mon_choix" />';
                        break;

                      case "C":
                        echo '<img src="../../includes/icons/foodadvisor/car.png" alt="car" class="icone_mon_choix" />';
                        break;

                      default:
                        break;
                    }
                  }
                echo '</div>';
              }
            echo '</div>';

            // Menu
            list($entree, $plat, $dessert) = explode(";", decodeString($monChoix->getMenu()));

            if (empty($entree) AND empty($plat) AND empty($dessert))
              echo '<div class="no_menu_mes_choix">Pas de menu saisi</div>';
            else
            {
              echo '<div class="zone_menus_mes_choix">';
                if (!empty($entree))
                {
                  echo '<div class="zone_menu_mes_choix">';
                    echo '<span class="titre_texte_mon_choix">Entrée</span>';
                    echo '<div class="texte_mon_choix">' . $entree . '</div>';
                  echo '</div>';
                }

                if (!empty($plat))
                {
                  echo '<div class="zone_menu_mes_choix">';
                    echo '<span class="titre_texte_mon_choix">Plat</span>';
                    echo '<div class="texte_mon_choix">' . $plat . '</div>';
                  echo '</div>';
                }

                if (!empty($dessert))
                {
                  echo '<div class="zone_menu_mes_choix">';
                    echo '<span class="titre_texte_mon_choix">Dessert</span>';
                    echo '<div class="texte_mon_choix">' . $dessert . '</div>';
                  echo '</div>';
                }
              echo '</div>';
            }
          echo '</div>';

          /***************************/
          /* Caché pour modification */
          /***************************/
          if ($actions["choix"] == true)
          {
            echo '<div class="zone_proposition" id="modifier_choix_' . $monChoix->getId() . '" style="display: none;">';
              echo '<form method="post" action="foodadvisor.php?action=doModifier">';
                echo '<input type="hidden" name="id_choix" value="' . $monChoix->getId() . '" />';

                // Validation modification
                echo '<input type="submit" name="modify_choix_' . $monChoix->getId() . '" value="" title="Valider" class="icon_validate_choix" />';

                // Annulation modification
                echo '<a id="annuler_' . $monChoix->getId() . '" title="Annuler" class="icone_cancel_choix annulerChoix"></a>';

                // Image + lien
                echo '<div class="lien_mon_choix">';
                  if (!empty($monChoix->getPicture()))
                    echo '<img src="../../includes/images/foodadvisor/' . $monChoix->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
                  else
                    echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
                echo '</div>';

                // Nom du restaurant
                echo '<div class="nom_mon_choix">' . $monChoix->getName() . '</div>';

                // Horaire et transport
                echo '<div class="zone_icones_update_mon_choix">';
                  // Horaire souhaitée
                  if (!empty($monChoix->getTime()))
                  {
                    echo '<div id="zone_update_listbox_horaire_' . $monChoix->getId() . '">';
                      echo '<a id="update_horaire_' . $monChoix->getId() . '" class="bouton_choix_update afficherHoraireUpdate" style="display: none">';
                        echo '<span class="fond_plus">+</span>';
                        echo 'Horaire';
                      echo '</a>';

                      $id_select_h = 'select_heures_' . $monChoix->getId();
                      $id_select_m = 'select_minutes_' . $monChoix->getId();
                      $id_annuler  = 'annuler_horaires_' . $monChoix->getId();

                      echo '<select id="' . $id_select_h . '" name="' . $id_select_h . '" class="listbox_horaires_update">';
                        for ($i = 11; $i < 14; $i++)
                        {
                          if ($i == substr($monChoix->getTime(), 0, 2))
                            echo '<option value="' . $i . '" selected>' . $i . '</option>';
                          else
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                      echo '</select>';

                      echo '<select id="' . $id_select_m . '" name="' . $id_select_m . '" class="listbox_horaires_update">';
                        for ($j = 0; $j < 4; $j++)
                        {
                          if ($j * 15 == substr($monChoix->getTime(), 2, 2))
                          {
                            if ($j == 0)
                              echo '<option value="0' . $j . '" selected>0' . $j . '</option>';
                            else
                              echo '<option value="' . $j * 15 . '" selected>' . $j * 15 . '</option>';
                          }
                          else
                          {
                            if ($j == 0)
                              echo '<option value="0' . $j . '">0' . $j . '</option>';
                            else
                              echo '<option value="' . $j * 15 . '">' . $j * 15 . '</option>';
                          }
                        }
                      echo '</select>';

                      echo '<a id="' . $id_annuler . '" class="bouton_annuler_update annulerHoraireUpdate">Annuler</a>';
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div id="zone_update_listbox_horaire_' . $monChoix->getId() . '">';
                      echo '<a id="update_horaire_' . $monChoix->getId() . '" class="bouton_choix_update afficherHoraireUpdate">';
                        echo '<span class="fond_plus">+</span>';
                        echo 'Horaire';
                      echo '</a>';
                    echo '</div>';
                  }

                  // Moyens de transport
                  $explodedTransports = explode(";", $monChoix->getTransports());
                  $feet = false;
                  $bike = false;
                  $tram = false;
                  $car  = false;

                  foreach ($explodedTransports as $transport)
                  {
                    switch ($transport)
                    {
                      case "F":
                        $feet = true;
                        break;

                      case "B":
                        $bike = true;
                        break;

                      case "T":
                        $tram = true;
                        break;

                      case "C":
                        $car  = true;
                        break;

                      default:
                        break;
                    }
                  }

                  $id_check_f = 'checkbox_feet_' . $monChoix->getId();
                  $id_check_b = 'checkbox_bike_' . $monChoix->getId();
                  $id_check_t = 'checkbox_tram_' . $monChoix->getId();
                  $id_check_c = 'checkbox_car_' . $monChoix->getId();

                  if ($feet == true)
                  {
                    echo '<div id="bouton_' . $id_check_f . '" class="switch_transport_2 bouton_checked">';
                      echo '<input id="' . $id_check_f . '" type="checkbox" value="F" name="' . $id_check_f . '" checked />';
                      echo '<label for="' . $id_check_f . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div id="bouton_' . $id_check_f . '" class="switch_transport_2">';
                      echo '<input id="' . $id_check_f . '" type="checkbox" value="F" name="' . $id_check_f . '" />';
                      echo '<label for="' . $id_check_f . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }

                  if ($bike == true)
                  {
                    echo '<div id="bouton_' . $id_check_b . '" class="switch_transport_2 margin_right_update bouton_checked">';
                      echo '<input id="' . $id_check_b . '" type="checkbox" value="B" name="' . $id_check_b . '" checked />';
                      echo '<label for="' . $id_check_b . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div id="bouton_' . $id_check_b . '" class="switch_transport_2 margin_right_update">';
                      echo '<input id="' . $id_check_b . '" type="checkbox" value="B" name="' . $id_check_b . '" />';
                      echo '<label for="' . $id_check_b . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }

                  if ($tram == true)
                  {
                    echo '<div id="bouton_' . $id_check_t . '" class="switch_transport_2 bouton_checked">';
                      echo '<input id="' . $id_check_t . '" type="checkbox" value="T" name="' . $id_check_t . '" checked />';
                      echo '<label for="' . $id_check_t . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div id="bouton_' . $id_check_t . '" class="switch_transport_2">';
                      echo '<input id="' . $id_check_t . '" type="checkbox" value="T" name="' . $id_check_t . '" />';
                      echo '<label for="' . $id_check_t . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }

                  if ($car == true)
                  {
                    echo '<div id="bouton_' . $id_check_c . '" class="switch_transport_2 margin_right_update bouton_checked">';
                      echo '<input id="' . $id_check_c . '" type="checkbox" value="C" name="' . $id_check_c . '" checked />';
                      echo '<label for="' . $id_check_c . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div id="bouton_' . $id_check_c . '" class="switch_transport_2 margin_right_update">';
                      echo '<input id="' . $id_check_c . '" type="checkbox" value="C" name="' . $id_check_c . '" />';
                      echo '<label for="' . $id_check_c . '" class="label_switch_transport cocherTransport"><img src="../../includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
                    echo '</div>';
                  }
                echo '</div>';

                // Menu
                list($entree, $plat, $dessert) = explode(";", decodeString($monChoix->getMenu()));

                echo '<div class="zone_update_menu">';
                  echo '<input type="text" value="' . $entree . '" placeholder="Entrée" name="update_entree_' . $monChoix->getId() . '" class="update_menu" />';
                  echo '<input type="text" value="' . $plat . '" placeholder="Plat" name="update_plat_' . $monChoix->getId() . '" class="update_menu" />';
                  echo '<input type="text" value="' . $dessert . '" placeholder="Dessert" name="update_dessert_' . $monChoix->getId() . '" class="update_menu" style="margin-bottom: 10px;" />';
                echo '</div>';
              echo '</form>';
            echo '</div>';
          }
        }
      }
    echo '</div>';
  }
  else
    echo '<div class="empty">Pas de choix encore saisis pour aujourd\'hui !</div>';
?>
