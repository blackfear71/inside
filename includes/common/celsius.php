<?php
  if ($_SESSION['user']['celsius'] == 'Y' AND !empty($celsius))
  {
    // Sélection du contenu Celsius à afficher
    switch ($celsius)
    {
      case 'bugs':
        $contenuCelsius = 'Le site ne présente aucun bug. Si toutefois tu penses être tombé sur ce qui prétend en être un, tu peux le signaler sur cette page. Ce que j\'appellerai désormais
        "évolution" sera traitée dans les plus brefs délais par une équipe exceptionnelle, toujours à ton écoute pour te servir au mieux.';
        break;

      case 'calendars':
        $contenuCelsius = 'Ils sont tous beaux mes calendriers ! Je les aime comme ils sont parce qu\'ils sont à nous !';
        break;

      case 'calendars_generator':
        $contenuCelsius = 'Et si la magie opérait aussi par ici ? Allez, affiche nous tes bô calendriers sur le mur ! Et n\'oublie pas de demander aux personnes de te donner un avatar pour les étiquettes...';
        break;

      case 'changelog':
        $contenuCelsius = 'Tu veux être au courant des toutes dernières nouveautés ? Tu veux tout savoir de l\'histoire de ton site préféré ? Alors tu es au bon endroit ! Passionné par la technique
        ou simple curieux, viens voir ce qui t\'attend et peut-être qu\'un jour ce sera à toi de compléter ces journaux...';
        break;

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

      case 'ideas':
        $contenuCelsius = 'Si tu as des idées et que tu veux en faire part aux autres, n\'hésite pas à en proposer de nouvelles. N\'importe qui peut les prendre en charge afin de devenir un super développeur !';
        break;

      case 'portail':
        $contenuCelsius = 'Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site.
        Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections du site. Ici tu peux accéder à la section pour voter pour le repas du midi et celle pour répartir les dépenses.
        Viens aussi choisir ton prochain film ou bien te moquer des bétises que disent les autres. Tu peux aussi télécharger les derniers calendriers ou en créer au besoin (sous réserve d\'être autorisé). Et n\'oublie pas les menus sur les côtés pour naviguer à travers le site !';
        break;

      case 'profil':
        $contenuCelsius = 'Voilà tes petites fiertés, tes contributions à toute l\'équipe de joyeux Insiders ! Allez, profite...';
        break;

      case 'moviehouse':
        $contenuCelsius = 'Parce que la culture n\'a pas de prix, parce que ce lieu est l\'origine de ma maison, viens voir quel sera le prochain film que vous allez partager !';
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
        $contenuCelsius = 'C\'est un début... au moins on peut changer sa police de caractères, c\'est cool nan ?';
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
