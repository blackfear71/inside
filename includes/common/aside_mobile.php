<?php
  // Récupération des préférences
  switch ($_SESSION['user']['view_movie_house'])
  {
    case 'C':
      $viewMovieHouse = 'cards';
      break;

    case 'H':
    default:
      $viewMovieHouse = 'home';
      break;
  }

  // Menu latéral gauche
  $listeAsidePortail = array(array('lien'  => '/inside/portail/portail/portail.php?action=goConsulter',
                                   'image' => 'inside_white',
                                   'titre' => 'PORTAIL'),
                             array('lien'  => '../moviehouse/moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter',
                                   'image' => 'movie_house',
                                   'titre' => 'MOVIE HOUSE'),
                             array('lien'  => '/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter',
                                   'image' => 'food_advisor',
                                   'titre' => 'LES ENFANTS ! À TABLE !'),
                             array('lien'  => '/inside/portail/cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter',
                                   'image' => 'cooking_box',
                                   'titre' => 'COOKING BOX'),
                             array('lien'  => '/inside/portail/expensecenter/expensecenter.php?year=' . date('Y') . '$filter=all&action=goConsulter',
                                   'image' => 'expense_center',
                                   'titre' => 'EXPENSE CENTER'),
                             array('lien'  => '/inside/portail/collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none',
                                   'image' => 'collector',
                                   'titre' => 'COLLECTOR ROOM'),
                             array('lien'  => '/inside/portail/petitspedestres/parcours.php?action=goConsulterListe',
                                   'image' => 'petits_pedestres',
                                   'titre' => 'LES PETITS PÉDESTRES'),
                             array('lien'  => '/inside/portail/calendars/calendars.php?year=' . date('Y') . 'action=goConsulter',
                                   'image' => 'calendars',
                                   'titre' => 'CALENDARS'),
                             array('lien'  => '/inside/portail/missions/missions.php?action=goConsulter',
                                   'image' => 'missions',
                                   'titre' => 'MISSIONS : INSIDER')
                            );

  // Menu latéral droit
  $listeAsideUser = array(array('lien'  => '/inside/portail/profil/profil.php?view=settings&action=goConsulter',
                                'image' => 'settings',
                                'titre' => 'PARAMÈTRES'),
                          array('lien'  => '/inside/portail/profil/profil.php?view=success&action=goConsulter',
                                'image' => 'cup',
                                'titre' => 'SUCCÈS'),
                          array('lien'  => '/inside/portail/profil/profil.php?view=themes&action=goConsulter',
                                'image' => 'themes',
                                'titre' => 'THÈMES'),
                          array('lien'  => '/inside/includes/functions/script_commun.php?function=disconnectUser',
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
    // Pseudo
    echo '<a href="/inside/portail/profil/profil.php?view=profile&action=goConsulter" class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/profile.png" alt="profile" title="Profil" class="icone_aside" />';
      echo '<div class="titre_aside">' . formatString($_SESSION['user']['pseudo'], 30) . '</div>';
    echo '</a>';

    // Expérience utilisateur
    echo '<div class="lien_aside">';
      // Logo
      echo '<img src="/inside/includes/icons/common/experience.png" alt="experience" title="Expérience" class="icone_aside" />';

      // Expérience
      echo '<div class="fond_experience_aside"><div class="experience_aside" style="width: ' . $_SESSION['user']['experience']['percent'] . '%;"></div></div>';

      // Niveau
      echo '<div class="niveau_aside">' . $_SESSION['user']['experience']['niveau'] . '</div>';
    echo '</div>';

    // Equipe
    echo '<div class="lien_aside">';
      echo '<img src="/inside/includes/icons/common/team.png" alt="team" title="Equipe" class="icone_aside" />';
      echo '<div class="titre_aside">' . formatString($_SESSION['user']['libelle_equipe'], 30) . '</div>';
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
