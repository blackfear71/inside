<?php
  if (!empty($listeLieux))
  {
    // Titre
    echo '<div id="titre_lieux_restaurants" class="titre_section">';
      echo '<img src="../../includes/icons/foodadvisor/solo_grey.png" alt="solo_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Les lieux</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section angle_fleche_titre_section" />';
    echo '</div>';

    // Affichage des lieux
    echo '<div id="afficher_lieux_restaurants" class="zone_liens_lieux" style="display: none;">';
      foreach ($listeLieux as $lieu)
      {
        echo '<a id="link_lieu_' . formatId($lieu) . '" class="lien_lieu lienLieu">';
          // Icône
          echo '<div class="image_lieu"></div>';

          // Nom du lieu
          echo '<div class="nom_lieu">' . $lieu . '</div>';
        echo '</a>';
      }
    echo '</div>';
  }
?>
