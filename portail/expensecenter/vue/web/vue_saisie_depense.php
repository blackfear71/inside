<?php
    /*********************************/
    /*** Zone de saisie de dépense ***/
    /*********************************/
    echo '<div id="zone_add_depense" style="display: none;" class="fond_saisie_depense">';
        echo '<div class="zone_saisie_depense">';
            // Titre
            echo '<div class="titre_saisie_depense">Saisir une dépense</div>';

            // Bouton fermeture
            echo '<a id="resetDepense" class="close_add"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="close_img" /></a>';

            // Saisie dépense
            echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&filter=' . $_GET['filter'] . '&action=doAjouterDepense" class="form_saisie_depense">';
                echo '<input type="hidden" name="id_expense_saisie" value="" />';

                // Achat
                echo '<div class="zone_saisie_left">';
                    // Acheteur
                    echo '<select id="select_user" name="buyer_user" class="saisie_buyer" required>';
                        echo '<option value="" hidden>Choisissez un acheteur...</option>';

                        foreach ($listeUsers as $user)
                        {
                            if ($user->getTeam() == $_SESSION['user']['equipe'])
                            {
                                if ($user->getIdentifiant() == $_SESSION['save']['buyer'])
                                    echo '<option value="' . $_SESSION['save']['buyer'] . '" selected>' . $user->getPseudo() . '</option>';
                                else
                                    echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
                            }
                        }
                    echo '</select>';

                    // Prix
                    echo '<div class="zone_saisie_prix">';
                        echo '<input type="text" name="depense" value="' . $_SESSION['save']['price'] . '" autocomplete="off" placeholder="Prix" maxlength="6" class="saisie_prix" required />';
                        echo '<img src="../../includes/icons/expensecenter/euro_grey.png" alt="euro_grey" title="Euros" class="euro" />';
                    echo '</div>';

                    // Date
                    echo '<input type="text" name="date_expense" value="' . $_SESSION['save']['date_expense'] . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker_depense" class="saisie_date_depense" required />';

                    // Commentaire
                    echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire">' . $_SESSION['save']['comment'] . '</textarea>';

                    // Bouton validation
                    echo '<div class="zone_bouton_saisie">';
                        echo '<input type="submit" name="add_depense" value="Valider" id="bouton_saisie_depense" class="saisie_bouton" />';
                    echo '</div>';

                    // Affichage explications
                    echo '<a id="afficherExplicationsDepense" class="lien_explications">';
                        echo '<span class="fond_plus">+</span>';
                        echo 'Fonctionnement';
                    echo '</a>';
                echo '</div>';

                // Parts utilisateurs
                echo '<div class="zone_saisie_right">';
                    // Explications
                    echo '<div id="explications_depense" class="explications" style="display: none;">';
                        echo 'Vous pouvez saisir ici une dépense dont le coût sera ensuite réparti équitablement sur chaque participant en fonction du nombre de parts. <strong>Les parts sont limitées à 5 maximum par personne</strong>.';

                        echo '<br /><br />';

                        echo '2 types de saisies peuvent être effectuées :';

                        echo '<ul>';
                            echo '<li>Une dépense : <strong>le prix doit être positif et des parts doivent être présentes sur au moins un utilisateur</strong>. Le coût est réparti proportionnellement entre chaque participant.</li>';
                            echo '<li>Une régularisation : <strong>le prix est soit positif soit négatif, mais le nombre de parts doit être nul pour tous</strong>. Le coût est simplement ajouté au bilan de l\'acheteur.</li>';
                        echo '</ul>';
                    echo '</div>';

                    // Parts
                    echo '<div class="zone_saisie_utilisateurs">';
                        foreach ($listeUsers as $user)
                        {
                            if ($user->getTeam() == $_SESSION['user']['equipe'])
                            {
                                $savedParts = false;

                                if (isset($_SESSION['save']['tableau_parts']) AND !empty($_SESSION['save']['tableau_parts']))
                                {
                                    if (isset($_SESSION['save']['tableau_parts'][$user->getIdentifiant()]) AND $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] > 0)
                                        $savedParts = true;
                                }

                                if ($savedParts == true)
                                    echo '<div class="zone_saisie_utilisateur part_selected" id="zone_user_' . $user->getId() . '">';
                                else
                                    echo '<div class="zone_saisie_utilisateur" id="zone_user_' . $user->getId() . '">';
                                    // Pseudo
                                    echo '<div class="pseudo_depense">' . $user->getPseudo() . '</div>';

                                    // Avatar
                                    $avatarFormatted = formatAvatar($user->getAvatar(), $user->getPseudo(), 2, 'avatar');

                                    echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_depense" />';

                                    // Identifiant (caché)
                                    echo '<input type="hidden" name="identifiant_quantite[]" value="' . $user->getIdentifiant() . '" />';

                                    // Zone saisie quantité
                                    echo '<div class="zone_saisie_quantite">';
                                        // Bouton -
                                        echo '<div id="retirer_part_' . $user->getId() . '" class="bouton_quantite retirerPart">-</div>';

                                        // Quantité
                                        if ($savedParts == true)
                                            echo '<input type="text" name="quantite_user[]" value="' . $_SESSION['save']['tableau_parts'][$user->getIdentifiant()] . '" id="quantite_user_' . $user->getId() . '" class="quantite" readonly />';
                                        else
                                            echo '<input type="text" name="quantite_user[]" value="0" id="quantite_user_' . $user->getId() . '" class="quantite" readonly />';

                                        // Bouton +
                                        echo '<div id="ajouter_part_' . $user->getId() . '" class="bouton_quantite ajouterPart">+</div>';
                                    echo '</div>';

                                    // Symbole
                                    echo '<img src="../../includes/icons/expensecenter/part_grey.png" alt="part_grey" title="Parts" class="parts_saisie" />';
                                echo '</div>';
                            }
                        }
                    echo '</div>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>