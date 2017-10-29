<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="stylePP.css" />
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script type="text/javascript" src="/inside/script.js"></script>

  	<title>Inside - PP</title>
  </head>

	<body>
    <!-- Onglets -->
    <header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/onglets.php') ;
      ?>
		</header>

    <section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect       = true;
					$profil_user      = true;
					$ajouter_parcours = true;
					$back             = true;
					$ideas            = true;
					$reports          = true;
          $notifs           = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

      <article class="article_portail">
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
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
