<?php
  // Bandeau
  echo '<div class="zone_bandeau">';
    // Si on est connecté
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true)
    {
      // Logo Inside
      echo '<a id="deployAsidePortail" class="zone_bandeau_logo">';
        echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" id="logo_inside_header" />';
      echo '</a>';

      // Notifications, recherche et profil
      echo '<div class="zone_bandeau_droite">';
        echo '<div class="zone_boutons_bandeau">';
          // Notifications
          echo '<div class="zone_notifications_bandeau"></div>';
  
          // Recherche
          echo '<div id="afficherBarreRecherche" class="zone_recherche_bandeau">';
            echo '<img src="/inside/includes/icons/common/search.png" alt="search" title="Rechercher" class="icone_recherche" />';
          echo '</div>';
        echo '</div>';
  
        // Avatar
        $avatarFormatted = formatAvatar($_SESSION['user']['avatar'], $_SESSION['user']['pseudo'], 0, 'avatar');
  
        echo '<a id="deployAsideUser" class="zone_bandeau_avatar">';
          echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bandeau" />';
        echo '</a>';
      echo '</div>';

      // Boutons missions
      $zoneInside = 'header';
      include($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/common/missions.php');
    }
    else
    {
      // Logo Inside
      echo '<div class="zone_bandeau_logo">';
        echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" />';
      echo '</div>';
    }
  echo '</div>';
?>
