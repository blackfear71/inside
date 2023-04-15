<?php
    // Titre
    echo '<div class="titre_section"><img src="../../includes/icons/admin/settings_grey.png" alt="settings_grey" class="logo_titre_section" /><div class="texte_titre_section">Gérer les succès des utilisateurs</div></div>';

    // Modification
    echo '<div class="titre_explications">Modifier les succès</div>';

    echo '<div class="explications_gestion_succes_full">';
        echo 'Il est possible de modifier l\'image, le titre, la description, le niveau, l\'ordonnancement, la condition, la mission liée,
              l\'unicité, la définition du code et les explications des succès. Bien contrôler l\'ordonnancement par rapport au niveau pour
              éviter les doublons. Il n\'est pas possible de modifier la référence afin d\'éviter les impacts sur les succès utilisateurs,
              s\'il y en a besoin il faut donc supprimer le succès via cet écran. Pour les explications, insérer les caractères
              <i>%limit%</i> permet de les remplacer par la valeur de la conditon d\'obtention du succès.';
    echo '</div>';

    // Purge
    echo '<div class="titre_explications">Gérer les succès</div>';

    echo '<div class="zone_gestion_succes">';
        echo '<form id="purgeSuccess" method="post" action="success.php?action=doPurgerSucces" class="form_gestion_succes">';
            echo '<input type="submit" name="purge_success" value="Purger les succès" class="bouton_gestion_succes eventConfirm" />';
            echo '<input type="hidden" value="Voulez-vous vraiment purger les succès ? Ceci est définitif." class="eventMessage" />';
        echo '</form>';

        echo '<div class="explications_gestion_succes">';
            echo 'Ce bouton permet d\'effacer tous les succès des utilisateurs dans la base de données sauf pour les exceptions suivantes :';

            echo '<ul class="margin_top_0 margin_bottom_0">';
                echo '<li>J\'étais là. (<strong>beginning</strong>) - défini manuellement par l\'administrateur</li>';
                echo '<li>Je l\'ai fait ! (<strong>developper</strong>) - défini manuellement par l\'administrateur</li>';
                echo '<li>Economie de marché (<strong>greedy</strong>) - pas d\'historique du plus haut bilan d\'un utilisateur</li>';
            echo '</ul>';
        echo '</div>';
    echo '</div>';

    // Initialisation
    echo '<div class="zone_gestion_succes margin_top_20">';
        echo '<form id="initializeSuccess" method="post" action="success.php?action=doInitialiserSucces" class="form_gestion_succes">';
            echo '<input type="submit" name="init_success" value="Initialiser les succès" class="bouton_gestion_succes eventConfirm" />';
            echo '<input type="hidden" value="Voulez-vous vraiment initialiser les succès ?" class="eventMessage" />';
        echo '</form>';

        echo '<div class="explications_gestion_succes">';
            echo 'Ce bouton permet d\'initialiser les succès pour tous les utilisateurs. Il faut faire attention lors de son utilisation car il va remplacer les valeurs déjà
            acquises par tous les utilisateurs et potentiellement bloquer des succès déjà débloqués. Le traitement peut prendre du temps en fonction du nombre de succès et d\'utilisateurs. Une
            purge est effectuée en fin de traitement sur tous les éventuels succès à 0.';
        echo '</div>';
    echo '</div>';
?>