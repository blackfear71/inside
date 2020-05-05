<?php
  // Numéro de version
  $version = '2.2';

  // Version
  echo '<div class="version">v' . $version . '</div>';

  // Zone footer
  echo '<div class="zone_footer_right">';
    // Basculement mobile
    $footerMobile = isMobile();

    if ($footerMobile == true)
    {
      echo '<a href="/inside/includes/functions/switch_mobile.php" class="link_footer" title="Basculer vers la version mobile">';
        echo '<img src="/inside/includes/icons/common/mobile.png" alt="mobile" title="Basculer vers la version mobile" class="icone_footer" />';
      echo '</a>';
    }

    // Copyright
    echo '<div class="copyright">© 2017-' . date("Y") . ' Inside</div>';
  echo '<div>';
?>
