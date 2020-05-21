<?php
  if (!empty($celsius))
  {
    switch($celsius)
    {
      case 'portail':
        $contenuCelsius = 'Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site.
        Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections du site. Ici tu peux accéder à la section pour voter pour le repas du midi.
        N\'oublie pas les menus sur les côtés pour naviguer à travers le site !';
        break;

      case 'foodadvisor':
        $contenuCelsius = 'Celle section est en cours de développement et n\'est pas encore totalement disponible. Tu peux déjà choisir d\'actualiser la page, de faire bande à part
        et de voir qui n\'a pas encore voté. La suite viendra très bientôt ! Sinon tu peux utiliser le bouton présent en bas du site pour revenir à la version classique et utiliser
        toutes ses fonctionnalités comme avant !';
        break;

      default:
        $contenuCelsius = '';
        break;
    }

    if (!empty($contenuCelsius))
    {
      // Icône
      echo '<img src="/inside/includes/icons/common/celsius.png" alt="celsius" title="Celsius" class="celsius" />';

      // Contenu
      echo '<div id="contenuCelsius" class="zone_contenu_celsius">';
        // Titre
        echo '<div class="titre_contenu_celsius">';
          echo 'Celsius';
        echo '</div>';

        // Texte
        echo '<div class="zone_texte_celsius">';
          echo '<div class="texte_contenu_celsius">';
            echo $contenuCelsius;
          echo '</div>';
        echo '</div>';

        // Bouton
        echo '<div class="zone_boutons_celsius">';
          echo '<a id="closeCelsius" class="bouton_celsius">Fermer</a>';
        echo '</div>';
      echo '</div>';
    }
  }
?>
