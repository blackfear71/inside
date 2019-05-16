<?php
  /************************************/
  /*** Zone de saisie de restaurant ***/
  /************************************/
  echo '<div id="zone_add_restaurant" style="display: none;" class="fond_saisie_restaurant">';
    echo '<div class="zone_saisie_restaurant">';
      // Titre
      echo '<div class="titre_saisie_restaurant">Ajouter un restaurant</div>';

      // Bouton fermeture
      echo '<a id="fermerRestaurant" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      // Saisie restaurant
      echo '<form method="post" action="restaurants.php?action=doAjouter" enctype="multipart/form-data" class="form_saisie_restaurant">';
        // Photo & numéro
        echo '<div class="zone_saisie_left">';
          // Photo
          echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

          echo '<span class="zone_parcourir_restaurant_saisie">+<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image_restaurant" class="bouton_parcourir_restaurant_saisie" onchange="loadFile(event, \'img_restaurant_saisie\')" /></span>';

          echo '<div class="mask_saisie_restaurant">';
            echo '<img id="img_restaurant_saisie" alt="" class="image_saisie_restaurant" />';
          echo '</div>';

          // Jours d'ouverture
          $semaine = array("Lu" => "lundi",
                           "Ma" => "mardi",
                           "Me" => "mercredi",
                           "Je" => "jeudi",
                           "Ve" => "vendredi");

          $i = 0;

          foreach ($semaine as $j => $jour)
          {
            if (empty($_SESSION['save']['ouverture_restaurant']) OR isset($_SESSION['save']['ouverture_restaurant'][$i]))
            {
              echo '<input type="checkbox" id="saisie_checkbox_ouverture_' . $jour . '" name="ouverture_restaurant[' . $i . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" checked />';
              echo '<label for="saisie_checkbox_ouverture_' . $jour . '" id="saisie_label_ouverture_' . $jour . '" class="label_jour_checked checkDay">' . $j . '</label>';
            }
            else
            {
              echo '<input type="checkbox" id="saisie_checkbox_ouverture_' . $jour . '" name="ouverture_restaurant[' . $i . ']' . $jour . '" value="' . $j . '" class="checkbox_jour" />';
              echo '<label for="saisie_checkbox_ouverture_' . $jour . '" id="saisie_label_ouverture_' . $jour . '" class="label_jour checkDay">' . $j . '</label>';
            }

            $i++;
          }

          // Prix min et max
          echo '<div class="zone_saisie_prix">';
            echo '<input type="text" name="prix_min_restaurant" value="' . $_SESSION['save']['prix_min'] . '" maxlength="5" placeholder="Prix min." class="saisie_prix_min_restaurant" />';
            echo '<input type="text" name="prix_max_restaurant" value="' . $_SESSION['save']['prix_max'] . '" maxlength="5" placeholder="Prix max." class="saisie_prix_max_restaurant" />';
          echo '</div>';
        echo '</div>';

        // Nom, lieu, action et types
        echo '<div class="zone_saisie_right">';
          // Nom
          if ($_SESSION['save']['location'] == "other_location")
            echo '<input type="text" name="name_restaurant" value="' . $_SESSION['save']['name_restaurant'] . '" placeholder="Nom du restaurant" id="saisie_nom" class="saisie_nom_restaurant" style="width: calc(34% - 77px);" required />';
          else
            echo '<input type="text" name="name_restaurant" value="' . $_SESSION['save']['name_restaurant'] . '" placeholder="Nom du restaurant" id="saisie_nom" class="saisie_nom_restaurant" required />';

          // Lieu
          if ($_SESSION['save']['location'] == "other_location")
            echo '<select name="location" id="saisie_location" onchange="afficherOther(\'saisie_location\', \'saisie_other_location\', \'saisie_nom\');" class="saisie_lieu" style="width: calc(33% - 100px);" required>';
          else
            echo '<select name="location" id="saisie_location" onchange="afficherOther(\'saisie_location\', \'saisie_other_location\', \'saisie_nom\');" class="saisie_lieu" required>';
              echo '<option value="" hidden>Choisissez...</option>';

              foreach ($listeLieux as $lieu)
              {
                if ($lieu == $_SESSION['save']['location'])
                  echo '<option value="' . $lieu . '" selected>' . $lieu . '</option>';
                else
                  echo '<option value="' . $lieu . '">' . $lieu . '</option>';
              }

              if ($_SESSION['save']['location'] == "other_location")
                echo '<option value="other_location" selected>Autre</option>';
              else
                echo '<option value="other_location">Autre</option>';
            echo '</select>';

          // Lieu "Autre"
          if ($_SESSION['save']['location'] == "other_location")
            echo '<input type="text" name="saisie_other_location" value="' . $_SESSION['save']['saisie_other_location'] . '" placeholder="Lieu personnalisé" maxlength="255" id="saisie_other_location" class="saisie_lieu_autre_restaurant" style="width: calc(33% - 100px);" />';
          else
            echo '<input type="text" name="saisie_other_location" placeholder="Lieu personnalisé" maxlength="255" id="saisie_other_location" class="saisie_lieu_autre_restaurant" style="display: none;" />';

          // Bouton d'ajout
          echo '<input type="submit" name="insert_restaurant" value="Ajouter" class="saisie_bouton" />';

          // Types
          echo '<div id="types_restaurants">';
            echo '<a id="addType" class="bouton_type_autre"><span class="fond_plus">+</span>Autre</a>';

            $i = 0;

            // Types existants
            foreach ($listeTypes as $type)
            {
              $id_type    = "type_" . formatId($type);
              $checked    = false;

              if (!empty($_SESSION['save']['types_restaurants']))
              {
                foreach ($_SESSION['save']['types_restaurants'] as $saved_types)
                {
                  if ($saved_types == $type)
                  {
                    $checked = true;
                    break;
                  }
                }
              }

              if ($checked == true)
              {
                echo '<div id="bouton_' . $id_type . '" class="switch_types bouton_checked">';
                  echo '<input id="' . $id_type . '" type="checkbox" value="' . $type . '" name="types_restaurants[' . $i . ']" checked />';
                  echo '<label for="' . $id_type . '" class="label_switch checkType">' . $type . '</label>';
                echo '</div>';
              }
              else
              {
                echo '<div id="bouton_' . $id_type . '" class="switch_types">';
                  echo '<input id="' . $id_type . '" type="checkbox" value="' . $type . '" name="types_restaurants[' . $i . ']" />';
                  echo '<label for="' . $id_type . '" class="label_switch checkType">' . $type . '</label>';
                echo '</div>';
              }

              $i++;
            }

            // Types personnalisés (sauvegardés en cas d'erreur)
            if (!empty($_SESSION['save']['types_restaurants']))
            {
              $j = $i;

              foreach ($_SESSION['save']['types_restaurants'] as $saved_types)
              {
                $custom_type = true;

                foreach ($listeTypes as $type)
                {
                  if ($saved_types == $type)
                  {
                    $custom_type = false;
                    break;
                  }
                }

                if ($custom_type == true)
                {
                  $id_custom_type    = "type_" . formatId($saved_types);
                  $label_custom_type = "label_" . formatId($saved_types);

                  echo '<input type="text" placeholder="Type" value="' . $saved_types . '" id="' . $id_custom_type . '" name="types_restaurants[' . $j . ']" oninput="changeTypeColor(\'' . $id_custom_type . '\')" class="type_other filled" />';

                  $j++;
                }
              }
            }
          echo '</div>';
        echo '</div>';

        // Téléphone, site web, plan et description
        echo '<div class="zone_saisie_under">';
          // Numéro
          echo '<input type="text" name="phone_restaurant" value="' . $_SESSION['save']['phone_restaurant'] . '" maxlength="10" placeholder="Téléphone" class="saisie_telephone_restaurant" />';

          // Site web
          echo '<input type="text" name="website_restaurant" value="' . $_SESSION['save']['website_restaurant'] . '" placeholder="Site web" class="saisie_lien_restaurant" />';

          // Plan
          echo '<input type="text" name="plan_restaurant" value="' . $_SESSION['save']['plan_restaurant'] . '" placeholder="Plan" class="saisie_lien_restaurant" />';

          // Description
          echo '<textarea placeholder="Description" name="description_restaurant" class="textarea_saisie_description_restaurant">' . $_SESSION['save']['description_restaurant'] . '</textarea>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
