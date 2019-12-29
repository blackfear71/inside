<?php
  echo '<div class="zone_bandeau">';
    // Partie gauche header
    echo '<div class="zone_bandeau_left">';
      // Logo
      if ($_SESSION['user']['identifiant'] == "admin")
        echo '<a href="/inside/administration/portail/portail.php?action=goConsulter">';
      else
        echo '<a href="/inside/portail/portail/portail.php?action=goConsulter">';
          echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" id="logo_inside_header" />';
        echo '</a>';

      // Notifications & Recherche (utilisateur)
      if ($_SESSION['user']['identifiant'] != "admin")
      {
        // Notifications
        echo '<div class="zone_notifications_bandeau"></div>';

        // Recherche
        echo '<div id="resizeBar" class="zone_recherche_bandeau">';
          echo '<form method="post" action="/inside/portail/search/search.php?action=doSearch" class="form_recherche_bandeau">';
            echo '<input type="submit" name="search" value="" class="logo_rechercher" />';
            echo '<input type="text" id="color_search" name="text_search" placeholder="Rechercher..." class="recherche_bandeau" />';
          echo '</form>';
        echo '</div>';
      }
    echo '</div>';

    // Partie droite header
    echo '<div class="zone_bandeau_right">';
      // Titre de la page
      echo '<div class="zone_titre_page">';
        echo '<span class="text_titre_page">';
          echo $title;
        echo '</span>';
      echo '</div>';

      // Profil
      if ($_SESSION['user']['identifiant'] == "admin")
        echo '<a href="/inside/administration/profil/profil.php?action=goConsulter" title="Mon profil" class="zone_profil_bandeau">';
      else
        echo '<a href="/inside/profil/profil.php?view=profile&action=goConsulter" title="Mon profil" class="zone_profil_bandeau">';

        // Expérience utilisateur
        if ($_SESSION['user']['identifiant'] != "admin")
        {
          echo '<div class="level_header">' . $_SESSION['user']['experience']['niveau'] . '</div>';
          echo '<div class="circular_bar_header" id="progress_circle_header" data-perc="' . $_SESSION['user']['experience']['percent'] . '" data-text=""></div>';
        }

        // Pseudo
        echo '<div class="pseudo_bandeau">' . $_SESSION['user']['pseudo'] . '</div>';

        // Avatar
        $avatarFormatted = formatAvatar($_SESSION['user']['avatar'], $_SESSION['user']['pseudo'], 0, "avatar");

        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bandeau" />';
      echo '</a>';

      // Actions
      echo '<div class="zone_actions_header">';
        // Déconnexion
        echo '<form method="post" action="/inside/connexion/disconnect.php" title="Déconnexion">';
          echo '<input type="submit" name="disconnect" value="" title="Déconnexion" class="icone_deconnexion_header" />';
        echo '</form>';

        // Succès
        if ($_SESSION['user']['identifiant'] != "admin")
          echo '<a href="/inside/profil/profil.php?view=success&action=goConsulter" title="Succès"><img src="/inside/includes/icons/common/cup.png" alt="cup" class="icone_action_header" /></a>';
      echo '</div>';
    echo '</div>';

    // Boutons missions
    $zone_inside = "header";
    include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');
  echo '</div>';
?>
