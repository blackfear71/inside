<?php
  if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true)
  {
    echo '<div class="zone_bandeau">';
      // Partie gauche header
      echo '<div class="zone_bandeau_left">';
        // Logo
        if ($_SESSION['user']['identifiant'] == 'admin')
          echo '<a href="/inside/administration/portail/portail.php?action=goConsulter">';
        else
          echo '<a href="/inside/portail/portail/portail.php?action=goConsulter">';
            echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" id="logo_inside_header" />';
          echo '</a>';

        // Notifications & Recherche (utilisateur)
        if ($_SESSION['user']['identifiant'] != 'admin')
        {
          // Notifications
          echo '<div id="afficherDetailNotifications" class="zone_notifications_bandeau"></div>';

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
        if ($_SESSION['user']['identifiant'] == 'admin')
          echo '<a href="/inside/administration/profil/profil.php?action=goConsulter" title="Mon profil" class="zone_profil_bandeau">';
        else
          echo '<a href="/inside/portail/profil/profil.php?view=profile&action=goConsulter" title="Mon profil" class="zone_profil_bandeau">';
          // Expérience utilisateur
          if ($_SESSION['user']['identifiant'] != 'admin')
          {
            // Niveau
            echo '<div class="level_header">' . $_SESSION['user']['experience']['niveau'] . '</div>';

            // Expérience
            echo '<canvas class="experience_header" id="canvas_header_100" width="70" height="70">Ce navigateur ne prend pas en charge &lt;canvas&gt;</canvas>';
            echo '<canvas class="experience_header" id="canvas_header_' . $_SESSION['user']['experience']['percent'] . '" width="70" height="70">Ce navigateur ne prend pas en charge &lt;canvas&gt;</canvas>';
          }

          // Pseudo
          echo '<div class="pseudo_bandeau">' . $_SESSION['user']['pseudo'] . '</div>';

          // Avatar
          $avatarFormatted = formatAvatar($_SESSION['user']['avatar'], $_SESSION['user']['pseudo'], 0, 'avatar');

          echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_bandeau" />';
        echo '</a>';

        // Actions
        echo '<div class="zone_actions_header">';
          // Déconnexion
          echo '<form method="post" action="/inside/includes/functions/script_commun.php?function=disconnectUser" title="Déconnexion">';
            echo '<input type="submit" name="disconnect" value="" title="Déconnexion" class="icone_deconnexion_header" />';
          echo '</form>';

          // Succès
          if ($_SESSION['user']['identifiant'] != 'admin')
            echo '<a href="/inside/portail/profil/profil.php?view=success&action=goConsulter" title="Succès"><img src="/inside/includes/icons/common/cup.png" alt="cup" class="icone_action_header" /></a>';
        echo '</div>';
      echo '</div>';

      // Boutons missions
      $zoneInside = 'header';
      include($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/common/missions.php');
    echo '</div>';
  }
  else
  {
    echo '<div class="zone_bandeau">';
      // Partie gauche header
      echo '<div class="zone_bandeau_left">';
        // Logo
        echo '<a href="/inside/index.php?action=goConsulter">';
          echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" id="logo_inside_header" />';
        echo '</a>';

        // Boutons
        echo '<div class="zone_liens_index">';
          // Connexion
          echo '<a id="afficherConnexion" class="lien_index lien_index_selected"><div class="test">Connexion</div></a>';

          // Inscription
          echo '<a id="afficherInscription" class="lien_index">Inscription</a>';

          // Mot de passe
          echo '<a id="afficherPassword" class="lien_index">Mot de passe oublié</a>';
        echo '</div>';
      echo '</div>';

      // Partie droite header
      echo '<div class="zone_bandeau_right">';
        // Logos catégories
        $icons = array('movie_house',
                       'food_advisor',
                       'cooking_box',
                       'expense_center',
                       'collector',
                       'calendars',
                       //'event_manager',
                       'petits_pedestres',
                       'missions'
                      );

        foreach ($icons as $icon)
        {
          echo '<img src="includes/icons/common/' . $icon . '.png" alt="' . $icon . '_grey" class="logo_categories" />';
        }
      echo '</div>';
    echo '</div>';
  }
?>
