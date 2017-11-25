<?php
  echo '<div class="version">V.2</div>';
  echo '<div class="copyright">© 2017 Inside</div>';

  // Boutons missions
  $zone_inside = "footer";

  include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/missions.php');

  // Chargement fond d'écran
  if ($_SESSION['identifiant'] != "admin")
    echo '<script>changeTheme("' . $_SESSION['theme']['background'] . '", "' . $_SESSION['theme']['header'] . '", "' . $_SESSION['theme']['footer'] . '");</script>';
?>
