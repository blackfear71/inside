<?php
    /********************************/
    /* Zone de saisie d'image culte */
    /********************************/
    echo '<div id="zone_saisie_image_culte" class="fond_saisie">';
        echo '<div class="zone_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                // Texte
                echo '<div class="texte_titre_saisie">Ajouter une image culte</div>';

                // Bouton fermeture
                echo '<a id="fermerImage" class="bouton_fermeture_saisie"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="image_fermeture_saisie" /></a>';
            echo '</div>';

            // Saisie image culte
            echo '<form method="post" action="collector.php?action=doAjouterCollector&page=' . $_GET['page'] . '" enctype="multipart/form-data" class="form_saisie">';
                // Type de saisie
                echo '<input type="hidden" name="type_collector" value="I" />';

                // Image
                echo '<div class="zone_image_left">';
                    // Saisie image
                    echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';

                    echo '<div class="zone_parcourir_image">';
                        echo '<img src="../../includes/icons/common/picture.png" alt="picture" class="logo_saisie_image" />';
                        echo '<input type="file" accept=".jpg, .jpeg, .bmp, .gif, .png" name="image" class="bouton_parcourir_image loadSaisieCollector" required />';
                    echo '</div>';

                    echo '<div class="mask_image">';
                        echo '<img id="image_collector" alt="" class="image" />';
                    echo '</div>';
                echo '</div>';

                // Zone saisie infos
                echo '<div class="zone_image_right">';
                    // Speaker
                    if (!empty($_SESSION['save']['other_speaker']))
                        echo '<select name="speaker" id="speaker_2" class="saisie_speaker speaker_autre" required>';
                    else
                        echo '<select name="speaker" id="speaker_2" class="saisie_speaker" required>';
                        echo '<option value="" hidden>Choisissez...</option>';

                        foreach ($listeUsers as $identifiant => $user)
                        {
                            if ($user['team'] == $_SESSION['user']['equipe'])
                            {
                                if ($identifiant == $_SESSION['save']['speaker'])
                                    echo '<option value="' . $identifiant . '" selected>' . $user['pseudo'] . '</option>';
                                else
                                    echo '<option value="' . $identifiant . '">' . $user['pseudo'] . '</option>';
                            }
                        }

                        if (!empty($_SESSION['save']['other_speaker']))
                            echo '<option value="other" selected>Autre</option>';
                        else
                            echo '<option value="other">Autre</option>';
                    echo '</select>';

                    // "Autre"
                    if (!empty($_SESSION['save']['other_speaker']))
                        echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="255" id="other_name_2" class="saisie_other_collector" />';
                    else
                        echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="255" id="other_name_2" class="saisie_other_collector" style="display: none;" />';

                    // Date
                    echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker_image" class="saisie_date_collector" required />';

                    // Boutons d'action
                    echo '<div class="zone_bouton_saisie_image">';
                        // Ajouter
                        echo '<input type="submit" name="insert_collector" value="Ajouter l\'image culte" id="bouton_saisie_image" class="saisie_bouton" />';
                    echo '</div>';

                    // Contexte
                    echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte_image">' . $_SESSION['save']['context'] . '</textarea>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>