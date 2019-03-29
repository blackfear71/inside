<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "FA";
      $style_head   = "styleFA.css";
      $script_head  = "scriptFA.js";
      $chat_head    = true;
      $masonry_head = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Les enfants ! À table !";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          /**********************/
          /*** Liens & saisie ***/
          /**********************/
          echo '<div class="zone_liens_saisie">';
            // Saisie utilisateur
            echo '<a onclick="afficherMasquerSaisieChoix(\'zone_saisie_choix\', \'zone_marge_choix\');" title="Proposer où manger" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/food_advisor.png" alt="food_advisor" class="image_lien" style="border-radius: 50%;" /></div>';
              echo '<div class="zone_texte_lien">Proposer où manger</div>';
            echo '</a>';

            // Restaurants
            echo '<a href="restaurants.php?action=goConsulter" title="Les restaurants" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Les restaurants</div>';
            echo '</a>';
          echo '</div>';

          // Zone de saisie de choix
          echo '<div id="zone_saisie_choix" style="display: none;" class="fond_saisie_restaurant">';
            echo '<div id="zone_marge_choix" class="zone_saisie_choix">';
              // Titre
              echo '<div class="titre_saisie_restaurant">Proposer où manger</div>';

              // Bouton fermeture
              echo '<a onclick="afficherMasquerSaisieChoix(\'zone_saisie_choix\', \'zone_marge_choix\');" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

              // Saisie restaurant initale
              echo '<form method="post" action="foodadvisor.php?action=doAjouter" class="form_choices">';
                echo '<div id="zone_choix">';
                  // Titre
                  echo '<div class="titre_choix">Proposition 1</div>';

                  // Choix restaurant
                  echo '<div id="zone_listbox_restaurant_1" class="zone_listbox">';
                    echo '<a id="choix_restaurant_1" onclick="afficherMasquer(\'choix_restaurant_1\'); afficherListboxLieux(\'zone_listbox_restaurant_1\');" class="bouton_choix">';
                      echo '<span class="fond_plus">+</span>';
                      echo 'Restaurant';
                    echo '</a>';
                  echo '</div>';

                  // Choix horaire
                  echo '<div id="zone_listbox_horaire_1" class="zone_listbox">';
                    echo '<a id="choix_horaire_1" onclick="afficherMasquer(\'choix_horaire_1\'); afficherListboxHoraires(\'zone_listbox_horaire_1\', \'choix_horaire_1\', \'create\', \'\');" class="bouton_choix">';
                      echo '<span class="fond_plus">+</span>';
                      echo 'Horaire';
                    echo '</a>';
                  echo '</div>';

                  // Choix transports
                  echo '<div id="zone_checkbox_transports_1" class="zone_listbox">';
                    echo '<a id="choix_transports_1" onclick="afficherMasquer(\'choix_transports_1\'); afficherCheckboxTransports(\'zone_checkbox_transports_1\');" class="bouton_choix">';
                      echo '<span class="fond_plus">+</span>';
                      echo 'Transports';
                    echo '</a>';
                  echo '</div>';

                  // Menu
                  echo '<div id="zone_saisie_menu_1" class="zone_listbox">';
                    echo '<a id="choix_menu_1" onclick="afficherMasquer(\'choix_menu_1\'); afficherSaisieMenu(\'zone_saisie_menu_1\');" class="bouton_choix">';
                      echo '<span class="fond_plus">+</span>';
                      echo 'Menu';
                    echo '</a>';
                  echo '</div>';

                  // Séparation
                  echo '<div class="separation_choix"></div>';
                echo '</div>';

                echo '<div class="zone_boutons">';
                  // Ajout autre saisie
                  echo '<a id="saisie_autre_choix" onclick="addChoice(\'zone_choix\', \'zone_marge_choix\');" class="bouton_autre_choix">';
                    echo '<span class="fond_plus">+</span>';
                    echo 'Ajouter une autre proposition';
                  echo '</a>';

                  // Validation
                  echo '<input type="submit" name="submit_choices" value="Soumettre les propositions" class="bouton_validation_choix" />';
                echo '</div>';
              echo '</form>';
            echo '</div>';
          echo '</div>';

          /***********************************/
          /*** Détails de la détermination ***/
          /***********************************/
          if (!empty($propositions))
          {
            foreach($propositions as $proposition)
            {
              if ($proposition->getDetermined() == "Y" OR $proposition->getClassement() == 1)
              {
                echo '<div id="zone_details_determined_' . $proposition->getId_restaurant() . '" style="display: none;" class="fond_saisie_restaurant">';
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

                      echo '<div class="zone_icones_mon_choix">';
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
                              echo '<div class="price_details">Prix moy. ' . $proposition->getMin_price() . '€</div>';
                            else
                            {
                              echo '<div class="price_details">Prix min. ' . $proposition->getMin_price() . '€</div>';
                              echo '<div class="price_details">Prix max. ' . $proposition->getMax_price() . '€</div>';
                            }
                          echo '</div>';
                        }

                        // Lieu
                        echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

                        // Nombre de participants
                        if ($proposition->getNb_participants() == 1)
                          echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
                        else
                          echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
                      echo '</div>';

                      // Type de restaurant
                      if (!empty($proposition->getTypes()))
                      {
                        $explodedTypes = explode(";", $proposition->getTypes());

                        foreach ($explodedTypes as $exploded)
                        {
                          if (!empty($exploded))
                            echo '<span class="horaire_proposition">' . $exploded . '</span>';
                        }
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
                                echo '<img src="../../includes/icons/common/default.png' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller_details" />';
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
                    echo '</div>';

                    // Détails utilisateurs
                    echo '<div class="zone_details_proposition_right">';
                      echo '<div class="titre_details" style="margin-top: -10px;">Les participants</div>';

                      // Bouton fermeture
                      echo '<a onclick="afficherMasquer(\'zone_details_determined_' . $proposition->getId_restaurant() . '\');" class="close_details"><img src="../../includes/icons/common/close_black.png" alt="close_black" title="Fermer" class="close_img" /></a>';

                      // Transports et horaires
                      foreach ($proposition->getDetails() as $detailsUser)
                      {
                        echo '<div class="zone_details_user_top">';
                          // Avatar
                          if (!empty($detailsUser['avatar']))
                            echo '<img src="../../includes/images/profil/avatars/' . $detailsUser['avatar'] . '" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_details" />';
                          else
                            echo '<img src="../../includes/icons/common/default.png' . $detailsUser['avatar'] . '" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_details" />';

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

                      echo '<div class="titre_details" style="margin-top: 40px;">Les menus proposés</div>';

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
                                echo '<img src="../../includes/icons/common/default.png' . $detailsUser['avatar'] . '" alt="avatar" title="' . $detailsUser['pseudo'] . '" class="avatar_menus" />';

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

          echo '<div class="zone_propositions_determination" style="display: none;">';
            /****************************/
            /*** Propositions du jour ***/
            /****************************/
            echo '<div class="titre_section">';
              echo 'Les propositions du jour';

              echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page"><img src="../../includes/icons/foodadvisor/refresh.png" class="image_refresh" /></a>';

              if (!empty($propositions) AND date("H") < 13)
              {
                echo '<form method="post" action="foodadvisor.php?action=doDeterminer">';
                  echo '<input type="submit" name="determiner" value="Lancer la détermination" class="bouton_determination" />';
                echo '</form>';
              }
            echo '</div>';

            if (!empty($propositions))
            {
              echo '<div class="zone_propositions">';
                foreach ($propositions as $proposition)
                {
                  if ($proposition->getClassement() == 1)
                  {
                    if ($proposition->getDetermined() == "Y")
                      echo '<div class="zone_proposition_determined">';
                    else
                      echo '<div class="zone_proposition_top">';
                      // Lien détails
                      if ($proposition->getDetermined() == "Y")
                        echo '<a onclick="afficherMasquer(\'zone_details_determined_' . $proposition->getId_restaurant() . '\');" class="lien_details_determined" title="Plus de détails"><span class="lien_plus">+</span></a>';
                      else
                        echo '<a onclick="afficherMasquer(\'zone_details_determined_' . $proposition->getId_restaurant() . '\');" class="lien_details_top" title="Plus de détails"><span class="lien_plus">+</span></a>';

                      // Image + lien
                      echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_proposition_top">';
                        if (!empty($proposition->getPicture()))
                          echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_proposition_top" />';
                        else
                          echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_proposition_top" />';
                      echo '</a>';

                      if ($proposition->getDetermined() == "Y")
                      {
                        // Nom du restaurant
                        echo '<div class="nom_proposition_determined">' . $proposition->getName() . '</div>';

                        echo '<div class="zone_icones_mon_choix">';
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

                          // Lieu
                          echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

                          // Nombre de participants
                          if ($proposition->getNb_participants() == 1)
                            echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
                          else
                            echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
                        echo '</div>';

                        echo '<div class="caller">';
                          echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

                          // Avatar
                          if (!empty($proposition->getAvatar()))
                            echo '<img src="../../includes/images/profil/avatars/' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller" />';
                          else
                            echo '<img src="../../includes/icons/common/default.png' . $proposition->getAvatar() . '" alt="avatar" title="' . $proposition->getPseudo() . '" class="avatar_caller" />';

                          // Numéro de téléphone
                          if (!empty($proposition->getPhone()))
                            echo formatPhoneNumber($proposition->getPhone());
                        echo '</div>';
                      }
                      else
                      {
                        // Nom du restaurant
                        echo '<div class="nom_mon_choix">' . $proposition->getName() . '</div>';

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
                                echo '<div class="jour_non_fa" style="background-color: white;">' . $semaine_short[$i] . '</div>';
                            }

                            $i++;
                          }
                        echo '</div>';

                        // Lieu
                        echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

                        // Nombre de participants
                        if ($proposition->getNb_participants() == 1)
                          echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
                        else
                          echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
                      }
                    echo '</div>';
                  }
                  else
                  {
                    echo '<div class="zone_proposition">';
                      // Image + lien
                      echo '<a href="restaurants.php?action=goConsulter&anchor=' . $proposition->getId_restaurant() . '" class="lien_mon_choix">';
                        if (!empty($proposition->getPicture()))
                          echo '<img src="../../includes/images/foodadvisor/' . $proposition->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
                        else
                          echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
                      echo '</a>';

                      // Nom du restaurant
                      echo '<div class="nom_mon_choix">' . $proposition->getName() . '</div>';

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

                      // Lieu
                      echo '<span class="lieu_proposition">' . $proposition->getLocation() . '</span>';

                      // Nombre de participants
                      if ($proposition->getNb_participants() == 1)
                        echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participant</span>';
                      else
                        echo '<span class="horaire_proposition">' . $proposition->getNb_participants() . ' participants</span>';
                    echo '</div>';
                  }
                }
              echo '</div>';
            }
            else
              echo '<div class="empty">Pas encore de propositions pour aujourd\'hui !</div>';

            /*************************/
            /*** Choix utilisateur ***/
            /*************************/
            echo '<div class="titre_section">Mes choix du jour</div>';

            if (!empty($mesChoix))
            {
              echo '<div class="zone_propositions">';
                foreach ($mesChoix as $monChoix)
                {
                  /*********************************************/
                  /* Visualisation normale (sans modification) */
                  /*********************************************/
                  echo '<div class="zone_proposition" id="modifier_choix_2[' . $monChoix->getId() . ']">';
                    if (date("H") < 13)
                    {
                      // Modification
                      echo '<a onclick="afficherMasquer(\'modifier_choix[' . $monChoix->getId() . ']\'); afficherMasquer(\'modifier_choix_2[' . $monChoix->getId() . ']\'); initMasonry();" title="Modifier le choix" class="icone_modify_choix"></a>';

                      // Suppression
                      echo '<form method="post" action="foodadvisor.php?delete_id=' . $monChoix->getId() . '&action=doSupprimer" onclick="if(!confirm(\'Supprimer ce choix ?\')) return false;">';
                        echo '<input type="submit" name="delete_choice" value="" title="Supprimer le choix" class="icon_delete_choix" />';
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
                      echo '<span class="lieu_mon_choix">' . $monChoix->getLocation() . '</span>';

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
                    list($entree, $plat, $dessert) = explode(";", $monChoix->getMenu());

                    if (empty($entree) AND empty($plat) AND empty($dessert))
                      echo '<div class="no_menu_mes_choix">Pas de menu saisi</div>';

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

                  /***************************/
                  /* Caché pour modification */
                  /***************************/
                  echo '<div class="zone_proposition" id="modifier_choix[' . $monChoix->getId() . ']" style="display: none;">';
                    echo '<form method="post" action="foodadvisor.php?action=doModifier&update_id=' . $monChoix->getId() . '">';
                      // Validation modification
                      echo '<input type="submit" name="modify_choix_' . $monChoix->getId() . '" value="" title="Valider" class="icon_validate_choix" />';

                      // Annulation modification
                      echo '<a onclick="afficherMasquer(\'modifier_choix[' . $monChoix->getId() . ']\'); afficherMasquer(\'modifier_choix_2[' . $monChoix->getId() . ']\'); initMasonry();" title="Annuler" class="icone_cancel_choix"></a>';

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
                            echo '<a id="update_horaire_' . $monChoix->getId() . '" onclick="afficherMasquer(\'update_horaire_' . $monChoix->getId() . '\'); afficherListboxHoraires(\'zone_update_listbox_horaire_' . $monChoix->getId() . '\', \'update_horaire_' . $monChoix->getId() . '\', \'update\', \'' . $monChoix->getId() . '\');" class="bouton_choix_update" style="display: none">';
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
                                if ($j == substr($monChoix->getTime(), 2, 2))
                                  echo '<option value="0' . $j . '" selected>0' . $j . '</option>';
                                else
                                  echo '<option value="' . $j*15 . '">' . $j*15 . '</option>';
                              }
                            echo '</select>';

                            echo '<a id="' . $id_annuler . '" onclick="cacherListboxHoraires(\'' . $id_select_h . '\',\'' . $id_select_m . '\', \'' . $id_annuler . '\', \'update_horaire_' . $monChoix->getId() . '\')" class="bouton_annuler_update">Annuler</a>';
                          echo '</div>';
                        }
                        else
                        {
                          echo '<div id="zone_update_listbox_horaire_' . $monChoix->getId() . '">';
                            echo '<a id="update_horaire_' . $monChoix->getId() . '" onclick="afficherMasquer(\'update_horaire_' . $monChoix->getId() . '\'); afficherListboxHoraires(\'zone_update_listbox_horaire_' . $monChoix->getId() . '\', \'update_horaire_' . $monChoix->getId() . '\', \'update\', \'' . $monChoix->getId() . '\');" class="bouton_choix_update">';
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
                        $id_label_f = 'label_feet_' . $monChoix->getId();
                        $id_check_b = 'checkbox_bike_' . $monChoix->getId();
                        $id_label_b = 'label_bike_' . $monChoix->getId();
                        $id_check_t = 'checkbox_tram_' . $monChoix->getId();
                        $id_label_t = 'label_tram_' . $monChoix->getId();
                        $id_check_c = 'checkbox_car_' . $monChoix->getId();
                        $id_label_c = 'label_car_' . $monChoix->getId();

                        if ($feet == true)
                        {
                          echo '<input type="checkbox" id="' . $id_check_f . '" name="' . $id_check_f . '" value="F" onchange="changeCheckedColor(\'' . $id_check_f . '\', \'' . $id_label_f . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 8px;" checked />';
                          echo '<label for="' . $id_check_f . '" id="' . $id_label_f . '" class="label_transport_update_checked" style="margin-right: 10px;"><img src="../../includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';
                        }
                        else
                        {
                          echo '<input type="checkbox" id="' . $id_check_f . '" name="' . $id_check_f . '" value="F" onchange="changeCheckedColor(\'' . $id_check_f . '\', \'' . $id_label_f . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 8px;" />';
                          echo '<label for="' . $id_check_f . '" id="' . $id_label_f . '" class="label_transport_update" style="margin-right: 10px;"><img src="../../includes/icons/foodadvisor/feet.png" alt="feet" title="A pieds" class="icone_checkbox" /></label>';
                        }

                        if ($bike == true)
                        {
                          echo '<input type="checkbox" id="' . $id_check_b . '" name="' . $id_check_b . '" value="B" onchange="changeCheckedColor(\'' . $id_check_b . '\', \'' . $id_label_b . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 9px;" checked />';
                          echo '<label for="' . $id_check_b . '" id="' . $id_label_b . '" class="label_transport_update_checked"><img src="../../includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';
                        }
                        else
                        {
                          echo '<input type="checkbox" id="' . $id_check_b . '" name="' . $id_check_b . '" value="B" onchange="changeCheckedColor(\'' . $id_check_b . '\', \'' . $id_label_b . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 9px;" />';
                          echo '<label for="' . $id_check_b . '" id="' . $id_label_b . '" class="label_transport_update"><img src="../../includes/icons/foodadvisor/bike.png" alt="bike" title="A vélo" class="icone_checkbox" /></label>';
                        }

                        if ($tram == true)
                        {
                          echo '<input type="checkbox" id="' . $id_check_t . '" name="' . $id_check_t . '" value="T" onchange="changeCheckedColor(\'' . $id_check_t . '\', \'' . $id_label_t . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 8px;" checked />';
                          echo '<label for="' . $id_check_t . '" id="' . $id_label_t . '" class="label_transport_update_checked" style="margin-right: 10px;"><img src="../../includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';
                        }
                        else
                        {
                          echo '<input type="checkbox" id="' . $id_check_t . '" name="' . $id_check_t . '" value="T" onchange="changeCheckedColor(\'' . $id_check_t . '\', \'' . $id_label_t . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 8px;" />';
                          echo '<label for="' . $id_check_t . '" id="' . $id_label_t . '" class="label_transport_update" style="margin-right: 10px;"><img src="../../includes/icons/foodadvisor/tram.png" alt="tram" title="En tram" class="icone_checkbox" /></label>';
                        }


                        if ($car == true)
                        {
                          echo '<input type="checkbox" id="' . $id_check_c . '" name="' . $id_check_c . '" value="C" onchange="changeCheckedColor(\'' . $id_check_c . '\', \'' . $id_label_c . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 9px;" checked />';
                          echo '<label for="' . $id_check_c . '" id="' . $id_label_c . '" class="label_transport_update_checked"><img src="../../includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
                        }
                        else
                        {
                          echo '<input type="checkbox" id="' . $id_check_c . '" name="' . $id_check_c . '" value="C" onchange="changeCheckedColor(\'' . $id_check_c . '\', \'' . $id_label_c . '\', \'label_transport_update_checked\', \'label_transport_update\');" class="checkbox_transport_update" style="margin-left: 9px;" />';
                          echo '<label for="' . $id_check_c . '" id="' . $id_label_c . '" class="label_transport_update"><img src="../../includes/icons/foodadvisor/car.png" alt="car" title="En voiture" class="icone_checkbox" /></label>';
                        }
                      echo '</div>';

                      // Menu
                      list($entree, $plat, $dessert) = explode(";", $monChoix->getMenu());

                      echo '<input type="text" value="' . $entree . '" placeholder="Entrée" name="update_entree_' . $monChoix->getId() . '" class="update_menu" />';
                      echo '<input type="text" value="' . $plat . '" placeholder="Plat" name="update_plat_' . $monChoix->getId() . '" class="update_menu" />';
                      echo '<input type="text" value="' . $dessert . '" placeholder="Dessert" name="update_dessert_' . $monChoix->getId() . '" class="update_menu" />';
                    echo '</form>';
                  echo '</div>';
                }
              echo '</div>';
            }
            else
              echo '<div class="empty">Pas de choix encore saisis pour aujourd\'hui !</div>';

            /**************************/
            /*** Historique semaine ***/
            /**************************/
            echo '<div class="titre_section">Le résumé de la semaine</div>';

            echo '<div class="zone_propositions">';
              foreach ($choixSemaine as $jour => $choixJour)
              {
                if (!empty($choixJour))
                {
                  echo '<div class="zone_proposition_top">';
                    // Jour
                    echo '<div class="jour_semaine">' . $jour . '</div>';

                    // Image + lien
                    echo '<a href="restaurants.php?action=goConsulter&anchor=' . $choixJour->getId_restaurant() . '" class="lien_mon_choix">';
                      if (!empty($choixJour->getPicture()))
                        echo '<img src="../../includes/images/foodadvisor/' . $choixJour->getPicture() . '" alt="restaurant" class="image_mon_choix" />';
                      else
                        echo '<img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_mon_choix" />';
                    echo '</a>';

                    // Nom du restaurant
                    echo '<div class="nom_mon_choix">' . $choixJour->getName() . '</div>';

                    echo '<div class="zone_icones_mon_choix">';
                      // Lieu
                      echo '<span class="lieu_proposition">' . $choixJour->getLocation() . '</span>';

                      // Nombre de participants
                      if ($choixJour->getNb_participants() == 1)
                        echo '<span class="horaire_proposition">' . $choixJour->getNb_participants() . ' participant</span>';
                      else
                        echo '<span class="horaire_proposition">' . $choixJour->getNb_participants() . ' participants</span>';
                    echo '</div>';

                    echo '<div class="caller">';
                      echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" class="icone_telephone" />';

                      // Avatar
                      if (!empty($choixJour->getAvatar()))
                        echo '<img src="../../includes/images/profil/avatars/' . $choixJour->getAvatar() . '" alt="avatar" title="' . $choixJour->getPseudo() . '" class="avatar_caller" />';
                      else
                        echo '<img src="../../includes/icons/common/default.png' . $choixJour->getAvatar() . '" alt="avatar" title="' . $choixJour->getPseudo() . '" class="avatar_caller" />';
                    echo '</div>';
                  echo '</div>';
                }
                else
                {
                  echo '<div class="zone_proposition_top">';
                    // Jour
                    echo '<div class="jour_semaine">' . $jour . '</div>';

                    // Pas de proposition
                    echo '<div class="no_proposal">Pas de proposition pour ce jour</div>';
                  echo '</div>';
                }
              }
            echo '</div>';
          echo '</div>';
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script type="text/javascript">
      // Récupération liste utilisateurs & identifiant pour le script
      var listLieux        = <?php echo $listeLieuxJson; ?>;
      var listeRestaurants = <?php echo $listeRestaurantsJson; ?>;
    </script>
  </body>
</html>
