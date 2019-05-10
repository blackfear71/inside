<?php
  // Onglets
  echo '<div class="titre_section"><img src="../../includes/icons/notifications/notifications_grey.png" alt="notifications_grey" class="logo_titre_section" />Vues</div>';

  $listeVues = array('me'    => 'Moi',
                     'today' => 'Aujourd\'hui',
                     'week'  => '7 jours',
                     'all'   => 'Toutes'
                    );

  foreach ($listeVues as $view => $vue)
  {
    if ($view == "all" OR $view == "week" OR $view == "me")
      $page = '&page=1';
    else
      $page = '';
      
    if ($_GET['view'] == $view)
      echo '<span class="view active">' . $vue . '</span>';
    else
      echo '<a href="notifications.php?view=' . $view . '&action=goConsulter' . $page . '" class="view inactive">' . $vue . '</a>';
  }
?>
