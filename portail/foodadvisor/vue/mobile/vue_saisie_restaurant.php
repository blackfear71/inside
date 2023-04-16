<?php
    echo '<div id="zone_saisie_restaurant" class="fond_saisie">';
        echo '<form method="post" action="restaurants.php?action=doAjouterRestaurant" enctype="multipart/form-data" class="form_saisie">';
            // Id restaurant (modification)
            echo '<input type="hidden" name="id_restaurant" id="id_saisie_restaurant" value="" />';

            // Titre
            echo '<div class="zone_titre_saisie">Saisir un restaurant</div>';

            // Saisie
            echo '<div class="zone_contenu_saisie">';
                echo '<div class="contenu_saisie">';
                    // Saisie image
                    echo '<div class="zone_image_saisie">';
                        echo '<div class="zone_parcourir_image">';
                            echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                            echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image_restaurant" id="saisie_image" class="bouton_parcourir_image loadSaisieRestaurant" />';
                        echo '</div>';

                        echo '<div class="mask_image">';
                            echo '<img id="image_restaurant_saisie" alt="" class="image" />';
                        echo '</div>';
                    echo '</div>';

                    // Titre
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/foodadvisor/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">Informations</div>';
                    echo '</div>';

                    // Nom
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

                    // Jours d'ouverture
                    $i       = 0;
                    $semaine = array(
                        'Lu' => 'lundi',
                        'Ma' => 'mardi',
                        'Me' => 'mercredi',
                        'Je' => 'jeudi',
                        'Ve' => 'vendredi'
                    );

                    echo '<div class="zone_checkbox_jour">';
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
                    echo '</div>';

                    // Prix min et max
                    echo '<div class="zone_saisie_prix">';
                        echo '<input type="text" name="prix_min_restaurant" value="' . $_SESSION['save']['prix_min'] . '" maxlength="5" placeholder="Prix min." class="saisie_prix_min_restaurant" />';
                        echo '<input type="text" name="prix_max_restaurant" value="' . $_SESSION['save']['prix_max'] . '" maxlength="5" placeholder="Prix max." class="saisie_prix_max_restaurant" />';
                    echo '</div>';

                    // Types
                    echo '<div id="types_restaurants" class="zone_saisie_types">';
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
                                if ($i % 2 == 0)
                                    echo '<div id="bouton_' . $idType . '" class="switch_types switch_types_margin bouton_checked">';
                                else
                                    echo '<div id="bouton_' . $idType . '" class="switch_types bouton_checked">';
                                    echo '<div class="zone_checkbox_type">';
                                        echo '<input id="' . $idType . '" type="checkbox" value="' . $type . '" name="types_restaurants[' . $i . ']" class="checkbox_type" checked />';
                                    echo '</div>';

                                    echo '<label for="' . $idType . '" class="label_switch checkType">' . $type . '</label>';
                                echo '</div>';
                            }
                            else
                            {
                                if ($i % 2 == 0)
                                    echo '<div id="bouton_' . $idType . '" class="switch_types switch_types_margin">';
                                else
                                    echo '<div id="bouton_' . $idType . '" class="switch_types">';
                                    echo '<div class="zone_checkbox_type">';
                                        echo '<input id="' . $idType . '" type="checkbox" value="' . $type . '" name="types_restaurants[' . $i . ']" class="checkbox_type" />';
                                    echo '</div>';

                                    echo '<label for="' . $idType . '" class="label_switch checkType">' . $type . '</label>';
                                echo '</div>';
                            }

                            $i++;
                        }

                        // Bouton "Autre"
                        if ($i % 2 == 0)
                            echo '<a class="bouton_type_autre switch_types_margin addType">';
                        else
                            echo '<a class="bouton_type_autre addType">';
                            echo '<span class="fond_plus">+</span>';
                            echo 'Autre';
                        echo '</a>';

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

                                    if ($j % 2 == 0)
                                        echo '<input type="text" placeholder="Type" value="' . $savedTypes . '" id="' . $idCustomType . '" name="types_restaurants[' . $j . ']" class="type_other type_other_margin filled saisieType" />';
                                    else
                                        echo '<input type="text" placeholder="Type" value="' . $savedTypes . '" id="' . $idCustomType . '" name="types_restaurants[' . $j . ']" class="type_other filled saisieType" />';

                                    $j++;
                                }
                            }
                        }
                    echo '</div>';

                    // Téléphone & liens
                    echo '<div class="zone_liens_rapides">';
                        // Téléphone
                        echo '<div class="zone_lien_rapide">';
                            echo '<div class="lien_rapide_saisie">';
                                echo '<img src="../../includes/icons/foodadvisor/phone.png" alt="phone" title="Téléphone" class="image_lien_rapide_saisie" />';
                            echo '</div>';

                            echo '<input type="text" name="phone_restaurant" value="' . $_SESSION['save']['phone_restaurant'] . '" maxlength="15" placeholder="Téléphone" id="saisie_telephone" class="saisie_lien_restaurant" />';
                        echo '</div>';

                        // Site web
                        echo '<div class="zone_lien_rapide">';
                            echo '<a href="https://www.google.fr/" target="_blank" class="lien_rapide_saisie">';
                                echo '<img src="../../includes/icons/foodadvisor/website.png" alt="website" title="Google" class="image_lien_rapide_saisie" />';
                            echo '</a>';

                            echo '<input type="text" name="website_restaurant" value="' . $_SESSION['save']['website_restaurant'] . '" placeholder="Site web" id="saisie_website" class="saisie_lien_restaurant" />';
                        echo '</div>';

                        // Plan
                        echo '<div class="zone_lien_rapide">';
                            echo '<a href="https://www.google.fr/maps" target="_blank" class="lien_rapide_saisie">';
                                echo '<img src="../../includes/icons/foodadvisor/plan.png" alt="plan" title="Google Maps" class="image_lien_rapide_saisie" />';
                            echo '</a>';

                            echo '<input type="text" name="plan_restaurant" value="' . $_SESSION['save']['plan_restaurant'] . '" placeholder="Plan" id="saisie_plan" class="saisie_lien_restaurant" />';
                        echo '</div>';

                        // Lien LaFourchette
                        echo '<div class="zone_lien_rapide">';
                            echo '<a href="https://www.lafourchette.com/" target="_blank" class="lien_rapide_saisie">';
                                echo '<img src="../../includes/icons/foodadvisor/lafourchette.png" alt="lafourchette" title="LaFourchette" class="image_lien_rapide_saisie" />';
                            echo '</a>';

                            echo '<input type="text" name="lafourchette_restaurant" value="' . $_SESSION['save']['lafourchette_restaurant'] . '" placeholder="LaFourchette" id="saisie_lafourchette" class="saisie_lien_restaurant" />';
                        echo '</div>';
                    echo '</div>';

                    // Titre
                    echo '<div class="titre_section">';
                        echo '<img src="../../includes/icons/foodadvisor/description_grey.png" alt="description_grey" class="logo_titre_section" />';
                        echo '<div class="texte_titre_section">À propos du restaurant</div>';
                    echo '</div>';

                    // Description
                    echo '<textarea placeholder="Description" name="description_restaurant" id="saisie_description" class="saisie_description">' . $_SESSION['save']['description_restaurant'] . '</textarea>';
                echo '</div>';
            echo '</div>';

            // Boutons
            echo '<div class="zone_boutons_saisie">';
                // Valider
                echo '<input type="submit" name="insert_restaurant" value="Valider" id="validerSaisieRestaurant" class="bouton_saisie_gauche" />';

                // Annuler
                echo '<a id="fermerSaisieRestaurant" class="bouton_saisie_droite">Annuler</a>';
            echo '</div>';
        echo '</form>';
    echo '</div>';
?>