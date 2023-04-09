<?php
    /**********************************/
    /* Zone de saisie de phrase culte */
    /**********************************/
    echo '<div id="zone_saisie_phrase_culte" class="fond_saisie">';
        echo '<div class="zone_saisie">';
            // Titre
            echo '<div class="zone_titre_saisie">';
                // Texte
                echo '<div class="texte_titre_saisie">Ajouter une phrase culte</div>';

                // Bouton fermeture
                echo '<a id="fermerCollector" class="bouton_fermeture_saisie"><img src="../../includes/icons/common/close.png" alt="close" title="Fermer" class="image_fermeture_saisie" /></a>';
            echo '</div>';

            // Saisie phrase culte
            echo '<form method="post" action="collector.php?action=doAjouterCollector&page=' . $_GET['page'] . '" class="form_saisie">';
                // Type de saisie
                echo '<input type="hidden" name="type_collector" value="T" />';

                // Speaker
                if (!empty($_SESSION['save']['other_speaker']))
                    echo '<select name="speaker" id="speaker" class="saisie_speaker speaker_autre" required>';
                else
                    echo '<select name="speaker" id="speaker" class="saisie_speaker" required>';
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
                    echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="255" id="other_name" class="saisie_other_collector" />';
                else
                    echo '<input type="text" name="other_speaker" value="' . $_SESSION['save']['other_speaker'] . '" placeholder="Nom" maxlength="255" id="other_name" class="saisie_other_collector" style="display: none;" />';

                // Date
                echo '<input type="text" name="date_collector" value="' . $_SESSION['save']['date_collector'] . '" placeholder="Date" maxlength="10" autocomplete="off" id="datepicker_collector" class="saisie_date_collector" required />';

                // Boutons d'action
                echo '<div class="zone_bouton_saisie_collector">';
                    // Ajouter
                    echo '<input type="submit" name="insert_collector" value="Ajouter la phrase culte" id="bouton_saisie_collector" class="saisie_bouton" />';
                echo '</div>';

                // Phrase culte
                echo '<textarea placeholder="Phrase culte" name="collector" class="saisie_collector" required>' . $_SESSION['save']['collector'] . '</textarea>';

                // Contexte
                echo '<textarea placeholder="Contexte (facultatif)" name="context" class="saisie_contexte">' . $_SESSION['save']['context'] . '</textarea>';
            echo '</form>';
        echo '</div>';
    echo '</div>';
?>