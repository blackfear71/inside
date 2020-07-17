<?php
  if ($_SESSION['user']['celsius'] == 'Y' AND !empty($celsius))
  {
    switch ($celsius)
    {
      case 'expensecenter':
        $contenuCelsius  = 'Tu peux saisir ici une dépense dont le coût sera ensuite réparti équitablement sur chaque participant en fonction du nombre de parts. <strong>Les parts sont limitées à 5 maximum par personne</strong>.<br /><br />';
        $contenuCelsius .= '2 types de saisies peuvent être effectuées :<br />';
        $contenuCelsius .= '<ul>';
          $contenuCelsius .= '<li>Une dépense : <strong>le prix doit être positif et des parts doivent être présentes sur au moins un utilisateur</strong>. Le coût est réparti proportionnellement entre chaque participant.</li>';
          $contenuCelsius .= '<li>Une régularisation : <strong>le prix est soit positif soit négatif, mais le nombre de parts doit être nul pour tous</strong>. Le coût est simplement ajouté au bilan de l\'acheteur.</li>';
        $contenuCelsius .= '</ul>';
        break;

      case 'foodadvisor':
        $contenuCelsius = 'Cette section est encore toute fraiche mais tu peux très bien l\'utiliser ! Tu peux voter dans la liste des restaurants ou bien aller encore plus vite
        grâce à la toute nouvelle barre de recherche instantanée ! Pour ajouter un nouveau restaurant par contre, tu peux toujours utiliser le bouton présent en bas du site pour
        revenir à la version classique et utiliser toutes ses fonctionnalités comme avant ! On peut même voir le détail d\'un vote et agir dessus comme avant.';
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
