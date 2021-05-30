<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Les enfants ! À table !';
      $styleHead       = 'styleFA.css';
      $scriptHead      = 'scriptFA.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;

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
        $celsius = 'foodadvisor';

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
          /* Saisies */
          /***********/
          // Propositions
          if ($actions['saisir_choix'] == true)
            include('vue/mobile/vue_saisie_propositions.php');

          // Résumé
          include('vue/mobile/vue_saisie_resume.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Actualiser
          echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page" class="lien_green lien_demi margin_lien">';
            echo '<img src="../../includes/icons/foodadvisor/refresh.png" alt="refresh" class="image_lien" />';
            echo '<div class="titre_lien">ACTUALISER</div>';
          echo '</a>';

          // Proposer un choix
          if ($actions['saisir_choix'] == true)
          {
            echo '<a id="afficherSaisiePropositions" title="Proposer où manger" class="lien_green lien_demi">';
              echo '<img src="../../includes/icons/foodadvisor/propositions_grey.png" alt="propositions_grey" class="image_lien" />';
              echo '<div class="titre_lien">PROPOSER</div>';
            echo '</a>';
          }

          // Faire bande à part
          if ($actions['solo'] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doSolo" class="lien_red lien_demi margin_lien">';
              echo '<img src="../../includes/icons/foodadvisor/solo_grey.png" alt="solo_grey" class="image_lien" />';
              echo '<div class="titre_lien">BANDE À PART</div>';
              echo '<input type="submit" name="solo" value="" class="lien_form" />';
            echo '</form>';
          }

          // Lancer la détermination
          if ($actions['determiner'] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doDeterminer" class="lien_red lien_demi margin_lien">';
              echo '<img src="../../includes/icons/foodadvisor/users_grey.png" alt="users_grey" class="image_lien" />';
              echo '<div class="titre_lien">DÉTERMINATION</div>';
              echo '<input type="submit" name="determiner" value="" class="lien_form" />';
            echo '</form>';
          }

          // Liste des restaurants
          echo '<a href="restaurants.php?action=goConsulter" title="Les restaurants" class="lien_green lien_demi">';
            echo '<img src="../../includes/icons/foodadvisor/restaurants_grey.png" alt="restaurants_grey" class="image_lien" />';
            echo '<div class="titre_lien">RESTAURANTS</div>';
          echo '</a>';

          /****************/
          /* Bande à part */
          /****************/
          include('vue/mobile/vue_bande_a_part.php');

          /************/
          /* Non voté */
          /************/
          include('vue/mobile/vue_sans_votes.php');

          /***********/
          /* Détails */
          /***********/
          include('vue/mobile/vue_details_proposition.php');

          /************************/
          /* Propositions du jour */
          /************************/
          include('vue/mobile/vue_propositions.php');

          /*************/
          /* Mes choix */
          /*************/
          include('vue/mobile/vue_mes_choix.php');

          /************************/
          /* Résumé de la semaine */
          /************************/
          include('vue/mobile/vue_resume_semaine.php')
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération de la liste des propositions pour le script
      var detailsPropositions = <?php if (isset($detailsPropositions) AND !empty($detailsPropositions)) echo $detailsPropositions; else echo '{}'; ?>;

      // Récupération utilisateur connecté
      var userSession = <?php echo json_encode($_SESSION['user']['identifiant']); ?>;
    </script>
  </body>
</html>
