<?php
  // Zone gauche
  echo '<div class="zone_footer_left">';
    // Numéro de version
    $version = '2.4';

    // Version
    echo '<div class="version">v' . $version . '</div>';

    // Liens
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true)
    {
      // Page courante
      $path = $_SERVER['PHP_SELF'];

      // Récupération des préférences
      switch ($_SESSION['user']['view_the_box'])
      {
        case 'P':
          $viewTheBox = 'inprogress';
          break;

        case 'M':
          $viewTheBox = 'mine';
          break;

        case 'D':
          $viewTheBox = 'done';
          break;

        case 'A':
        default:
          $viewTheBox = 'all';
          break;
      }

      // Lien #TheBox
      if ($path == '/inside/portail/ideas/ideas.php')
        echo '<a href="/inside/portail/ideas/ideas.php?view=' . $viewTheBox . '&action=goConsulter&page=1" title="&#35;TheBox" class="link_footer_active">';
      else
        echo '<a href="/inside/portail/ideas/ideas.php?view=' . $viewTheBox . '&action=goConsulter&page=1" title="&#35;TheBox" class="link_footer">';
        // Logo
        echo '<img src="/inside/includes/icons/common/ideas.png" alt="ideas" title="&#35;TheBox" class="icone_footer" />';
      echo '</a>';
    }
  echo '</div>';

  // Zone droite
  echo '<div class="zone_footer_right">';
    // Récupération de la plateforme
    $plateforme = getPlateforme();

    // Affichage switch version sur mobile
    if ($plateforme == 'mobile')
    {
      echo '<a href="/inside/includes/functions/script_commun.php?function=switchMobile" class="link_footer" title="Basculer vers la version classique">';
        echo '<img src="/inside/includes/icons/common/classic.png" alt="classic" title="Basculer vers la version classique" class="icone_footer" />';
      echo '</a>';
    }

    // Copyright
    echo '<div class="copyright">© 2017-' . date('Y') . ' Inside</div>';
  echo '</div>';
?>
