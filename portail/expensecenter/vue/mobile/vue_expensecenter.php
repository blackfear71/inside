<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Expense Center';
      $styleHead      = 'styleEC.css';
      $scriptHead     = 'scriptEC.js';
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
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . strtoupper($titleHead) . '</div>';

          /**********/
          /* Années */
          /**********/
          include('vue/mobile/vue_annees.php');

          /***********/
          /* Saisies */
          /***********/
          include('vue/mobile/vue_saisie_depense.php');
          include('vue/mobile/vue_saisie_montants.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Années
          echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_red">' . $_GET['year'] . '</a>';

          // Saisie dépense
          echo '<a id="afficherSaisieDepense" title="Saisir une dépense" class="lien_green">Saisir une dépense</a>';

          // Saisie montants
          echo '<a id="afficherSaisieMontants" title="Saisir des montants" class="lien_green">Saisir des montants</a>';

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
      // Récupération de la liste des dépenses pour le script
      var listeDepenses = <?php if (isset($listeDepensesJson) AND !empty($listeDepensesJson)) echo $listeDepensesJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
