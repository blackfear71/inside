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
        <img src="../../includes/icons/common/loading.png" alt="" id="loading_image" class="loading_image" />
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
          /* Saisie */
          /**********/

          /********************/
          /* Boutons d'action */
          /********************/
          // Années
          echo '<a id="" title="Changer d\'année" class="lien_red">' . $_GET['year'] . '</a>';

          // Saisie dépense
          echo '<a id="" title="Saisir une dépense" class="lien_green">Saisir une dépense</a>';

          /**********/
          /* Bilans */
          /**********/
          echo '<div id="titre_depenses_bilan" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/total_grey.png" alt="total_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Bilan</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
          echo '</div>';

          echo '<div id="afficher_depenses_bilan" class="empty">';
            echo 'Ici apparaîtra bientôt le bilan de tous les utilisateurs du site. Il reste consultable à tout moment sur la version web.';
          echo '</div>';

          /************/
          /* Dépenses */
          /************/
          echo '<div id="titre_depenses_utilisateurs" class="titre_section">';
            echo '<img src="../../includes/icons/expensecenter/expenses_grey.png" alt="expenses_grey" class="logo_titre_section" />';
            echo '<div class="texte_titre_section">Les dépenses</div>';
            echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section angle_fleche_titre_section" />';
          echo '</div>';

          echo '<div id="afficher_depenses_utilisateurs" class="empty" style="display: none;">';
            echo 'De même, la liste des dépenses sera prochainement accessible.';
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
