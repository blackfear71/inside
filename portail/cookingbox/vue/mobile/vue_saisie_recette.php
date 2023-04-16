<?php
    /*****************************/
    /* Zone de saisie de recette */
    /*****************************/
    echo '<div id="zone_saisie_recette" class="fond_saisie">';
        echo '<form method="post" action="cookingbox.php?action=doAjouterRecette" enctype="multipart/form-data" class="form_saisie">';
            // Id recette (modification)
            echo '<input type="hidden" name="id_recipe" value="" />';

            // Titre
            echo '<div class="zone_titre_saisie">Ajouter une recette</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Titre
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">La photo</div>';
                    echo '</div>';

                    // Saisie image
                    echo '<div class="zone_image_saisie">';
                        echo '<div class="zone_parcourir_image">';
                            echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieRecette" required />';
                        echo '</div>';

                        echo '<div class="mask_image">';
                            echo '<img id="image_recette" alt="" class="image" />';
                        echo '</div>';
                    echo '</div>';

                    // Titre
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/cookingbox/recipe_grey.png" alt="recipe_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">La recette</div>';
                    echo '</div>';

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
                        // Sous-titre
                        echo '<div class="sous_titre_section">Les ingrédients</div>';

                        // Définition des unités
                        $unites = array('sans', 'g', 'kg', 'ml', 'cl', 'L', 'CC', 'CS');

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

                        // Lien ajout ingrédient
                        echo '<a id="ajoutIngredient" class="bouton_ingredient">';
                            echo '<span class="fond_plus">+</span>';
                            echo 'Ajouter un ingrédient';
                        echo '</a>';
                    echo '</div>';

                    // Préparation
                    echo '<div class="zone_preparation">';
                        // Sous-titre
                        echo '<div class="sous_titre_section">La préparation</div>';

                        // Saisie
                        if (isset($_SESSION['save']['preparation']) AND !empty($_SESSION['save']['preparation']))
                            echo '<textarea placeholder="Préparation" name="preparation" id="saisie_preparation" class="textarea_saisie">' . $_SESSION['save']['preparation'] . '</textarea>';
                        else
                            echo '<textarea placeholder="Préparation" name="preparation" id="saisie_preparation" class="textarea_saisie"></textarea>';
                    echo '</div>';

                    // Remarques
                    echo '<div class="zone_remarques">';
                        // Sous-titre
                        echo '<div class="sous_titre_section">Quelques remarques</div>';

                        // Saisie
                        if (isset($_SESSION['save']['remarks']) AND !empty($_SESSION['save']['remarks']))
                            echo '<textarea placeholder="Remarques & astuces" name="remarks" id="saisie_remarques" class="textarea_saisie">' . $_SESSION['save']['remarks'] . '</textarea>';
                        else
                            echo '<textarea placeholder="Remarques & astuces" name="remarks" id="saisie_remarques" class="textarea_saisie"></textarea>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_boutons_saisie">';
                // Valider
                echo '<input type="submit" name="submit_recipe" value="Valider" id="validerSaisieRecette" class="bouton_saisie_gauche" />';

                // Annuler
                echo '<a id="fermerSaisieRecette" class="bouton_saisie_droite">Annuler</a>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>