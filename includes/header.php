<div class="zone_bandeau">
  <div class="zone_bandeau_left">
    <!-- Logo -->
    <?php
      if ($_SESSION['identifiant'] == "admin")
        echo '<a href="/inside/administration/administration.php?action=goConsulter">';
      else
        echo '<a href="/inside/portail/portail/portail.php?action=goConsulter">';
          echo '<img src="/inside/includes/icons/inside.png" alt="inside" class="logo_bandeau" />';
        echo '</a>';

      // Notifications & Recherche (utilisateur)
      if ($_SESSION['identifiant'] != "admin")
      {
        // Notifications
        echo '<div class="zone_notifications_bandeau">';
            // Récupération des préférences
            switch ($_SESSION['view_notifications'])
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
                $page               = "";
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
                echo '<img src="/inside/includes/icons/notifications_blue.png" alt="notifications" title="Notifications" class="icon_notifications" />';
                if ($nb_notifs <= 9)
                  echo '<div class="number_notifications" style="color: white;">' . $nb_notifs . '</div>';
                else
                  echo '<div class="number_notifications" style="color: white;">9+</div>';
              echo '</a>';
            }
            else
            {
              echo '<a href="/inside/portail/notifications/notifications.php?view=' . $view_notifications . '&action=goConsulter' . $page . '" title="Notifications" class="link_notifications">';
                echo '<img src="/inside/includes/icons/notifications.png" alt="notifications" title="Notifications" class="icon_notifications" />';
                echo '<div class="number_notifications">0</div>';
              echo '</a>';
            }
        echo '</div>';

        // Recherche
        echo '<div id="resizeBar" class="zone_recherche_bandeau">';
          echo '<form method="post" action="" class="form_recherche_bandeau">';
            echo '<input type="submit" name="search" value="" class="logo_rechercher" />';
            echo '<input type="text" id="color_search" name="text_search" placeholder="Rechercher..." class="recherche_bandeau" />';
          echo '</form>';
        echo '</div>';
      echo '</div>';
    }
  ?>

  <div class="zone_bandeau_right">
    <!-- Titre de la page -->
    <div class="zone_titre_page">
      <span class="text_titre_page">
        <?php echo $title; ?>
      </span>
    </div>

    <!-- Profil -->
    <a href="/inside/profil/profil.php?user=<?php echo $_SESSION['identifiant']; ?>&view=settings&action=goConsulter" title="Mon profil" class="zone_profil_bandeau">
      <div class="pseudo_bandeau"><?php echo $_SESSION['pseudo']; ?></div>
      <?php
        if (isset($_SESSION['avatar']) AND !empty($_SESSION['avatar']))
          echo '<img src="/inside/profil/avatars/' . $_SESSION['avatar'] . '" alt="avatar" class="avatar_bandeau" />';
        else
          echo '<img src="/inside/includes/icons/default.png" alt="avatar" class="avatar_bandeau" />';
      ?>
    </a>
  </div>
</div>
