<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = 'Les enfants ! À table !';
      $style_head      = 'styleFA.css';
      $script_head     = 'scriptFA.js';
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

      <article>
        <?php
          /********************/
          /* Boutons d'action */
          /********************/
          // Actualiser
          echo '<a href="foodadvisor.php?action=goConsulter" title="Rafraichir la page" class="lien_green">Actualiser</a>';

          // Proposer un choix
          if ($actions["saisir_choix"] == true)
            echo '<a id="saisiePropositions" title="Proposer où manger" class="lien_green">Proposer où manger</div></a>';

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

          // Message vide
          echo '<div class="zone_celsius">';
            echo '<img src="../../includes/icons/common/celsius.png" alt="celsius" title="Celsius" class="celsius" />';

            echo '<div class="empty"> Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version mobile du site. Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections du site.</div>';

            echo '<div class="empty">Celle-ci est en cours de développement et n\'est pas encore disponible. Tu peux utiliser le bouton présent en bas du site pour revenir à la version classique et utiliser toutes ses fonctionnalités comme avant !</div>';
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
