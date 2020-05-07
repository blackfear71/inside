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
          // Liens des catégories
          echo '<div class="zone_liens_portail">';
            foreach ($portail as $lienPortail)
            {
              if ($lienPortail['mobile'] == 'Y')
              {
                echo '<a href="' . $lienPortail['lien'] . '" title="' . $lienPortail['title'] . '" class="lien_portail">';
                  echo '<img src="' . $lienPortail['image'] . '" alt="' . $lienPortail['alt'] . '" class="icone_lien_portail" />';
                  echo '<div class="texte_lien_portail">' . str_replace('<br />', ' ', $lienPortail['categorie']) . '</div>';
                echo '</a>';
              }
            }
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
