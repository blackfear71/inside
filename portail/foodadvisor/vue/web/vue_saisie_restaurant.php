<?php
    /************************************/
    /*** Zone de saisie de restaurant ***/
    /************************************/
    echo '<div id="zone_saisie_restaurant" class="fond_saisie">';
        echo '<div class="zone_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                // Texte
                echo '<div class="texte_titre_saisie">Ajouter un restaurant</div>';

                // Bouton fermeture
                echo '<a id="fermerRestaurant" class="bouton_fermeture_saisie"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="image_fermeture_saisie" /></a>';
            echo '</div>';

            // Saisie restaurant
            echo '<form method="post" action="restaurants.php?action=doAjouterRestaurant" enctype="multipart/form-data" class="form_saisie">';
                // Photo, jours d'ouverture & prix
                echo '<div class="zone_saisie_left">';
                    // Photo
                    echo '<div class="zone_parcourir_restaurant_saisie">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image_restaurant" class="bouton_parcourir_restaurant_saisie loadSaisieRestaurant" />';
                    echo '</div>';

                    echo '<div class="mask_saisie_restaurant">';
                        echo '<img id="image_restaurant_saisie" alt="" class="image_saisie_restaurant" />';
                    echo '</div>';

                    // Jours d'ouverture
                    $i       = 0;
                    $semaine = array(
                        'Lu' => 'lundi',
                        'Ma' => 'mardi',
                        'Me' => 'mercredi',
                        'Je' => 'jeudi',
                        'Ve' => 'vendredi'
                    );

                    foreach ($semaine as $j => $jour)
                    {
                        if (empty($_SESSION['save']['ouverture_restaurant']) OR isset($_SESSION['save']['ouverture_restaurant'][$i]))
                        {
                            echo '<input type="checkbox" id="saisie_checkbox_ouverture_' . $jour . '" name="ouverture_restaurant[' . $i . ']" value="' . $j . '" class="checkbox_jour" checked />';
                            echo '<label for="saisie_checkbox_ouverture_' . $jour . '" id="saisie_label_ouverture_' . $jour . '" class="label_jour_checked checkDay">' . $j . '</label>';
                        }
                        else
                        {
                            echo '<input type="checkbox" id="saisie_checkbox_ouverture_' . $jour . '" name="ouverture_restaurant[' . $i . ']" value="' . $j . '" class="checkbox_jour" />';
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
                    if ($_SESSION['save']['location'] == 'other_location')
                        echo '<input type="text" name="name_restaurant" value="' . $_SESSION['save']['name_restaurant'] . '" placeholder="Nom du restaurant" id="saisie_nom" class="saisie_nom_restaurant_autre" required />';
                    else
                        echo '<input type="text" name="name_restaurant" value="' . $_SESSION['save']['name_restaurant'] . '" placeholder="Nom du restaurant" id="saisie_nom" class="saisie_nom_restaurant" required />';

                    // Lieu
                    echo '<select name="location" id="saisie_location" class="saisie_lieu" required>';
                        echo '<option value="" hidden>Choisissez...</option>';

                        foreach ($listeLieux as $lieu)
                        {
                            if ($lieu == htmlspecialchars($_SESSION['save']['location']))
                                echo '<option value="' . $lieu . '" selected>' . $lieu . '</option>';
                            else
                                echo '<option value="' . $lieu . '">' . $lieu . '</option>';
                        }

                        if ($_SESSION['save']['location'] == 'other_location')
                            echo '<option value="other_location" selected>Autre</option>';
                        else
                            echo '<option value="other_location">Autre</option>';
                    echo '</select>';

                    // Lieu "Autre"
                    if ($_SESSION['save']['location'] == 'other_location')
                        echo '<input type="text" name="saisie_other_location" value="' . $_SESSION['save']['saisie_other_location'] . '" placeholder="Lieu personnalisé" maxlength="255" id="saisie_other_location" class="saisie_lieu_autre_restaurant" />';
                    else
                        echo '<input type="text" name="saisie_other_location" placeholder="Lieu personnalisé" maxlength="255" id="saisie_other_location" class="saisie_lieu_autre_restaurant" style="display: none;" />';

                    // Boutons d'action
                    echo '<div class="zone_bouton_saisie_restaurant">';
                        // Ajouter
                        echo '<input type="submit" name="insert_restaurant" value="Ajouter le restaurant" id="bouton_saisie_restaurant" class="saisie_bouton" />';
                    echo '</div>';

                    // Types
                    echo '<div id="types_restaurant" class="zone_types_restaurant">';
                        $i = 0;

                        // Types existants
                        foreach ($listeTypes as $type)
                        {
                            $idType  = 'type_' . formatId($type);
                            $checked = false;

                            if (!empty($_SESSION['save']['types_restaurants']))
                            {
                                foreach ($_SESSION['save']['types_restaurants'] as $savedTypes)
                                {
                                    if ($savedTypes == $type)
                                    {
                                        $checked = true;
                                        break;
                                    }
                                }
                            }

                            if ($checked == true)
                            {
                                echo '<div id="bouton_' . $idType . '" class="switch_types bouton_checked">';
                                    echo '<input id="' . $idType . '" type="checkbox" value="' . $type . '" name="types_restaurants[' . $i . ']" checked />';
                                    echo '<label for="' . $idType . '" class="label_switch checkType">' . $type . '</label>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<div id="bouton_' . $idType . '" class="switch_types">';
                                    echo '<input id="' . $idType . '" type="checkbox" value="' . $type . '" name="types_restaurants[' . $i . ']" />';
                                    echo '<label for="' . $idType . '" class="label_switch checkType">' . $type . '</label>';
                                echo '</div>';
                            }

                            $i++;
                        }

                        // Types personnalisés (sauvegardés en cas d'erreur)
                        if (!empty($_SESSION['save']['types_restaurants']))
                        {
                            $j = $i;

                            foreach ($_SESSION['save']['types_restaurants'] as $savedTypes)
                            {
                                $customType = true;

                                foreach ($listeTypes as $type)
                                {
                                    if ($savedTypes == $type)
                                    {
                                        $customType = false;
                                        break;
                                    }
                                }

                                if ($customType == true)
                                {
                                    $idCustomType    = 'type_' . formatId($savedTypes);
                                    $labelCustomType = 'label_' . formatId($savedTypes);

                                    echo '<input type="text" placeholder="Type" value="' . $savedTypes . '" id="' . $idCustomType . '" name="types_restaurants[' . $j . ']" class="type_other filled saisieType" />';

                                    $j++;
                                }
                            }
                        }

                        // Bouton "Autre"
                        echo '<a id="addType" class="bouton_type_autre">';
                            echo '<span class="fond_plus">+</span>';
                            echo 'Autre';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';

                // Téléphone, site web, plan et description
                echo '<div class="zone_saisie_bottom">';
                    // Numéro
                    echo '<input type="text" name="phone_restaurant" value="' . $_SESSION['save']['phone_restaurant'] . '" maxlength="15" placeholder="Téléphone" class="saisie_telephone_restaurant" />';

                    // Site web
                    echo '<a href="https://www.google.fr/" target="_blank" class="lien_rapide_saisie">';
                        echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Google" class="image_lien_rapide_saisie" />';
                    echo '</a>';

                    echo '<input type="text" name="website_restaurant" value="' . $_SESSION['save']['website_restaurant'] . '" placeholder="Site web" class="saisie_lien_restaurant" />';

                    // Plan
                    echo '<a href="https://www.google.fr/maps" target="_blank" class="lien_rapide_saisie">';
                        echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Google Maps" class="image_lien_rapide_saisie" />';
                    echo '</a>';

                    echo '<input type="text" name="plan_restaurant" value="' . $_SESSION['save']['plan_restaurant'] . '" placeholder="Plan" class="saisie_lien_restaurant" />';

                    // Lien LaFourchette
                    echo '<a href="https://www.lafourchette.com/" target="_blank" class="lien_rapide_saisie">';
                        echo '<img src="../../includes/icons/foodadvisor/lafourchette.png" alt="lafourchette" title="LaFourchette" class="image_lien_rapide_saisie" />';
                    echo '</a>';

                    echo '<input type="text" name="lafourchette_restaurant" value="' . $_SESSION['save']['lafourchette_restaurant'] . '" placeholder="LaFourchette" class="saisie_lien_restaurant" />';

                    // Description
                    echo '<textarea placeholder="Description" name="description_restaurant" class="textarea_saisie_description_restaurant">' . $_SESSION['save']['description_restaurant'] . '</textarea>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>