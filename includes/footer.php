<?php
  echo '<div class="version">V.2</div>';
  echo '<div class="copyright">© 2017 Inside</div>';

  // Boutons missions
  $zone_inside = "footer";
  include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/missions.php');

  // Chargement thème
  if ($_SESSION['user']['identifiant'] != "admin" AND !empty($_SESSION['theme']))
    echo '<script>changeTheme("' . $_SESSION['theme']['background'] . '", "' . $_SESSION['theme']['header'] . '", "' . $_SESSION['theme']['footer'] . '");</script>';
?>
