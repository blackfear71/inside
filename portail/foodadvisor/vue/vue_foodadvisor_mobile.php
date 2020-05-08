<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = 'Les enfants ! À table !';
      $style_head      = 'styleFA.css';
      $script_head     = 'scriptFA.js';
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Menus -->
      <aside>
  			<?php include('../../includes/common/aside_mobile.php'); ?>
      </aside>

      <article>
        <?php
          // Message vide
          echo '<div class="zone_celsius">';
            echo '<img src="../../includes/icons/common/celsius.png" alt="celsius" title="Celsius" class="celsius" />';

            echo '<div class="empty"> Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site. Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections du site.</div>';

            echo '<div class="empty">Celle-ci est en cours de développement et n\'est pas encore disponible. Tu peux utiliser le bouton présent en bas du site pour revenir à la version classique et utiliser toutes ses fonctionnalités comme avant !</div>';
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
