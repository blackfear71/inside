<?php
  // Recettes & photos des semaines passées
  echo '<div class="zone_cooking_right">';
    echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" />Les anciennes recettes</div>';

    if (!empty($recettes))
    {
      echo '<div class="zone_recettes">';
        foreach ($recettes as $recette)
        {
          echo '<div class="zone_recette">';
            // Présence recette & photo
            if (!empty($recette->getIngredients()) OR !empty($recette->getRecipe()) OR !empty($recette->getTips()))
            {
              echo '<a class="lien_recette agrandirRecette">';
                echo '<img src="../../includes/icons/cookingbox/recipe_grey.png" alt="recipe_grey" title="Recette" class="icone_recette" />';
                echo '<img src="../../includes/images/cookingbox/' . $_GET['year'] . '/mini/' . $recette->getPicture() . '" alt="' . $recette->getPicture() . '" title="' . $recette->getName() . '" class="image_recette" />';
              echo '</a>';
            }
            else
              echo '<img src="../../includes/images/cookingbox/' . $_GET['year'] . '/mini/' . $recette->getPicture() . '" alt="' . $recette->getPicture() . '" title="' . $recette->getName() . '" class="image_recette" />';

            // Zone infos
            echo '<div class="">';
              // Semaine
              echo '<div class="semaine_recette">';
                echo 'Semaine ' . $recette->getWeek();
              echo '</div>';

              // Avatar
              if (!empty($recette->getAvatar()))
                echo '<img src="../../includes/images/profil/avatars/' . $recette->getAvatar() . '" alt="avatar" title="' . $recette->getPseudo() . '" class="avatar_recette" />';
              else
                echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $recette->getPseudo() . '" class="avatar_recette" />';

                // Boutons d'action
                if ($recette->getIdentifiant() == $_SESSION['user']['identifiant'])
                {
                  // Supprimer
                  echo '<form id="delete_recipe_' . $recette->getWeek() . '" method="post" action="cookingbox.php?year=' . $_GET['year'] . '&action=doSupprimer" class="form_delete_week">';
                    echo '<input type="hidden" name="week_cake" value="' . $recette->getWeek() . '" />';
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
