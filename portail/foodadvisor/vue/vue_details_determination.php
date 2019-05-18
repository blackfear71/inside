<?php
  /***********************************/
  /*** Détails de la détermination ***/
  /***********************************/
  if (!empty($propositions))
  {
    foreach($propositions as $proposition)
    {
      if ($proposition->getDetermined() == "Y" OR $proposition->getClassement() == 1)
      {
        echo '<div id="zone_details_determined_' . $proposition->getId_restaurant() . '" class="fond_saisie_restaurant">';
          echo '<div class="zone_details_proposition">';
            // Détails restaurant
            echo '<div class="zone_details_proposition_left">';
              // Image + lien
              echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_proposition_top">';
                if (!empty($proposition->getPicture()))
                  echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_proposition_top" />';
                else
                  echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_proposition_top" />';
              echo '</a>';

              // Nom du restaurant
              echo '<div class="nom_restaurant_details">' . $proposition->getName() . '</div>';

              echo '<div>';
                // Jours d'ouverture
                echo '<div class="zone_ouverture_mes_choix">';
                  $explodedOpened = explode(";", $proposition->getOpened());
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

                // Prix
                if (!empty($proposition->getMin_price()) AND !empty($proposition->getMax_price()))
                {
                  echo '<div class="zone_price_details">';
                    if ($proposition->getMin_price() == $proposition->getMax_price())
                      echo '<div class="price_details">Prix ~ ' . $proposition->getMin_price() . '€</div>';
                    else
                      echo '<div class="price_details">Prix ' . $proposition->getMin_price() . ' - ' . $proposition->getMax_price() . '€</div>';
                  echo '</div>';
                }

                // Lieu
                echo '<span class="lieu_proposition"><img src="../../includes/icons/foodadvisor/location.png" alt="location" class="image_lieu_proposition" />' . $proposition->getLocation() . '</span>';

                // Nombre de participants
                if ($proposition->getNb_participants() == 1)
                  echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participant</span>';
                else
                  echo '<span class="horaire_proposition"><img src="../../includes/icons/foodadvisor/users.png" alt="users" class="image_lieu_proposition" />' . $proposition->getNb_participants() . ' participants</span>';
              echo '</div>';

              // Type de restaurant
              if (!empty($proposition->getTypes()))
              {
                echo '<div class="zone_types_details">';
                  $explodedTypes = explode(";", $proposition->getTypes());

                  foreach ($explodedTypes as $exploded)
                  {
                    if (!empty($exploded))
                      echo '<span class="horaire_proposition">' . $exploded . '</span>';
                  }
                echo '</div>';
              }

              if (!(empty($proposition->getPhone()) AND empty($proposition->getAvatar())))
              {
                echo '<div class="zone_caller_details">';
                  echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone_details" />';

                  // Numéro de téléphone
                  if (!empty($proposition->getPhone()))
                    echo '<div class="telephone_details">' . formatPhoneNumber($proposition->getPhone()) . '</div>';

                  // Avatar
                  if ($proposition->getDetermined() == "Y")
                  {
                    echo '<div class="zone_avatar_details">';
                      if (!empty($proposition->getAvatar()))
                        echo '<img src="../../includes/images/profil/avatars/' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller_details" />';
                      else
                        echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller_details" />';
                    echo '</div>';
                  }
                echo '</div>';
              }

              // Liens
              if (!empty($proposition->getWebsite()) OR !empty($proposition->getPlan()))
              {
                echo '<div class="zone_liens_details">';
                  if (!empty($proposition->getWebsite()))
                  {
                    echo '<a href="' . $proposition->getWebsite() . '" target="_blank">';
                      echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Site web" class="icone_lien_details" />';
                    echo '</a>';
                  }

                  if (!empty($proposition->getPlan()))
                  {
                    echo '<a href="' . $proposition->getPlan() . '" target="_blank">';
                      echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Plan" class="icone_lien_details" />';
                    echo '</a>';
                  }
                echo '</div>';
              }

              // Bouton réservation / annulation
              if ($actions["reserver"] == true)
              {
                // On vérifie si on participe à chacun des restaurants pour pouvoir réserver
                $participe = false;

                foreach ($proposition->getDetails() as $detailsUser)
                {
                  if ($_SESSION['user']['identifiant'] == $detailsUser['identifiant'])
                  {
                    $participe = true;
                    break;
                  }
                }

                if ($participe == true)
                {
                  echo '<div class="zone_reservation">';
                    echo '<form method="post" action="foodadvisor.php?id=' . $proposition->getId_restaurant() . '&action=doReserver">';
                      echo '<input type="submit" name="reserve" value="J\'ai réservé !" class="bouton_reserver_details"/>';
                    echo '</form>';
                  echo '</div>';
                }
              }

              if ($proposition->getDetermined() == "Y" AND ($proposition->getReserved() == "Y" OR $actions["annuler_reserver"] == true))
              {
                echo '<div class="zone_reservation">';
                  if ($proposition->getReserved() == "Y")
                    echo '<div class="reserved_details">Réservé !</div>';

                  if ($actions["annuler_reserver"] == true)
                  {
                    echo '<form method="post" action="foodadvisor.php?delete_id=' . $proposition->getId_restaurant() . '&action=doAnnulerReserver">';
                      echo '<input type="submit" name="unreserve" value="Annuler la réservation" class="bouton_reserver_details" style="margin-top: 10px;"/>';
                    echo '</form>';
                  }
                echo '</div>';
              }
            echo '</div>';

            // Détails utilisateurs
            echo '<div class="zone_details_proposition_right">';
              echo '<div class="titre_details" style="margin-top: -10px;"><img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="logo_titre_section" />Les participants</div>';

              // Bouton fermeture
              echo '<a id="fermer_details_' . $proposition->getId_restaurant() . '" class="close_details fermerDetails"><img src="../../includes/icons/common/close_grey.png" alt="close_grey" title="Fermer" class="close_img" /></a>';

              // Transports et horaires
              foreach ($proposition->getDetails() as $detailsUser)
              {
                echo '<div class="zone_details_user_top">';
                  // Avatar
                  if (!empty($detailsUser['avatar']))
                    echo '<img src="../../includes/images/profil/avatars/' . $detailsUser['avatar'] . '" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_details" />';
                  else
                    echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_details" />';

                  // Pseudo
                  echo '<div class="pseudo_details">' . $detailsUser['pseudo'] . '</div>';

                  // Transports
                  if (!empty($detailsUser['transports']))
                  {
                    $explodedTransports = explode(";", $detailsUser['transports']);

                    echo '<div class="zone_details_transports">';
                      foreach ($explodedTransports as $transport)
                      {
                        switch ($transport)
                        {
                          case "F":
                            echo '<img src="../../includes/icons/foodadvisor/feet.png" alt="feet" class="icone_details" />';
                            break;

                          case "B":
                            echo '<img src="../../includes/icons/foodadvisor/bike.png" alt="bike" class="icone_details" />';
                            break;

                          case "T":
                            echo '<img src="../../includes/icons/foodadvisor/tram.png" alt="tram" class="icone_details" />';
                            break;

                          case "C":
                            echo '<img src="../../includes/icons/foodadvisor/car.png" alt="car" class="icone_details" />';
                            break;

                          default:
                            break;
                        }
                      }
                    echo '</div>';
                  }

                  // Horaires
                  if (!empty($detailsUser['horaire']))
                    echo '<div class="horaire_details">' . formatTimeForDisplayLight($detailsUser['horaire']) . '</div>';
                echo '</div>';
              }

              echo '<div class="titre_details" style="margin-top: 40px;"><img src="../../includes/icons/foodadvisor/menu_grey.png" alt="menu_grey" class="logo_titre_section" />Les menus proposés</div>';

              // Menus
              echo '<div class="zone_details_user_bottom">';
                $menuPresent = false;

                foreach ($proposition->getDetails() as $detailsUser)
                {
                  if ($detailsUser['menu'] != ";;;")
                  {
                    $menuPresent = true;
                    list($entree, $plat, $dessert) = explode(";", $detailsUser['menu']);

                    echo '<div class="zone_details_user_menu">';
                      // Avatar
                      if (!empty($detailsUser['avatar']))
                        echo '<img src="../../includes/images/profil/avatars/' . $detailsUser['avatar'] . '" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_menus" />';
                      else
                        echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_menus" />';

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
                }

                if ($menuPresent == false)
                  echo '<div class="no_menu_details">Pas de menus proposés pour ce choix.</div>';
              echo '</div>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
      else
        break;
    }
  }
?>
