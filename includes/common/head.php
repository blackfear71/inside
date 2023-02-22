<?php
    // Dates de dernière modification (CSS et JS) pour mise à jour automatique du cache du navigateur
    $dateModificationCss = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/style.css');

    $dateModificationCssFonts = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/fonts/styleFonts.css');

    if (isset($_SESSION['user']['font']) AND !empty($_SESSION['user']['font']))
        $dateModificationCssFonts2 = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/fonts/style' . $_SESSION['user']['font'] . '.css');
    else
        $dateModificationCssFonts2 = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/fonts/styleRoboto.css');

    if (!empty($styleHead))
        $dateModificationCssSection = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/' . $styleHead);

    if (isset($chatHead) AND $chatHead == true)
    {
        $dateModificationCssChat = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/styleChat.css');
        $dateModificationJsChat  = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/js/' . $_SESSION['index']['plateforme'] . '/scriptChat.js');
    }

    if (isset($datepickerHead) AND $datepickerHead == true)
        $dateModificationCssDatepicker = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/libraries/css/datepicker.css');

    $dateModificationJs = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/js/' . $_SESSION['index']['plateforme'] . '/script.js');

    if (!empty($scriptHead))
        $dateModificationJsSection = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/js/' . $_SESSION['index']['plateforme'] . '/' . $scriptHead);

    // Meta-données
    echo '<meta charset="utf-8" />';
    echo '<meta name="description" content="Bienvenue sur Inside, portail de partage et d\'échange au cœur de l\'équipe (ex-portail interne au seul vrai CDS Finance)" />';
    echo '<meta name="keywords" content="Inside, portail, CDS Finance" />';

    if ($_SESSION['index']['plateforme'] == 'mobile')
        echo '<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1.0, minimum-scale=1, maximum-scale=1.0, user-scalable=no">';

    // Styles communs
    echo '<link rel="icon" type="image/png" href="/inside/favicon.png" />';
    echo '<link rel="stylesheet" href="/inside/includes/assets/css/fonts/styleFonts.css?version=' . $dateModificationCssFonts . '" />';

    if (isset($_SESSION['user']['font']) AND !empty($_SESSION['user']['font']))
        echo '<link rel="stylesheet" href="/inside/includes/assets/css/fonts/style' . $_SESSION['user']['font'] . '.css?version=' . $dateModificationCssFonts2 . '" />';
    else
        echo '<link rel="stylesheet" href="/inside/includes/assets/css/fonts/styleRoboto.css?version=' . $dateModificationCssFonts2 . '" />';

    echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/style.css?version=' . $dateModificationCss . '" />';

    // Styles spécifiques
    if (!empty($styleHead))
        echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/' . $styleHead . '?version=' . $dateModificationCssSection . '" />';

    if (isset($chatHead) AND $chatHead == true)
        echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/styleChat.css?version=' . $dateModificationCssChat . '" />';

    if (isset($datepickerHead) AND $datepickerHead == true)
        echo '<link rel="stylesheet" href="/inside/includes/libraries/css/datepicker.css?version=' . $dateModificationCssDatepicker . '">';

    // Titre
    if (!empty($titleHead))
        echo '<title>Inside - ' . $titleHead . '</title>';
    else
        echo '<title>Inside</title>';
?>

<!-- Scripts communs -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    window.jQuery || document.write('<script src="/inside/includes/libraries/js/jquery-3.6.3.min.js"><\/script>')
</script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
<script>
    window.jQuery || document.write('<script src="/inside/includes/libraries/js/jquery-ui-1.13.2.min.js"><\/script>')
</script>
<script src="/inside/includes/assets/js/<?php echo $_SESSION['index']['plateforme']; ?>/script.js?version=<?php echo $dateModificationJs; ?>"></script>

<!-- Scripts spécifiques -->
<?php if (isset($angularHead) AND $angularHead == true) { ?>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular.min.js"></script>
    <script>
        window.angular || document.write('<script src="/inside/includes/libraries/js/angular.min.js"><\/script>')
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular-animate.min.js"></script>
    <script>
        window.angular || document.write('<script src="/inside/includes/libraries/js/angular-animate.min.js"><\/script>')
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular-resource.min.js"></script>
    <script>
        window.angular || document.write('<script src="/inside/includes/libraries/js/angular-resource.min.js"><\/script>')
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular-sanitize.min.js"></script>
    <script>
        window.angular || document.write('<script src="/inside/includes/libraries/js/angular-sanitize.min.js"><\/script>')
    </script>
<?php } ?>

<?php if (!empty($scriptHead)) { ?>
    <script src="/inside/includes/assets/js/<?php echo $_SESSION['index']['plateforme']; ?>/<?php echo $scriptHead; ?>?version=<?php echo $dateModificationJsSection; ?>"></script>
<?php } ?>

<?php if (isset($chatHead) AND $chatHead == true) { ?>
    <script src="/inside/includes/assets/js/<?php echo $_SESSION['index']['plateforme']; ?>/scriptChat.js?version=<?php echo $dateModificationJsChat; ?>"></script>
<?php } ?>

<?php if (isset($masonryHead) AND $masonryHead == true) { ?>
    <script src="/inside/includes/libraries/js/masonry.pkgd.min.js"></script>
<?php } ?>

<?php if (isset($exifHead) AND $exifHead == true) { ?>
    <script src="/inside/includes/libraries/js/exif.js"></script>
<?php } ?>

<?php if (isset($datepickerHead) AND $datepickerHead == true) { ?>
    <script src="/inside/includes/libraries/js/datepicker.js"></script>
<?php } ?>

<?php if (isset($html2canvasHead) AND $html2canvasHead == true) { ?>
    <script src="/inside/includes/libraries/js/html2canvas.min.js"></script>
<?php } ?>

<?php if (isset($jqueryCsv) AND $jqueryCsv == true) { ?>
    <script src="/inside/includes/libraries/js/jquery.csv.js"></script>
<?php } ?>