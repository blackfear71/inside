<?php
  // Menu portail
  echo '<div class="aside_portail">';
    // Portail
    echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/inside_red.png" alt="inside_red" title="" class="icone_aside" />';
      echo '<div class="titre_aside">PORTAIL</div>';
    echo '</a>';
  echo '</div>';

  // Menu utilisateur
  echo '<div class="aside_user">';
    // Déconnexion
    echo '<a href="/inside/includes/functions/disconnect.php" class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/logout.png" alt="logout" title="" class="icone_aside" />';
      echo '<div class="titre_aside">DÉCONNEXION</div>';
    echo '</a>';
  echo '</div>';
?>
