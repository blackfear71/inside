<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Expense Center';
      $styleHead      = 'styleEC.css';
      $scriptHead     = 'scriptEC.js';
      $angularHead    = false;
      $chatHead       = true;
      $datepickerHead = true;
      $masonryHead    = true;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = 'Expense Center';

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
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
            // Saisie nouvelle dépense
            echo '<a id="ajouterDepense" title="Saisir une dépense" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/expense_center.png" alt="expense_center" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Saisir une dépense</div>';
            echo '</a>';

            // Saisie nouveaux montant
            echo '<a id="ajouterMontants" title="Saisir des montants" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/expensecenter/expenses.png" alt="expenses" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Saisir des montants</div>';
            echo '</a>';
          echo '</div>';

          /***********/
          /* Saisies */
          /***********/
          include('vue/web/vue_saisie_depense.php');
          include('vue/web/vue_saisie_montants.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /*******************/
          /* Affichage bilan */
          /*******************/
          include('vue/web/vue_bilan_depenses.php');

          /********************/
          /* Dépenses saisies */
          /********************/
          include('vue/web/vue_depenses.php');
        ?>
			</article>

      <!-- Chat -->
      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération de la liste des dépenses pour le script
      var listeDepenses = <?php if (isset($listeDepensesJson) AND !empty($listeDepensesJson)) echo $listeDepensesJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
