<?php
  // Version
  echo '<div class="version">V.2</div>';

  // Lien #TheBox
  if ($_SESSION['user']['identifiant'] != "admin")
  {
    // Récupération des préférences
    switch ($_SESSION['user']['view_the_box'])
    {
      case "P":
        $view_the_box = "inprogress";
        break;

      case "M":
        $view_the_box = "mine";
        break;

      case "D":
        $view_the_box = "done";
        break;

      case "A":
      default:
        $view_the_box = "all";
        break;
    }

    echo '<a href="/inside/portail/ideas/ideas.php?view=' . $view_the_box . '&action=goConsulter" title="&#35;TheBox" class="link_footer">';
      echo '<img src="/inside/includes/icons/common/ideas.png" alt="ideas" title="&#35;TheBox" class="icone_footer" />';
    echo '</a>';
  }

  // Lien Bugs
  if ($_SESSION['user']['identifiant'] != "admin")
  {
    echo '<a href="/inside/portail/bugs/bugs.php?view=submit&action=goConsulter" title="Signaler un bug" class="link_footer">';
      echo '<img src="/inside/includes/icons/common/bug.png" alt="bug" title="Signaler un bug" class="icone_footer" />';
    echo '</a>';
  }

  // Copyright
  echo '<div class="copyright">© 2017-' . date("Y") . ' Inside</div>';

  // Boutons missions
  $zone_inside = "footer";
  include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');

  // Chargement thème
  if ($_SESSION['user']['identifiant'] != "admin" AND !empty($_SESSION['theme']))
    echo '<script>changeTheme("' . $_SESSION['theme']['background'] . '", "' . $_SESSION['theme']['header'] . '", "' . $_SESSION['theme']['footer'] . '", "' . $_SESSION['theme']['logo'] . '");</script>';
?>
