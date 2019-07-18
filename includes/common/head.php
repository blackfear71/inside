<?php
  // Dates de dernière modification (CSS et JS) pour mise à jour automatique du cache du navigateur
  $last_modification_css = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/css/style.css');

  if (!empty($style_head))
    $last_modification_css2 = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/css/' . $style_head);

  if (isset($chat_head) AND $chat_head == true)
    $last_modification_css_chat = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/css/styleChat.css');

  if (isset($datepicker_head) AND $datepicker_head == true)
    $last_modification_css_datepicker = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/libraries/css/datepicker.css');

  $last_modification_js  = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/js/script.js');

  if (!empty($script_head))
    $last_modification_js2  = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/js/' . $script_head);

  // Meta-données
  echo '<meta charset="utf-8" />';
  echo '<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />';
  echo '<meta name="keywords" content="Inside, portail, CDS Finance" />';

  // Styles communs
  echo '<link rel="icon" type="image/png" href="/inside/favicon.png" />';
  echo '<link rel="stylesheet" href="/inside/includes/assets/css/style.css?version=' . $last_modification_css . '" />';

  // Styles spécifiques
  if (!empty($style_head))
    echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $style_head . '?version=' . $last_modification_css2 . '" />';

  if (isset($chat_head) AND $chat_head == true)
    echo '<link rel="stylesheet" href="/inside/includes/assets/css/styleChat.css?version=' . $last_modification_css_chat . '" />';

  if (isset($datepicker_head) AND $datepicker_head == true)
    echo '<link rel="stylesheet" href="/inside/includes/libraries/css/datepicker.css?version=' . $last_modification_css_datepicker . '">';

  if (isset($bootstrap) AND $bootstrap == true)
    echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">'; 

  // Title
  if (!empty($title_head))
    echo '<title>Inside - ' . $title_head . '</title>';
  else
    echo '<title>Inside</title>';
?>

<!-- Scripts communs -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="/inside/includes/assets/js/script.js?version=<?php echo $last_modification_js; ?>"></script>
<script src="/inside/includes/libraries/js/jCirclize.js"></script>

<!-- Scripts spécifiques -->
<?php if (isset($angular) AND $angular == true) { ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular-animate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular-resource.min.js"></script>
<?php } ?>

<?php if (!empty($script_head)) { ?>
  <script src="/inside/includes/assets/js/<?php echo $script_head; ?>?version=<?php echo $last_modification_js2; ?>"></script>
<?php } ?>

<?php if (isset($chat_head) AND $chat_head == true) { ?>
  <script src="/inside/includes/assets/js/scriptChat.js?version=<?php echo $last_modification_css_chat; ?>"></script>
<?php } ?>

<?php if (isset($masonry_head) AND $masonry_head == true) { ?>
  <script src="/inside/includes/libraries/js/masonry.pkgd.js"></script>
<?php } ?>

<?php if (isset($exif_head) AND $exif_head == true) { ?>
  <script src="/inside/includes/libraries/js/exif.js"></script>
<?php } ?>

<?php if (isset($datepicker_head) AND $datepicker_head == true) { ?>
  <script src="/inside/includes/libraries/js/datepicker.js"></script>
<?php } ?>

