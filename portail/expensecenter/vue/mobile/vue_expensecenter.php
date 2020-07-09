<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Expense Center";
      $style_head      = "styleEC.css";
      $script_head     = "scriptEC.js";
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
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'expensecenter';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /**********/
          /* Années */
          /**********/
          include('vue/mobile/vue_annees.php');

          /**********/
          /* Saisie */
          /**********/
          include('vue/mobile/vue_saisie_depense.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Années
          echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_red">' . $_GET['year'] . '</a>';

          // Saisie dépense
          echo '<a id="afficherSaisieDepense" title="Saisir une dépense" class="lien_green">Saisir une dépense</a>';

          /**********/
          /* Bilans */
          /**********/
          include('vue/mobile/vue_bilans.php');

          /***********/
          /* Détails */
          /***********/
          include('vue/mobile/vue_details_depense.php');

          /************/
          /* Dépenses */
          /************/
          include('vue/mobile/vue_depenses.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération liste dépenses pour le script
      var listExpenses = <?php if (isset($listeDepensesJson) AND !empty($listeDepensesJson)) echo $listeDepensesJson; else echo '{}'; ?>;
    </script>
  </body>
</html>