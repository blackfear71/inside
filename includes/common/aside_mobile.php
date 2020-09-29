<?php
  // Tableaux des menus
  $listeAsidePortail = array(array('lien'  => '/inside/portail/portail/portail.php?action=goConsulter',
                                   'image' => 'inside_white',
                                   'titre' => 'PORTAIL'),
                             array('lien'  => '/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter',
                                   'image' => 'food_advisor',
                                   'titre' => 'LES ENFANTS ! À TABLE !'),
                             array('lien'  => '/inside/portail/expensecenter/expensecenter.php?year=' . date('Y') . '&action=goConsulter',
                                   'image' => 'expense_center',
                                   'titre' => 'EXPENSE CENTER'),
                             array('lien'  => '/inside/portail/collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none',
                                   'image' => 'collector',
                                   'titre' => 'COLLECTOR ROOM')
                            );

  $listeAsideUser = array(array('lien'  => '/inside/includes/functions/script_commun.php?function=disconnectUser',
                                'image' => 'logout',
                                'titre' => 'DÉCONNEXION')
                         );

  // Menu portail
  echo '<div class="aside_portail">';
    foreach ($listeAsidePortail as $asidePortail)
    {
      echo '<a href="' . $asidePortail['lien'] . '" class="lien_aside">';
        echo '<img src="/inside/includes/icons/common/' . $asidePortail['image'] . '.png" alt="' . $asidePortail['image'] . '" title="Portail" class="icone_aside" />';
        echo '<div class="titre_aside">' . $asidePortail['titre'] . '</div>';
      echo '</a>';
    }
  echo '</div>';

  // Menu utilisateur
  echo '<div class="aside_user">';
    echo '<div class="zone_infos_user_aside">';
      // Niveau
      echo '<div class="niveau_aside">' . $_SESSION['user']['experience']['niveau'] . '</div>';

      // Pseudo
      echo '<div class="pseudo_aside">' . formatString($_SESSION['user']['pseudo'], 30) . '</div>';
    echo '</div>';

    // Liens
    foreach ($listeAsideUser as $asideUser)
    {
      echo '<a href="' . $asideUser['lien'] . '" class="lien_aside">';
        echo '<img src="/inside/includes/icons/common/' . $asideUser['image'] . '.png" alt="' . $asideUser['image'] . '" title="Portail" class="icone_aside" />';
        echo '<div class="titre_aside">' . $asideUser['titre'] . '</div>';
      echo '</a>';
    }
  echo '</div>';
?>
