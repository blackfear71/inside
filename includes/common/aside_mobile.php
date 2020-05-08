<?php
  // Menu portail
  echo '<div class="aside_portail">';
    // Portail
    echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/inside_white.png" alt="inside_white" title="Portail" class="icone_aside" />';
      echo '<div class="titre_aside">PORTAIL</div>';
    echo '</a>';

    // Les enfants ! À table !
    echo '<a href="/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter" class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="icone_aside" />';
      echo '<div class="titre_aside">LES ENFANTS ! À TABLE !</div>';
    echo '</a>';
  echo '</div>';

  // Menu utilisateur
  echo '<div class="aside_user">';
    // Déconnexion
    echo '<a href="/inside/includes/functions/disconnect.php" class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/logout.png" alt="logout" title="Déconnexion" class="icone_aside" />';
      echo '<div class="titre_aside">DÉCONNEXION</div>';
    echo '</a>';
  echo '</div>';
?>
