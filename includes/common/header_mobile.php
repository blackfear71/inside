<?php
  // Bandeau
  echo '<div class="zone_bandeau">';
    // Si on est connecté
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true)
    {
      // Logo Inside
      echo '<a id="deployAsidePortail" class="zone_bandeau_logo">';
        echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" />';
      echo '</a>';

      // Avatar
      $avatarFormatted = formatAvatar($_SESSION['user']['avatar'], $_SESSION['user']['pseudo'], 0, 'avatar');

      echo '<a id="deployAsideUser" class="zone_bandeau_avatar">';
        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bandeau" />';
      echo '</a>';
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
