<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Récupération de la plateforme
  $plateforme = 'web';
  $userAgent  = $_SERVER['HTTP_USER_AGENT'];

  if (preg_match('/iphone/i', $userAgent)
  ||  preg_match('/android/i', $userAgent)
  ||  preg_match('/blackberry/i', $userAgent)
  ||  preg_match('/symb/i', $userAgent)
  ||  preg_match('/ipad/i', $userAgent)
  ||  preg_match('/ipod/i', $userAgent)
  ||  preg_match('/phone/i', $userAgent))
    $plateforme = 'mobile';

  // Date de dernière modification pour mise à jour automatique du cache du navigateur
  $last_modification_errors = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/css/' . $_SESSION['index']['plateforme'] . '/styleErrors.css');

  // Feuille de style
  echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $plateforme . '/styleErrors.css?version=' . $last_modification_errors . '" />';

  // Erreurs
  echo '<div class="titre_erreur">';
    echo '<img src="/inside/includes/icons/common/inside_red.png" alt="inside" title="Inside" class="logo_erreur" />';

    switch ($_GET['code'])
    {
      /*** Erreurs client ***/
      case 403:
        echo 'Accès interdit';
        break;

      case 404:
        echo 'Page non trouvée';
        break;

      case 408:
        echo 'Délai d\'attente dépassé';
        break;

      /*** Erreurs serveur ***/
      case 500:
        echo 'Erreur interne du serveur';
        break;

      case 503:
        echo 'Service non disponible';
        break;
    }
  echo '</div>';

  // Retour au portail
  echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" class="lien_erreur">Revenir au portail</a>';
?>
