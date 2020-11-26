<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Portail';
      $styleHead      = 'stylePortail.css';
      $scriptHead     = 'scriptPortail.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = false;

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

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'portail';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /***********/
          /* Portail */
          /***********/
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
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>
  </body>
</html>
