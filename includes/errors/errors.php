<?php
  // Date de dernière modification pour mise à jour automatique du cache du navigateur
  $last_modification_errors = filemtime($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/assets/css/styleErrors.css');

  // Feuille de style
  echo '<link rel="stylesheet" href="/inside/includes/assets/css/styleErrors.css?version=' . $last_modification_errors . '" />';

  // Erreurs
  echo '<div class="error_title">';
    echo '<img src="/inside/includes/icons/common/inside_red.png" alt="inside" title="Inside" class="error_logo" />';

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
?>
