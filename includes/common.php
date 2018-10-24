<?php
  // Dates de dernière modification (CSS et JS)
  $last_modification_css = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/style.css');
  $last_modification_js  = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/script.js');

  // Metas
  echo '<meta charset="utf-8" />';
  echo '<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />';
  echo '<meta name="keywords" content="Inside, portail, CDS Finance" />';

  // Links
  echo '<link rel="icon" type="image/png" href="/inside/favicon.png" />';
  echo '<link rel="stylesheet" href="/inside/style.css?version=' . $last_modification_css . '" />';
  echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';

  // Scripts
  echo '<script type="text/javascript" src="/inside/script.js?version=' . $last_modification_js . '"></script>';
  echo '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>';
  echo '<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>';
?>
