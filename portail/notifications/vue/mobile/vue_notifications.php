<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Notifications';
      $styleHead       = 'styleNO.css';
      $scriptHead      = 'scriptNO.js';
      $angularHead     = false;
      $chatHead        = true;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/mobile/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/mobile/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'notifications';
        include('../../includes/common/mobile/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*********************/
          /* Zone de recherche */
          /*********************/
          include('../../includes/common/mobile/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /********/
          /* Vues */
          /********/
          include('vue/mobile/vue_vues.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Vues
          echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_green">';
            echo '<img src="../../includes/icons/notifications/notifications_grey.png" alt="notifications_grey" class="image_lien" />';

            switch ($_GET['view'])
            {
              case 'me':
                echo '<div class="titre_lien">MOI</div>';
                break;

              case 'today':
                echo '<div class="titre_lien">AUJOURD\'HUI</div>';
                break;

              case 'week':
                echo '<div class="titre_lien">7 JOURS</div>';
                break;

              case 'all':
              default:
                echo '<div class="titre_lien">TOUTES</div>';
                break;
            }
          echo '</a>';

          /****************************/
          /* Nombres de notifications */
          /****************************/
          // Nombre de notification du jour
          echo '<div class="zone_nombre_notifications zone_nombre_margin">';
            echo '<div class="nombre_notifications">' . $nombresNotifications['nombreNotificationsJour'] . '</div>';

            echo '<div class="zone_titre_nombre_notifications">';
              if ($nombresNotifications['nombreNotificationsJour'] == 1)
                echo '<div class="titre_nombre_notifications">NOTIFICATION AUJOURD\'HUI</div>';
              else
                echo '<div class="titre_nombre_notifications">NOTIFICATIONS AUJOURD\'HUI</div>';
            echo '</div>';
          echo '</div>';

          // Nombre de notifications de la semaine
          echo '<div class="zone_nombre_notifications">';
            echo '<div class="nombre_notifications">' . $nombresNotifications['nombreNotificationsSemaine'] . '</div>';
            
            echo '<div class="zone_titre_nombre_notifications">';
              if ($nombresNotifications['nombreNotificationsSemaine'] == 1)
                echo '<div class="titre_nombre_notifications">NOTIFICATION CETTE SEMAINE</div>';
              else
                echo '<div class="titre_nombre_notifications">NOTIFICATIONS CETTE SEMAINE</div>';
            echo '</div>';
          echo '</div>';

          /*****************/
          /* Notifications */
          /*****************/
          echo '<div class="zone_notifications">';
            if (!empty($notifications))
            {
              $dateNotification = '';

              foreach ($notifications as $keyNotification => $notification)
              {
                if (!empty($notification->getIcon()) AND !empty($notification->getSentence()))
                {
                  // Date
                  if ($notification->getDate() != $dateNotification)
                  {
                    // Titre
                    echo '<div id="titre_notifications_' . $notification->getDate() . '" class="titre_section">';
                      echo '<img src="../../includes/icons/notifications/date_grey.png" alt="date_grey" class="logo_titre_section" />';
                      echo '<div class="texte_titre_section_fleche">' . formatDateForDisplay($notification->getDate()) . '</div>';
                      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                    echo '</div>';

                    $dateNotification = $notification->getDate();

                    // Zone notifications
                    echo '<div id="afficher_notifications_' . $notification->getDate() . '">';
                  }

                  // Lien si présent
                  if (!empty($notification->getLink()))
                    if ($notification->getCategory() == 'doodle')
                      echo '<a href="' . $notification->getLink() . '" id="lien_details_' . $notification->getContent() . '" target="_blank" class="lien_notification lienDetails">';
                    else
                      echo '<a href="' . $notification->getLink() . '" class="lien_notification">';
                  else
                    echo '<div class="lien_notification">';

                      // Contenu (icône, phrase & date)
                      echo '<table class="zone_notification">';
                        echo '<tr>';
                          echo '<td class="zone_notification_icone">';
                            echo '<img src="../../includes/icons/common/' . $notification->getIcon() . '.png" alt="' . $notification->getIcon() . '" class="icone_notification" />';
                          echo '</td>';
                          echo '<td class="zone_notification_contenu">';
                            echo $notification->getSentence();
                          echo '</td>';
                          echo '<td class="zone_notification_date">';
                            echo formatTimeForDisplayLight($notification->getTime());
                          echo '</td>';
                        echo '</tr>';
                      echo '</table>';

                  if (!empty($notification->getLink()))
                    echo '</a>';
                  else
                    echo '</div>';

                  // Termine la zone des notifications
                  if (!isset($notifications[$keyNotification + 1]) OR $notification->getDate() != $notifications[$keyNotification + 1]->getDate())
                    echo '</div>';
                }
              }
            }
            else
            {
              // Titre
              echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/notifications/date_grey.png" alt="date_grey" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">' . formatDateForDisplay(date('Ymd')) . '</div>';
              echo '</div>';

              echo '<div class="empty">Pas encore de notifications...</div>';
            }
          echo '</div>';

          /**************/
          /* Pagination */
          /**************/
          include('vue/mobile/vue_pagination.php');
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>
