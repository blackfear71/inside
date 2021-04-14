<?php
  // Récupération de la plateforme
  $plateforme = 'web';
  $userAgent  = $_SERVER['HTTP_USER_AGENT'];

  if (preg_match('/iphone/i', $userAgent)
  OR  preg_match('/android/i', $userAgent)
  OR  preg_match('/blackberry/i', $userAgent)
  OR  preg_match('/symb/i', $userAgent)
  OR  preg_match('/ipad/i', $userAgent)
  OR  preg_match('/ipod/i', $userAgent)
  OR  preg_match('/phone/i', $userAgent))
    $plateforme = 'mobile';

  // Date de dernière modification pour mise à jour automatique du cache du navigateur
  $dateModificationCssErrors = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $plateforme . '/styleErrors.css');

  // Import de la feuille de style
  echo '<link rel="stylesheet" href="/inside/includes/assets/css/' . $plateforme . '/styleErrors.css?version=' . $dateModificationCssErrors . '" />';

  // Affichage de l'erreur
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

  // Lien retour au portail
  echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" class="lien_erreur">Revenir sur Inside</a>';
?>
