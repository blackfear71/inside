<?php
  if ($_SESSION['user']['celsius'] == 'Y' AND !empty($celsius))
  {
    switch ($celsius)
    {
      case 'collector':
        $contenuCelsius = 'Magie ! Je sais que tu m\'adores ! Je sais que tu m\'attendais avec impatience, hé bien tu peux te sentir rassuré. Oui je suis bien là à tes côtés, que tu sois le chasseur
        ou la proie, on se retrouve toujours tous ici...';
        break;

      case 'expensecenter':
        $contenuCelsius = 'Tu peux saisir ici une dépense en commun ou bien une dépense en montants pour répartir par exemple une commande sur chacun en fonction de ce qu\'il a pris.
        Ça peut même tenir compte des frais de livraison ! Trop bien !';
        break;

      case 'foodadvisor':
        $contenuCelsius = 'Dans cette section, tu peux voter dans la liste des restaurants ou bien aller encore plus vite grâce à la toute nouvelle barre de recherche instantanée ! Ajoute tes choix
        et n\'oublie pas non plus le résumé de la semaine !';
        break;

      case 'portail':
        $contenuCelsius = 'Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site.
        Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections du site. Ici tu peux accéder à la section pour voter pour le repas du midi et celle pour répartir les dépenses.
        N\'oublie pas les menus sur les côtés pour naviguer à travers le site !';
        break;

      case 'profil':
        $contenuCelsius = 'Voilà tes petites fiertés, tes contributions à toute l\'équipe de joyeux Insiders ! Allez, profite...';
        break;

      case 'restaurants':
        $contenuCelsius = 'Ajoute tes restaurants favoris ici, allez ne sois pas timide on veut tous en profiter ! Il faut en rajouter un maximum pour que le choix soit le plus grand possible !';
        break;

      case 'settings':
        $contenuCelsius = 'Un petit réglage par-ci, un tour de vis par-là... Allez, règle-moi tout ça comme tu veux que ça ait l\'air plus personnel !';
        break;

      case 'success':
      case 'ranking':
        $contenuCelsius = 'Alors on veut faire le malin ? Bon ok, y\'a moyen... Mais fais attention ! On ne sais jamais qui peut te dépasser à ce petit jeu-là. Regarde bien où en est ton voisin. Et nargue-le pour voir.';
        break;

      case 'themes':
        $contenuCelsius = 'En cours de construction...';
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

        // Boutons
        echo '<div class="zone_boutons_celsius">';
          // Réinitialisation position
          echo '<a id="resetCelsius" class="bouton_celsius_left">Réinitialiser</a>';

          // Fermeture
          echo '<a id="closeCelsius" class="bouton_celsius_right">Fermer</a>';
        echo '</div>';
      echo '</div>';
    }
  }
?>
