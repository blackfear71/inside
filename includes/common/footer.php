<?php
  // Version
  echo '<div class="version">v2.1</div>';

  // Liens
  if ($_SESSION['user']['identifiant'] != "admin")
  {
    $path = $_SERVER['PHP_SELF'];

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

    // Lien #TheBox
    if ($path == '/inside/portail/ideas/ideas.php')
      echo '<a href="/inside/portail/ideas/ideas.php?view=' . $view_the_box . '&action=goConsulter&page=1" title="&#35;TheBox" class="link_footer_active">';
    else
      echo '<a href="/inside/portail/ideas/ideas.php?view=' . $view_the_box . '&action=goConsulter&page=1" title="&#35;TheBox" class="link_footer">';
      echo '<img src="/inside/includes/icons/common/ideas.png" alt="ideas" title="&#35;TheBox" class="icone_footer" />';
    echo '</a>';

    // Lien Bugs
    if ($path == '/inside/portail/bugs/bugs.php')
      echo '<a href="/inside/portail/bugs/bugs.php?view=unresolved&action=goConsulter" title="Signaler un bug" class="link_footer_active">';
    else
      echo '<a href="/inside/portail/bugs/bugs.php?view=unresolved&action=goConsulter" title="Signaler un bug" class="link_footer">';
      echo '<img src="/inside/includes/icons/common/bug.png" alt="bug" title="Signaler un bug" class="icone_footer" />';
    echo '</a>';
  }

  // Copyright
  echo '<div class="copyright">© 2017-' . date("Y") . ' Inside</div>';

  // Boutons missions
  $zone_inside = "footer";
  include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');

  // Chargement des données du thème pour le script
  if ($_SESSION['user']['identifiant'] != "admin" AND !empty($_SESSION['theme']))
    $themeUser = json_encode($_SESSION['theme']);
  else
    $themeUser = json_encode('');
?>

<script>
  // Récupération du thème pour le script
  var themeUser = <?php echo $themeUser; ?>;
</script>
