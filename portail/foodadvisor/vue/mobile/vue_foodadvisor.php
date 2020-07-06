<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Les enfants ! À table !";
      $style_head      = "styleFA.css";
      $script_head     = "scriptFA.js";
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

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'foodadvisor';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /**********/
          /* Saisie */
          /**********/
          if ($actions["saisir_choix"] == true)
            include('vue/mobile/vue_saisie_propositions.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Actualiser
          echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page" class="lien_green">Actualiser</a>';

          // Proposer un choix
          if ($actions["saisir_choix"] == true)
            echo '<a id="afficherSaisiePropositions" title="Proposer où manger" class="lien_green">Proposer où manger</a>';

          // Faire bande à part
          if ($actions["solo"] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doSolo">';
              echo '<input type="submit" name="solo" value="Faire bande à part" class="lien_red" />';
            echo '</form>';
          }

          // Lancer la détermination
          if ($actions["determiner"] == true)
          {
            echo '<form method="post" action="foodadvisor.php?action=doDeterminer">';
              echo '<input type="submit" name="determiner" value="Lancer la détermination" class="lien_red" />';
            echo '</form>';
          }

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
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération liste propositions pour le script
      var detailsPropositions = <?php echo $detailsPropositions; ?>;
    </script>
  </body>
</html>
