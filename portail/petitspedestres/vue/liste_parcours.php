<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "PP";
      $style_head  = "stylePP.css";
      $script_head = "";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Onglets -->
    <header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

    <section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect       = true;
					$ajouter_parcours = true;
					$back             = true;
					$ideas            = true;
					$reports          = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

      <article>
				<?php
					// Tableau des parcours
        	echo '<table class="PP-table">';
          	echo '<tr>';
              echo '<td class="PP-table-titre">Nom du parcours</td>';
              echo '<td class="PP-table-titre">Distance</td>';
              echo '<td class="PP-table-titre">Lieu</td>';
            echo '</tr>';

            $i = 0;

            foreach ($parcours as $prcr)
            {
              if ($i % 2 == 0)
                echo '<tr>';
              else
                echo '<tr class="PP-tr">';
                  echo '<td class="PP-table-ligne">';
                    echo '<div>';
                      echo '<a href="parcours.php?id=' . $prcr->getId() . '&action=consulter">'. $prcr->getNom() . '</a>';
                    echo '</div>';
                  echo '</td>';

  								/*
  								Monsieur et madame Santé ont un fils, comment qu'y s'appelle ?
  								Réponse : Parcours.
  								C'est nul ? Oui, c'est nul.
  								*/

                  echo '<td class="PP-table-ligne">';
                    echo '<div>';
                      echo $prcr->getDistance() . ' km';
                    echo '</div>';
                  echo '</td>';

                  echo '<td class="PP-table-ligne">';
                    echo '<div>';
                      echo $prcr->getLieu();
                    echo '</div>';
                  echo '</td>';
              echo '</tr>';

              $i++;
            }

          echo '</table>';
        ?>
    	</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
