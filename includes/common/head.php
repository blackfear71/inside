<?php
  // Dates de dernière modification (CSS et JS) pour mise à jour automatique du cache du navigateur
  $lastModificationCss = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/style.css');

  if (!empty($styleHead))
    $lastModificationCss2 = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/' . $styleHead);

  if (isset($chatHead) AND $chatHead == true)
    $lastModificationCssChat = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/styleChat.css');

  if (isset($datepickerHead) AND $datepickerHead == true)
    $lastModificationCss_datepicker = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/libraries/css/datepicker.css');

  $lastModificationJs  = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/js/' . $_SESSION['index']['plateforme'] . '/script.js');

  if (!empty($scriptHead))
    $lastModificationJs2  = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/js/' . $_SESSION['index']['plateforme'] . '/' . $scriptHead);

  // Meta-données
  echo '<meta charset="utf-8" />';
  echo '<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />';
  echo '<meta name="keywords" content="Inside, portail, CDS Finance" />';

  if ($_SESSION['index']['plateforme'] == 'mobile')
    echo '<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1.0">';

  // Styles communs
  echo '<link rel="icon" type="image/png" href="/inside/favicon.png" />';
  echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/style.css?version=' . $lastModificationCss . '" />';

  // Styles spécifiques
  if (!empty($styleHead))
    echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/' . $styleHead . '?version=' . $lastModificationCss2 . '" />';

  if (isset($chatHead) AND $chatHead == true)
    echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/styleChat.css?version=' . $lastModificationCssChat . '" />';

  if (isset($datepickerHead) AND $datepickerHead == true)
    echo '<link rel="stylesheet" href="/inside/includes/libraries/css/datepicker.css?version=' . $lastModificationCss_datepicker . '">';

  // Title
  if (!empty($titleHead))
    echo '<title>Inside - ' . $titleHead . '</title>';
  else
    echo '<title>Inside</title>';
?>

<!-- Scripts communs -->
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
<script src="/inside/includes/assets/js/<?php echo $_SESSION['index']['plateforme']; ?>/script.js?version=<?php echo $lastModificationJs; ?>"></script>
<script src="/inside/includes/libraries/js/jCirclize.js"></script>

<!-- Scripts spécifiques -->
<?php if (isset($angularHead) AND $angularHead == true) { ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular-animate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular-resource.min.js"></script>
<?php } ?>

<?php if (!empty($scriptHead)) { ?>
  <script src="/inside/includes/assets/js/<?php echo $_SESSION['index']['plateforme']; ?>/<?php echo $scriptHead; ?>?version=<?php echo $lastModificationJs2; ?>"></script>
<?php } ?>

<?php if (isset($chatHead) AND $chatHead == true) { ?>
  <script src="/inside/includes/assets/js/<?php echo $_SESSION['index']['plateforme']; ?>/scriptChat.js?version=<?php echo $lastModificationCssChat; ?>"></script>
<?php } ?>

<?php if (isset($masonryHead) AND $masonryHead == true) { ?>
  <script src="/inside/includes/libraries/js/masonry.pkgd.js"></script>
<?php } ?>

<?php if (isset($exifHead) AND $exifHead == true) { ?>
  <script src="/inside/includes/libraries/js/exif.js"></script>
<?php } ?>

<?php if (isset($datepickerHead) AND $datepickerHead == true) { ?>
  <script src="/inside/includes/libraries/js/datepicker.js"></script>
<?php } ?>
