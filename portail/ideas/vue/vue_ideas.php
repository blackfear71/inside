<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = '&#35;TheBox';
      $styleHead      = 'styleTheBox.css';
      $scriptHead     = 'scriptTheBox.js';
      $angularHead    = false;
      $chatHead       = true;
      $datepickerHead = false;
      $masonryHead    = true;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = '#TheBox';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section class="section_no_nav">
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*******************/
          /* Liens de saisie */
          /*******************/
          echo '<div class="zone_liens_saisie">';
            echo '<a id="ajouterIdee" title="Proposer une idée" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/ideas.png" alt="ideas" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Proposer une idée</div>';
            echo '</a>';
          echo '</div>';

          /*************************/
          /* Zone de saisie d'idée */
          /*************************/
          include('vue/vue_saisie_idea.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Onglets */
          /***********/
          include('vue/vue_onglets.php');

          /*********/
          /* Idées */
          /*********/
          include('vue/vue_liste_ideas.php');

          /**************/
          /* Pagination */
          /**************/
          include('vue/vue_pagination.php');
				?>
			</article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
