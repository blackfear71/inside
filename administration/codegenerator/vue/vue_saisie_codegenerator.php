<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/datas_grey.png" alt="datas_grey" class="logo_titre_section" /><div class="texte_titre_section">Données de la page à générer</div></div>';

    // Paramétrage des données
    echo '<form method="post" action="codegenerator.php?action=generateCode" class="form_saisie_admin">';
        // Titre
        echo '<div class="categorie_generator">Données générales</div>';

        // Nom fonctionnel
        echo '<input type="text" name="nom_fonctionnel" placeholder="Nom de la section (ex : Portail)" value="' . $generatorParameters->getNom_section() . '" class="saisie_generator margin_right_10" required>';

        // Nom technique
        echo '<input type="text" name="nom_technique" placeholder="Nom technique (ex : portail)" value="' . $generatorParameters->getNom_technique() . '" class="saisie_generator" required>';

        // Nom head
        echo '<input type="text" name="nom_head" placeholder="Nom de l\'onglet du navigateur (ex : Portail)" value="' . $generatorParameters->getNom_head() . '" class="saisie_generator_full" required>';

        // Style spécifique
        echo '<input type="text" name="style_specifique" placeholder="Style CSS spécifique (ex : stylePortail)" value="' . $generatorParameters->getStyle_specifique() . '" class="saisie_generator margin_right_10">';

        // Script spécifique
        echo '<input type="text" name="script_specifique" placeholder="Script JS spécifique (ex : scriptPortail)" value="' . $generatorParameters->getScript_specifique() . '" class="saisie_generator">';

        // Options
        $old_category = '';

        foreach ($generatorParameters->getOptions() as $generatorOption)
        {
            $current_category = $generatorOption->getCategorie();

            if ($current_category != $old_category)
            {
                $old_category = $current_category;
                echo '<div class="categorie_generator">' . $current_category . '</div>';
            }

            if ($generatorOption->getChecked() == 'Y')
            {
                echo '<div id="switch_' . $generatorOption->getOption() . '" class="switch_saisie_admin switch_checked">';
                    echo '<input type="checkbox" id="checkbox_' . $generatorOption->getOption() . '" name="' . $generatorOption->getOption() . '" value="' . $generatorOption->getOption() . '" checked />';
                    echo '<label for="checkbox_' . $generatorOption->getOption() . '" id="label_' . $generatorOption->getOption() . '" class="label_switch">' . $generatorOption->getTitre() . '</label>';
                echo '</div>';
            }
            else
            {
                echo '<div id="switch_' . $generatorOption->getOption() . '" class="switch_saisie_admin">';
                    echo '<input type="checkbox" id="checkbox_' . $generatorOption->getOption() . '" name="' . $generatorOption->getOption() . '" value="' . $generatorOption->getOption() . '" />';
                    echo '<label for="checkbox_' . $generatorOption->getOption() . '" id="label_' . $generatorOption->getOption() . '" class="label_switch">' . $generatorOption->getTitre() . '</label>';
                echo '</div>';
            }
        }

        // Bouton
        echo '<input type="submit" name="generate_code" value="Générer le code" class="bouton_saisie_blanc" />';
    echo '</form>';
?>