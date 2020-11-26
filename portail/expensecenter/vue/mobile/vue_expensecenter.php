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
        $celsius = 'expensecenter';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

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
          // Saisie dépense
          echo '<a id="afficherSaisieDepense" title="Saisir une dépense" class="lien_green lien_demi margin_lien">';
            echo '<img src="../../includes/icons/expensecenter/expense_center_grey.png" alt="expense_center_grey" class="image_lien" />';
            echo '<div class="titre_lien">DÉPENSE</div>';
          echo '</a>';

          // Saisie montants
          echo '<a id="afficherSaisieMontants" title="Saisir des montants" class="lien_green lien_demi">';
            echo '<img src="../../includes/icons/expensecenter/expenses_grey.png" alt="expenses_grey" class="image_lien" />';
            echo '<div class="titre_lien">MONTANTS</div>';
          echo '</a>';

          // Années
          echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_red">';
            echo '<img src="../../includes/icons/expensecenter/year_grey.png" alt="year_grey" class="image_lien" />';
            echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
          echo '</a>';

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
