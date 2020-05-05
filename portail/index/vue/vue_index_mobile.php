<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = '';
      $style_head      = 'styleIndex.css';
      $script_head     = 'scriptIndex.js';
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('includes/common/alerts.php'); ?>

      <article>
        <?php
          // Bandeau
          echo '<div class="zone_bandeau">';
            echo '<img src="/inside_test/includes/icons/common/inside.png" alt="inside" class="logo_bandeau" />';
          echo '</div>';

          // Formulaire de connexion
          echo '<div class="zone_form_index">';
            echo '<form method="post" action="index.php?action=doConnecter" id="formConnexion" class="form_index">';
              echo '<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne_index" id="focus_identifiant" required />';
              echo '<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne_index" required />';
              echo '<input type="submit" name="connect" value="CONNEXION" class="bouton_index" />';
            echo '</form>';
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
