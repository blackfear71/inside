<?php
  echo '<div class="titre_section"><img src="../../includes/icons/admin/download_grey.png" alt="download_grey" class="logo_titre_section" /><div class="texte_titre_section">Autorisations de gestion des calendriers</div></div>';

  echo '<form method="post" action="calendars.php?action=doUpdateAutorisations" class="form_autorisations">';
    echo '<div class="zone_autorisations">';
      foreach ($listeAutorisations as $autorisation)
      {
        if ($autorisation['manage_calendars'] == "Y")
        {
          echo '<div id="bouton_autorisation_' . $autorisation['identifiant'] . '" class="switch_autorisation switch_checked">';
            echo '<input id="autorisation_' . $autorisation['identifiant'] . '" type="checkbox" name="autorization[' . $autorisation['identifiant'] . ']" checked />';
            echo '<label for="autorisation_' . $autorisation['identifiant'] . '" class="label_switch">' . $autorisation['pseudo'] . '</label>';
          echo '</div>';
        }
        else
        {
          echo '<div id="bouton_autorisation_' . $autorisation['identifiant'] . '" class="switch_autorisation">';
            echo '<input id="autorisation_' . $autorisation['identifiant'] . '" type="checkbox" name="autorization[' . $autorisation['identifiant'] . ']" />';
            echo '<label for="autorisation_' . $autorisation['identifiant'] . '" class="label_switch">' . $autorisation['pseudo'] . '</label>';
          echo '</div>';
        }
      }
    echo '</div>';

    echo '<input type="submit" name="saisie_autorisations" value="Mettre à jour" class="saisie_autorisations" />';
  echo '</form>';
?>
