<?php
  // Recettes & photos des semaines passées
  echo '<div class="zone_cooking_right">';
    echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" /><div class="texte_titre_section">Les anciennes recettes</div></div>';

    if (!empty($recettes))
    {
      echo '<div class="zone_recettes">';
        foreach ($recettes as $recette)
        {
          echo '<div class="zone_recette" id="' . $recette->getId() . '">';
            echo '<div id="zone_shadow_' . $recette->getId() . '" class="zone_shadow">';
              // Présence recette & photo
              if (!empty($recette->getIngredients()) OR !empty($recette->getRecipe()) OR !empty($recette->getTips()))
              {
                echo '<a id="afficher_recette_' . $recette->getId() . '" class="lien_recette afficherRecette">';
                  echo '<img src="../../includes/icons/cookingbox/recipe_grey.png" alt="recipe_grey" title="Recette" class="icone_recette" />';
                  echo '<img src="../../includes/images/cookingbox/' . $_GET['year'] . '/mini/' . $recette->getPicture() . '" alt="' . $recette->getPicture() . '" title="' . $recette->getName() . '" class="image_recette" />';
                echo '</a>';
              }
              else
              {
                echo '<a id="afficher_recette_' . $recette->getId() . '" class="lien_recette afficherRecette">';
                  echo '<img src="../../includes/images/cookingbox/' . $_GET['year'] . '/mini/' . $recette->getPicture() . '" alt="' . $recette->getPicture() . '" title="' . $recette->getName() . '" class="image_recette" />';
                echo '</a>';
              }
            echo '</div>';

            // Zone infos
            echo '<div class="zone_infos_recette">';
              // Semaine
              echo '<div class="semaine_recette">';
                echo 'Semaine ' . formatWeekForDisplay($recette->getWeek());
              echo '</div>';

              // Avatar
              $avatarFormatted = formatAvatar($recette->getAvatar(), $recette->getPseudo(), 2, 'avatar');

              echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_recette" />';

              // Boutons d'action
              if ($recette->getIdentifiant() == $_SESSION['user']['identifiant'])
              {
                // Supprimer
                echo '<form id="delete_recipe_' . $recette->getWeek() . '" method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doSupprimerRecette" class="form_delete_week">';
                  echo '<input type="hidden" name="week_cake" value="' . $recette->getWeek() . '" />';
                  echo '<input type="hidden" name="year_cake" value="' . $recette->getYear() . '" />';
                  echo '<input type="submit" name="delete_week" value="" title="Supprimer la photo et la recette" class="icon_delete_week eventConfirm" />';
                  echo '<input type="hidden" value="Supprimer la photo et la recette de cette semaine ?" class="eventMessage" />';
                echo '</form>';

                // Modifier
                echo '<a id="modifier_' . $recette->getId() . '" title="Modifier" class="lien_update_week modifierRecette"><img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="icon_update_week" /></a>';
              }
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas de recettes pour cette année...</div>';
  echo '</div>';
?>