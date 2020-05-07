<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = 'Portail';
      $style_head      = 'stylePortail.css';
      $script_head     = 'scriptPortail.js';
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
          echo '<div class="empty">Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site.</div>';

          echo '<div class="empty">Celui-ci est en cours de développement et cette section n\'est pas encore disponible. Veuillez utiliser le bouton présent en bas du site
          pour revenir à la version classique et utiliser toutes ses fonctionnalités.</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
