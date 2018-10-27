<?php
  echo '<div class="zone_bandeau">';
    // Partie gauche header
    echo '<div class="zone_bandeau_left">';
      // Logo
      if ($_SESSION['user']['identifiant'] == "admin")
        echo '<a href="/inside/administration/administration.php?action=goConsulter">';
      else
        echo '<a href="/inside/portail/portail/portail.php?action=goConsulter">';
          echo '<img src="/inside/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" />';
        echo '</a>';

      // Notifications & Recherche (utilisateur)
      if ($_SESSION['user']['identifiant'] != "admin")
      {
        // Notifications
        echo '<div class="zone_notifications_bandeau">';
            // Récupération des préférences
            switch ($_SESSION['user']['view_notifications'])
            {
              case "M":
                $view_notifications = "me";
                $page               = "&page=1";
                break;

              case "T":
                $view_notifications = "today";
                $page               = "";
                break;

              case "W":
                $view_notifications = "week";
                $page               = "&page=1";
                break;

              case "A":
              default:
                $view_notifications = "all";
                $page               = "&page=1";
                break;
            }

            // On compte le nombre de notifications du jour
            $nb_notifs = 0;

            $reponse = $bdd->query('SELECT COUNT(id) AS nb_notifs FROM notifications WHERE date = ' . date("Ymd"));
            $donnees = $reponse->fetch();
            $nb_notifs = $donnees['nb_notifs'];
            $reponse->closeCursor();

            // Affichage en fonction du nombre de notifications
            if ($nb_notifs > 0)
            {
              echo '<a href="/inside/portail/notifications/notifications.php?view=' . $view_notifications . '&action=goConsulter' . $page . '" title="Notifications" class="link_notifications">';
                echo '<img src="/inside/includes/icons/common/notifications_blue.png" alt="notifications" title="Notifications" class="icon_notifications" />';
                if ($nb_notifs <= 9)
                  echo '<div class="number_notifications" style="color: white;">' . $nb_notifs . '</div>';
                else
                  echo '<div class="number_notifications" style="color: white;">9+</div>';
              echo '</a>';
            }
            else
            {
              echo '<a href="/inside/portail/notifications/notifications.php?view=' . $view_notifications . '&action=goConsulter' . $page . '" title="Notifications" class="link_notifications">';
                echo '<img src="/inside/includes/icons/common/notifications.png" alt="notifications" title="Notifications" class="icon_notifications" />';
                echo '<div class="number_notifications">0</div>';
              echo '</a>';
            }
        echo '</div>';

        // Recherche
        echo '<div id="resizeBar" class="zone_recherche_bandeau">';
          echo '<form method="post" action="/inside/portail/search/search.php?action=doSearch" class="form_recherche_bandeau">';
            echo '<input type="submit" name="search" value="" class="logo_rechercher" />';
            echo '<input onmouseover="changeColorToWhite(\'color_search\')" onmouseout="changeColorToGrey(\'color_search\', \'resizeBar\')" type="text" id="color_search" name="text_search" placeholder="Rechercher..." class="recherche_bandeau" />';
          echo '</form>';
        echo '</div>';
      echo '</div>';
    }

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
        echo '<a href="/inside/administration/profil.php?action=goConsulter" title="Mon profil" class="zone_profil_bandeau">';
      else
        echo '<a href="/inside/profil/profil.php?user=' . $_SESSION['user']['identifiant'] . '&view=settings&action=goConsulter" title="Mon profil" class="zone_profil_bandeau">';
          echo '<div class="pseudo_bandeau">' . $_SESSION['user']['pseudo'] . '</div>';
            if (isset($_SESSION['user']['avatar']) AND !empty($_SESSION['user']['avatar']))
              echo '<img src="/inside/includes/images/profil/avatars/' . $_SESSION['user']['avatar'] . '" alt="avatar" class="avatar_bandeau" />';
            else
              echo '<img src="/inside/includes/icons/common/default.png" alt="avatar" class="avatar_bandeau" />';
        echo '</a>';
    echo '</div>';

    // Boutons missions
    $zone_inside = "header";
    include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/missions.php');
  echo '</div>';
?>
