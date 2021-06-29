<?php
  /***********/
  /* Polices */
  /***********/
  echo '<div class="zone_preferences_police">';
    // Titre
    echo '<div class="titre_section">';
      echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">Polices de caractères</div>';
    echo '</div>';

    // Choix de la police
    echo '<form method="post" action="profil.php?action=doModifierPolice" class="form_update_police">';
      echo '<select id="select_police" name="police" class="select_update_police" required>';
        foreach ($policesCaracteres as $police)
        {
          if ($police == $_SESSION['user']['font'])
            echo '<option value="' . $police . '" selected>' . $police . '</option>';
          else
            echo '<option value="' . $police . '">' . $police . '</option>';
        }
      echo '</select>';

      echo '<input type="submit" name="update_font" value="Mettre à jour la police" class="bouton_form_police" />';
    echo '</form>';

    // Exemple de texte
    echo '<div id="exemple_police" style="font-family: ' . $_SESSION['user']['font'] . ',Verdana, sans-serif;">';
      echo '<p>Exemple de texte</p>';

      echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec,
      ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec
      nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in,
      pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede.
      Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
      posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.</p>';
    echo '</div>';
  echo '</div>';
?>
