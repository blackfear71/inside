<?php
  // Dates de dernière modification (CSS et JS)
  $last_modification_css = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/style.css');
  $last_modification_js  = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/script.js');

  if (!empty($style_head))
    $last_modification_css2 = filemtime($style_head);

  if (!empty($script_head))
    $last_modification_js2  = filemtime($script_head);

  if (isset($chat_head) AND $chat_head == true)
    $last_modification_css_chat = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/chat/styleChat.css');

  // Metas
  echo '<meta charset="utf-8" />';
  echo '<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />';
  echo '<meta name="keywords" content="Inside, portail, CDS Finance" />';

  // Links
  echo '<link rel="icon" type="image/png" href="/inside/favicon.png" />';
  echo '<link rel="stylesheet" href="/inside/style.css?version=' . $last_modification_css . '" />';
  echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';

  // Style spécifique
  if (!empty($style_head))
    echo '<link rel="stylesheet" href="' . $style_head . '?version=' . $last_modification_css2 . '" />';

  if (isset($chat_head) AND $chat_head == true)
    echo '<link rel="stylesheet" href="/inside/includes/chat/styleChat.css?version=' . $last_modification_css_chat . '" />';

  // Title
  if (!empty($title_head))
    echo '<title>Inside - ' . $title_head . '</title>';
  else
    echo '<title>Inside</title>';
?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript" src="/inside/script.js?version=<?php echo $last_modification_js; ?>"></script>

<!-- Scripts spécifiques -->
<?php if (!empty($script_head)) { ?>
  <script type="text/javascript" src="<?php echo $script_head; ?>?version=<?php echo $last_modification_js2; ?>"></script>
<?php } ?>

<?php if (isset($masonry_head) AND $masonry_head == true) { ?>
  <script type="text/javascript" src="/inside/includes/masonry/masonry.pkgd.js"></script>
<?php } ?>

<?php if (isset($image_loaded_head) AND $image_loaded_head == true) { ?>
  <script type="text/javascript" src="/inside/includes/masonry/imagesloaded.pkgd.js"></script>
<?php } ?>

<?php if (isset($chat_head) AND $chat_head == true) { ?>
  <script type="text/javascript" src="/inside/includes/chat/scriptChat.js?version=<?php echo $last_modification_css_chat; ?>"></script>
<?php } ?>
