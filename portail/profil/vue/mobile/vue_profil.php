<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Profil';
      $styleHead      = 'styleProfil.css';
      $scriptHead     = 'scriptProfil.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = true;

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
        $celsius = 'profil';

        include('../../includes/common/celsius.php');
      ?>

			<article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . strtoupper($titleHead) . '</div>';

          /*************/
          /* Affichage */
          /*************/
          switch ($_GET['view'])
          {
            case 'settings':
              include('vue/mobile/vue_settings.php');
              break;

            case 'success':
              //include('vue/mobile/vue_success.php');
              echo '<div class="empty">En cours de construction...</div>';
              break;

            case 'ranking':
              //include('vue/mobile/vue_ranking.php');
              echo '<div class="empty">En cours de construction...</div>';
              break;

            case 'themes':
              //include('vue/mobile/vue_infos_themes.php');
              echo '<div class="empty">En cours de construction...</div>';
              break;

            case 'profile':
            default:
              include('vue/mobile/vue_informations.php');
              break;
          }
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération de la liste des succès débloqués
      var listeSuccess = <?php if (isset($listeSuccessJson) AND !empty($listeSuccessJson)) echo $listeSuccessJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
