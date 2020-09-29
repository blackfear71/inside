<?php
  if ($_SESSION['user']['celsius'] == 'Y' AND !empty($celsius))
  {
    switch ($celsius)
    {
      case 'collector':
        $contenuCelsius = 'Magie ! On peut me consulter ! Oui, bon c\'est juste limité à la consultation mais mon créateur fait tout ce qu\'il peut pour que ça avance. Je l\'espère...';
        break;

      case 'expensecenter':
        $contenuCelsius = 'Tu peux saisir ici une dépense en commun ou bien une dépense en montants pour répartir par exemple une commande sur chacun en fonction de ce qu\'il a pris.
        Ça peut même tenir compte des frais de livraison ! Trop bien !';
        break;

      case 'foodadvisor':
        $contenuCelsius = 'Dans cette section, tu peux voter dans la liste des restaurants ou bien aller encore plus vite grâce à la toute nouvelle barre de recherche instantanée !
        Pour ajouter un nouveau restaurant par contre, tu peux toujours utiliser le bouton présent en bas du site pour revenir à la version classique et utiliser toutes ses
        fonctionnalités comme avant ! On peut même voir le détail d\'un vote et agir dessus comme avant.';
        break;

      case 'portail':
        $contenuCelsius = 'Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site.
        Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections du site. Ici tu peux accéder à la section pour voter pour le repas du midi et celle pour répartir les dépenses.
        N\'oublie pas les menus sur les côtés pour naviguer à travers le site !';
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
