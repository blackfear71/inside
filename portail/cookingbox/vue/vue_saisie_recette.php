<?php
  /*****************************/
  /* Zone de saisie de recette */
  /*****************************/
  echo '<div id="zone_add_recipe" class="fond_saisie_recette" style="display: block;">';
    echo '<div class="zone_saisie_recette">';
      // Titre
      echo '<div class="titre_saisie_recette">Ajouter une recette</div>';

      // Bouton fermeture
      echo '<a id="fermerRecette" class="zone_close"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

      echo '<form method="post" action="cookingbox.php?action=doAjouter" enctype="multipart/form-data" class="form_saisie_recette">';
        // Zone saisie image
        echo '<div class="zone_recette_left">';
          // Saisie image
          echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

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
          // Année et semaine
          echo '<select name="year_recipe" id="saisie_annee" class="saisie_annee_semaine" required>';
            echo '<option value="" hidden>Année</option>';

            foreach ($listeSemaines as $year => $week)
            {
              echo '<option value="' . $year . '">' . $year . '</option>';
            }
          echo '</select>';

          // Nom de la recette
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

            // Zones initiales ingrédients
            $unites = array('g', 'kg', 'ml', 'cl', 'L');

            for ($i = 1; $i <= 4; $i++)
            {
              echo '<div class="zone_ingredient">';
                // Ingrédient
                echo '<input type="text" placeholder="Ingrédient" value="" id="ingredient_' . $i . '" name="ingredient_' . $i . '" class="input_ingredient saisieIngredient" />';

                // Quantité
                echo '<input type="text" placeholder="Quantité" value="" id="quantite_ingredient_' . $i . '" name="quantite_ingredient_' . $i . '" class="input_quantite" />';

                // Unité
                echo '<select id="unite_ingredient_' . $i . '" name="unite_ingredient_' . $i . '" class="select_unite">';
                  echo '<option value="" hidden>Unité</option>';

                  foreach($unites as $unite)
                  {
                    echo '<option value="' . $unite . '">' . $unite . '</option>';
                  }
                echo '</select>';
              echo '</div>';
            }
          echo '</div>';

          // Préparation & remarques
          echo '<div class="zones_preparation">';
            // Préparation
            echo '<div class="zone_preparation margin_right">';
              // Titre
              echo '<div class="sous_titre_2">Préparation</div>';

              // Saisie
              echo '<textarea placeholder="Préparation" name="saisie_preparation" class="textarea_saisie"></textarea>';
            echo '</div>';

            // Remarques & astuces
            echo '<div class="zone_preparation">';
              // Titre
              echo '<div class="sous_titre_2">Remarques & astuces</div>';

              // Saisie
              echo '<textarea placeholder="Remarques & astuces" name="saisie_remarques" class="textarea_saisie"></textarea>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      echo '</form>';
    echo '</div>';
  echo '</div>';
?>
