<?php
  /*****************************/
  /* Zone de saisie de recette */
  /*****************************/
  echo '<div id="zone_add_recipe" class="fond_saisie_recette">';
    echo '<div class="zone_saisie_recette">';
      // Titre
      echo '<div class="titre_saisie_recette">Ajouter une recette</div>';

      // Bouton fermeture
      echo '<a id="fermerRecette" class="zone_close"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      echo '<form method="post" action="cookingbox.php?action=doAjouterRecette" enctype="multipart/form-data" class="form_saisie_recette">';
        echo '<input type="hidden" name="id_recipe" value="" />';

        // Zone saisie image
        echo '<div class="zone_recette_left">';
          // Saisie image
          echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

          echo '<div class="zone_parcourir_image">';
            echo '<div class="symbole_saisie_image">+</div>';
            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieRecette" required />';
          echo '</div>';

          echo '<div class="mask_image">';
            echo '<img id="image_recette" alt="" class="image" />';
          echo '</div>';

          // Bouton d'ajout
          echo '<input type="submit" name="insert_recipe" value="Ajouter" class="saisie_bouton" />';
        echo '</div>';

        // Zone saisie infos
        echo '<div class="zone_recette_right">';
          // Année
          echo '<select name="year_recipe" id="saisie_annee" class="saisie_annee_semaine" required>';
            echo '<option value="" hidden>Année</option>';

            foreach ($listeSemaines as $year => $week)
            {
              if ($year == $_SESSION['save']['year_recipe'])
                echo '<option value="' . $year . '" selected>' . $year . '</option>';
              else
                echo '<option value="' . $year . '">' . $year . '</option>';
            }
          echo '</select>';

          // Semaine (en cas d'erreur)
          if ((isset($_SESSION['save']['year_recipe']) AND !empty($_SESSION['save']['year_recipe']))
          AND (isset($_SESSION['save']['week_recipe']) AND !empty($_SESSION['save']['week_recipe'])))
          {
            echo '<select name="week_recipe" id="saisie_semaine" class="saisie_annee_semaine" required>';
              echo '<option value="" hidden>Semaine</option>';

              foreach ($listeSemaines[$_SESSION['save']['year_recipe']] as $week)
              {
                if ($week == $_SESSION['save']['week_recipe'])
                  echo '<option value="' . $week . '" selected>' . $week . '</option>';
                else
                  echo '<option value="' . $week . '">' . $week . '</option>';
              }
            echo '</select>';
          }

          // Nom de la recette
          if (isset($_SESSION['save']['name_recipe']) AND !empty($_SESSION['save']['name_recipe']))
            echo '<input type="text" name="name_recipe" value="' . $_SESSION['save']['name_recipe'] . '" placeholder="Nom de la recette" id="saisie_nom" class="saisie_nom_recette short" required />';
          else
            echo '<input type="text" name="name_recipe" value="" placeholder="Nom de la recette" id="saisie_nom" class="saisie_nom_recette" required />';

          // Ingrédients
          echo '<div id="zone_ingredients">';
            // Titre
            echo '<div class="sous_titre_1">Ingredients</div>';

            // Lien ajout ingrédient
            echo '<a id="addIngredient" class="bouton_ingredient">';
              echo '<span class="fond_plus">+</span>';
              echo 'Ajouter un ingrédient';
            echo '</a>';

            $unites = array('sans', 'g', 'kg', 'ml', 'cl', 'L');

            // Ingrédients (en cas d'erreur)
            if ((isset($_SESSION['save']['ingredients'])           AND !empty($_SESSION['save']['ingredients']))
            AND (isset($_SESSION['save']['quantites_ingredients']) AND !empty($_SESSION['save']['quantites_ingredients']))
            AND (isset($_SESSION['save']['unites_ingredients'])    AND !empty($_SESSION['save']['unites_ingredients'])))
            {
              $i = 0;

              foreach ($_SESSION['save']['ingredients']  as $key => $ingredient)
              {
                if (!empty($ingredient))
                {
                  $i++;

                  echo '<div class="zone_ingredient">';
                    // Ingrédient
                    echo '<input type="text" placeholder="Ingrédient" value="' . $ingredient . '" id="ingredient_' . $i . '" name="ingredients[' . $i . ']" class="input_ingredient colored saisieIngredient" />';

                    // Quantité
                    echo '<input type="text" placeholder="Quantité" value="' . $_SESSION['save']['quantites_ingredients'][$key] . '" id="quantite_ingredient_' . $i . '" name="quantites_ingredients[' . $i . ']" class="input_quantite" />';

                    // Unité
                    echo '<select id="unite_ingredient_' . $i . '" name="unites_ingredients[' . $i . ']" class="select_unite">';
                      echo '<option value="" hidden>Unité</option>';

                      foreach ($unites as $unite)
                      {
                        if ($unite == $_SESSION['save']['unites_ingredients'][$key])
                          echo '<option value="' . $unite . '" selected>' . $unite . '</option>';
                        else
                          echo '<option value="' . $unite . '">' . $unite . '</option>';
                      }
                    echo '</select>';
                  echo '</div>';
                }
              }

              // Champs saisie ingrédients supplémentaires si < 4
              if ($i > 0 AND $i < 4)
              {
                for ($j = $i + 1; $j <= 4; $j++)
                {
                  echo '<div class="zone_ingredient">';
                    // Ingrédient
                    echo '<input type="text" placeholder="Ingrédient" value="" id="ingredient_' . $j . '" name="ingredients[' . $j . ']" class="input_ingredient saisieIngredient" />';

                    // Quantité
                    echo '<input type="text" placeholder="Quantité" value="" id="quantite_ingredient_' . $j . '" name="quantites_ingredients[' . $j . ']" class="input_quantite" />';

                    // Unité
                    echo '<select id="unite_ingredient_' . $j . '" name="unites_ingredients[' . $j . ']" class="select_unite">';
                      echo '<option value="" hidden>Unité</option>';

                      foreach ($unites as $unite)
                      {
                        echo '<option value="' . $unite . '">' . $unite . '</option>';
                      }
                    echo '</select>';
                  echo '</div>';
                }
              }
            }
            // Zones initiales ingrédients
            else
            {
              for ($i = 1; $i <= 4; $i++)
              {
                echo '<div class="zone_ingredient">';
                  // Ingrédient
                  echo '<input type="text" placeholder="Ingrédient" value="" id="ingredient_' . $i . '" name="ingredients[' . $i . ']" class="input_ingredient saisieIngredient" />';

                  // Quantité
                  echo '<input type="text" placeholder="Quantité" value="" id="quantite_ingredient_' . $i . '" name="quantites_ingredients[' . $i . ']" class="input_quantite" />';

                  // Unité
                  echo '<select id="unite_ingredient_' . $i . '" name="unites_ingredients[' . $i . ']" class="select_unite">';
                    echo '<option value="" hidden>Unité</option>';

                    foreach ($unites as $unite)
                    {
                      echo '<option value="' . $unite . '">' . $unite . '</option>';
                    }
                  echo '</select>';
                echo '</div>';
              }
            }
          echo '</div>';

          // Préparation & remarques
          echo '<div class="zones_preparation">';
            // Préparation
            echo '<div class="zone_preparation margin_right_10">';
              // Titre
              echo '<div class="sous_titre_2">Préparation</div>';

              // Saisie
              if (isset($_SESSION['save']['preparation']) AND !empty($_SESSION['save']['preparation']))
                echo '<textarea placeholder="Préparation" name="preparation" id="saisie_preparation" class="textarea_saisie">' . $_SESSION['save']['preparation'] . '</textarea>';
              else
                echo '<textarea placeholder="Préparation" name="preparation" id="saisie_preparation" class="textarea_saisie"></textarea>';
            echo '</div>';

            // Remarques & astuces
            echo '<div class="zone_preparation">';
              // Titre
              echo '<div class="sous_titre_2">Remarques & astuces</div>';

              // Saisie
              if (isset($_SESSION['save']['remarks']) AND !empty($_SESSION['save']['remarks']))
                echo '<textarea placeholder="Remarques & astuces" name="remarks" id="saisie_remarques" class="textarea_saisie">' . $_SESSION['save']['remarks'] . '</textarea>';
              else
                echo '<textarea placeholder="Remarques & astuces" name="remarks" id="saisie_remarques" class="textarea_saisie"></textarea>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
