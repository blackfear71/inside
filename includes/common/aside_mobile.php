<?php
  // Tableaux des menus
  $listeAsidePortail = array(array('lien'  => '/inside/portail/portail/portail.php?action=goConsulter',
                                   'image' => 'inside_white',
                                   'titre' => 'PORTAIL'),
                             array('lien'  => '/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter',
                                   'image' => 'food_advisor',
                                   'titre' => 'LES ENFANTS ! À TABLE !')
                            );

  $listeAsideUser = array(array('lien'  => '/inside/includes/functions/disconnect.php',
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
    foreach ($listeAsideUser as $asideUser)
    {
      echo '<a href="' . $asideUser['lien'] . '" class="lien_aside">';
        echo '<img src="/inside/includes/icons/common/' . $asideUser['image'] . '.png" alt="' . $asideUser['image'] . '" title="Portail" class="icone_aside" />';
        echo '<div class="titre_aside">' . $asideUser['titre'] . '</div>';
      echo '</a>';
    }
  echo '</div>';
?>
