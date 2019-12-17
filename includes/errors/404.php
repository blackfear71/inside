<?php
  // Date de dernière modification pour mise à jour automatique du cache du navigateur
  $last_modification_errors = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/css/styleErrors.css');

  // Feuille de style
  echo '<link rel="stylesheet" href="/inside/includes/assets/css/styleErrors.css?version=' . $last_modification_errors . '" />';

  // Erreur
  echo '<div class="error_title">';
    echo '<img src="/inside/includes/icons/common/inside_red.png" alt="inside" title="Inside" class="error_logo" />';
    echo 'Page non trouvée';
  echo '</div>';
?>
