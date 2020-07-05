<?php
  echo '<div id="zone_details_depense" class="fond_details" style="display: none;">';
    echo '<div class="div_details">';
      echo '<div class="zone_contenu_details">';
        // Titre
        echo '<div class="titre_details">';
          echo '<img src="../../includes/icons/expensecenter/expenses_grey.png" alt="expenses_grey" class="logo_titre_section" />';
          echo '<div class="texte_titre_section"></div>';
        echo '</div>';

        // Prix
        echo '<div class="zone_details_prix"></div>';

        // Informations
        echo '<div class="zone_details_informations">';
          // Titre
          echo '<div id="titre_depenses_infos" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/informations_grey.png" alt="informations_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Informations</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Acheteur & commentaires
          echo '<div id="afficher_depenses_infos" class="zone_details_acheteur_commentaires">';
            // Informations acheteur
            echo '<div class="zone_details_acheteur">';
              // Avatar
              echo '<img src="" alt="" title="" class="details_avatar_acheteur" />';

              // Pseudo
              echo '<div class="details_pseudo_acheteur"></div>';
            echo '</div>';

            // Commentaires
            echo '<div class="details_commentaires"></div>';

            // Régularisation
            echo '<div class="details_regularisation">Régularisation</div>';
          echo '</div>';
        echo '</div>';

        // Parts utilisateurs
        echo '<div class="zone_details_parts">';
          // Titre
          echo '<div id="titre_depenses_parts" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/users_grey.png" alt="users_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Répartition</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          // Répartition des parts
          echo '<div id="afficher_depenses_parts" class="zone_details_repartition"></div>';
        echo '</div>';

        // Actions
        echo '<div class="zone_details_actions">';
          // Modifier
          echo '<a title="Modifier" class="lien_modifier_depense modifierDepense">';
            echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="icone_modifier_depense" />';
          echo '</a>';

          // Supprimer
          echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&action=doSupprimer" class="form_supprimer_depense">';
            echo '<input type="hidden" name="id_expense" value="" />';
            echo '<input type="submit" name="delete_depense" value="" title="Supprimer" class="icone_supprimer_depense eventConfirm" />';
            echo '<input type="hidden" value="" class="eventMessage" />';
          echo '</form>';
        echo '</div>';
      echo '</div>';

      // Bouton fermeture
      echo '<div class="zone_boutons_saisie">';
        echo '<a id="fermerDetailsDepense" class="bouton_saisie_fermer">Fermer</a>';
      echo '</div>';
    echo '</div>';
  echo '</div>';
?>
